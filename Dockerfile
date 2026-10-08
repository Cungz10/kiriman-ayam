# ── Stage 1: Build Vue Frontend ──────────────────────────────────────
FROM node:20-alpine AS frontend-build

WORKDIR /build

# Copy frontend files
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci

COPY frontend/ .
RUN npm run build
# Hasil build ada di /build/../public → karena outDir: '../public'
# Vite outputnya relatif ke WORKDIR, jadi hasilnya di /public

# ── Stage 2: Laravel PHP App ────────────────────────────────────────
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
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy seluruh kodingan backend ke container
COPY . .

# Copy hasil build frontend ke folder public Laravel
COPY --from=frontend-build /public/index.html /app/public/index.html
COPY --from=frontend-build /public/assets /app/public/assets

# Install PHP dependencies (tanpa dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 8000

# Command buat jalanin Laravel artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
