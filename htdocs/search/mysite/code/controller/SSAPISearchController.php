<?php
class SSAPISearchController extends Controller {

	function Form() {
		$form = new Form(
			$this, 
			'Form', 
			new FieldSet(
				new TextField('q', 'Term')
			),
			new FieldSet(
				new FormAction('doSearch', 'Search')
			)
		);
		
		return $form;
	}
	
	function doSearch($data, $form) {
		$s = new SSAPISearch($data);
		
		return $this->customise(array(
			'Results' => $s->getResults()
		))->renderWith('OpenSearchResultList_Atom');
	}
}