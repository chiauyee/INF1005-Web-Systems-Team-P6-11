FROM php:8.2-apache
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy entrypoint that auto-installs vendor/ on first boot
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

COPY src/ /var/www/html/

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
