#!/bin/bash
if [ ! -f /root/bin/helm ]; then
  curl https://get.helm.sh/helm-v3.5.4-linux-amd64.tar.gz -o helm-v3.5.4-linux-amd64.tar.gz;
  tar zxvf helm-v3.5.4-linux-amd64.tar.gz;
  mkdir -p /root/bin/;
  mv linux-amd64/helm /root/bin/helm;
fi
