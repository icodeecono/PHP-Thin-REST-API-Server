<?php

class Com_Icodeecono_Api_ApiHttpRequestMethods {
	const HTTP_REQUEST_METHOD_GET = "GET";
	const HTTP_REQUEST_METHOD_POST = "POST";
	const HTTP_REQUEST_METHOD_PUT = "PUT";
	const HTTP_REQUEST_METHOD_DELETE = "DELETE";
	
	public static function allHttpMethods() {
		$returnArray = array();
		$returnArray[] = Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_DELETE;
		$returnArray[] = Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_GET;
		$returnArray[] = Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_POST;
		$returnArray[] = Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_PUT;
		return $returnArray;
	}
}

?>