FROM php:8.2-apache

# Install OS dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    libzip-dev \
    zip \
    unzip \
    curl \
    gnupg \
    && docker-php-ext-install pdo pdo_sqlite zip

# Install Node.js (for Vite build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Enable Apache mod_rewrite (required for Laravel routing)
RUN a2enmod rewrite

# Update Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy all project files to the container
COPY . /var/www/html

# Install PHP and Node.js dependencies, then build Vite assets
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Create SQLite database file
RUN touch database/database.sqlite

# Set directory permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Configure Apache to listen on Render's dynamic PORT
RUN sed -i "s/Listen 80/Listen \${PORT}/g" /etc/apache2/ports.conf
RUN sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:\${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Create startup script to run migrations and seeders before starting the server
RUN echo '#!/bin/bash\n\
php artisan migrate --force\n\
php artisan db:seed --force\n\
apache2-foreground\n' > /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Expose Render PORT
EXPOSE $PORT

# Run the entrypoint script
CMD ["/usr/local/bin/entrypoint.sh"]
