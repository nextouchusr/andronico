ARG BASE_IMAGE=452103867664.dkr.ecr.eu-central-1.amazonaws.com/prd/base-php:latest

FROM ${BASE_IMAGE}

ARG STAGE=devbox
ARG BRAND
ARG COMPOSER_HOME
ENV COMPOSER_ALLOW_SUPERUSER 1

ADD kubernetes/.infra/docker/php-cli/custom-configs.ini /usr/local/etc/php/conf.d/custom-configs.ini
COPY kubernetes/.infra/docker/php-cli/docker-php-cli-entrypoint /docker-php-cli-entrypoint

# BUILD APP
COPY --chown=www-data:www-data . .
RUN STAGE=${STAGE} BRAND=${BRAND} COMPOSER_HOME=${COMPOSER_HOME} su www-data -s /bin/bash -c /var/www/deploy/kubernetes/build/build-php.sh
RUN rm -rf ./root/.composer \
&& rm -rf /home/www-data/.composer/cache \
&& rm -rf var/report \
&& rm -rf var/log \
&& rm -rf var/session \
&& mkdir -p var \
&& mkdir -p var/report \
&& mkdir -p var/log \
&& mkdir -p var/session \
&& chmod -R g+w var \
&& chown -R www-data:www-data var \
&& mkdir -p generated \
&& chmod -R g+w generated \
&& chown -R www-data:www-data generated
