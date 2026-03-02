#!/bin/bash

# Cache configurations for faster loading
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations automatically
php artisan migrate --force

# Start the Apache server
apache2-foreground