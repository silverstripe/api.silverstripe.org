<?php
/**
 * Used as a short-hand for phpDocumentor URLs, mainly to make them
 * more "guessable". phpDocumentor URLs include package and subpackage,
 * which makes it impractical to compose them from scratch.
 * 
 * Used alongside a .htaccess fallback this can be pretty handy:
 * 
 */
class SSAPILookupControllerTest extends FunctionalTest {
	
	static $fixture_file = 'mysite/tests/unit/SSAPISearchTest.yml';
	
	protected $autoFollowRedirection = false;
	
	function testIndex() {
		$prop1 = $this->objFromFixture('SSAPIProperty', 'prop1');
		$method1 = $this->objFromFixture('SSAPIProperty', 'method1');
		$class1 = $this->objFromFixture('SSAPIProperty', 'class1');
		$class1old = $this->objFromFixture('SSAPIProperty', 'class1old');
		
		$response = $this->get('lookup/?q=Class1');
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals($class1->URL, $response->getHeader('Location'));
		
		$response = $this->get('lookup/?q=Class1::Method1()');
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals($method1->URL, $response->getHeader('Location'));
		
		$response = $this->get('lookup/?q=Unknown');
		$this->assertEquals(404, $response->getStatusCode());
	}
	
}