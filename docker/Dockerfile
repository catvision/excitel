# docker/Dockerfile

# Use the official PHP 7.4 image with Apache
FROM php:7.4-apache

# Enable necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your backend code to the container
COPY ../ /var/www/html

# Set proper permissions for the Apache user
RUN chown -R www-data:www-data /var/www/html

# Expose the default Apache port
EXPOSE 80