<?php
class SSAPIPropertyTest extends SapphireTest {
	
	function testGenerateTitle() {
		$obj = new SSAPIProperty(array(
			'Name' => 'Class1',
			'Class' => 'Class1',
			'Type' => 'class',
		));
		$this->assertEquals('Class1', $obj->generateTitle());
		
		$obj = new SSAPIProperty(array(
			'Name' => 'staticprop',
			'Class' => 'Class1',
			'Type' => 'property',
			'Static' => true
		));
		$this->assertEquals('Class1::$staticprop', $obj->generateTitle());
		
		$obj = new SSAPIProperty(array(
			'Name' => 'instanceprop',
			'Class' => 'Class1',
			'Type' => 'property',
			'Static' => false
		));
		$this->assertEquals('Class1->instanceprop', $obj->generateTitle());
		
		$obj = new SSAPIProperty(array(
			'Name' => 'staticmethod',
			'Class' => 'Class1',
			'Type' => 'method',
			'Static' => true
		));
		$this->assertEquals('Class1::staticmethod()', $obj->generateTitle());
	}
	
}