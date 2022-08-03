#!/bin/bash
set -e

# "This script is only for devbox purpose"

# Get absolute path to main directory
ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`
PHP_BIN=$(which php7.4) || PHP_BIN=$(which php)

STAGE=${STAGE:-devbox}

STAGE=${STAGE} ${SOURCE_DIR}/build-php.sh

#go up to the root of project
SOURCE_DIR="${SOURCE_DIR}/.."

cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:upgrade || (echo "magento setup:upgrade failed"; exit 1)
#cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento index:reindex || (echo "magento index:reindex failed"; exit 1)
cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento cache:clean || (echo "magento cache:clean failed"; exit 1)

cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento deploy:mode:set developer || (echo "magento deploy:mode:set developer failed"; exit 1)
mkdir -p ${SOURCE_DIR}/.idea
touch ${SOURCE_DIR}/.idea/misc.xml
cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento dev:urn-catalog:generate ./.idea/misc.xml || (echo "magento dev:urn-catalog:generate error"; exit 1)

echo
echo "************************************************************"
echo "Update successful!"
echo "************************************************************"
echo
