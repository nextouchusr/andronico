#!/usr/bin/env bash

tput setaf 4; echo "###Task: Refresh Index & Cache"; tput sgr0

php bin/magento indexer:reindex
php bin/magento cache:enable
php bin/magento cache:clean
php bin/magento cache:flush