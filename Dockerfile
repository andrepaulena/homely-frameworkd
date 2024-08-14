FROM php:8.3-fpm

RUN set -xe \
    && apt-get update \
    && apt-get install -y libfreetype6-dev libonig-dev libpng-dev libjpeg-dev zlib1g-dev libzip-dev libmcrypt-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr \
    && docker-php-ext-install pdo pdo_mysql gd mbstring mysqli zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install mcrypt \
    && docker-php-ext-enable mcrypt

RUN apt-get update && apt-get install -y nginx supervisor

# Nginx configuration
COPY .build/nginx.conf /etc/nginx/nginx.conf
COPY .build/blog.com.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Supervisor configuration
COPY .build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www

EXPOSE 80

ENTRYPOINT [ "supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n" ]
