FROM php:8.3-cli

# Install dependencies & PHP extensions yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy seluruh kodingan ke container
COPY . .

EXPOSE 8000

# Command buat jalanin Laravel artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
