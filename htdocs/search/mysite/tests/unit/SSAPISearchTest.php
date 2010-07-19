<?php
class SSAPISearchTest extends SapphireTest {
	static $fixture_file = 'mysite/tests/unit/SSAPISearchTest.yml';
	
	function testSearchResult() {
		$class1 = $this->objFromFixture('SSAPIProperty', 'class1');
		$class1old = $this->objFromFixture('SSAPIProperty', 'class1old');
		
		$s = new SSAPISearch(array('q' => 'class1'));
		$results = $s->getResults();
		$this->assertType('DataObjectSet', $results);
		$this->assertEquals(2, $results->Count());
		$this->assertEquals($class1->ID, $results->First()->ID);
		$this->assertEquals($class1old->ID, $results->Last()->ID);
	}
	
	function testSearchResultLimit() {
		$class1 = $this->objFromFixture('SSAPIProperty', 'class1');
		$class1old = $this->objFromFixture('SSAPIProperty', 'class1old');
		
		$s = new SSAPISearch(array('q' => 'class1', 'limit' => 1));
		$results = $s->getResults();
		$this->assertEquals(1, $results->Count());
		$this->assertEquals($class1->ID, $results->First()->ID);
	}
	
	function testSearchResultOffset() {
		$class1 = $this->objFromFixture('SSAPIProperty', 'class1');
		$class1old = $this->objFromFixture('SSAPIProperty', 'class1old');
		
		$s = new SSAPISearch(array('q' => 'class1', 'offset' => 1));
		$results = $s->getResults();
		$this->assertEquals(1, $results->Count());
		$this->assertEquals($class1old->ID, $results->First()->ID);
	}
	
	function testSearchResultWithVersionLimit() {
		$class1 = $this->objFromFixture('SSAPIProperty', 'class1');
		
		$s = new SSAPISearch(array('q' => 'class1', 'version' => '1.1'));
		$results = $s->getResults();
		$this->assertType('DataObjectSet', $results);
		$this->assertEquals(1, $results->Count());
		$this->assertEquals($class1->ID, $results->First()->ID);
	}
	
	function testGetVersions() {
		$this->assertEquals(
			array('1.0', '1.1'),
			SSAPIProperty::get_versions()
		);
	}
}