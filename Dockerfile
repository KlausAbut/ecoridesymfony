FROM php:8.4-cli

WORKDIR /app

# Install system dependencies and required PHP extensions
RUN apt-get update && \
    apt-get install -y git unzip libpq-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-install pdo pdo_pgsql

# Copy application source
COPY . /app

# Install Composer and project dependencies (optimized for production)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader

# Expose port 8000 and start PHP built-in server for Heroku container
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
