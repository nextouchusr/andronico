#!/usr/bin/env bash

tput setaf 4; echo "###Task: Build Frontend"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

# Install frontend
bash dev/scripts/install/frontend.sh

# Clean directories
rm -rf var/view_preprocessed/*
rm -rf pub/static/adminhtml
rm -rf pub/static/frontend
rm -rf pub/static/deployed_version.txt

# Compile assets
php bin/magento setup:static-content:deploy --area adminhtml -f
php bin/magento setup:static-content:deploy --area frontend -f