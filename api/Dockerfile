# Use the official PHP 8.2-apache image
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Copy the Apache configuration file with ServerName directive
COPY ./docker-config/apache.conf /etc/apache2/conf-available/

# Enable the new Apache configuration
RUN a2enconf apache.conf

RUN rm -f /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Copy the application code
COPY . /var/www/app

# Set the working directory
WORKDIR /var/www/app

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache
RUN chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache

# Expose port 80
EXPOSE 80
