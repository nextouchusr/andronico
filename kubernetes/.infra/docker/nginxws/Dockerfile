ARG BASE_IMAGE=nginx:1.20-alpine

ARG CODE_REGISTRY=nextouch-docker.registry.com
ARG CODE_REPO=nextouch/nextouch
ARG CODE_TAG=latest

FROM ${CODE_REGISTRY}/${CODE_REPO}:${CODE_TAG} AS build

FROM ${BASE_IMAGE}

COPY magento.conf /etc/nginx/magento.conf
COPY default.conf /etc/nginx/default.conf
COPY cors.conf /etc/nginx/cors.conf
COPY cors-values.conf /etc/nginx/cors-values.conf
RUN mkdir -p /var/www/deploy \
&& chmod -R g+w /var/www/deploy \
&& chown -R 82:82 /var/www/deploy
COPY --from=build --chown=82:82 /var/www/deploy /var/www/deploy

WORKDIR /var/www/deploy
