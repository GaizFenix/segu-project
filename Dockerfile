FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli


# Modified Dockerfile code by Gaizka

# # Use the official PHP image with Apache
# FROM php:7.2.2-apache

# # Install and enable the mysqli extension
# RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# # Uncomment a specific line in php.ini
# RUN sed -i 's/;extension=mysqli/extension=mysqli/' /usr/local/etc/php/php.ini

# # Copy the application files to the Apache document root
# COPY ./app /var/www/html

# # Set the appropriate permissions for the Apache document root
# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 755 /var/www/html

# # Expose port 80
# EXPOSE 80

# # Start Apache in the foreground
# CMD ["apache2-foreground"]