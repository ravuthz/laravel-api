FROM ravuthz/laravel-php:8.3-alpine

RUN apk update && apk add git sed

COPY ./composer.json /var/www/html/composer.json
COPY ./composer.lock /var/www/html/composer.lock
COPY ./package.json /var/www/html/package.json

COPY . /var/www/html

RUN composer install --quiet --no-progress --no-suggest --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

ENTRYPOINT ["frankenphp", "php-server", "--listen", ":8000", "--root", "/var/www/html/public/"]
