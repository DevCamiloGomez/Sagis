#!/bin/bash

# Asegurar permisos correctos
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

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

# Iniciar nginx
nginx -g "daemon off;" 