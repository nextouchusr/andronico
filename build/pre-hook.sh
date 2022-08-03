#!/bin/bash
set -e

STAGE=${STAGE:-devbox}

# Get absolute path to main directory
ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`

#go up to the root of project
SOURCE_DIR="${SOURCE_DIR}/.."

#cd ${SOURCE_DIR} && ${SOURCE_DIR}/vendor/bin/zettr apply --excludeGroups=db,session,page_cache,cache,varnish $STAGE ${SOURCE_DIR}/.config/settings.csv || (echo "Zettr failed"; exit 1)
cd ${SOURCE_DIR} && php bin/magento setup:upgrade --keep-generated
