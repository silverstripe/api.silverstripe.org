<?php
class SSAPILookupController extends Controller {
	
	function index($request) {
		if(!$request->getVar('q')) return false;
		
		$obj = SSAPILookup::lookup($request->getVar('q'), $request->getVar('version'));
		if($obj) {
			var_dump($obj->URL);die();
			return $this->redirect($obj->URL, 302);
		} else {
			// TODO Redirect to search
			return new SS_HTTPResponse(null, 404);
		}
	}
	
}

class SSAPILookup {
	
	/**
	 * @param String $str Search string
	 * @return SSAPIProperty
	 */
	static function lookup($str, $version = null) {
		$parts = self::parse($str);
		
		$filter = sprintf('"Class" = \'%s\'', Convert::raw2sql($parts['class']));
		if($parts['property']) $filter .= sprintf(' AND "Name" = \'%s\'', Convert::raw2sql($parts['property']));
		else $filter .= sprintf(' AND "Name" = \'%s\'', Convert::raw2sql($parts['class']));
		if($version) $filter .= sprintf(' AND "VersionString" = \'%s\'', Convert::raw2sql($version));
		
		return DataObject::get_one('SSAPIProperty', $filter);
	}
	
	/**
	 * @param String $str
	 * @return Array 'class' and 'property'
	 */
	static function parse($str) {
		$result = array();
		
		// Sanitize
		$str = str_replace(array('()', '$'), '', $str);
		
		// Break into parts
		$parts = preg_split('/(::|\->)/', $str);
		if(count($parts) == 2) {
			$result['class'] = $parts[0];
			$result['property'] = $parts[1];
		} else {
			$result['class'] = $str;
			$result['property'] = '';
		}
		
		return $result;
	}
	
}