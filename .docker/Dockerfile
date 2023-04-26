# Define a imagem base
FROM php:7.4-apache

# Copia o código do projeto para a pasta do servidor web
COPY . /var/www/html/
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Instala as dependências necessárias
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git

# Instala as extensões do PHP necessárias
RUN apt-get update \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) intl mysqli pdo_mysql bcmath mbstring exif gd

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite && service apache2 restart