FROM php:8.2-fpm-alpine3.18
# Install composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

RUN apk update && apk add --no-cache \
    libpng-dev                       \
    freetype-dev                     \
    oniguruma-dev                    \
    libxml2-dev

RUN rm -rf /var/lib/apt/lists/* && rm -rf /var/cache/apk/*
RUN docker-php-ext-configure gd --enable-gd --with-freetype
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

WORKDIR /var/www

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install        \
    --ignore-platform-reqs  \
    --no-ansi               \
    --no-interaction        \
    --no-scripts

COPY . .
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www-data

COPY --chown=www:www-data . /var/www

EXPOSE 80

RUN php artisan config:clear
RUN php artisan key:generate
# RUN php artisan migrate
CMD php artisan serve --host 0.0.0.0
