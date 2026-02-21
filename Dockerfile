FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql mbstring zip opcache gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY composer.json composer.lock ./

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD php -S 0.0.0.0:80 -t public
