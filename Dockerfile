FROM php:7.2-apache

LABEL maintainer="09npier@gmail.com"

COPY src/ /var/www/html/

RUN apt-get update && apt-get install -y \
    && docker-php-ext-install mysqli

EXPOSE 80
