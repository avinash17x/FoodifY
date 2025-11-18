# Use an official PHP image that includes Apache
FROM php:8.2-apache

# Copy your entire application code into the Apache web root
# Replace 'FoodifY/' with '.' if all files are in the repository root
COPY . /var/www/html/ 

# By default, the php:apache image starts Apache in the foreground, 
# so you might not even need a separate Start Command in the Render dashboard.

# Expose port 80 (standard HTTP)
EXPOSE 80
