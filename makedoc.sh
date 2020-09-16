#!/bin/bash

# Move to the base folder
cd $(dirname "$0");
set -e

bin/docs checkout -v
bin/docs update conf/doctum.php --version
bin/docs update conf/doctum.php --ignore-parse-errors --force -v
