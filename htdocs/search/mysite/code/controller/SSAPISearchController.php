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
				new HiddenField('format', false, 'atom')
			),
			new FieldSet(
				new FormAction('doSearch', 'Search')
			)
		);
		$versionsField->setHasEmptyDefault(true);
		
		return $form;
	}
	
	function doSearch($data, $form) {
		$validFormats = array('atom');
		if(!in_array(@$data['format'], $validFormats)) throw new InvalidArgumentException('Invalid "format"');
		
		// Execute search
		$s = new SSAPISearch($data);
		
		// Choose template
		if($data['format'] == 'atom') {
			$template = 'OpenSearchResultList_Atom';
		}
		
		$lastUpdated = DB::query('SELECT MAX("LastEdited") FROM "SSAPIProperty" LIMIT 1')->value();
		
		$this->getResponse()->addHeader('Content-Type', 'application/atom+xml');
		return $this->customise(array(
			'LastUpdated' => DBField::create('SS_DateTime', $lastUpdated)->Format('c'),
			'Query' => Convert::raw2xml($data['q']),
			'Results' => $s->getResults()
		))->renderWith($template);
	}
	
	function description($request) {
		$version = $request->getVar('version');
		
		$this->getResponse()->addHeader('Content-Type', 'text/xml');
		return $this->customise(array(
			'Version' => Convert::raw2xml($version)
		))->renderWith('OpenSearchDescription');
	}
}