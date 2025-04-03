FROM php:8.2-apache

# Mets à jour et installe les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


# Copie ton fichier php.ini personnalisé (si nécessaire)
# Le fichier php.ini doit être placé dans le même dossier que ton Dockerfile ou adapter le chemin.
COPY php.ini /usr/local/etc/php/php.ini
COPY docker-php-ext-mongodb.ini /usr/local/etc/php/conf.d/


# Continue avec le reste de ton Dockerfile (copie du projet, configuration Apache, etc.)
COPY . /var/www/html

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -qq -y --no-install-recommends \
    cron \
     locales coreutils apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev;

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql mysqli gd opcache intl zip calendar dom mbstring zip gd xsl && a2enmod rewrite
RUN pecl install apcu && docker-php-ext-enable apcu

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

WORKDIR /var/www

