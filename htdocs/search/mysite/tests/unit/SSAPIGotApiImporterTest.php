<?php
class SSAPIGotApiImporterTest extends SapphireTest {

	function testImport() {
		$c = new SSAPIGotApiImporter(BASE_PATH . '/mysite/tests/unit/SSAPIGotApiImporterTest_fixture.xml');
		$ids = $c->run();
		
		$classes = DataObject::get('SSAPIProperty', '"Type" = \'class\'');
		$this->assertEquals(2, $classes->Count());
		$this->assertEquals(
			array('TestClass1', 'TestClass2'),
			$classes->column('Name')
		);
		$this->assertEquals(
			array('TestClass1', 'TestClass2'),
			$classes->column('Class')
		);
		
		$properties = DataObject::get('SSAPIProperty', '"Type" = \'property\'');
		$this->assertEquals(1, $properties->Count());
		$property = $properties->First();
		$this->assertEquals('$prop', $property->Name);
		$this->assertEquals('TestClass1', $property->Class);
		$this->assertEquals('vardb Short Desc', $property->SDesc);
		$this->assertEquals('vardb Long Desc', $property->Desc);
		$this->assertEquals("1", $property->Static);
		
		$methods = DataObject::get('SSAPIProperty', '"Type" = \'method\'');
		$this->assertEquals(2, $methods->Count());
		$this->assertEquals(
			array('testMethod1', 'testMethod2'),
			$methods->column('Name')
		);
		$this->assertEquals(
			array('0', '0'),
			$methods->column('Static')
		);
	}
}