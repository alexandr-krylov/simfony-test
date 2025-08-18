FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/taskmanager
