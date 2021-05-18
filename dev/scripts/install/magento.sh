#!/usr/bin/env bash

tput setaf 4; echo "###Task: Install Magento"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

php bin/magento setup:install \
    --base-url="$APP_BASE_URL" \
    --db-host="$MYSQL_HOST" \
    --db-name="$MYSQL_DATABASE" \
    --db-user="$MYSQL_USER" \
    --db-password="$MYSQL_PASSWORD" \
    --cleanup-database \
    --search-engine="$ELASTICSEARCH_ENGINE" \
    --elasticsearch-host="$ELASTICSEARCH_HOST" \
    --backend-frontname="$APP_BACKEND_FRONTNAME" \
    --admin-firstname="$ADMIN_FIRSTNAME" \
    --admin-lastname="$ADMIN_LASTNAME" \
    --admin-email="$ADMIN_EMAIL" \
    --admin-user="$ADMIN_USER" \
    --admin-password="$ADMIN_PASSWORD"