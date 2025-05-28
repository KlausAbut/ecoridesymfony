FROM php:8.4-cli

WORKDIR /app

# Install system dependencies and required PHP extensions
RUN apt-get update && \
    apt-get install -y git unzip libpq-dev libsqlite3-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-install pdo_pgsql pdo_sqlite intl

# Copy application source
COPY . /app

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies, ignore ext-mongodb mismatch, skip scripts
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb --no-scripts

# Expose HTTP port
EXPOSE 8000

# Start PHP built-in server on container start
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
