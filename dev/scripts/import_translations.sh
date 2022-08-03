#!/usr/bin/env bash

tput setaf 4; echo "###Task: Import Translations"; tput sgr0

# Import it_IT translations
php bin/magento experius_missingtranslations:collect --magento --locale it_IT
php bin/magento experius_missingtranslations:existing-translations-to-database --global --locale it_IT
php bin/magento experius_missingtranslations:missing-translations-to-database --global --locale it_IT
