FROM php:8.2-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Apache permissions
RUN chown -R www-data:www-data /var/www/html/

# Copy entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Use ENTRYPOINT to run the initialization script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

EXPOSE 80