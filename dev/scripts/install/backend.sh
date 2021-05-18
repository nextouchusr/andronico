#!/usr/bin/env bash

tput setaf 4; echo "###Task: Install Backend Packages"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

if [[ "$APP_ENV" == "production" ]]; then
    composer install --no-dev
else
    composer install
fi