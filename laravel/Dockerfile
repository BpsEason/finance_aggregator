# ./laravel/Dockerfile
FROM php:8.2-fpm-alpine

# 安裝必要的系統套件和 PHP 擴充功能
RUN apk add --no-cache \
    git \
    curl \
    bash \
    mysql-client \
    nodejs \
    npm \
    nginx \
    # 安裝 PHP 擴充功能
    && docker-php-ext-install pdo_mysql opcache \
    # 清理快取
    && rm -rf /var/cache/apk/*

# 設定 Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 設定工作目錄
WORKDIR /var/www/html

# 複製 Laravel 專案檔案 (在容器內部，將由 volumes 覆蓋，但此步驟確保 Composer 可用)
COPY . .

# 設定權限
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 複製 Nginx 設定
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# 暴露埠口
EXPOSE 8000

# 啟動 PHP-FPM (Nginx 會反向代理到此)
CMD ["php-fpm"]
