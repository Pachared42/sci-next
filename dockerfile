FROM php:8.2-apache

# เปิด mod rewrite (เผื่อใช้)
RUN a2enmod rewrite

# copy ไฟล์ทั้งหมดเข้า apache
COPY . /var/www/html/

# ตั้ง permission
RUN chown -R www-data:www-data /var/www/html