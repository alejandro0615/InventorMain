# Use the official PHP image with Apache
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (for building assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# 🔥 Copiar solo composer primero (para evitar problemas de cache)
COPY composer.json composer.lock ./


RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

COPY . .

# 🔥 Instalar dependencias de Node y compilar assets de Vite
RUN npm install && npm run build

RUN rm -rf bootstrap/cache/*

RUN cp .env.example .env || true

# 🔥 Forzar autoload actualizado (CLAVE)
RUN composer dump-autoload

RUN php artisan package:discover

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache

# Configure Apache DocumentRoot to point to public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose the port Render will use
ARG PORT=10000
ENV PORT=${PORT}

# Cambiar Apache para escuchar en PORT
RUN sed -ri -e "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

EXPOSE ${PORT}

# Set entrypoint
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
