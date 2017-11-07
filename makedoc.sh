#!/bin/bash

# Move to the base folder
cd $(dirname "$0");

bin/docs checkout -v
bin/docs build -v
