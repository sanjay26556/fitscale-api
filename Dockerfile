FROM php:8.2-apache

WORKDIR /var/www/html

# Install dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install zip pdo pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Laravel setup
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && php artisan key:generate \
    && php artisan migrate --force

# Set Apache to public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]