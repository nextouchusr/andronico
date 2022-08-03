#!/usr/bin/env bash
set -e

if [[ ! $1 ]]; then
    echo "Missing brand version on the argument. add brand= on your make command like 'make prd-eu-build-nextouch-m2 brand=nextouch image=25'"
    exit 2
fi

if [[ ! $2 ]]; then
    echo "Missing image version on the argument. add image= on your make command like 'make prd-eu-build-nextouch-m2 brand=nextouch image=25'"
    exit 2
fi

AWS_REGION=eu-central-1
AWS_ACCOUNT_ID=452103867664
BRAND="nextouch"
IMAGE=$2

aws --profile=nextouch ecr get-login-password --region $AWS_REGION | docker login --username AWS --password-stdin $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com
./kubernetes/build/build-images.sh $BRAND $IMAGE production $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/k8s-prd $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/prd/utils
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/k8s-prd/$BRAND-code:$BRAND-$IMAGE
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/k8s-prd/$BRAND-nginxws:$BRAND-$IMAGE
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/k8s-prd/$BRAND-php-fpm:$BRAND-$IMAGE
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/k8s-prd/$BRAND-php-cli:$BRAND-$IMAGE
