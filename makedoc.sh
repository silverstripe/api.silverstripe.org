#!/bin/sh

ignore="_config.php,main.php,static-main.php,rewritetest.php,_register_database.php,index.php,install.php,Core.php,thirdparty/,lang/,tests/,*.js,*.css,*.yml,*.ss,*.jpg,*.gif,*.png,*.inc"
defaultargs="--templatebase templates --ignore $ignore  --output HTML:Smarty:PHP --defaultpackagename sapphire"

cd `dirname $0`y

# trunk
svn co http://svn.silverstripe.com/open/phpinstaller/trunk src/trunk
nice phpdoc --directory src/trunk --target trunk --title "SilverStripe trunk API Documentation" $defaultargs

# 2.4
svn co http://svn.silverstripe.com/open/phpinstaller/branches/2.4 src/2.4
nice phpdoc --directory src/2.4 --target 2.4 --title "SilverStripe 2.4 API Documentation" $defaultargs

# 2.3
svn co http://svn.silverstripe.com/open/phpinstaller/branches/2.3 src/2.3
nice phpdoc --directory src/2.3 $output --target 2.3 --title "SilverStripe 2.3 API Documentation" $defaultargs

# 2.2
svn co http://svn.silverstripe.com/open/phpinstaller/branches/2.2 src/2.2
nice phpdoc --directory src/2.2 $output --target 2.2 --title "SilverStripe 2.2 API Documentation" $defaultargs
