FROM php:8.4-cli
WORKDIR /app
RUN apt-get update && apt-get install -y git unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql
COPY . /app
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --optimize-autoloader
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
