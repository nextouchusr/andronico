#!/bin/bash

set -e

BRAND="nextouch"

ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
UPDATE_DIR=`dirname "${ABSPATH}"`
CONFIGURATION_DIR=`realpath "${UPDATE_DIR}"/../../.config`
BRAND_CONFIGURATION_DIR=`realpath "${UPDATE_DIR}"/../../.config/${BRAND}`
SOURCE_DIR=`realpath "${CONFIGURATION_DIR}"/../..`

ENV=$1

if [ -z $ENV ];
then
  echo "You must specify the environment"
  exit 1
fi

case "$ENV" in
  devbox | staging | production)
    # echo "general match"
    ;;
  *)
    echo "You must specify a valid environment [devbox|staging|production]"
    echo "use -nodocker to run setup without docker"
    exit 1
    ;;
esac

# Add project specific files
cd ${SOURCE_DIR}
cp ${CONFIGURATION_DIR}/env.template.php app/etc/env.php
cp ${BRAND_CONFIGURATION_DIR}/config.php app/etc/config.php

#if  [[ $2 = "--nodocker" ]];
#then
    # Run Zettr (processing only env.php file)
    cd ${SOURCE_DIR} && php kubernetes/tools/zettr.phar apply --groups=db,session,page_cache,cache,varnish $ENV ${BRAND_CONFIGURATION_DIR}/settings.csv || (echo "Zettr env failed"; exit 1)

    # Run Zettr (processing DB settings)
    cd ${SOURCE_DIR} && php kubernetes/tools/zettr.phar apply --excludeGroups=db,session,page_cache,cache,varnish $ENV ${BRAND_CONFIGURATION_DIR}/settings.csv || (echo "Zettr settings failed"; exit 1)

    # Magento operations
    cd ${SOURCE_DIR} && php -d memory_limit=-1 bin/magento setup:upgrade -vvv || (echo "magento setup:upgrade failed"; exit 1)
    cd ${SOURCE_DIR} && php -d memory_limit=-1 bin/magento cache:clean || (echo "magento cache:clean failed"; exit 1)

    cd ${SOURCE_DIR} && php -d memory_limit=-1 bin/magento deploy:mode:set developer || (echo "magento deploy:mode:set developer failed"; exit 1)

OLDPWD="${PWD}"
cd ${OLDPWD}

echo
echo "------------------"
echo "Update successful!"
echo "------------------"
echo
