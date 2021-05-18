#!/usr/bin/env bash

tput setaf 4; echo "###Task: Install Redis"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

php bin/magento setup:config:set \
    --cache-backend=redis \
    --cache-backend-redis-server="$REDIS_HOST" \
    --cache-backend-redis-port="$REDIS_PORT" \
    --cache-backend-redis-db=0

php bin/magento setup:config:set \
    --page-cache=redis \
    --page-cache-redis-server="$REDIS_HOST" \
    --page-cache-redis-port="$REDIS_PORT" \
    --page-cache-redis-db=1

php bin/magento setup:config:set \
    --session-save=redis \
    --session-save-redis-host="$REDIS_HOST" \
    --session-save-redis-port="$REDIS_PORT" \
    --session-save-redis-log-level=3 \
    --session-save-redis-db=2