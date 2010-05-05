#!/bin/sh

ignore="_config.php,main.php,static-main.php,rewritetest.php,_register_database.php,index.php,install.php,Core.php,thirdparty/,lang/,tests/,*.js,*.css,*.yml,*.ss,*.jpg,*.gif,*.png,*.inc"
defaultargs="--templatebase templates --ignore $ignore  --output HTML:Smarty:default"

cd `dirname $0`

# trunk
svn co http://svn.silverstripe.com/open/phpinstaller/trunk src/trunk
nice phpdoc --directory src/trunk --target trunk --title "SilverStripe trunk API Documentation" --defaultpackagename sapphire $defaultargs

# 2.4
svn co http://svn.silverstripe.com/open/phpinstaller/branches/2.4 src/2.4
nice phpdoc --directory src/2.4 --target 2.4 --title "SilverStripe 2.4 API Documentation" --defaultpackagename sapphire $defaultargs

# 2.3
svn co http://svn.silverstripe.com/open/phpinstaller/branches/2.3 src/2.3
nice phpdoc --directory src/2.3 $output --target 2.3 --title "SilverStripe 2.3 API Documentation" --defaultpackagename sapphire $defaultargs

# 2.2
svn co http://svn.silverstripe.com/open/phpinstaller/branches/2.2 src/2.2
nice phpdoc --directory src/2.2 $output --target 2.2 --title "SilverStripe 2.2 API Documentation" --defaultpackagename sapphire $defaultargs

# modules: auth_openid
svn co http://svn.silverstripe.com/open/modulesvn/auth_openid/trunk src/modules/auth_openid/trunk
nice phpdoc --directory src/modules/auth_openid/trunk --target modules/auth_openid/trunk --title "SilverStripe auth_openid module trunk API Documentation" --defaultpackagename auth_openid $defaultargs

# modules: blog
svn co http://svn.silverstripe.com/open/modules/blog/trunk src/modules/blog/trunk
nice phpdoc --directory src/modules/blog/trunk --target modules/blog/trunk --title "SilverStripe blog module trunk API Documentation" --defaultpackagename blog $defaultargs

# modules: cmsworkflow
svn co http://svn.silverstripe.com/open/modules/cmsworkflow/trunk src/modules/cmsworkflow/trunk
nice phpdoc --directory src/modules/cmsworkflow/trunk --target modules/cmsworkflow/trunk --title "SilverStripe cmsworkflow module trunk API Documentation" --defaultpackagename cmsworkflow $defaultargs

# modules: ecommerce
svn co http://svn.silverstripe.com/open/modules/ecommerce/trunk src/modules/ecommerce/trunk
nice phpdoc --directory src/modules/ecommerce/trunk --target modules/ecommerce/trunk --title "SilverStripe ecommerce module trunk API Documentation" --defaultpackagename ecommerce $defaultargs

# modules: forum
svn co http://svn.silverstripe.com/open/modules/forum/trunk src/modules/forum/trunk
nice phpdoc --directory src/modules/forum/trunk --target modules/forum/trunk --title "SilverStripe forum module trunk API Documentation" --defaultpackagename forum $defaultargs

# modules: googlesitemaps
svn co http://svn.silverstripe.com/open/modules/googlesitemaps/trunk src/modules/googlesitemaps/trunk
nice phpdoc --directory src/modules/googlesitemaps/trunk --target modules/googlesitemaps/trunk --title "SilverStripe googlesitemaps module trunk API Documentation" --defaultpackagename googlesitemaps $defaultargs

# modules: mssql
svn co http://svn.silverstripe.com/open/modules/mssql/trunk src/modules/mssql/trunk
nice phpdoc --directory src/modules/mssql/trunk --target modules/mssql/trunk --title "SilverStripe mssql module trunk API Documentation" --defaultpackagename mssql $defaultargs

# modules: multiform
svn co http://svn.silverstripe.com/open/modules/multiform/trunk src/modules/multiform/trunk
nice phpdoc --directory src/modules/multiform/trunk --target modules/multiform/trunk --title "SilverStripe multiform module trunk API Documentation" --defaultpackagename multiform $defaultargs

# modules: postgresql
svn co http://svn.silverstripe.com/open/modules/postgresql/trunk src/modules/postgresql/trunk
nice phpdoc --directory src/modules/postgresql/trunk --target modules/postgresql/trunk --title "SilverStripe postgresql module trunk API Documentation" --defaultpackagename postgresql $defaultargs

# modules: recaptcha
svn co http://svn.silverstripe.com/open/modules/recaptcha/trunk src/modules/recaptcha/trunk
nice phpdoc --directory src/modules/recaptcha/trunk --target modules/recaptcha/trunk --title "SilverStripe recaptcha module trunk API Documentation" --defaultpackagename recaptcha $defaultargs

# modules: subsites
svn co http://svn.silverstripe.com/open/modules/subsites/trunk src/modules/subsites/trunk
nice phpdoc --directory src/modules/subsites/trunk --target modules/subsites/trunk --title "SilverStripe subsites module trunk API Documentation" --defaultpackagename subsites $defaultargs

# modules: sqlite3
svn co http://svn.silverstripe.com/open/modules/sqlite3/trunk src/modules/sqlite3/trunk
nice phpdoc --directory src/modules/sqlite3/trunk --target modules/sqlite3/trunk --title "SilverStripe sqlite3 module trunk API Documentation" --defaultpackagename sqlite3 $defaultargs

# modules: tagfield
svn co http://svn.silverstripe.com/open/modules/tagfield/trunk src/modules/tagfield/trunk
nice phpdoc --directory src/modules/tagfield/trunk --target modules/tagfield/trunk --title "SilverStripe tagfield module trunk API Documentation" --defaultpackagename tagfield $defaultargs

# modules: userforms
svn co http://svn.silverstripe.com/open/modules/userforms/trunk src/modules/userforms/trunk
nice phpdoc --directory src/modules/userforms/trunk --target modules/userforms/trunk --title "SilverStripe userforms module trunk API Documentation" --defaultpackagename userforms $defaultargs