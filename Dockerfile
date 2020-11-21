FROM php:7.4-fpm

# Copy composer.lock and composer.json
COPY api/composer.lock api/composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config

RUN pecl install \
    xdebug
    redis

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* \
    && pecl clear-cache

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl pdo
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-enable redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY ./api /var/www

# Copy existing application directory permissions
COPY --chown=www:www ./api /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]


