#!/usr/bin/env bash

tput setaf 4; echo "###Task: Build Backend"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

# Unlock files
if [[ "$APP_ENV" == "production" ]]; then
    find app/etc/env.php generated/code generated/metadata \( -type f -or -type d \) -exec chmod 775 {} +
fi

# Install backend
bash dev/scripts/install/backend.sh

# Clean directories
rm -rf generated/code/Magento/*
rm -rf generated/code
rm -rf generated/metadata

php bin/magento setup:upgrade --safe-mode=1
php bin/magento setup:db-declaration:generate-whitelist
php bin/magento setup:di:compile

# Lock files
if [[ "$APP_ENV" == "production" ]]; then
    find app/etc/env.php generated/code generated/metadata \( -type f -or -type d \) -exec chmod 755 {} +
fi
