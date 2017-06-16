<?php
// Lookup script to convert symbol names and other parameters
// to their URL representation in the API docs. Redirects to this URL.
// See README for more info.

$paths = array();

// Module
// Only include modules path if we're not request core.
if(@$_GET['module'] && !in_array($_GET['module'], array('cms', 'framework', 'sapphire'))) {
	$paths[] = 'modules/' . $_GET['module'];
}

// Version
$version = !empty($_GET['version']) ? $_GET['version'] : $version = 'trunk';

// Convert major branchs into minors
$versionParts = explode('.', $version);
$version = array_shift($versionParts);

// IMPORTANT: Redirect the latest unstable major version to "master". This will need to be updated occasionally.
if ($version === '4') {
	$version = 'master';
}

$paths[] = $version;

// Search
if($searchOrig = @$_GET['q']) {
	// Replace backslashes in namespaced class names with periods (matches apigen's format), and trim leading off
	$search = str_replace('\\', '.', ltrim($searchOrig, '\\'));

	$search = str_replace(array('()', '$'), '', $search);
	$searchParts = preg_split('/(::|\->)/', $search);
	$searchConfig = array();
	if(count($searchParts) == 2) {
		$searchConfig['class'] = $searchParts[0];
		$searchConfig['property'] = $searchParts[1];
		$searchConfig['type'] = (strpos($searchOrig, '()') !== FALSE) ? 'method' : 'property';
	} else {
		$searchConfig['class'] = $search;
		$searchConfig['property'] = '';
		$searchConfig['type'] = 'class';
	}
	$searchPath = 'class-' . $searchConfig['class'] . '.html';
	if($searchConfig['property']) {
		if($searchConfig['type'] == 'method') {
			$searchPath .= '#_' . $searchConfig['property'];
		} else {
			$searchPath .= '#$' . $searchConfig['property'];
		}
	}
	$paths[] = $searchPath;

}
$url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . implode('/', array_filter($paths));
header('Location: ' . $url);
