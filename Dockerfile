# ── Stage 1: Build Vue Frontend ──────────────────────────────────────
FROM node:20-alpine AS frontend-build

WORKDIR /build

# Copy manifest dulu supaya layer npm ci ke-cache
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci

COPY frontend/ .
RUN npm run build
# outDir: '../public' → hasil build ada di /public

# ── Stage 2: Laravel PHP App ────────────────────────────────────────
FROM php:8.3-cli

# Dependencies & ekstensi PHP untuk Laravel
# - --no-install-recommends: skip paket "rekomendasi" yang nggak perlu
# - -j$(nproc): compile ekstensi paralel (lebih cepat)
# - git tetap ada demi aman; hapus kalau semua paket composer ter-install via dist
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer dipin ke major version 2 (bukan latest)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Layer vendor: hanya rebuild kalau composer.json / composer.lock berubah
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# Kodingan backend
COPY . .

# Hasil build frontend → public Laravel
COPY --from=frontend-build /public/index.html /app/public/index.html
COPY --from=frontend-build /public/assets /app/public/assets

# Finalisasi autoload + jalankan package:discover (butuh kode lengkap)
RUN composer dump-autoload --no-dev --optimize --no-interaction

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]