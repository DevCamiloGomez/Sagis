#!/bin/bash

# Download composer
curl -sS https://getcomposer.org/installer | php

# Install dependencies
php composer.phar install --no-dev --optimize-autoloader

# Generate key if not set
php artisan key:generate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache 