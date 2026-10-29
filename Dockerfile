FROM php:8.2-apache

# I-install ang system dependencies at PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# I-install ang Node.js at npm para ma-build ang frontend assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Kopyahin ang Composer mula sa opisyal nitong image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Itakda ang working directory
WORKDIR /var/www/html

# Kopyahin ang buong project files muna para naroon ang artisan at iba pang files
COPY . .

# Patakbuhin ang composer install at npm build
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# I-set ang tamang permissions sa storage at bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# I-expose ang port
EXPOSE 10000

# Patakbuhin ang migrations at i-start ang server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000