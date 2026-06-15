FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

COPY . /app

RUN if [ -f /app/start.sh ]; then chmod +x /app/start.sh; fi

EXPOSE 10000

CMD ["./start.sh"]
