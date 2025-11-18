# Use an official PHP image that includes Apache
FROM php:8.2-apache

# Install the mysqli extension needed for database connection in process_order.php
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli

# Set the document root to /var/www/html/ and copy all your files into it
COPY . /var/www/html/

# Ensure Apache uses index.html if index.php is not found (and vice versa)
RUN echo 'DirectoryIndex index.php index.html' > /etc/apache2/conf-enabled/directory-index.conf

# Expose port 80 (standard HTTP)
EXPOSE 80
