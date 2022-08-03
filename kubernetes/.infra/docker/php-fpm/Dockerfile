ARG CODE_REGISTRY=nextouch-docker.registry.com
ARG CODE_REPO=nextouch/nextouch
ARG CODE_TAG=latest

FROM ${CODE_REGISTRY}/${CODE_REPO}:${CODE_TAG}

RUN docker-php-ext-install opcache
COPY ./conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./conf.d/custom-configs.ini /usr/local/etc/php/conf.d/custom-configs.ini

ENTRYPOINT ["docker-php-entrypoint"]
CMD ["php-fpm"]
