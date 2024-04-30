FROM php:8-apache
ENV TZ=Asia/Tokyo
RUN apt-get update && apt-get install -y libyaml-dev vim curl git tzdata
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY src /var/www/html
RUN composer install -q
