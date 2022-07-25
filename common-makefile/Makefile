DOCKERFILE_GENERIC := $(if $(DOCKERFILE_GENERIC),$(DOCKERFILE_GENERIC),docker-compose-linux.yml)
DOCKERFILE_LINUX := $(if $(DOCKERFILE_LINUX),$(DOCKERFILE_LINUX),docker-compose-linux.yml)
DOCKERFILE_OSX := $(if $(DOCKERFILE_OSX),$(DOCKERFILE_OSX),docker-compose-osx.yml)
BUILD_SERVICES := $(if $(BUILD_SERVICES),$(BUILD_SERVICES),httpd fpm devtools)
CONTAINER_ROOT_DIR := $(if $(CONTAINER_ROOT_DIR),$(CONTAINER_ROOT_DIR),/var/www/deploy)
DOCKER_DIR := $(if $(DOCKER_DIR),$(DOCKER_DIR),hevelop_docker)

check_defined = \
    $(strip $(foreach 1,$1, \
        $(call __check_defined,$1,$(strip $(value 2)))))
__check_defined = \
    $(if $(value $1),, \
        $(error Undefined $1$(if $2, ($2))$(if $(value @), \
                required by target `$@')))

ifeq ($(OS),Windows_NT)
    dockerfile = ${DOCKERFILE_GENERIC}
    ifeq ($(PROCESSOR_ARCHITEW6432),AMD64)
        dockerfile = ${DOCKERFILE_GENERIC}
    else
        ifeq ($(PROCESSOR_ARCHITECTURE),AMD64)
            dockerfile = ${DOCKERFILE_GENERIC}
        endif
        ifeq ($(PROCESSOR_ARCHITECTURE),x86)
            dockerfile = ${DOCKERFILE_GENERIC}
        endif
    endif
else
    UNAME_S := $(shell uname -s)
    ifeq ($(UNAME_S),Linux)
        dockerfile = ${DOCKERFILE_LINUX}
    endif
    ifeq ($(UNAME_S),Darwin)
        dockerfile = ${DOCKERFILE_OSX}
    endif
endif

CUR_UID := $(shell id -u)
CUR_GID := $(shell id -g)



help:                  ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

dump-config:           ## Dump current config
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} exec app bash -c "cd ${CONTAINER_ROOT_DIR} && php bin/magento app:config:dump"

build-devbox:          ## build docker devbox env
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} build --build-arg UID=${CUR_UID} --build-arg GID=${CUR_GID} ${BUILD_SERVICES}

start-devbox:          ## Start docker devbox env
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} up -d

stop-devbox:           ## Stop docker devbox env
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} stop

restart-devbox:        ## Restart docker devbox env
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} restart

purge-devbox:          ## Purge docker devbox env
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} down

update-devbox:         ## Update devbox env
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} run --rm -u www-data devtools bash -c "${CONTAINER_ROOT_DIR}/update.sh devbox"

shell-service:			## Open bash with exec in requested service
	@read -p "Enter Service Name:" service; \
	cd ${DOCKER_DIR} && docker-compose -f ${dockerfile} exec -u www-data $$service bash
