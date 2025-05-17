#!/bin/bash

# Iniciar PHP-FPM
php-fpm -D

# Generar key si no existe
php artisan key:generate --force

# Ejecutar migraciones
php artisan migrate --force

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar nginx
nginx -g "daemon off;" 