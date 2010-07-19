<?php
class SSAPISearchControllerTest extends FunctionalTest {
	static $fixture_file = 'mysite/tests/unit/SSAPISearchTest.yml';
	
	function testDescription() {
		$response = $this->get('opensearch/description');
		$xml = simplexml_load_string($response->getBody());
		$this->assertContains(
			"opensearch/doSearch", 
			(string)$xml->Url['template']
		);
		$this->assertNotContains(
			"version=", 
			(string)$xml->Url['template']
		);
	}
	
	function testDescriptionWithVersion() {
		$response = $this->get('opensearch/description/?version=2.4');
		$xml = simplexml_load_string($response->getBody());
		$this->assertContains(
			"version=2.4", 
			(string)$xml->Url['template']
		);
	}
	
	function testSearch() {
		$response = $this->get('opensearch/search/?format=atom&q=class1');
		$body = str_replace('opensearch:', '', $response->getBody());
		$xml = simplexml_load_string($body);
		$this->assertEquals("2", (string)$xml->totalResults);
		$this->assertEquals(2, count($xml->entry));
		$this->assertEquals('Class1', (string)$xml->entry[0]->title);
		$this->assertEquals('Class1', (string)$xml->entry[1]->title);
	}
	
	function testSuggestions() {
		$response = $this->get('opensearch/suggestions/?format=json&q=Cla');
		$json = json_decode($response->getBody());
		$this->assertEquals('Cla', $json[0]);
		$this->assertContains('Class1', $json[1]);
	}
	
	function testSuggestionsUnknownQuery() {
		$response = $this->get('opensearch/suggestions/?format=json&q=Unknown');
		$json = json_decode($response->getBody());
		$this->assertEquals('Unknown', $json[0]);
		$this->assertEquals(0, count($json[1]));
	}
	
}