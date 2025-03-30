FROM php:7.4.3-fpm-alpine3.11
# FROM php:8.1-fpm
# FROM php:7.1-apache
RUN docker-php-ext-install pdo pdo_mysql
# Copy the php config file
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy the application code
COPY . /app

VOLUME ["/app"]
