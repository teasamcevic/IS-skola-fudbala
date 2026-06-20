FROM node:22-alpine AS angular-build

WORKDIR /frontend

COPY angular-frontend/package.json angular-frontend/package-lock.json ./
RUN npm ci

COPY angular-frontend/ ./
# Angular koristi isti osnovni stylesheet kao postojeći Laravel/Blade interfejs.
COPY public/css/ /public/css/
RUN npm run build

FROM php:8.3-cli-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev libxml2-dev unzip \
    && docker-php-ext-install dom mbstring pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY --from=angular-build /frontend/dist/angular-frontend/ /app/public/angular-build/

EXPOSE 8080

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
