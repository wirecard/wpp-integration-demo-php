#!/bin/bash
set -e # Exit with nonzero exit code if anything fails
TARGET_DIRECTORY="target"
TARGET_VERSION=$1
COPY_PATTERN="!(.*|vendor|target|node_modules)"

rm -rf $TARGET_DIRECTORY
echo "copying files to target directory ${TARGET_DIRECTORY}"
mkdir $TARGET_DIRECTORY
shopt -s extglob
cp -R ${COPY_PATTERN} ${TARGET_DIRECTORY}/
shopt -u extglob

cd $TARGET_DIRECTORY

composer install --no-dev
zip -r wirecard-wpp-integration-demo-php-${TARGET_VERSION}.zip .