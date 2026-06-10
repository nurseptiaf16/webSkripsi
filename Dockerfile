FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install gd zip pdo pdo_mysql bcmath mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --optimize-autoloader --no-scripts --no-interaction
RUN npm install
RUN npm run build

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]