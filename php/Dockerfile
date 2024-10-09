# Basisimage
FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev

# Installiere die PHP-Erweiterungen
RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    zip

WORKDIR /app

COPY ./php/app /app

# Installiere Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installiere die Composer-Abhängigkeiten
# RUN composer install 

# Generiere den Autoloader
RUN composer install --no-scripts --no-autoloader
COPY ./php/app/* /app

RUN composer dump-autoload --optimize
# Setze die Dateiberechtigungen
RUN chown -R www-data:www-data /app

# Exponiere den Container-Port
# EXPOSE 9000

# Starte den PHP-FPM-Server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
