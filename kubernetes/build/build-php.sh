#!/bin/bash
set -e

STAGE=${STAGE:-devbox}
COMPOSER_HOME=${COMPOSER_HOME}

# Get absolute path to main directory
ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`
PHP_BIN=$(which php7.4) || PHP_BIN=$(which php)

#go up to the root of project
SOURCE_DIR="${SOURCE_DIR}/../.."

# Run composer
cd ${SOURCE_DIR} && ${PHP_BIN} kubernetes/tools/composer install --prefer-dist --no-dev --ignore-platform-reqs -vvv || (echo "Composer failed"; exit 1)

cp ${SOURCE_DIR}/kubernetes/.config/env.template.php ${SOURCE_DIR}/app/etc/env.php || (echo "Copy failed"; exit 1)


# Run EnvSettingsTool (processing all)
if [ $STAGE = "devbox" ]; then
    cd ${SOURCE_DIR} && ${PHP_BIN} ${SOURCE_DIR}/kubernetes/tools/zettr.phar apply --groups=db,session,page_cache,cache,varnish $STAGE ${SOURCE_DIR}/kubernetes/.config/${BRAND}/settings.csv || (echo "Zettr db failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} ${SOURCE_DIR}/kubernetes/tools/zettr.phar apply --excludeGroups=db,session,page_cache,cache,varnish $STAGE ${SOURCE_DIR}/kubernetes/.config/${BRAND}/settings.csv || (echo "Zettr failed"; exit 1)

     # Magento operations
        #cd ${SOURCE_DIR} && php bin/magento deploy:mode:set developer
        cd ${SOURCE_DIR} && php bin/magento setup:upgrade || (echo "magento setup:upgrade failed"; exit 1)
        cd ${SOURCE_DIR} && php bin/magento cache:clean || (echo "magento cache:clean failed"; exit 1)
else
    if [ -f "${SOURCE_DIR}/kubernetes/.config/${BRAND}/${STAGE}/robots.txt" ]; then
        cp ${SOURCE_DIR}/kubernetes/.config/${BRAND}/${STAGE}/robots.txt ${SOURCE_DIR}/pub/robots.txt
    else
        cp ${SOURCE_DIR}/kubernetes/.config/robots_default.txt ${SOURCE_DIR}/robots.txt
    fi
    # Run EnvSettingsTool (processing only rows tagged with 'db')
    cp ${SOURCE_DIR}/kubernetes/.config/env.noredis-template.php ${SOURCE_DIR}/app/etc/env.php
    cd ${SOURCE_DIR} && ${SOURCE_DIR}/kubernetes/tools/zettr.phar apply --groups=db,session,page_cache,varnish $STAGE ${SOURCE_DIR}/kubernetes/.config/${BRAND}/settings.csv || (echo "Zettr db failed"; exit 1)

    cp ${SOURCE_DIR}/kubernetes/.config/${BRAND}/config.php ${SOURCE_DIR}/app/etc/config.php

    # Magento operations on files
    cp ${SOURCE_DIR}/kubernetes/.config/env.small-template.php ${SOURCE_DIR}/app/etc/env.php
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:di:compile || (echo "magento setup:di:compile failed"; exit 1)
    cd ${SOURCE_DIR} && rm -rf var/view_preprocessed/
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy en_US --area=adminhtml || (echo "magento setup:static-content:deploy failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy it_IT --area=adminhtml || (echo "magento setup:static-content:deploy failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy it_IT --area=frontend  --exclude-theme Magento/luma || (echo "magento setup:static-content:deploy failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy en_US --area=frontend  --exclude-theme Magento/luma || (echo "magento setup:static-content:deploy failed"; exit 1)


    cp ${SOURCE_DIR}/.config/pub/get.php ${SOURCE_DIR}/pub/get.php
    cp ${SOURCE_DIR}/kubernetes/.config/env.template.php ${SOURCE_DIR}/app/etc/env.php

    cd ${SOURCE_DIR} && ${PHP_BIN} kubernetes/tools/zettr.phar apply --groups=db,session,page_cache,cache,varnish $STAGE ${SOURCE_DIR}/kubernetes/.config/${BRAND}/settings.csv || (echo "Zettr db failed"; exit 1)
fi
