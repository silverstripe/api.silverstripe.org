#!/bin/sh

# master
if [ -d src/trunk/cms/.git ]; then
	(cd src/trunk/cms && git pull)
else
	git clone --depth=100 --depth=100 git://github.com/silverstripe/silverstripe-cms.git src/trunk/cms
fi
if [ -d src/trunk/sapphire/.git ]; then
	(cd src/trunk/sapphire && git pull)
else
	git clone --depth=100 --depth=100 git://github.com/silverstripe/sapphire.git src/trunk/sapphire
fi
nice apigen --config conf/apigen/apigen.neon --source src/trunk --destination htdocs/trunk --title "SilverStripe master API Docs"

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
if [ -d src/modules/blog/trunk/.git ]; then
	(cd src/modules/blog/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-blog.git src/modules/blog/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/blog/trunk --destination htdocs/modules/blog/trunk --title "SilverStripe blog module trunk API Docs"

# modules: cmsworkflow
if [ -d src/modules/cmsworkflow/trunk/.git ]; then
	(cd src/modules/cmsworkflow/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-cmsworkflow.git src/modules/cmsworkflow/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/cmsworkflow/trunk --destination htdocs/modules/cmsworkflow/trunk --title "SilverStripe cmsworkflow module trunk API Docs"

# modules: forum
if [ -d src/modules/forum/trunk/.git ]; then
	(cd src/modules/forum/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-forum.git src/modules/forum/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/forum/trunk --destination htdocs/modules/forum/trunk --title "SilverStripe forum module trunk API Docs"

# modules: mssql
if [ -d src/modules/mssql/trunk/.git ]; then
	(cd src/modules/mssql/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-mssql.git src/modules/mssql/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/mssql/trunk --destination htdocs/modules/mssql/trunk --title "SilverStripe mssql module trunk API Docs"

# modules: multiform
if [ -d src/modules/cmsworkflow/trunk/.git ]; then
	(cd src/modules/cmsworkflow/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-cmsworkflow.git src/modules/cmsworkflow/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/multiform/trunk --destination htdocs/modules/multiform/trunk --title "SilverStripe multiform module trunk API Docs"

# modules: postgresql
if [ -d src/modules/postgresql/trunk/.git ]; then
	(cd src/modules/postgresql/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-postgresql.git src/modules/postgresql/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/postgresql/trunk --destination htdocs/modules/postgresql/trunk --title "SilverStripe postgresql module trunk API Docs"

# modules: recaptcha
if [ -d src/modules/recaptcha/trunk/.git ]; then
	(cd src/modules/recaptcha/trunk && git pull)
else
	git clone --depth=100 git://github.com/chillu/recaptcha.git src/modules/recaptcha/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/recaptcha/trunk --destination htdocs/modules/recaptcha/trunk --title "SilverStripe recaptcha module trunk API Docs"

# modules: subsites
if [ -d src/modules/subsites/trunk/.git ]; then
	(cd src/modules/subsites/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-subsites.git src/modules/subsites/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/subsites/trunk --destination htdocs/modules/subsites/trunk --title "SilverStripe subsites module trunk API Docs"

# modules: sqlite3
if [ -d src/modules/sqlite3/trunk/.git ]; then
	(cd src/modules/sqlite3/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-sqlite3.git src/modules/sqlite3/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/sqlite3/trunk --destination htdocs/modules/sqlite3/trunk --title "SilverStripe sqlite3 module trunk API Docs"

# modules: tagfield
if [ -d src/modules/tagfield/trunk/.git ]; then
	(cd src/modules/tagfield/trunk && git pull)
else
	git clone --depth=100 git://github.com/chillu/tagfield.git src/modules/tagfield/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/tagfield/trunk --destination htdocs/modules/tagfield/trunk --title "SilverStripe tagfield module trunk API Docs"

# modules: userforms
if [ -d src/modules/userforms/trunk/.git ]; then
	(cd src/modules/userforms/trunk && git pull)
else
	git clone --depth=100 git://github.com/silverstripe/silverstripe-userforms.git src/modules/userforms/trunk
fi
nice apigen --config conf/apigen/apigen.neon --source src/modules/userforms/trunk --destination htdocs/modules/userforms/trunk --title "SilverStripe userforms module trunk API Docs"
