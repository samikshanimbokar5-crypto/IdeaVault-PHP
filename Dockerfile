FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql \
    && (a2dismod mpm_event mpm_worker mpm_prefork || true) \
    && a2enmod mpm_prefork rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80