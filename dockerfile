FROM node:20 AS node_builder

WORKDIR /app

COPY package*.json ./
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm install

RUN npm run build
# Install node dependencies and build assets
RUN if [ -f package-lock.json ]; then \
            npm ci --prefer-offline --no-audit --progress=false; \
        else \
            npm install --no-audit --progress=false; \
        fi

FROM php:8.3-fpm

# Install dependencies
# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip build-essential autoconf pkg-config libssl-dev zlib1g-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip exif pcntl bcmath \
    && pecl channel-update pecl.php.net || true \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files AND artisan file first
COPY composer.json composer.lock artisan ./

# Copy essential Laravel directories needed for package:discover
COPY bootstrap/ ./bootstrap/
COPY app/ ./app/
COPY config/ ./config/
COPY routes/ ./routes/

# Create Laravel storage folders first
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Copy the rest of the application
COPY . .

# Copy built frontend assets from node builder
COPY --from=node_builder /app/public/build /var/www/public/build

# Ensure .env exists (CI copies env.contoh to .env before build)
RUN if [ ! -f .env ]; then cp env.contoh .env; fi

# Generate optimized autoload
RUN composer dump-autoload --optimize

# Create storage directories
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
