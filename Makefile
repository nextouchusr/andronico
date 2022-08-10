BUILD_SERVICES := nginx fpm devtools

-include common-makefile/Makefile

# Variables
current_dir := $(shell pwd)
docker_compose:= cd ${DOCKER_DIR} && docker-compose -f ${dockerfile}
run_devtools := ${docker_compose} run --rm devtools

#@todo aggiungere composer-install update-devbox dopo start-devbox quando installato magento.
init-project: get-common-makefile ## init project files
	make dist-files build-devbox start-devbox composer-install update-devbox \
	|| printf "\n\033[0;31mIMPORT DATABASE AND RE-RUN make update-devbox\033[0m\n"

composer-install:   			## run composer install
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'cd /var/www/deploy && tools/composer install'"

composer-remove:   			## run composer install
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'cd /var/www/deploy && tools/composer remove $(packages)'"

composer-require:   			##  Update packages inserted in "packages" param (ex. packages='hevelop/common-makefile')
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'cd /var/www/deploy && php -dmemory_limit=5G tools/composer require $(packages) -vvv'"

composer-update:			## Update packages inserted in "packages" param (ex. packages='hevelop/common-makefile')
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'cd /var/www/deploy && php -dmemory_limit=5G tools/composer update $(packages) $(param1) $(param2) -vvv'"

get-common-makefile:
	git submodule update --init

git-pre-commit-hook: phpcs-pre-commit

cghooks-update:				## Update git hooks
	${run_devtools} bash -c \
	"su www-data -s /bin/bash -c \
	'vendor/bin/cghooks update --git-dir=.git'"

cghooks-list:				## list hooks
	${run_devtools} bash -c \
	"su www-data -s /bin/bash -c \
	'vendor/bin/cghooks list-hooks --git-dir=.git'"

phpcs-pre-commit:			## run phpcs on committing files in pre-commit git hook
	[ -z "$(shell git diff --cached --name-only --diff-filter=ACMRTUXB -- '*.php' '*.phtml' )" ] || \
	(${run_devtools} bash -c "\
	su www-data -s /bin/bash -c '\
		vendor/bin/phpcs -q -s \
		--runtime-set installed_paths vendor/magento/magento-coding-standard \
		--standard=Standards \
		--extensions=php,phtml \
		--error-severity=1 \
		--warning-severity=3 \
		--report=code \
		$(shell git diff --cached --name-only --diff-filter=ACMRTUXB -- '*.php' '*.phtml')'")

phpcs-pre-push:				## run phpcs on pughing files in pre-push git hook
	[ -z "$(shell git diff --cached --name-only --diff-filter=ACMRTUXB @{upstream} -- '*.php' '*.phtml' )" ] || \
	(${run_devtools} bash -c "\
	su www-data -s /bin/bash -c '\
		vendor/bin/phpcs -q -s \
		--runtime-set installed_paths vendor/magento/magento-coding-standard \
		--standard=Standards \
		--extensions=php,phtml \
		--error-severity=1 \
		--warning-severity=3 \
		--report=code \
		$(shell git diff --cached --name-only --diff-filter=ACMRTUXB @{upstream})'")

phpcs-project:				## run phpcs on hevelop files in the project
	${run_devtools} bash -c "\
	su www-data -s /bin/bash -c '\
		vendor/bin/phpcs -q -s \
		--runtime-set installed_paths vendor/magento/magento-coding-standard \
		--standard=Standards \
		--extensions=php,phtml \
		--error-severity=1 \
		--warning-severity=3 \
		--report=code \
		app/code/Hevelop app/design'"

phpcbf-file: 				## run phpcbf on the file ex. make phpcbf-file file="path/to/file.php"
	${run_devtools} bash -c "\
	su www-data -s /bin/bash -c '\
		vendor/bin/phpcbf -q -s \
		--runtime-set installed_paths vendor/magento/magento-coding-standard \
		--standard=Standards \
		--extensions=php,phtml \
		--error-severity=1 \
		--warning-severity=3 \
		--report=code \
		$(file)'"

update-devbox: 				## Update devbox env
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'tools/update.sh'"

setup-upgrade: 				## Setup upgrade
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'php bin/magento setup:upgrade'"

setup-di-compile: 				## Setup di compile
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'php bin/magento setup:di:compile'"

dist-files:
	cd ${DOCKER_DIR} \
	&& [ -f .env ] || cp .env.dist .env \
	&& [ -f docker-compose-linux.yml ] || cp docker-compose-linux.yml.dist docker-compose-linux.yml \
	&& [ -f docker-compose-osx.yml ] || cp docker-compose-osx.yml.dist docker-compose-osx.yml 2>/dev/null \
	&& cd -

devtools:				## enter devtools
	${run_devtools} bash

devtools-www-data:				## enter devtools as www-data user
	${run_devtools} bash -c "su www-data -s /bin/bash"

cache-flush:				## Redis flushall
	${docker_compose} exec redis redis-cli flushall

n98:					## run n98-magerun22 [command] [additional]
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'php tools/n98-magerun2 $(command) $(additional)'"

urn-generate:				## generate magento urn-catalog [catalog]
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'php bin/magento dev:urn-catalog:generate $(catalog)'"

cert:
	mkdir -p $(HOME)/nginx/certs
	docker run -it --rm --init \
	-e UID=$(uid) \
	-e GID=$(gid) \
	-w="/certs" \
	--mount type=bind,source=$(current_dir)/.config/ssl,target=/cert_config/ \
	--mount type=bind,source=$(HOME)/nginx/certs,target=/certs \
	--mount type=bind,source=$(current_dir)/tools/create_certificates.sh,target=/usr/local/bin/create_certificates \
	alpine:latest create_certificates

db-console:   				## Open a db console
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'php tools/n98-magerun2 db:console'"

cache-clean:   				## Clean all magento cache
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'rm -rf /tmp/magento && php -d memory_limit=1G tools/n98-magerun2 cache:clean'"

cache-clean-fe:				## Clean layout block_html full_page magento cache
	${run_devtools} -c "su www-data -s /bin/bash -c 'rm -rf /tmp/magento && rm -rf var/view_preprocessed && php tools/n98-magerun2 cache:clean block_html layout full_page'"

clean:
	cd docker && docker-compose -f ${dockerfile} run --rm devtools sudo -u www-data bash -c "rm -rf /tmp/magento && cd ${CONTAINER_ROOT_DIR} && php -d memory_limit=1G tools/n98-magerun2 cache:flush && php -d memory_limit=1G tools/n98-magerun2 dev:asset:clear"

bin/magento:
	${run_devtools} bash -c "su www-data -s /bin/bash -c 'php -dmemory_limit=3G bin/magento $(command)'"

enable-fpm-xdebug: ## enable fpm xdebug
	cd docker && docker-compose -f ${dockerfile} exec fpm bash -c "docker-php-ext-enable xdebug"
	cd docker && docker-compose -f ${dockerfile} restart fpm

disable-fpm-xdebug: ## disable fpm xdebug
	cd docker && docker-compose -f ${dockerfile} stop fpm
	cd docker && docker-compose -f ${dockerfile} rm fpm
	cd docker && docker-compose -f ${dockerfile} up -d

critical:
	npx mix -p

critical-watch:
	npx mix watch

critical-dev:
	npx mix

build-prod-fe:
	rm -rf pub/static/frontend && rm -rf pub/static/_cache && rm -rf var/view_preprocessed/pub
	make setup-upgrade
	make setup-di-compile
	make bin/magento command="setup:static-content:deploy -f --area=frontend --exclude-theme=luma -j6 --language=it_IT"

static-content:
	rm -rf pub/static/frontend && rm -rf pub/static/_cache && rm -rf var/view_preprocessed/pub
	make bin/magento command="setup:static-content:deploy -f --area=frontend --exclude-theme=luma -j6 --language=it_IT"
