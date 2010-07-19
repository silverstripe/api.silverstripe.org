<?php
/**
 * Wrapper to control {@link SSAPIGotApiImporter} through a cli-script.
 * 
 * @author Ingo Schommer
 */
class SSAPIGotApiImporterController extends CliController {
	
	function index($request) {
		if(!$request->getVar('file')) throw new InvalidArgumentException('"file" parameter missing');

		$importer = new SSAPIGotApiImporter($request->getVar('file'), $request->getVar('version'));
		$importer->run();
	}
	
}

/**
 * Imports an XML file in gotapi.com style.
 * 
 * Example:
 * <pages>
 *     <page title="DOMStringList" type="class" url="http://www.w3...core.html#DOMStringList">
 *         <page title="length" type="property" url="http://www.w3...core.html#DOMStringList-length"/>
 *           <page title="contains" type="method" url="http://www.w3...core.html#DOMStringList-contains"/>
 *        </page>
 *     <page title="NameList" type="class" url="http://www.w3...core.html#NameList">
 *         <page title="length" type="property" url="http://www.w3...core.html#NameList-length"/>
 *           <page title="getName" type="method" url="http://www.w3...core.html#NameList-getName"/>
 *        </page>
 * </pages>
 * 
 * @see http://www.gotapi.com/contribute/index.html
 * @author Ingo Schommer
 */
class SSAPIGotApiImporter {

	protected $filePath = null;
	
	protected $version = null;
	
	/**
	 * @param String $filePath
	 * @param String $version Optional version string
	 */
	function __construct($filePath, $version = null) {
		$this->filePath = $filePath;
		$this->version = $version;
	}
	
	function run() {
		$ids = array();
		
		$xml = simplexml_load_file($this->filePath);
		if($xml->page) {
			foreach($xml->page as $class) {
				$ids[] = $this->importProperty($class);
				// nested into properties
				if($class->page) foreach($class->page as $propertyOrMethod) {
					$ids[] = $this->importProperty($propertyOrMethod);
				}
			}
		}
		
		$obsoletes = DataObject::get(
			'SSAPIProperty',
			sprintf(
				'"VersionString" = \'%s\' AND "ID" NOT IN (\'%s\')', 
				Convert::raw2sql($this->version),
				implode(',', $ids)
			)
		);
		if($obsoletes) {
			foreach($obsoletes as $obsolete) {
				$obsolete->delete();
			}
			Debug::message(sprintf('Deleted %d obsolete properties', $obsoletes->Count()));
		}		
	}
	
	/**
	 * @param SimpleXMLElement $propertyXML
	 * @return Int database ID
	 */
	function importProperty($propertyXML) {
		$title = (string)$propertyXML['title'];
		$link = (string)$propertyXML['link'];
		$type = (string)$propertyXML['type'];
		
		$sql = sprintf(
			'"Title" = \'%s\' AND "Type" = \'%s\'', 
			Convert::raw2sql($title),
			// Assumes that types in gotAPI and internal datamodel matches
			Convert::raw2sql($type)
		);
		if($this->version) $sql .= sprintf(' AND "VersionString" = \'%s\'', Convert::raw2sql($this->version));
		$propObj = DataObject::get_one(
			'SSAPIProperty', 
			$sql
		);
		if($propObj) {
			Debug::message(sprintf('Found property for "%s" (Type: %s)', $title, $type));
		} else {
			$propObj = new SSAPIProperty(array(
				'Title' => $title,
				'Type' => $type
			));
			Debug::message(sprintf('Creating new property for "%s" (Type: %s)', $title, $type));
		}
		$propObj->URL = $link;
		$propObj->VersionString = $this->version;
		$propObj->write();
	}
}