FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    && docker-php-ext-install mysqli

COPY src/ /var/www/html/

EXPOSE 80

LABEL version="2.0"
LABEL description="demo web app"
LABEL maintainer="Nicholas Pier <npier@candoris.com>"