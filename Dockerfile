FROM php:7-fpm

RUN apt-get update \
    && apt-get install -y nginx \
    && apt-get clean && apt-get purge \
    && rm -rf /var/lib/apt/lists/* /var/cache/apt/*

EXPOSE 80 443

WORKDIR /opt/frameworkless/public

ENTRYPOINT /usr/local/sbin/php-fpm -D && /usr/sbin/nginx -g 'daemon off;'