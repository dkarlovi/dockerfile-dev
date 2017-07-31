FROM php:7.1-fpm-alpine
ENV REDIS_VERSION 3.1.2
# required modules:
# ampq apcu igbinary intl opcache pcntl pdo_mysql redis xdebug
RUN apk add --update --no-cache fcgi icu inotify-tools tini autoconf cmake g++ icu-dev make openssl-dev && \
    docker-php-ext-install intl opcache pcntl pdo_mysql && \
    pecl install apcu igbinary xdebug && \
    docker-php-ext-enable apcu igbinary xdebug && \
    apk del autoconf g++ icu-dev make cmake openssl-dev && \
    rm -rf /tmp/pear /usr/src /usr/local/include/php /usr/include /var/cache/*
# order is important here
COPY vendor /app/vendor
COPY web /app/web
COPY var /app/var
COPY src /app/src
COPY etc /app/etc
RUN chown -R www-data /app/var
COPY ./.infra/docker/app/php.ini ./.infra/docker/app/ext-* /usr/local/etc/php/conf.d/
COPY ./.infra/docker/app/entrypoint.sh ./.infra/docker/app/docker-healthcheck /usr/local/bin/
HEALTHCHECK CMD ["docker-healthcheck"]
ENTRYPOINT ["/sbin/tini", "--", "/usr/local/bin/entrypoint.sh", "/usr/local/sbin/php-fpm", "--nodaemonize"]
WORKDIR /app
