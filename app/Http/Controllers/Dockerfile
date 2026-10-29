FROM php:8.2-apache

# I-install ang mga kailangang system dependencies para sa PHP at Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Kopyahin ang Composer mula sa opisyal nitong image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Itakda ang working directory sa loob ng container
WORKDIR /var/www/html

# Kopyahin ang buong project files mula sa laptop papunta sa container
COPY . .

# I-set ang tamang permissions para sa storage at bootstrap cache ng Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# I-expose ang port na gagamitin ng Render
EXPOSE 10000

# Patakbuhin ang database migration at i-start ang server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000