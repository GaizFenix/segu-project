FROM php:7.2.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable mod_ssl module
RUN a2enmod ssl

# Generate SSL key and certificate
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/server.key -out /etc/ssl/certs/server.crt -subj "/C=US/ST=Denial/L=Bilbao/O=Denial/CN=localhost"

# Enable mod_headers module
# RUN a2enmod headers

# Add Apache configuration to set security headers
# RUN echo 'Header always set Content-Security-Policy "default-src '\''self'\''; script-src '\''self'\'' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/; style-src '\''self'\'' https://cdn.jsdelivr.net https://fonts.googleapis.com; img-src '\''self'\'' data:; font-src '\''self'\'' https://fonts.gstatic.com; frame-src '\''self'\'' https://www.google.com/recaptcha/; frame-ancestors '\''self'\'';"' >> /etc/apache2/conf-available/security-headers.conf \    && echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> /etc/apache2/conf-available/security-headers.conf \
#   && echo 'Header always set X-Content-Type-Options "nosniff"' >> /etc/apache2/conf-available/security-headers.conf \#
#   && echo 'Header always set X-XSS-Protection "1; mode=block"' >> /etc/apache2/conf-available/security-headers.conf \
#   && a2enconf security-headers

# Add Apache SSL configuration
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf \
    && echo '    Redirect permanent / https://localhost/' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '<IfModule mod_ssl.c>' > /etc/apache2/sites-available/default-ssl.conf \
    && echo '<VirtualHost _default_:443>' >> /etc/apache2/sites-available/default-ssl.conf \
    && echo '    DocumentRoot /var/www/html' >> /etc/apache2/sites-available/default-ssl.conf \
    && echo '    SSLEngine on' >> /etc/apache2/sites-available/default-ssl.conf \
    && echo '    SSLCertificateFile /etc/ssl/certs/server.crt' >> /etc/apache2/sites-available/default-ssl.conf \
    && echo '    SSLCertificateKeyFile /etc/ssl/private/server.key' >> /etc/apache2/sites-available/default-ssl.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/default-ssl.conf \
    && echo '</IfModule>' >> /etc/apache2/sites-available/default-ssl.conf \
    && a2ensite default-ssl
# Expose ports 80 and 443
EXPOSE 80 443

# Start Apache in the foreground
CMD ["apache2-foreground"]