#!/bin/sh

# Move to the base folder
cd $(dirname "$0");

#=== FUNCTION ==================================================================
# NAME: 		checkout
# DESCRIPTION:	Checks out a specific branch of a module into a folder. Not
#				particular good for taking up space, but at the moment separate
#				folders for each version we need will do.
#
#				The master branch will checked out by default
# PARAMETERS:
#				$1 - module path on github (e.g silverstripe/sapphire.git)
#				$2 - branch name (e.g 3.0)
#				$3 - module name (e.g sapphire)
#
#===============================================================================
function checkout {
	if [ -d src/$2/$3/.git ]; then
		(cd src/$2/$3 && git reset --hard HEAD && git pull)
	else
		git clone --depth=100 --branch=$2 $1 src/$2/$3
	fi
}

#=== FUNCTION ================================================================
# NAME: 		generate
# DESCRIPTION:	Generates APIGEN documentation
# PARAMETERS:
#				$1 - branch name (e.g. 3.0)
#				$2 - Title (e.g. "SilverStripe master API Docs")
#
#===============================================================================
function generate {
	vendor/bin/apigen generate --config conf/apigen/apigen.neon --source src/$1 --destination htdocs/$1 --title \"$2\"
}

# master
checkout 'git://github.com/silverstripe/silverstripe-cms.git' 'master' 'cms'
checkout 'git://github.com/silverstripe/sapphire.git' 'master' 'sapphire'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' 'master' 'siteconfig'
checkout 'git://github.com/silverstripe-labs/silverstripe-reports.git' 'master' 'reports'
generate 'master' 'SilverStripe master API Docs'

# 3.2
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.2' 'cms'
checkout 'git://github.com/silverstripe/sapphire.git' '3.2' 'sapphire'
checkout 'git://github.com/silverstripe/silverstripe-siteconfig.git' '3.2' 'siteconfig'
checkout 'git://github.com/silverstripe-labs/silverstripe-reports.git' '3.2' 'reports'
generate '3.2' 'SilverStripe 3.2 API Docs'

# 3.1
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.1' 'cms'
checkout 'git://github.com/silverstripe/sapphire.git' '3.1' 'sapphire'
generate '3.1' 'SilverStripe 3.1 API Docs'

# 3.0
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '3.0' 'cms'
checkout 'git://github.com/silverstripe/sapphire.git' '3.0' 'sapphire'
generate '3.0' 'SilverStripe 3.0 API Docs'

# 2.4
checkout 'git://github.com/silverstripe/silverstripe-cms.git' '2.4' 'cms'
checkout 'git://github.com/silverstripe/sapphire.git' '2.4' 'sapphire'
generate '2.4' 'SilverStripe 2.4 API Docs'

