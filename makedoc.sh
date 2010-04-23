directorybase=".src/"
ignore="_config.php,main.php,static-main.php,rewritetest.php,_register_database.php,index.php,install.php,Core.php,thirdparty/,lang/,tests/,*.js,*.css,*.yml,*.ss,*.jpg,*.gif,*.png,*.inc"
output="HTML:frames:default"
templatebase="templates"
defaultargs="--ignore '$ignore'  --output '$output' --defaultpackagename 'sapphire' --title 'SilverStripe API Documentation'"

# prepare base directory
svn up .src

# trunk
svn up .src/trunk
phpdoc --directory .src/trunk --target trunk $defaultargs

# 2.4
svn up .src/2.4
phpdoc --directory .src/2.4 --target 2.4 $defaultargs

# 2.3
svn up .src/2.3
phpdoc --directory .src/2.3 $output --target 2.3 $defaultargs