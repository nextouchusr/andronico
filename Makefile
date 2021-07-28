#!make

help:
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF}' $(MAKEFILE_LIST)

magento_install: ## Install Magento
	bash dev/scripts/install/install.sh

magento_upgrade: ## Upgrade Magento
	make build_backend
	make build_frontend
	make refresh

build_backend: ## Build project backend
	bash dev/scripts/build_backend.sh

build_frontend: ## Build project frontend
	bash dev/scripts/build_frontend.sh

refresh: ## Refresh index and cache
	bash dev/scripts/refresh.sh

compile_di: ## Compile generated classes
	php bin/magento setup:di:compile

cache_flush: ## Cache flush
	php bin/magento cache:flush

config_import: ## Import Magento configuration
	php bin/magento app:config:import

config_export: ## Export Magento configuration
	php bin/magento app:config:dump

resize_images: ## Resize images according to view.xml
	php bin/magento catalog:images:resize

# Static & Quality Tools
phpcs: ## Run phpcs to analyze code
	php vendor/bin/phpcs --standard=Magento2 app/code/Nextouch

phpcbf: ## Run phpcbf to fix code
	php vendor/bin/phpcbf --standard=Magento2 app/code/Nextouch

static: ## Run static tests on custom files
	php ./vendor/bin/phpunit --testsuite="Local Test Suite" -c dev/tests/static/phpunit.xml

# Debugging
template_hints_enable: ## Enable template hints
	php bin/magento dev:template-hints:enable
	make refresh

template_hints_disable: ## Disable template hints
	php bin/magento dev:template-hints:disable
	make refresh

# Docker
run: ## Start containers
	cd docker && docker-compose up -d

stop: ## Stop containers
	cd docker && docker-compose stop

restart: ## Restart containers
	cd docker && docker-compose restart

remove: ## Stop containers and completely remove everything
	cd docker && docker-compose rm -sf

php_container: ## Connect to php container
	cd docker && docker-compose exec php bash

db_container: ## Connect to db container
	cd docker && docker-compose exec db bash

cache_container: ## Connect to cache container
	cd docker && docker-compose exec cache bash

search_container: ## Connect to search container
	cd docker && docker-compose exec search bash

cron_container: ## Connect to cron container
	cd docker && docker-compose exec cron bash

ftpd_container: ## Connect to ftpd container
	cd docker && docker-compose exec ftpd bash
