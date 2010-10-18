<?php

require_once (ROOT_DIR.'/../system/com/icodeecono/api/ApiRoutableClassBase.php');
require_once (ROOT_DIR.'/../system/com/icodeecono/api/IApiRoutableClass.php');

class Com_Icodeecono_Api_APIResponderTestRouteBaseClassTwo extends Com_Icodeecono_Api_ApiRoutableClassBase {
	
	function __construct() {
		parent::__construct ();
	}
	
	public function httpGetIndex(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null) {
		// return no content
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_204_NO_CONTENT);
	}
	
	public function httpGetItem(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_200_OK);
		$responder->setRawReturnData($passedParams);
	}
	
	public function httpPostCreate(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams = null) {
		if(array_key_exists("addOne",$_POST) &&array_key_exists("addTwo",$_POST) ) {
			$responder->setRawReturnData(array(($_POST["addOne"]+$_POST["addTwo"])));
			$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_200_OK);
		}
	}
	
	public function httpPutUpdate(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_200_OK);
		$put_vars = array();
		parse_str(file_get_contents('php://input'), $put_vars);  
		$responder->setRawReturnData($put_vars);
	}
	
	public function httpDelete(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null) {
		$responder->setResponseStatus(Com_Icodeecono_Api_ApiResponseStatuses::STATUS_204_NO_CONTENT);
		$responder->setRawReturnData(array());
	}
}

?>