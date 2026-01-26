FROM php:8.2-apache

# === ล้าง MPM ทุกตัวแบบบังคับ ===
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf

# === เปิด MPM แค่ prefork ตัวเดียว ===
RUN ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# === PHP extension ===
RUN docker-php-ext-install mysqli

# === Apache modules ===
RUN a2enmod rewrite

# === App files ===
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html