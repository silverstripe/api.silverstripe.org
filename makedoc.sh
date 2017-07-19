#!/bin/bash

# Move to the base folder
cd $(dirname "$0");

# Where the module source code should be stored
STORAGE_DIR="assets/src"

#=== FUNCTION ==================================================================
# NAME:         checkout
# DESCRIPTION:  Checks out a specific branch of a module into a folder. Not
#               particular good for taking up space, but at the moment separate
#               folders for each version we need will do.
#
#               The master branch will checked out by default
# PARAMETERS:
#               $1 - module path on github (e.g silverstripe/silverstripe-framework.git)
#               $2 - branch/tag name (e.g 3.0), if $4 is defined this references the tag
#               $3 - module name (e.g framework)
#               $4 - whether we should use a branch for the clone
#
#===============================================================================
function checkout {
    if [ -z $4 ]; then
        VERSION_DIR=$2
    else
        VERSION_DIR=$(echo $2 | cut -c1-3)
    fi
    if [ -d "$STORAGE_DIR"/$2/$3/.git ]; then
        (cd "$STORAGE_DIR"/$2/$3 && git reset --hard HEAD && git pull)
    else
        git clone --depth=100 --branch=$2 $1 "$STORAGE_DIR"/"$VERSION_DIR"/$3
    fi
}

#=== FUNCTION ================================================================
# NAME:         generate
# DESCRIPTION:  Generates APIGEN documentation
# PARAMETERS:
#               $1 - branch name (e.g. 3.0)
#               $2 - Title (e.g. "SilverStripe master API Docs")
#
#===============================================================================
function generate {
    vendor/bin/apigen generate --config conf/apigen/apigen.neon --source "$STORAGE_DIR"/$1 --destination htdocs/$1 --title "$2"
}

# Ensure storage directory exists
mkdir -p "$STORAGE_DIR"

# master
checkout 'git://github.com/silverstripe/silverstripe-admin.git' 'master' 'admin'
checkout 'git://github.com/silverstripe/silverstripe-asset-admin.git' 'master' 'asset-admin'
checkout 'git://github.com/silverstripe/silverstripe-assets.git' 'master' 'assets'
checkout 'git://github.com/silverstripe/silverstripe-campaign-admin.git' 'master' 'campaign-admin'
checkout 'git://github.com/silverstripe/silverstripe-cms.git' 'master' 'cms'
checkout 'git://github.com/silverstripe/silverstripe-config.git' 'master' 'config'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' 'master' 'framework'
checkout 'git://github.com/silverstripe/silverstripe-graphql.git' 'master' 'graphql'
checkout 'git://github.com/silverstripe/silverstripe-reports.git' 'master' 'reports'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' 'master' 'siteconfig'
checkout 'git://github.com/silverstripe/silverstripe-versioned.git' 'master' 'versioned'
generate 'master' 'SilverStripe master API Docs'

# 3.6
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.6' 'cms'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.6' 'framework'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' '3.6' 'siteconfig'
checkout 'git://github.com/silverstripe/silverstripe-reports.git' '3.6' 'reports'
generate '3.6' 'SilverStripe 3.6 API Docs'

# 3.5
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.5' 'cms'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.5' 'framework'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' '3.5' 'siteconfig'
checkout 'git://github.com/silverstripe/silverstripe-reports.git' '3.5' 'reports'
generate '3.5' 'SilverStripe 3.5 API Docs'

# 3.4
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.4.1' 'cms' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.4.1' 'framework' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' '3.4.1' 'siteconfig' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-reports.git' '3.4.1' 'reports' 'tag'
generate '3.4' 'SilverStripe 3.4 API Docs'

# 3.3
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.3.1' 'cms' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.3.1' 'framework' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' '3.3.1' 'siteconfig' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-reports.git' '3.3.1' 'reports' 'tag'
generate '3.3' 'SilverStripe 3.3 API Docs'

# 3.2
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.2.1' 'cms' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.2.1' 'framework' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' '3.2.1' 'siteconfig' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-reports.git' '3.2.1' 'reports' 'tag'
generate '3.2' 'SilverStripe 3.2 API Docs'

# 3.1
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.1.1' 'cms' 'tag'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.1.1' 'framework' 'tag'
generate '3.1' 'SilverStripe 3.1 API Docs'

# 3.0
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.0' 'cms'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '3.0' 'framework'
generate '3.0' 'SilverStripe 3.0 API Docs'

# 2.4
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '2.4' 'cms'
checkout 'git://github.com/silverstripe/silverstripe-framework.git' '2.4' 'framework'
generate '2.4' 'SilverStripe 2.4 API Docs'

