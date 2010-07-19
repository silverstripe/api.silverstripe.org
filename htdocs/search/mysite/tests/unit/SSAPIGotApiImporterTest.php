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
		$this->assertEquals(
			array('$prop'),
			$properties->column('Name')
		);
		$this->assertEquals(
			array('TestClass1'),
			$properties->column('Class')
		);
		$this->assertEquals(
			array('vardb Short Desc'),
			$properties->column('SDesc')
		);
		$this->assertEquals(
			array('vardb Long Desc'),
			$properties->column('Desc')
		);
		
		$methods = DataObject::get('SSAPIProperty', '"Type" = \'method\'');
		$this->assertEquals(2, $methods->Count());
		$this->assertEquals(
			array('testMethod1', 'testMethod2'),
			$methods->column('Name')
		);
	}
}