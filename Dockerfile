FROM php:8.3-apache
RUN docker-php-ext-install mysqli
# Mesmo comportamento do XAMPP: permite header() depois de já ter enviado HTML
RUN echo "output_buffering=4096" > /usr/local/etc/php/conf.d/bestway.ini
