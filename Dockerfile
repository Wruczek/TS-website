FROM php:7.2-apache
RUN apt-get update && apt-get install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY src/ /var/www/html/
WORKDIR /var/www/html/
RUN composer update
RUN chown -R www-data:www-data /var/www/html
RUN docker-php-ext-install pdo_mysql
