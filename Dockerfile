FROM dunglas/frankenphp:php8.2

RUN install-php-extensions mysqli

WORKDIR /app
COPY . /app

# บอก Caddy/FrankenPHP ให้ฟัง port ของ Railway
ENV CADDY_SERVER_ADDR=0.0.0.0:${PORT}

CMD ["frankenphp", "run"]