#!/bin/bash
# install-all.sh
for dir in ms/*/; do
    cd "$dir"
    composer install
    cd ../../
done