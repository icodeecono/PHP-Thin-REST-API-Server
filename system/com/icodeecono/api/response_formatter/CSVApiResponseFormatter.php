<?php

require_once (ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/IApiResponseFormatter.php');

class Com_Icodeecono_Api_ResponseFormatter_CSVApiResponseFormatter implements Com_Icodeecono_Api_ResponseFormatter_iApiResponseFormatter {
	
	public static function formatResponse(array $returnArray) {
		$output = "";
		
		// get headers
		if(!empty($returnArray)) {
			$keys = array_keys($returnArray);
			for($i = 0; $i < count($keys); $i++) {
				$output .= '"' . str_replace('"', '""', $keys[$i]) . '"';
				if(($i+1) < count($keys)) {
					$output .= ",";
				}
			}
			$output .= "\n";
		}
		
		$lines = array();
		foreach ($returnArray as $v) {
			$lines[] = Com_Icodeecono_Api_ResponseFormatter_CSVApiResponseFormatter::arr_to_csv_line($v);
		}
		$output .= implode("\n", $lines);
		
		return $output;
	}
	
	public static function arr_to_csv_line($returnArray) {
		$line = array();
		if(is_array($returnArray)) {
			foreach ($returnArray as $v) {
				$line[] = is_array($v) ? Com_Icodeecono_Api_ResponseFormatter_CSVApiResponseFormatter::arr_to_csv_line($v) : '"' . str_replace('"', '""', $v) . '"';
			}
		} else {
			$line[] = $returnArray;
		}
		return implode(",", $line);
	}
}

?>