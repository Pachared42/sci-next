FROM php:8.2-apache

# ปิด mpm อื่น ๆ ก่อน (กันชน)
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# ติดตั้ง mysqli
RUN docker-php-ext-install mysqli

# เปิด rewrite
RUN a2enmod rewrite

# copy project
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html