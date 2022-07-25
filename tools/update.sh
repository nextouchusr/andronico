#!/bin/bash
set -e

# "This script is only for devbox porpose"

# Get absolute path to main directory
ABSPATH=$(cd "${0%/*}" 2>/dev/null; echo "${PWD}/${0##*/}")
SOURCE_DIR=`dirname "${ABSPATH}"`

# Run composer
cd ${SOURCE_DIR} && ../build/build-dev.sh