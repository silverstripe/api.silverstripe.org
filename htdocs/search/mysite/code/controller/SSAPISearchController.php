<?php
class SSAPISearchController extends Controller {

	protected $template = 'BlankPage';
	
	function Link($action = null) {
		return Controller::join_links('opensearch', $action);
	}

	/**
	 * This is a placeholder form, the request is usually formulated through
	 * an OpenSearch client.
	 */
	function Form() {
		$form = new Form(
			$this, 
			'Form', 
			new FieldSet(
				new TextField('q', 'Term'),
				$versionsField = new DropdownField('version', 'Version', SSAPIProperty::get_versions()),
				new HiddenField('format', false, 'html')
			),
			new FieldSet(
				new FormAction('doSearch', 'Search')
			)
		);
		$versionsField->setHasEmptyDefault(true);
		
		return $form;
	}
	
	function doSearch($data, $form = null) {
		$validFormats = array('atom', 'html');
		if(!in_array(@$data['format'], $validFormats)) throw new InvalidArgumentException('Invalid "format"');
		
		// Execute search
		// $s = new SSAPISearch($data);
		// $results = $s->getResults();
		
		$opts = array(
			'field_weights' => array(
				'Name' => 5,
				'Title' => 1,
				'Desc' => 2,
				'SDesc' => 2
			)
		);
		if(@$data['offset']) $opts['start'] = $data['offset'];
		if(@$data['limit']) $opts['pagesize'] = $data['limit'];
		$searchResultSpec = SphinxSearch::search(
			'SSAPIProperty', 
			$data['q'], 
			$opts
		);
		$results = $searchResultSpec->Matches;
		
		$lastUpdated = DB::query('SELECT MAX("LastEdited") FROM "SSAPIProperty" LIMIT 1')->value();
		
		// Choose template
		if($data['format'] == 'atom') {
			$template = 'OpenSearchResultList_Atom';
			$this->getResponse()->addHeader('Content-Type', 'application/atom+xml');
			return $this->customise(array(
				'LastUpdated' => DBField::create('SS_DateTime', $lastUpdated)->Format('c'),
				'Query' => Convert::raw2xml($data['q']),
				'Results' => $results
			))->renderWith($template);
		} elseif($data['format'] == 'html') {
			$template = 'OpenSearchResultList_HTML';
			$this->getResponse()->addHeader('Content-Type', 'text/html');
			$body = $this->customise(array(
				'LastUpdated' => DBField::create('SS_DateTime', $lastUpdated)->Format('c'),
				'Query' => Convert::raw2xml($data['q']),
				'Results' => $results
			))->renderWith($template);
			return $this->customise(array(
				'Content' => $body
			))->renderWith('Page');
		} 
		
		
	}
	
	function search($request) {
		return $this->doSearch($request->getVars());
	}
	
	/**
	 * @see https://developer.mozilla.org/en/Supporting_search_suggestions_in_search_plugins
	 */
	function suggestions($request) {
		$data = $request->getVars();
		
		$validFormats = array('json');
		if(!in_array(@$data['format'], $validFormats)) throw new InvalidArgumentException('Invalid "format"');
		
		// Hardcode suggestion length
		$data['limit'] = 5;
		
		// Execute search
		$s = new SSAPISearch($data);
		$results = $s->getResults();
		$resultsNames = $results->column('Name');
		
		return Convert::raw2json(array(
			$data['q'],
			$resultsNames
		));
	}
	
	function description($request) {
		$version = $request->getVar('version');
		
		$this->getResponse()->addHeader('Content-Type', 'text/xml');
		return $this->customise(array(
			'Version' => Convert::raw2xml($version)
		))->renderWith('OpenSearchDescription');
	}
}