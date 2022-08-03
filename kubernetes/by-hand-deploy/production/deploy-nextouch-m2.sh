#!/usr/bin/env bash
set -e

if [[ ! $1 ]]; then
    echo "Missing chart path on the argument. add chart= on your make command like 'make prd-eu-deploy-nextouch-m2 chart=../helm-chart-magento2 brand=nextouch image=c2551a74bfd1cea2f1eed6fae4154652dd7bebc4'"
    exit 2
fi

if [[ ! $2 ]]; then
    echo "Missing brand on the argument. add brand= on your make command like 'make prd-eu-deploy-eu-m2 chart=../helm-chart-magento2 brand=nextouch image=c2551a74bfd1cea2f1eed6fae4154652dd7bebc4'"
    exit 2
fi

if [[ ! $3 ]]; then
    echo "Missing image version on the argument. add image= on your make command like 'make prd-eu-deploy-eu-m2 chart=../helm-chart-magento2 brand=nextouch image=c2551a74bfd1cea2f1eed6fae4154652dd7bebc4'"
    exit 2
fi

echo "Switching to aws k8s-prd context..."
kubectl config use-context arn:aws:eks:eu-central-1:560985267959:cluster/k8s-prd
echo "Deploy with SKIP_SETTINGS_AND_SETUP_UPGRADE = 1 ..."

[ -f ./kubernetes/answers/production/helm-answer-m2-$2.compiled.yml ] && rm ./kubernetes/answers/production/helm-answer-m2-$2.compiled.yml
sed s/"%TAG_VERSION%"/$3/ ./kubernetes/answers/production/helm-answer-m2-$2.yml > ./kubernetes/answers/production/helm-answer-m2-$2.compiled.yml
sed s/"%SKIP_SETTINGS_AND_SETUP_UPGRADE%"/1/ ./kubernetes/answers/production/helm-answer-m2-$2.compiled.yml > ./kubernetes/answers/production/helm-answer-m2-$2.2.compiled.yml
sed s/"%NO_INTERACTION_SETUP_UPGRADE%"/0/ ./kubernetes/answers/production/helm-answer-m2-$2.2.compiled.yml > ./kubernetes/answers/production/helm-answer-m2-$2.3.compiled.yml
helm upgrade --install nextouch-m2 -f ./kubernetes/answers/production/helm-answer-m2-$2.3.compiled.yml --debug --create-namespace --namespace $2 $1
