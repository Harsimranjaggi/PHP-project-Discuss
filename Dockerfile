FROM php:8.2-fpm-alpine

# Copy project files
WORKDIR /app
COPY . .

# Set document root (adjust if needed)
WORKDIR /app 

EXPOSE 9000
CMD ["php-fpm"]