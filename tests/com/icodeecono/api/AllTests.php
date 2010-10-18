<?php
require_once 'APIResponderTest.php';

class Com_Icodeecono_Api_AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('Com_Icodeecono_Api');
		$suite->addTestSuite('Com_Icodeecono_Api_APIResponderTest');
		return $suite;
	}
}

?>