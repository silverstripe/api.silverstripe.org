#!/bin/sh

# master
if [ -d src/master/cms/.git ]; then
	(cd src/master/cms && git pull)
else
	git clone --depth=100 --depth=100 git://github.com/silverstripe/silverstripe-cms.git src/master/cms
fi
if [ -d src/master/sapphire/.git ]; then
	(cd src/master/sapphire && git pull)
else
	git clone --depth=100 --depth=100 git://github.com/silverstripe/sapphire.git src/master/sapphire
fi
nice apigen --config conf/apigen/apigen.neon --source src/master --destination htdocs/master --title "SilverStripe master API Docs"

# 3.1
if [ -d src/3.1/cms/.git ]; then
	(cd src/3.1/cms && git pull)
else
	git clone --depth=100 --branch 3.1 git://github.com/silverstripe/silverstripe-cms.git src/3.1/cms
fi
if [ -d src/3.1/sapphire/.git ]; then
	(cd src/3.1/sapphire && git pull)
else
	git clone --depth=100 --branch 3.1 git://github.com/silverstripe/sapphire.git src/3.1/sapphire
fi
nice apigen --config conf/apigen/apigen.neon --source src/3.1 --destination htdocs/3.1 --title "SilverStripe 3.1 API Docs"

# 3.0
if [ -d src/3.0/cms/.git ]; then
	(cd src/3.0/cms && git pull)
else
	git clone --depth=100 --branch 3.1 git://github.com/silverstripe/silverstripe-cms.git src/3.0/cms
fi
if [ -d src/3.0/sapphire/.git ]; then
	(cd src/3.0/sapphire && git pull)
else
	git clone --depth=100 --branch 3.0 git://github.com/silverstripe/sapphire.git src/3.0/sapphire
fi
nice apigen --config conf/apigen/apigen.neon --source src/3.0 --destination htdocs/3.0 --title "SilverStripe 3.0 API Docs"

# 2.4
if [ -d src/2.4/cms/.git ]; then
	(cd src/2.4/cms && git pull)
else
	git clone --depth=100 --branch 2.4 git://github.com/silverstripe/silverstripe-cms.git src/2.4/cms
fi
if [ -d src/2.4/sapphire/.git ]; then
	(cd src/2.4/sapphire && git pull)
else
	git clone --depth=100 --branch 2.4 git://github.com/silverstripe/sapphire.git src/2.4/sapphire
fi
nice apigen --config conf/apigen/apigen.neon --source src/2.4 --destination htdocs/2.4 --title "SilverStripe 2.4 API Docs"

# modules: blog
if [ -d src/modules/blog/master/.git ]; then
	(cd src/modules/blog/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-blog.git src/modules/blog/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/blog/master --destination htdocs/modules/blog/master --title "SilverStripe blog module trunk API Docs"

# modules: cmsworkflow
if [ -d src/modules/cmsworkflow/master/.git ]; then
	(cd src/modules/cmsworkflow/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-cmsworkflow.git src/modules/cmsworkflow/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/cmsworkflow/master --destination htdocs/modules/cmsworkflow/master --title "SilverStripe cmsworkflow module trunk API Docs"

# modules: forum
if [ -d src/modules/forum/master/.git ]; then
	(cd src/modules/forum/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-forum.git src/modules/forum/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/forum/master --destination htdocs/modules/forum/master --title "SilverStripe forum module trunk API Docs"

# modules: mssql
if [ -d src/modules/mssql/master/.git ]; then
	(cd src/modules/mssql/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-mssql.git src/modules/mssql/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/mssql/master --destination htdocs/modules/mssql/master --title "SilverStripe mssql module trunk API Docs"

# modules: multiform
if [ -d src/modules/multiform/master/.git ]; then
	(cd src/modules/multiform/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-multiform.git src/modules/multiform/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/multiform/master --destination htdocs/modules/multiform/master --title "SilverStripe multiform module trunk API Docs"

# modules: postgresql
if [ -d src/modules/postgresql/master/.git ]; then
	(cd src/modules/postgresql/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-postgresql.git src/modules/postgresql/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/postgresql/master --destination htdocs/modules/postgresql/master --title "SilverStripe postgresql module trunk API Docs"

# modules: recaptcha
if [ -d src/modules/recaptcha/master/.git ]; then
	(cd src/modules/recaptcha/master && git pull)
else
	git clone --depth=100 git://github.com/chillu/silverstripe-recaptcha.git src/modules/recaptcha/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/recaptcha/master --destination htdocs/modules/recaptcha/master --title "SilverStripe recaptcha module trunk API Docs"

# modules: subsites
if [ -d src/modules/subsites/master/.git ]; then
	(cd src/modules/subsites/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-subsites.git src/modules/subsites/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/subsites/master --destination htdocs/modules/subsites/master --title "SilverStripe subsites module trunk API Docs"

# modules: sqlite3
if [ -d src/modules/sqlite3/master/.git ]; then
	(cd src/modules/sqlite3/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe-labs/silverstripe-sqlite3.git src/modules/sqlite3/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/sqlite3/master --destination htdocs/modules/sqlite3/master --title "SilverStripe sqlite3 module trunk API Docs"

# modules: tagfield
if [ -d src/modules/tagfield/master/.git ]; then
	(cd src/modules/tagfield/master && git pull)
else
	git clone --depth=100 git://github.com/chillu/silverstripe-tagfield.git src/modules/tagfield/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/tagfield/master --destination htdocs/modules/tagfield/master --title "SilverStripe tagfield module trunk API Docs"

# modules: userforms
if [ -d src/modules/userforms/master/.git ]; then
	(cd src/modules/userforms/master && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-userforms.git src/modules/userforms/master
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/userforms/master --destination htdocs/modules/userforms/master --title "SilverStripe userforms module trunk API Docs"
