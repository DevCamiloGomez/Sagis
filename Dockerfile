FROM php:8.1-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    libzip-dev \
    libssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zlib1g-dev \
    libwebp-dev \
    libxpm-dev \
    libmagickwand-dev \
    ghostscript

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Configurar PHP
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "max_execution_time=300" > /usr/local/etc/php/conf.d/timeout.ini
RUN echo "upload_max_filesize=50M" > /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "request_terminate_timeout=300" >> /usr/local/etc/php-fpm.d/www.conf

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar composer.json y composer.lock primero
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copiar el resto de los archivos
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN echo "fastcgi_read_timeout 300;" > /etc/nginx/conf.d/timeout.conf
RUN echo "proxy_read_timeout 300;" >> /etc/nginx/conf.d/timeout.conf

# Crear archivo de variables de entorno si no existe
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Configurar variables de AWS en tiempo de construcción
ARG AWS_ACCESS_KEY_ID
ARG AWS_SECRET_ACCESS_KEY
ARG AWS_DEFAULT_REGION
ARG AWS_BUCKET
ARG AWS_URL

# Configurar variables de AWS en el entorno
ENV AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
ENV AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
ENV AWS_DEFAULT_REGION=${AWS_DEFAULT_REGION}
ENV AWS_BUCKET=${AWS_BUCKET}
ENV AWS_URL=${AWS_URL}
ENV FILESYSTEM_DRIVER=s3

# Variables para el servicio de correo
ARG MAIL_MAILER
ARG MAIL_HOST
ARG MAIL_PORT
ARG MAIL_USERNAME
ARG MAIL_PASSWORD
ARG MAIL_ENCRYPTION
ARG MAIL_FROM_ADDRESS
ARG MAIL_FROM_NAME

ENV MAIL_MAILER=${MAIL_MAILER}
ENV MAIL_HOST=${MAIL_HOST}
ENV MAIL_PORT=${MAIL_PORT}
ENV MAIL_USERNAME=${MAIL_USERNAME}
ENV MAIL_PASSWORD=${MAIL_PASSWORD}
ENV MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
ENV MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
ENV MAIL_FROM_NAME=${MAIL_FROM_NAME}

# Optimizar Laravel para producción
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Exponer puerto
EXPOSE 80

# Script de inicio
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"] 