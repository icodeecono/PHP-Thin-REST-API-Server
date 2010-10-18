<?php

require_once (ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/IApiResponseFormatter.php');

class Com_Icodeecono_Api_ResponseFormatter_HTMLApiResponseFormatter implements Com_Icodeecono_Api_ResponseFormatter_iApiResponseFormatter {
	
	public static function formatResponse(array $returnArray) {
		return Com_Icodeecono_Api_ResponseFormatter_HTMLApiResponseFormatter::makeHtmlList($returnArray);
	}
	
	public static function makeHtmlList($array, $depth=0, $key_map=FALSE) {
	    $whitespace = str_repeat("\t", $depth*2);
	    //Base case: an empty array produces no list
	    if (empty($array)) return '';
	    
	    if(is_array($array)) {
		    //Recursive Step: make a list with child lists
		    $output = "$whitespace<ul>\n";
		    foreach ($array as $key => $subArray) {
		        $subList = Com_Icodeecono_Api_ResponseFormatter_HTMLApiResponseFormatter::makeHtmlList($subArray, $depth+1, $key_map);
		        if($key_map AND $key_map[$key]) $key = $key_map[$key];
		        if($subList) $output .= "$whitespace\t<li>" . $key . "\n" . $subList . "$whitespace\t</li>\n";
		        else $output .= "$whitespace\t<li>" . $key . $subList . "</li>\n";
		    }
		    $output .= "$whitespace</ul>\n";
	    } else {
	    	 $output.= " => ".$array;
	    }
	    return $output;
	}
}

?>