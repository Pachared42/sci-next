FROM dunglas/frankenphp:php8.2

# ติดตั้ง mysqli (วิธีที่ถูกต้องกับ FrankenPHP)
RUN install-php-extensions mysqli

# copy project
COPY . /app
WORKDIR /app