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
$paths[] = (@$_GET['version']) ? $_GET['version'] : 'trunk';

// Search
if($searchOrig = @$_GET['q']) {
	$search = str_replace(array('()', '$'), '', $searchOrig);
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