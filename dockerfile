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
    nodejs-current \
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

# Install PHP extensions
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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Composer install
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Install node modules
RUN npm install

# Build vite assets
RUN npm run build

# Permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
