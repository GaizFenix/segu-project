# Use the official PHP image with Apache
FROM php:7.2.2-apache

# Install the mysqli extension
RUN docker-php-ext-install mysqli

# Copy the application files to the Apache document root
COPY ./app /var/www/html

# Set the appropriate permissions for the Apache document root
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]