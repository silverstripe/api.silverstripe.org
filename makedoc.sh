#!/bin/bash

# Move to the base folder
cd $(dirname "$0");
set -e

bin/docs checkout -v

DOCTUM_COMPOSER_AUTOLOAD_FILE='./vendor/autoload.php' ./vendor/bin/doctum.php --version
DOCTUM_COMPOSER_AUTOLOAD_FILE='./vendor/autoload.php' ./vendor/bin/doctum.php --ignore-parse-errors --force -v
