#!/bin/bash

# Asegurar permisos correctos
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Crear directorios necesarios si no existen
mkdir -p /var/www/html/storage/app/public/people
mkdir -p /var/www/html/storage/app/public/posts
mkdir -p /var/www/html/storage/app/public/temp
chmod -R 775 /var/www/html/storage/app/public

# Limpiar y regenerar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Iniciar PHP-FPM
php-fpm -D

# Generar key si no existe
php artisan key:generate --force

# Ejecutar migraciones si la base de datos está disponible
php artisan migrate --force || true

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico del storage si no existe
php artisan storage:link || true

# Verificar conexión con S3
php artisan s3:test || echo "Warning: S3 connection test failed"

# Iniciar nginx
nginx -g "daemon off;" 