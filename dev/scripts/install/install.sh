#!/usr/bin/env bash

tput setaf 4; echo "###Task: Install Project"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

# Install backend
bash dev/scripts/install/backend.sh

# Install frontend
bash dev/scripts/install/frontend.sh

# Change permissions
find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +
find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +
chmod u+x bin/magento

# Install Magento
bash dev/scripts/install/magento.sh

# Install Redis
bash dev/scripts/install/redis.sh

# Change deploy mode
php bin/magento deploy:mode:set "$APP_ENV"

# Change indexer mode
php bin/magento indexer:set-mode schedule

# Build frontend
bash dev/scripts/build_frontend.sh

# Refresh index and cache
bash dev/scripts/refresh.sh