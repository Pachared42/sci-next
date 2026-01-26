FROM dunglas/frankenphp:php8.2

RUN install-php-extensions mysqli

WORKDIR /app
COPY . /app
COPY Caddyfile /etc/caddy/Caddyfile

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]