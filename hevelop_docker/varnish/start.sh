#!/bin/bash

set -e

exec bash -c \
  "exec varnishd -j unix,user=varnish -F \
  -a 0.0.0.0:80 \
  -T localhost:6082 \
  -f /etc/varnish/default.vcl \
  -s malloc,$CACHE_SIZE \
  $VARNISHD_PARAMS"
