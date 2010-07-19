<?php
/**
 * Alternative MySQL based search for the API.
 * By default, Sphinx is used for more accurate results.
 * The simple MySQL LIKE calls are still handy for 
 * suggestions though.
 */
class SSAPISearch {

	protected $params = array();
	
	protected $defaultLimit = 20;
	
	protected $maxLimit = 100;
	
	/**
	 * @param $params 'offset', 'limit', 'q'
	 */
	function __construct($params) {
		if(!@$params['q']) throw new InvalidArgumentException('Missing "q" parameter');
		
		$this->params = $params;
	}

	/**
	 * @param array $params
	 * @return DataObjectSet
	 */
	function getResults() {
		$q = $this->getQuery();
		$results = singleton('SSAPIProperty')->buildDataObjectSet($q->execute());
		if(!$results) $results = new DataObjectSet();
		$results->setPageLimits(
			$this->getOffset(),
			$this->getLimit(),
			$q->unlimitedRowCount()
		);
		
		return $results;
	}
	
	protected function getQuery() {
		$filter = sprintf('"Name" LIKE \'%%%s%%\'', Convert::raw2sql($this->params['q']));
		if(@$this->params['version']) $filter .= sprintf(' AND "VersionString" = \'%s\'', Convert::raw2sql($this->params['version']));
		$sort = (@$this->params['sort']) ? Convert::raw2sql($this->params['sort']) : null;
		$q = singleton('SSAPIProperty')->buildSQL(
			$filter,
			$sort,
			array(
				'start' => $this->getOffset(),
				'limit' => $this->getLimit()
			)
		);
		
		return $q;
	}
	
	function getOffset() {
		return (@$this->params['offset']) ? (int)$this->params['offset'] : 0;
	}
	
	function getLimit() {
		return (@$this->params['limit'] && $this->params['limit'] <= $this->maxLimit) ? (int)$this->params['limit'] : $this->defaultLimit;
	}
}