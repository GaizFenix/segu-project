FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli

# Modified Dockerfile by Gaizka

# Enable mod_headers module
# RUN a2enmod headers

# Add Apache configuration to set security headers
RUN echo 'Header always set Content-Security-Policy "default-src '\''self'\''; script-src '\''self'\'' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/; style-src '\''self'\'' https://cdn.jsdelivr.net https://fonts.googleapis.com; img-src '\''self'\'' data:; font-src '\''self'\'' https://fonts.gstatic.com; frame-src '\''self'\'' https://www.google.com/recaptcha/; frame-ancestors '\''self'\'';"' >> /etc/apache2/conf-available/security-headers.conf \    && echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> /etc/apache2/conf-available/security-headers.conf \
   && echo 'Header always set X-Content-Type-Options "nosniff"' >> /etc/apache2/conf-available/security-headers.conf \
   && echo 'Header always set X-XSS-Protection "1; mode=block"' >> /etc/apache2/conf-available/security-headers.conf \
   && a2enconf security-headers

# Start Apache in the foreground
CMD ["apache2-foreground"]
