<?php
global $project;
$project = 'mysite';

global $database;
$database = 'SS_ss2apisearch';

/**
 * Include _ss_environment.php files
 */
$envFiles = array('_ss_environment.php', '../_ss_environment.php', '../../_ss_environment.php', '../../../_ss_environment.php','../../../../_ss_environment.php');
foreach($envFiles as $envFile) {
	if(@file_exists($envFile)) {
		define('SS_ENVIRONMENT_FILE', $envFile);
		include_once($envFile);
		break;
	}
}

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('ss2apisearch');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

Director::addRules(51, array(
	'opensearch/$Action/$ID/$OtherID' => 'SSAPISearchController',
	'lookup/$Action/$ID/$OtherID' => 'SSAPILookupController',
	'globalsearch//$Action/$ID' => 'OpenSearchController' // this might be spun off to a new project
));

// Sphinx::$var_path = BASE_PATH . '/mysite/conf';

OpenSearchController::register_description('ssapi_trunk', new OpenSearchDescription(Director::absoluteBaseURL() . '/opensearch/description/?version=trunk'));
// OpenSearchController::register_description('ssdoc', new OpenSearchDescription('http://doc.silverstripe.org/lib/exe/opensearch.php'));