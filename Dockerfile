
FROM php:7.1.6-cli

MAINTAINER Patsura Dmitry <talk@dmtry.me>

WORKDIR /usr/src/app
COPY . /usr/src/app/

RUN apt-get update \
    && apt-get -y upgrade \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        zlib1g-dev \
        libzmq-dev \
    && pecl install zmq-beta \
    && docker-php-ext-enable zmq \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENTRYPOINT php /usr/src/app/scripts/cruftflake.php -u tcp://127.0.0.1:5599 -m `echo %H | cksum | cut -c 1-3`

EXPOSE 5599
