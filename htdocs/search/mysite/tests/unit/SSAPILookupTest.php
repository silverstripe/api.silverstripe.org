<?php
class SSAPILookupTest extends SapphireTest {
	
	static $fixture_file = 'mysite/tests/unit/SSAPISearchTest.yml';
	
	function testParse() {
		$this->assertEquals(
			array('class' => 'MyClass', 'property' => null, 'type' => 'class'), 
			SSAPILookup::parse('MyClass')
		);
		
		$this->assertEquals(
			array('class' => 'MyClass', 'property' => 'myprop', 'type' => 'property'), 
			SSAPILookup::parse('MyClass::myprop')
		);
		
		$this->assertEquals(
			array('class' => 'MyClass', 'property' => 'myprop', 'type' => 'property'), 
			SSAPILookup::parse('MyClass::$myprop')
		);
		
		$this->assertEquals(
			array('class' => 'MyClass', 'property' => 'mymethod', 'type' => 'method'), 
			SSAPILookup::parse('MyClass->mymethod()')
		);
		
		$this->assertEquals(
			array('class' => 'MyClass', 'property' => 'mymethod', 'type' => 'method'), 
			SSAPILookup::parse('MyClass::mymethod()')
		);
		
	}
	
	function testLookup() {
		$prop1 = $this->objFromFixture('SSAPIProperty', 'prop1');
		$method1 = $this->objFromFixture('SSAPIProperty', 'method1');
		$class1 = $this->objFromFixture('SSAPIProperty', 'class1');
		$class1old = $this->objFromFixture('SSAPIProperty', 'class1old');

		$this->assertEquals($class1, SSAPILookup::lookup('Class1'));
		$this->assertEquals($prop1, SSAPILookup::lookup('Class1::Prop1'));
		$this->assertEquals($method1, SSAPILookup::lookup('Class1::Method1()'));
		$this->assertEquals($class1old, SSAPILookup::lookup('Class1', '1.0'));
	}
	
}