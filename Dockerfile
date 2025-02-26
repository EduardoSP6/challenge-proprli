FROM php:8.2-fpm

ARG APP_DIR=/var/www

USER root

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    openssl \
    libzip-dev \
    libmcrypt-dev \
    openssh-server \
    default-mysql-client \
    && apt-get autoremove --purge -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    sockets

RUN chmod 777 /run
RUN usermod -u 1000 www-data

WORKDIR $APP_DIR
RUN cd $APP_DIR

RUN chmod 755 -R * || true
RUN chown -R www-data:www-data * || true

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER www-data

EXPOSE 9000
