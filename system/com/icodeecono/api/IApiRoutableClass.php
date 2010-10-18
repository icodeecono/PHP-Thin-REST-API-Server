<?php
require_once (ROOT_DIR.'/../system/com/icodeecono/api/ApiResponder.php');

interface Com_Icodeecono_Api_IApiRoutableClass {
	public function httpGetIndex(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null);
	public function httpGetItem(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null);
	public function httpPostCreate(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null);
	public function httpPutUpdate(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null);
	public function httpDelete(Com_Icodeecono_Api_ApiResponder &$responder, Array $passedParams=null);
}

?>