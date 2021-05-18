#!/usr/bin/env bash

tput setaf 4; echo "###Task: Install Frontend Packages"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

if [[ "$APP_ENV" == "production" ]]; then
    npm install --only=prod
else
    npm install
fi
