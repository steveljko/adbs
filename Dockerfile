FROM php:8.4-apache

# install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libsodium-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# enable mod_rewrite
RUN a2enmod rewrite

# install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    zip \
    sodium \
    sockets

# veify extensions
RUN php -m | grep -E "(pdo_mysql|sodium|zip|sockets)"

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# copy the application code
COPY . /var/www/html

WORKDIR /var/www/html

# install composer & project dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Install npm dependencies and build assets
RUN npm install
RUN npm run build

RUN php artisan storage:link
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
