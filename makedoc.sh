#!/bin/sh

# copy "master" files from phpDocumentor templates for re-usage in index.html
cp templates/Converters/HTML/Smarty/templates/default/templates/media/* htdocs/index-files

# phpDocumentor settings
ignore="_config.php,main.php,static-main.php,rewritetest.php,_register_database.php,index.php,install.php,Core.php,thirdparty/,lang/,tests/,*.js,*.css,*.yml,*.ss,*.jpg,*.gif,*.png,*.inc"
defaultargs="--templatebase templates --ignore $ignore  --output HTML:Smarty:default"
cd `dirname $0` # workaround for missing templatebase

# trunk
if [ -d src/trunk/cms/.git ]; then
	(cd src/trunk/cms && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-cms.git src/trunk/cms
fi
if [ -d src/trunk/sapphire/.git ]; then
	(cd src/trunk/sapphire && git pull)
else
	git clone git://github.com/silverstripe/sapphire.git src/trunk/sapphire
fi

nice phpdoc --directory src/trunk --target htdocs/trunk --title "SilverStripe trunk API Documentation" --defaultpackagename sapphire $defaultargs
nice phpdoc --directory src/trunk --target htdocs/trunk/gotapi $defaultargs --output XML:GotAPI:default baseurl='http://api.silverstripe.org/trunk/'
php htdocs/search/sapphire/cli-script.php SSAPIGotApiImporterController file="`pwd`/htdocs/trunk/gotapi/index.xml" version=trunk

# trunk-js
mkdir -p htdocs/js/trunk/
nice lib/naturaldocs/NaturalDocs --input src/trunk/cms/javascript --input src/trunk/sapphire/javascript --output HTML htdocs/js/trunk --project conf/naturaldocs/trunk-js --style Default screen --rebuild
cp templates/Converters/HTML/Smarty/templates/default/templates/media/*.gif htdocs/js/trunk/styles/

# 2.4
if [ -d src/2.4/cms/.git ]; then
	(cd src/2.4/cms && git pull)
else
	git clone --branch 2.4 git://github.com/silverstripe/silverstripe-cms.git src/2.4/cms
fi
if [ -d src/2.4/sapphire/.git ]; then
	(cd src/2.4/sapphire && git pull)
else
	git clone --branch 2.4 git://github.com/silverstripe/sapphire.git src/2.4/sapphire
fi
nice phpdoc --directory src/2.4 --target htdocs/2.4 --title "SilverStripe 2.4 API Documentation" --defaultpackagename sapphire $defaultargs
nice phpdoc --directory src/2.4 --target htdocs/2.4/gotapi $defaultargs --output XML:GotAPI:default baseurl='http://api.silverstripe.org/2.4/'
php htdocs/search/sapphire/cli-script.php SSAPIGotApiImporterController file="`pwd`/htdocs/2.4/gotapi/index.xml" version=2.4

# 2.3
if [ -d src/2.3/cms/.git ]; then
	(cd src/2.3/cms && git pull)
else
	git clone --branch 2.3 git://github.com/silverstripe/silverstripe-cms.git src/2.3/cms
fi
if [ -d src/2.3/sapphire/.git ]; then
	(cd src/2.3/sapphire && git pull)
else
	git clone --branch 2.3 git://github.com/silverstripe/sapphire.git src/2.3/sapphire
fi
nice phpdoc --directory src/2.3 $output --target htdocs/2.3 --title "SilverStripe 2.3 API Documentation" --defaultpackagename sapphire $defaultargs
php htdocs/search/sapphire/cli-script.php SSAPIGotApiImporterController file="`pwd`/htdocs/2.3/gotapi/index.xml" version=2.3 baseurl='http://api.silverstripe.org/2.3/'

# 2.2
if [ -d src/2.2/cms/.git ]; then
	(cd src/2.2/cms && git pull)
else
	git clone --branch 2.2 git://github.com/silverstripe/silverstripe-cms.git src/2.2/cms
fi
if [ -d src/2.2/sapphire/.git ]; then
	(cd src/2.2/sapphire && git pull)
else
	git clone --branch 2.2 git://github.com/silverstripe/sapphire.git src/2.4/sapphire
fi
nice phpdoc --directory src/2.2 $output --target htdocs/2.2 --title "SilverStripe 2.2 API Documentation" --defaultpackagename sapphire $defaultargs
php htdocs/search/sapphire/cli-script.php SSAPIGotApiImporterController file="`pwd`/htdocs/2.2/gotapi/index.xml" version=2.2 baseurl='http://api.silverstripe.org/2.2/'

# modules: blog
if [ -d src/modules/blog/trunk/.git ]; then
	(cd src/modules/blog/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-blog.git src/modules/blog/trunk
fi
nice phpdoc --directory src/modules/blog/trunk --target htdocs/modules/blog/trunk --title "SilverStripe blog module trunk API Documentation" --defaultpackagename blog $defaultargs

# modules: cmsworkflow
if [ -d src/modules/cmsworkflow/trunk/.git ]; then
	(cd src/modules/cmsworkflow/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-cmsworkflow.git src/modules/cmsworkflow/trunk
fi
nice phpdoc --directory src/modules/cmsworkflow/trunk --target htdocs/modules/cmsworkflow/trunk --title "SilverStripe cmsworkflow module trunk API Documentation" --defaultpackagename cmsworkflow $defaultargs

# modules: forum
if [ -d src/modules/forum/trunk/.git ]; then
	(cd src/modules/forum/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-forum.git src/modules/forum/trunk
fi
nice phpdoc --directory src/modules/forum/trunk --target htdocs/modules/forum/trunk --title "SilverStripe forum module trunk API Documentation" --defaultpackagename forum $defaultargs

# modules: mssql
if [ -d src/modules/mssql/trunk/.git ]; then
	(cd src/modules/mssql/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-mssql.git src/modules/mssql/trunk
fi
nice phpdoc --directory src/modules/mssql/trunk --target htdocs/modules/mssql/trunk --title "SilverStripe mssql module trunk API Documentation" --defaultpackagename mssql $defaultargs

# modules: multiform
if [ -d src/modules/cmsworkflow/trunk/.git ]; then
	(cd src/modules/cmsworkflow/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-cmsworkflow.git src/modules/cmsworkflow/trunk
fi
nice phpdoc --directory src/modules/multiform/trunk --target htdocs/modules/multiform/trunk --title "SilverStripe multiform module trunk API Documentation" --defaultpackagename multiform $defaultargs

# modules: postgresql
if [ -d src/modules/postgresql/trunk/.git ]; then
	(cd src/modules/postgresql/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-postgresql.git src/modules/postgresql/trunk
fi
nice phpdoc --directory src/modules/postgresql/trunk --target htdocs/modules/postgresql/trunk --title "SilverStripe postgresql module trunk API Documentation" --defaultpackagename postgresql $defaultargs

# modules: recaptcha
if [ -d src/modules/recaptcha/trunk/.git ]; then
	(cd src/modules/recaptcha/trunk && git pull)
else
	git clone git://github.com/chillu/recaptcha.git src/modules/recaptcha/trunk
fi
nice phpdoc --directory src/modules/recaptcha/trunk --target htdocs/modules/recaptcha/trunk --title "SilverStripe recaptcha module trunk API Documentation" --defaultpackagename recaptcha $defaultargs

# modules: subsites
if [ -d src/modules/subsites/trunk/.git ]; then
	(cd src/modules/subsites/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-subsites.git src/modules/subsites/trunk
fi
nice phpdoc --directory src/modules/subsites/trunk --target htdocs/modules/subsites/trunk --title "SilverStripe subsites module trunk API Documentation" --defaultpackagename subsites $defaultargs

# modules: sqlite3
if [ -d src/modules/sqlite3/trunk/.git ]; then
	(cd src/modules/sqlite3/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-sqlite3.git src/modules/sqlite3/trunk
fi
nice phpdoc --directory src/modules/sqlite3/trunk --target htdocs/modules/sqlite3/trunk --title "SilverStripe sqlite3 module trunk API Documentation" --defaultpackagename sqlite3 $defaultargs

# modules: tagfield
if [ -d src/modules/tagfield/trunk/.git ]; then
	(cd src/modules/tagfield/trunk && git pull)
else
	git clone git://github.com/chillu/tagfield.git src/modules/tagfield/trunk
fi
nice phpdoc --directory src/modules/tagfield/trunk --target htdocs/modules/tagfield/trunk --title "SilverStripe tagfield module trunk API Documentation" --defaultpackagename tagfield $defaultargs

# modules: userforms
if [ -d src/modules/userforms/trunk/.git ]; then
	(cd src/modules/userforms/trunk && git pull)
else
	git clone git://github.com/silverstripe/silverstripe-userforms.git src/modules/userforms/trunk
fi
nice phpdoc --directory src/modules/userforms/trunk --target htdocs/modules/userforms/trunk --title "SilverStripe userforms module trunk API Documentation" --defaultpackagename userforms $defaultargs
