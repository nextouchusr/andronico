#!/usr/bin/env bash

tput setaf 4; echo "###Task: Change Permissions"; tput sgr0

find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +
find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +
chmod u+x bin/magento
