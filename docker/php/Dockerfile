FROM php:8.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs
    
# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo '[www]' > /usr/local/etc/php-fpm.d/zz-custom.conf \
    && echo 'pm.max_children = 20' >> /usr/local/etc/php-fpm.d/zz-custom.conf

# Set working directory
WORKDIR /var/www/html