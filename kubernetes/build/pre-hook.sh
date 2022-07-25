#!/bin/bash
set -e

BRAND=${BRAND:-$1}
STAGE=${STAGE:-$2}
SKIP_SETTINGS_AND_SETUP_UPGRADE=${3:-0}
NO_INTERACTION_SETUP_UPGRADE=${4:-0}

# Get absolute path to main directory
ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`

#go up to the root of project
SOURCE_DIR="${SOURCE_DIR}"

if [[ ${SKIP_SETTINGS_AND_SETUP_UPGRADE} == '1' ]]; then
    echo "skipping settings.csv apply and setup:upgrade"
elif [[ ${NO_INTERACTION_SETUP_UPGRADE} == '1' ]]; then
    echo "running no interactions setup:upgrade"
    cd ${SOURCE_DIR}/../../ && php bin/magento setup:upgrade --keep-generated -n -vvv
else
    echo "running settings.csv apply and setup:upgrade"
    cd ${SOURCE_DIR}/../../ && ./vendor/bin/zettr apply --excludeGroups=db,session,page_cache,cache,varnish $STAGE kubernetes/.config/${BRAND}/settings.csv
    cd ${SOURCE_DIR}/../../ && ${PHP_BIN} bin/magento setup:upgrade --keep-generated -vvv
fi
