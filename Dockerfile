FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    bash \
    openssh-client \
    unzip \
    nginx \
    nano

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    intl \
    gd \
    mbstring \
    xml \
    zip \
    opcache \
    bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

RUN mkdir -p /var/www/html

WORKDIR /var/www

EXPOSE 9000
