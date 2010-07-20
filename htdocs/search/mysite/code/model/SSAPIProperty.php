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
		'Name' => 'Text', // Name of attribute (e.g. "myfunction()")
		'Class' => 'Text',
		'Title' => 'Text', // Fully qualified title (e.g. "MyClass::myfunction()")
		'Type' => "Enum('class,method,property')",
		'URL' => 'Text', // Absolute URLs so we can easily reuse them in search results (can include anchors)
		'SDesc' => 'Text',
		'Desc' => 'Text',
		'VersionString' => 'Varchar' // Can't use "Version" because of Versioned extension. Example: "2.4"
	);
	
	static $indexes = array(
		'Name_SDesc_Desc' => array('type' => 'fulltext', 'value' => '"Name","SDesc","Desc"'),
		// 'Name' => array('type' => 'fulltext', 'value' => 'Name'),
		// 'Class' => array('type' => 'fulltext', 'value' => 'Class'),
		// 'SDesc' => array('type' => 'fulltext', 'value' => 'SDesc'),
		// 'Desc' => array('type' => 'fulltext', 'value' => 'Desc'),
		'Type' => true,
		'VersionString' => true
	);
	
	// static $extensions = array(
	// 	'SphinxSearchable'
	// );

	// static $sphinx = array(
	// 	"search_fields" => array("Name", 'Title', 'SDesc', 'Desc'),
	// 	"sort_fields" => array("Name"),
	// 	"mode" => "xmlpipe"
	// );
	
	/**
	 * @return Array
	 */
	static function get_versions() {
		return DB::query('SELECT "VersionString" FROM "SSAPIProperty" GROUP BY "VersionString"')->column();
	}
}