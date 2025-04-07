FROM php:8.2-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    cron \
    locales coreutils apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurer les locales
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli gd opcache intl zip calendar dom mbstring zip gd xsl
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Installer Composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

# Configurer Apache
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Créez le répertoire conf.d s'il n'existe pas
RUN mkdir -p /usr/local/etc/php/conf.d

# Copier les fichiers de configuration
COPY php.ini /usr/local/etc/php/php.ini
COPY docker-php-ext-mongodb.ini /usr/local/etc/php/conf.d/

# Installer les extensions supplémentaires
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

WORKDIR /var/www
COPY . /var/www/html
