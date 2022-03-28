#!/usr/bin/env bash

tput setaf 4; echo "###Task: Build Frontend"; tput sgr0

# Export the vars in .env into your shell:
export $(egrep -v '^#' .env | xargs)

# Install frontend
bash dev/scripts/install/frontend.sh

# Clean directories
rm -rf var/view_preprocessed/*
rm -rf pub/static/adminhtml
rm -rf pub/static/frontend
rm -rf pub/static/deployed_version.txt

# Compile assets
php bin/magento setup:static-content:deploy --area adminhtml -f
php bin/magento setup:static-content:deploy --area frontend -f

# Path to shops' active theme (multiple locations allowed, separated by a ' ' (SPACE))
THEME_FOLDER=('pub/static/frontend/Devmy/nextouch/')

# Find deployed themes (languages) and copy the themes. 'us_US' becomes 'us_US_source'. We skip directories already having a '_source' suffix
find ${THEME_FOLDER[@]} -mindepth 1 -maxdepth 1 \( -not -name '*_source' \) -type d -execdir mv -f \{} \{}_source \;

# Find all *_source themes and use them as input for r.js. The output directories are the input directory with '_source' stripped again.
find ${THEME_FOLDER[@]} -mindepth 1 -maxdepth 1 \( -name '*_source' \) -type d -exec bash -c 'r.js -o requirejs-bundle-config.js baseUrl=\{} dir="${@%"_source"}"' bash {} \;

# Find the themes that don't have _source as suffix, assuming these are the ones that are now bundled.
BUNDLED_THEMES=$(find ${THEME_FOLDER[@]} -mindepth 1 -maxdepth 1 \( -not -name '*_source' \) -type d);

# Find all .js files in Bundled Themes (that are not already provided as minified) and run them through Terser.
find ${BUNDLED_THEMES[@]} -path '*bundles*' \( -name '*.js' -not -name '*.min.js' \) -exec terser \{} -c -m reserved=['$','jQuery','define','require','exports'] -o \{} \;
find ${BUNDLED_THEMES[@]} \( -name 'require.js' -o -name 'requirejs-config.js' \) -exec terser \{} -c -m reserved=['$','jQuery','define','require','exports'] -o \{} \;
