#!/usr/bin/env bash
set -ex

# echo "Switching to minikube context..."
# kubectl config use-context minikube

# KUBEENV=$(env | grep MINIKUBE_ACTIVE_DOCKERD || true)

# if [[ $KUBEENV != *"minikube"* ]]; then
#     echo "If you want the images to be available on the kubernetes environment, remember to launch"
#     echo "  eval \$(minikube -p minikube docker-env)"
#     echo ""
#     exit 1
# fi

if [ -z $1 ]; then
    echo "Please give the brand name as parameter! Eg: ./build-images.sh nextouch 100"
    exit 1
fi

if [ -z $2 ]; then
    echo "Please give the release version as parameter! Eg: ./build-images.sh nextouch 100"
    exit 1
fi

BRANDS=("nextouch")
BRAND=$1

if [[ ! " ${BRANDS[@]} " =~ " ${BRAND} " ]]; then
    echo "Invalid brand '${BRAND}'. Brands are: ${BRANDS[*]}"
    exit 1
fi

RELEASE=$2
STAGE=${3:-minikube}
CODE_REGISTRY=${4:-local}
#DEFAULT_UTILS_REGISTRY="452103867664.dkr.ecr.eu-central-1.amazonaws.com/stg/utils"
#UTILS_REGISTRY=${5:-$DEFAULT_UTILS_REGISTRY}
DEFAULT_ROOT_REGISTRY="452103867664.dkr.ecr.eu-central-1.amazonaws.com/"
STAGING_ROOT_REGISTRY="452103867664.dkr.ecr.eu-central-1.amazonaws.com/"
ROOT_REGISTRY=${5:-DEFAULT_ROOT_REGISTRY}
STG_REGISTRY=${5:-STAGING_ROOT_REGISTRY}
CODE_REPO=${BRAND:-nextouch}
DEFAULT_TAG="${STAGE}-${BRAND}-${RELEASE}"
STAGING_TAG="${BRAND}-${RELEASE}"

if [ $STAGE == 'production' ]; then
    CODE_TAG=$STAGING_TAG
    UTILS_REGISTRY="${ROOT_REGISTRY}/prd/utils"
    BASE_PHP_REGISTRY="${ROOT_REGISTRY}/prd/base-php"
elif [ $STAGE == 'staging' ]; then
    CODE_TAG=$STAGING_TAG
    UTILS_REGISTRY="${STG_REGISTRY}/stg/utils"
    BASE_PHP_REGISTRY="${STG_REGISTRY}/stg/base-php"
else
    CODE_TAG=$DEFAULT_TAG
    UTILS_REGISTRY="local/utils"
    BASE_PHP_REGISTRY="local/base-php"
fi

ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`
SOURCE_DIR=`dirname "${SOURCE_DIR}"`
SOURCE_DIR=`dirname "${SOURCE_DIR}"`

# MAGENTO
docker build --build-arg BASE_IMAGE="${BASE_PHP_REGISTRY}:latest" --build-arg STAGE=$STAGE --build-arg BRAND=$BRAND -t "${CODE_TAG}" -f kubernetes/.infra/docker/Dockerfile $SOURCE_DIR
if [ ! $? -eq 0 ]; then
    echo "Unable to complete the magento code build :("
    exit 1
fi

docker build --build-arg BASE_IMAGE="${UTILS_REGISTRY}:nginx-1.20-alpine" --build-arg STAGE=$STAGE --build-arg CODE_REGISTRY=$CODE_REGISTRY --build-arg CODE_REPO="${CODE_REPO}-code" --build-arg CODE_TAG=$CODE_TAG -t "${CODE_REGISTRY}/${CODE_REPO}-nginxws:${CODE_TAG}" -f kubernetes/.infra/docker/nginxws/Dockerfile kubernetes/.infra/docker/nginxws/
if [ ! $? -eq 0 ]; then
    echo "Unable to complete the magento nginx build :("
    exit 1
fi
docker build --build-arg STAGE=$STAGE --build-arg CODE_REGISTRY=$CODE_REGISTRY --build-arg CODE_REPO="${CODE_REPO}-code" --build-arg CODE_TAG=$CODE_TAG -t "${CODE_REGISTRY}/${CODE_REPO}-php-cli:${CODE_TAG}" -f kubernetes/.infra/docker/php-cli/Dockerfile kubernetes/.infra/docker/php-cli/
if [ ! $? -eq 0 ]; then
    echo "Unable to complete the magento php-cli build :("
    exit 1
fi
docker build --build-arg STAGE=$STAGE --build-arg CODE_REGISTRY=$CODE_REGISTRY --build-arg CODE_REPO=$CODE_REPO-code --build-arg CODE_TAG=$CODE_TAG -t "${CODE_REGISTRY}/${CODE_REPO}-php-fpm:${CODE_TAG}" -f kubernetes/.infra/docker/php-fpm/Dockerfile kubernetes/.infra/docker/php-fpm/
if [ ! $? -eq 0 ]; then
    echo "Unable to complete the magento php-fpm build :("
    exit 1
fi
