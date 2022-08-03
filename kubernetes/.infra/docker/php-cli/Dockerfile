ARG CODE_REGISTRY=nextouch-docker.registry.com
ARG CODE_REPO=nextouch/nextouch
ARG CODE_TAG=latest

FROM ${CODE_REGISTRY}/${CODE_REPO}:${CODE_TAG}

ADD custom-configs.ini /usr/local/etc/php/conf.d/custom-configs.ini

# for vendor/magento/module-indexer/Model/ProcessManager.php:92
#RUN docker-php-ext-install pcntl

ENTRYPOINT ["/docker-php-cli-entrypoint"]
CMD ["php", "-a"]
