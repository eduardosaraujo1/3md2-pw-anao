FROM php:8.3-fpm-alpine

# add mysql usage
RUN docker-php-ext-install pdo pdo_mysql

# allow super user - must have since composer is run through `docker compose exec app bash`
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install composer
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# Note: composer install should be done manually from the docker-compose file
