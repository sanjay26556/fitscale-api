FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev zip \
    && docker-php-ext-install zip pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

RUN touch database/database.sqlite

RUN php artisan key:generate

RUN php artisan migrate --force

CMD php artisan serve --host=0.0.0.0 --port=10000