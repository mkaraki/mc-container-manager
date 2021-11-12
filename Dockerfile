FROM php:8.0-apache

RUN apt-get update \
    && apt-get -y install sudo \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN echo "www-data ALL=NOPASSWD: /usr/bin/docker" >> /etc/sudoers

COPY web /var/www/html
COPY _config.sample.php /var/www/html/

VOLUME /mc