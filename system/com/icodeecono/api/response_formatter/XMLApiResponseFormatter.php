<?php

require_once (ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/IApiResponseFormatter.php');

class Com_Icodeecono_Api_ResponseFormatter_XMLApiResponseFormatter implements Com_Icodeecono_Api_ResponseFormatter_iApiResponseFormatter {
	
	public static function formatResponse(array $returnArray) {
		$xml = new XMLWriter();
		$xml->openMemory();
		$xml->startDocument('1.0', 'UTF-8');
		$xml->startElement('root');
		Com_Icodeecono_Api_ResponseFormatter_XMLApiResponseFormatter::writeXMLElement($xml, $returnArray);
		$xml->endElement();
		echo $xml->outputMemory(true);
	}
	
	public static function writeXMLElement(XMLWriter $xml, $data){
		foreach($data as $key => $value){
	        if(preg_match('/(\d+)/',$key)) {
	        	$key = 'key_'.$key;
	        }
			if(is_array($value)){
	            $xml->startElement($key);
	            Com_Icodeecono_Api_ResponseFormatter_XMLApiResponseFormatter::writeXMLElement($xml, $value);
	            $xml->endElement();
	            continue;
	        }
	        $xml->writeElement($key, $value);
	    }
	}
}

?>