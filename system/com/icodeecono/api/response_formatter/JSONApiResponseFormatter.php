<?php

require_once (ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/IApiResponseFormatter.php');

class Com_Icodeecono_Api_ResponseFormatter_JSONApiResponseFormatter implements Com_Icodeecono_Api_ResponseFormatter_iApiResponseFormatter {
	
	public static function formatResponse(array $returnArray) {
		return json_encode($returnArray);
	}
}

?>