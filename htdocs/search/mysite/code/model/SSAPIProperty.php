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
		'Title' => 'Text', // Fully qualified title (e.g. "MyClass::myfunction()")
		'Type' => "Enum('class,method,property')",
		'URL' => 'Text', // Absolute URLs so we can easily reuse them in search results (can include anchors)
		'VersionString' => 'Varchar' // Can't use "Version" because of Versioned extension. Example: "2.4"
	);
	
	static $indexes = array(
		'Type' => true,
		'VersionString' => true
	);
	
	/**
	 * @return Array
	 */
	static function get_versions() {
		return DB::query('SELECT "VersionString" FROM "SSAPIProperty" GROUP BY "VersionString"')->column();
	}
}