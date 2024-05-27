
FROM php:8.0-fpm


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql


WORKDIR /var/www/html


COPY . .


EXPOSE 8000


CMD php artisan serve --host=0.0.0.0 --port=8000
