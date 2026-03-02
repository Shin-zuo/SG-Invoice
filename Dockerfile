FROM php:8.2-apache

# Install system dependencies, PostgreSQL drivers, and Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql mbstring pcntl bcmath gd zip

# Enable Apache routing for Laravel
RUN a2enmod rewrite

# Point Apache to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy your project files
WORKDIR /var/www/html
COPY . /var/www/html

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build Vite frontend assets
RUN npm install
RUN npm run build

# Fix permissions so Laravel can write to storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Make the deploy script executable
RUN chmod +x /var/www/html/deploy.sh

# Run the deploy script
CMD ["/var/www/html/deploy.sh"]