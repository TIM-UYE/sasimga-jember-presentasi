FROM php:8.3-fpm

# Install dependencies
# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip exif pcntl bcmath \
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

# Ensure .env exists and APP_KEY is generated
RUN if [ ! -f .env ]; then cp env.contoh .env; fi \
    && if ! grep -q '^APP_KEY=' .env || [ -z "$(grep '^APP_KEY=' .env | cut -d'=' -f2)" ]; then \
        php artisan key:generate --force; \
    fi

# Generate optimized autoload
RUN composer dump-autoload --optimize

# Cache configuration and routes for production
RUN php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Create storage directories
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
