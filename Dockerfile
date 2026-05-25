FROM php:8.4-cli

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /app/

WORKDIR /app

CMD ["php", "-S", "0.0.0.0:$PORT"]