FROM php:8.1-cli-alpine

RUN apk add icu-dev icu-data-full
RUN docker-php-ext-install intl

COPY ./crontab /etc/crontabs/root

COPY ./src /app
WORKDIR /app

RUN mkdir -p /data

CMD ["crond", "-f", "-d", "8"]
