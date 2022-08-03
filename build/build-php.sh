#!/bin/bash
set -e

STAGE=${STAGE:-devbox}
COMPOSER_HOME=${COMPOSER_HOME}

# Get absolute path to main directory
ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`
PHP_BIN=$(which php7.4) || PHP_BIN=$(which php)

#go up to the root of project
SOURCE_DIR="${SOURCE_DIR}/.."

# Run composer
if [ $STAGE = "devbox" ]; then
    cd ${SOURCE_DIR} && ${PHP_BIN} tools/composer install --prefer-dist || (echo "Composer failed"; exit 1)
else
    cd ${SOURCE_DIR} && ${PHP_BIN} tools/composer install --prefer-dist --no-dev --ignore-platform-reqs || (echo "Composer failed"; exit 1)
fi

#cp ${SOURCE_DIR}/.config/env.template.php ${SOURCE_DIR}/app/etc/env.php || (echo "Copy failed"; exit 1)
#cp ${SOURCE_DIR}/.config/pub/index.php ${SOURCE_DIR}/pub || (echo "Copy failed"; exit 1)

# Run EnvSettingsTool (processing all)
if [ $STAGE = "devbox" ]; then
    cd ${SOURCE_DIR}
    #cd ${SOURCE_DIR} && ${PHP_BIN} ${SOURCE_DIR}/vendor/bin/zettr apply --groups=db,session,page_cache,cache,varnish $STAGE ${SOURCE_DIR}/.config/settings.csv || (echo "Zettr db failed"; exit 1)
    #cd ${SOURCE_DIR} && ${PHP_BIN} ${SOURCE_DIR}/vendor/bin/zettr apply --excludeGroups=db,session,page_cache,cache,varnish $STAGE ${SOURCE_DIR}/.config/settings.csv || (echo "Zettr failed"; exit 1)
else
    #cp ${SOURCE_DIR}/.config/${STAGE}/robots.txt ${SOURCE_DIR}/pub/robots.txt
    # Run EnvSettingsTool (processing only rows tagged with 'db')
    #cp ${SOURCE_DIR}/.config/env.noredis-template.php ${SOURCE_DIR}/app/etc/env.php
    #cd ${SOURCE_DIR} && ${SOURCE_DIR}/vendor/bin/zettr apply --groups=db,session,page_cache,varnish $STAGE ${SOURCE_DIR}/.config/settings.csv || (echo "Zettr db failed"; exit 1)

    # Magento operations on files
    cd ${SOURCE_DIR} && ${PHP_BIN} bin/magento deploy:mode:set production -s
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:di:compile || (echo "magento setup:di:compile failed"; exit 1)
    #cp ${SOURCE_DIR}/.config/env.small-template.php ${SOURCE_DIR}/app/etc/env.php
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy en_US --area=adminhtml || (echo "magento setup:static-content:deploy failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy it_IT --area=adminhtml || (echo "magento setup:static-content:deploy failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy it_IT --area=frontend || (echo "magento setup:static-content:deploy failed"; exit 1)
    cd ${SOURCE_DIR} && ${PHP_BIN} -d memory_limit=-1 bin/magento setup:static-content:deploy en_US --area=frontend || (echo "magento setup:static-content:deploy failed"; exit 1)

    #cp ${SOURCE_DIR}/.config/env.template.php ${SOURCE_DIR}/app/etc/env.php
    #cd ${SOURCE_DIR} && ${SOURCE_DIR}/vendor/bin/zettr apply --groups=db,session,page_cache,cache,varnish,rabbitmq $STAGE ${SOURCE_DIR}/.config/settings.csv || (echo "Zettr db failed"; exit 1)
fi
