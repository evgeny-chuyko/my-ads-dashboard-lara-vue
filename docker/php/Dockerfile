FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/backend

# Create system user
RUN addgroup -g 1000 www && \
    adduser -D -u 1000 -G www www

# Change ownership
RUN chown -R www:www /var/www/backend

# Switch to user
USER www

# Expose port
EXPOSE 9000

CMD ["php-fpm"]
