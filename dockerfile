FROM php:8.3-fpm-alpine

WORKDIR /var/www

# Install dependencies
RUN apk add --no-cache \
    freetype \
    libjpeg-turbo \
    libpng \
    libzip \
    oniguruma \
    icu-libs \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Build deps
RUN apk add --no-cache --virtual .build-deps \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    autoconf \
    gcc \
    g++ \
    make

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    gd \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cache composer layer
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist

# Cache npm layer
COPY package*.json ./

RUN npm ci

# Copy source code
COPY . .

# Generate optimized autoload
RUN composer dump-autoload --optimize

# Build frontend
RUN npm run build

# Permission
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
