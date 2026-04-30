FROM php:8.4-apache

WORKDIR /var/www/html

# Install deps + PHP extensions
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

# Ensure writable dirs
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

# Install PHP deps
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate key (safe even if already exists)
RUN php artisan key:generate || true

# Run migrations (don’t fail build if DB not ready yet)
RUN php artisan migrate --force || true

# Point Apache to /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]