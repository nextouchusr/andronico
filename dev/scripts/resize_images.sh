#!/usr/bin/env bash

tput setaf 4; echo "###Task: Compress Images"; tput sgr0

# list the directories that contain images you want to resize / compress. Separated by a space.
folders=('pub/media/wysiwyg' 'pub/media/catalog/category' 'pub/media/catalog/product')

# set the max allowed height for images
WIDTH=1920

# set the max allowed width for images
HEIGHT=1200

# search for jpg/jpeg/png files and resize them to max width/height, keeping aspect ration
find ${folders[@]} \( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' \) -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;

# find all jpg/jpeg files and run them through jpegoptim to compress them
find "${folders[@]}" \( -iname '*.jpg' -o -iname '*.jpeg' \) -type f -print0 | xargs -0 jpegoptim -o --max=90 --strip-all --all-progressive >/dev/null 2>/dev/null

# find all png files and run them through optipng
find "${folders[@]}" -iname '*.png' -type f -print0 | xargs -0 optipng -o2 -strip all
