<?php

class Com_Icodeecono_Api_ApiContentTypes {
	const CONTENT_TYPE_CSV = "text/csv; charset=utf-8";
	const CONTENT_TYPE_HTML = "text/html; charset=utf-8";
	const CONTENT_TYPE_JSON = "application/json; charset=utf-8";
	const CONTENT_TYPE_XML = "text/xml; charset=utf-8";
	const CONTENT_TYPE_ERROR = "text/plain; charset=utf-8";
	
	public static function findContentTypeFromFormat($format) {
		switch(strtolower($format)) {
			case 'csv':
				return Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_CSV;
			break;
			case 'json':
				return Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_JSON;
			break;
			case 'xml':
				return Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_XML;
			break;
			case 'html':
				return Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_HTML;
			break;
			default:
				return Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_ERROR;
			break;
		}
	}
}

?>