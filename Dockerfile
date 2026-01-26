FROM dunglas/frankenphp:php8.2

RUN install-php-extensions mysqli

WORKDIR /app
COPY . /app

# Railway จะส่ง PORT มาให้
ENV PORT=8080

# บังคับให้ FrankenPHP listen ที่ PORT
CMD ["frankenphp", "run", "--listen", "0.0.0.0:${PORT}", "--root", "/app"]