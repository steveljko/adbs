FROM composer:latest AS vendor
WORKDIR /app
RUN apk add --no-cache linux-headers \
    && docker-php-ext-install sockets
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.4-fpm-alpine
# install runtime dependencies
RUN apk add --no-cache \
    bash \
    nginx \
    supervisor \
    libpq \
    libzip \
    oniguruma \
    sqlite-libs \
    && apk add --no-cache --virtual .build-deps \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    linux-headers \
    && docker-php-ext-install pdo_mysql pdo_pgsql pdo_sqlite zip mbstring sockets opcache \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/*

# configure PHP for production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.validate_timestamps=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

# copy application files
COPY --chown=www-data:www-data . /var/www

COPY --from=vendor /app/vendor /var/www/vendor
COPY --from=vendor /app/composer.json /app/composer.lock /var/www/

# Copy only built assets from frontend
COPY --from=frontend /app/public/build /var/www/public/build

RUN if [ -f .env.example ]; then cp .env.example .env; fi \
    && chown www-data:www-data .env \
    && chmod 664 .env \
    && rm -rf bootstrap/cache/*.php \
    && php artisan key:generate \
    && php artisan route:cache \
    && php artisan view:cache

# Nginx configuration
RUN rm -rf /etc/nginx/http.d/* && \
    cat > /etc/nginx/http.d/default.conf <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/public;
    index index.php;
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    charset utf-8;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# PHP-FPM configuration
RUN sed -i 's/listen = .*/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf

# supervisor configuration
RUN mkdir -p /etc/supervisor/conf.d && \
    cat > /etc/supervisor/conf.d/supervisord.conf <<'EOF'
[supervisord]
nodaemon=true
user=root
logfile=/dev/null
logfile_maxbytes=0
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=php-fpm -F
stdout_logfile=/dev/null
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
autorestart=true

[program:nginx]
command=nginx -g 'daemon off;'
stdout_logfile=/dev/null
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
autorestart=true
EOF

# permissions and cleanup
RUN mkdir -p /var/www/storage/logs /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache && \
    rm -rf /var/www/tests /var/www/.git

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
