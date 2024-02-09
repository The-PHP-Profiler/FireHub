FROM php:8.2-apache-bullseye

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./ /var/www/html

WORKDIR /var/www/html

EXPOSE 80
