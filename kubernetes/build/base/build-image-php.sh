#!/usr/bin/env bash
set -e

if [ -z $1 ]; then
    echo "Please give the release version as parameter! Eg: ./build-images.sh nextouch 100"
    exit 1
fi

RELEASE=$1
STAGE=${2:-minikube}
CODE_REGISTRY=${3:-local}
DEFAULT_UTILS_REGISTRY="452103867664.dkr.ecr.eu-central-1.amazonaws.com/prd/utils"
UTILS_REGISTRY=${4:-$DEFAULT_UTILS_REGISTRY}

ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`
SOURCE_DIR=`dirname "${SOURCE_DIR}"`
SOURCE_DIR=`dirname "${SOURCE_DIR}"`
SOURCE_DIR=`dirname "${SOURCE_DIR}"`

echo ${SOURCE_DIR};

# MAGENTO
docker build --build-arg BASE_IMAGE=$UTILS_REGISTRY:php-7.4-fpm-alpine -t $CODE_REGISTRY:$RELEASE -f kubernetes/.infra/docker/base/DockerfilePhp $SOURCE_DIR
if [ ! $? -eq 0 ]; then
    echo "Unable to complete the magento code build :("
    exit 1
fi
