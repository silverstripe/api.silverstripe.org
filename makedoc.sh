#!/bin/sh

directorybase="src/"
ignore="sapphire/,_config.php,main.php,static-main.php,rewritetest.php,_register_database.php,index.php,install.php,Core.php,thirdparty/,lang/,tests/,*.js,*.css,*.yml,*.ss,*.jpg,*.gif,*.png,*.inc"
output="HTML:frames/Extjs:default"
defaultargs="--templatebase \"/Users/ingo/Silverstripe/ss2api/templates\" --ignore '$ignore'  --output '$output' --defaultpackagename sapphire"

# trunk
#svn co http://svn.silverstripe.com/open/phpinstaller/trunk src/trunk
echo "nice phpdoc --directory src/trunk/mysite --target trunk --title $defaultargs"
nice phpdoc --directory src/trunk/cms --target trunk --title "SilverStripe trunk API Documentation" $defaultargs

# 2.4
# svn co http://svn.silverstripe.com/open/phpinstaller/2.4 src/2.4
# nice phpdoc --directory src/2.4 --target 2.4 --title "SilverStripe 2.4 API Documentation" $defaultargs
# 
# # 2.3
# svn co http://svn.silverstripe.com/open/phpinstaller/2.3 src/2.3
# nice phpdoc --directory src/2.3 $output --target 2.3 --title "SilverStripe 2.3 API Documentation" $defaultargs
# 
# # 2.2
# svn co http://svn.silverstripe.com/open/phpinstaller/2.2 src/2.2
# nice phpdoc --directory src/2.2 $output --target 2.2 --title "SilverStripe 2.2 API Documentation" $defaultargs