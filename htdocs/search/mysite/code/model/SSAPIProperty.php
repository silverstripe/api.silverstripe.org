<?php
/**
 * Stores an "property" of a class-based codebase,
 * typically PHP classes in a SilverStripe project.
 * "Property" here means any token that might be of interest
 * to developers, like a method, property or class name.
 * 
 * Has a "URL" column to point to the actual API documentation.
 */
class SSAPIProperty extends DataObject {
	static $db = array(
		'Title' => 'Text',
		'Type' => "Enum('class,method,property')",
		'IsStatic' => 'Boolean',
		'URL' => 'Text', // Absolute URLs so we can easily reuse them in search results (can include anchors)
		'VersionString' => 'Varchar' // Can't use "Version" because of Versioned extension. Example: "2.4"
	);
	
	static $indexes = array(
		'Type' => true,
		'Version' => true
	);
}