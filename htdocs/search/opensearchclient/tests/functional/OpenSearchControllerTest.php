<?php
/**
 * @package opensearchclient
 */
class OpenSearchControllerTest extends FunctionalTest {
	
	function setUp() {
		parent::setUp();
		
		OpenSearchController::clear_descriptions();
		OpenSearchController::register_description('test1', new OpenSearchDescription('http://test.com/OpenSearchDescriptionTest/opensearch/valid'));
		OpenSearchController::register_description('test2', new OpenSearchDescription('http://test.com/OpenSearchDescriptionTest/opensearch/otherdescription'));
				
		Object::useCustomClass('OpenSearchHTTPClient', 'OpenSearchTestHTTPClient');
	}
	
	function tearDown() {
		parent::tearDown();
		
		Object::useCustomClass('OpenSearchHTTPClient', 'OpenSearchHTTPClient');
	}
	
	function testResults() {
		$response = $this->get('OpenSearchControllerTest_Controller/search/?' . http_build_query(array('q' => 'test')));
		$resultsBySource = $this->cssParser()->getByXpath("//ul[@class='opensearch-results']");
		$this->assertEquals(2, count($resultsBySource));
		
		$this->assertEquals(2, count($resultsBySource[0]->li));
		$this->assertEquals('http://test.com/result1', (string)$resultsBySource[0]->li[0]->h4[0]->a[0]['href']);
		$this->assertEquals('http://test.com/result2', (string)$resultsBySource[0]->li[1]->h4[0]->a[0]['href']);
		
		$this->assertEquals(2, count($resultsBySource[1]->li));
		$this->assertEquals('http://test2.com/result1', (string)$resultsBySource[1]->li[0]->h4[0]->a[0]['href']);
		$this->assertEquals('http://test2.com/result2', (string)$resultsBySource[1]->li[1]->h4[0]->a[0]['href']);
	}
	
	function testResultsSearchesAllDescriptionsByDefault() {
		$response = $this->get('OpenSearchControllerTest_Controller/search/?' . http_build_query(array('q' => 'test')));
		$this->assertExactMatchBySelector(
			'ul.opensearch-resultsBySource h3', 
			array('Test Search 1', 'Test Search 2')
		);
	}
	
	function testResultsLimitedByDescription() {
		$response = $this->get('OpenSearchControllerTest_Controller/search/?' . http_build_query(array('q' => 'test', 'descriptions' => array('test1'))));
		$xml = $this->assertExactMatchBySelector(
			'ul.opensearch-resultsBySource h3', 
			array('Test Search 1')
		);
	}
	
}

class OpenSearchControllerTest_Controller extends OpenSearchController implements TestOnly {

	function Link($action = null) {
		return Controller::join_links('OpenSearchControllerTest_Controller', $action);
	}
	
}