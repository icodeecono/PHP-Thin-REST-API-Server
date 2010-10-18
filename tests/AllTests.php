<?php
require_once './com/icodeecono/api/AllTests.php';

class AllTests {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('API Shell');
		$suite->addTest(Com_Icodeecono_Api_AllTests::suite());
		return $suite;
	}
}

?>