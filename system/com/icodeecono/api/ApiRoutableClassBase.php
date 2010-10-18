<?php
require_once (ROOT_DIR.'/../system/com/icodeecono/api/IApiRoutableClass.php');
require_once (ROOT_DIR.'/../system/com/icodeecono/api/ApiResponseStatuses.php');
require_once (ROOT_DIR.'/../system/com/icodeecono/api/ApiResponder.php');

class Com_Icodeecono_Api_ApiRoutableClassBase implements Com_Icodeecono_Api_IApiRoutableClass {
	
	function __construct() {
		
	}
	
	public function httpDelete(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams = null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_501_NOT_IMPLEMENTED);
		$responder->setRawReturnData(array("ERROR" => "Request not implimented"));
	}
	
	public function httpGetIndex(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams = null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_501_NOT_IMPLEMENTED);
		$responder->setRawReturnData(array("ERROR" => "Request not implimented"));
	}
	
	public function httpGetItem(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams = null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_501_NOT_IMPLEMENTED);
		$responder->setRawReturnData(array("ERROR" => "Request not implimented"));
	}
	
	public function httpPostCreate(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams = null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_501_NOT_IMPLEMENTED);
		$responder->setRawReturnData(array("ERROR" => "Request not implimented"));
	}
	
	public function httpPutUpdate(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams = null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_501_NOT_IMPLEMENTED);
		$responder->setRawReturnData(array("ERROR" => "Request not implimented"));
	}
}

?>