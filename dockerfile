FROM php:8.2-apache

# ติดตั้ง mysqli
RUN docker-php-ext-install mysqli

# เปิด rewrite (ถ้ามี .htaccess)
RUN a2enmod rewrite

# copy project
COPY . /var/www/html/

# permission
RUN chown -R www-data:www-data /var/www/html