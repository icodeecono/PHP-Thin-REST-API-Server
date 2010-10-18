<?php
require_once ROOT_DIR.'/../system/com/icodeecono/api/ApiResponseStatuses.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/ApiContentTypes.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/ApiRouteClass.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/ApiHttpRequestMethods.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/XMLApiResponseFormatter.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/JSONApiResponseFormatter.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/CSVApiResponseFormatter.php';
require_once ROOT_DIR.'/../system/com/icodeecono/api/response_formatter/HTMLApiResponseFormatter.php';

class Com_Icodeecono_Api_ApiResponder {
	const DEFAULT_RESPONSE_STATUS = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_500_INTERNAL_SERVER_ERROR;
	const DEFAULT_CONTENT_TYPE = Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_JSON;
	
	private $_uri;
	private $_requst_method;
	private $_responseStatus;
	private $_contentType;
	private $_isKeyValOnEndOfURI = false;
	private $_apiVersion = null;
	private $_uriRoutableMethod = null;
	private $_routeMap = array();
	private $_rawReturnData = array();
	private $_incomingPassedParams = array();
	
	function __construct($uri,$request_mothod) {
		$this->_uri = $uri;
		$this->_requst_method = strtoupper($request_mothod);
		$this->_responseStatus = Com_Icodeecono_Api_ApiResponder::DEFAULT_RESPONSE_STATUS;
		$this->_contentType = Com_Icodeecono_Api_ApiResponder::DEFAULT_CONTENT_TYPE;
		$this->parseIncomingUri();
	}
	
	public function setResponseStatus($responseStatus) {
		$this->_responseStatus = $responseStatus;
	}
	
	public function setRawReturnData($data) {
		$this->_rawReturnData = $data;
	}
	
	public function addRoute($routeString,$className) {
		$allMethods = Com_Icodeecono_Api_ApiHttpRequestMethods::allHttpMethods();
		foreach ($allMethods as $requestMethod) {
			if($requestMethod != "DELETE" && $requestMethod != "PUT") {
				$this->_routeMap[$routeString."__".$requestMethod] = new Com_Icodeecono_Api_ApiRouteClass($className,$requestMethod);
			}
		}
		$this->_routeMap[$routeString."/:id__".Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_GET] = new Com_Icodeecono_Api_ApiRouteClass($className,Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_GET);
		$this->_routeMap[$routeString."/:id__".Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_DELETE] = new Com_Icodeecono_Api_ApiRouteClass($className,Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_DELETE);
		$this->_routeMap[$routeString."/:id__".Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_PUT] = new Com_Icodeecono_Api_ApiRouteClass($className,Com_Icodeecono_Api_ApiHttpRequestMethods::HTTP_REQUEST_METHOD_PUT);
	}
	
	public function printRoutes() {
		echo "<pre><code>";
		var_dump($this->_routeMap);
		echo "</code></pre>";
	}
	
	private function parseIncomingUri() {
		$uriSplit = explode("/",$this->_uri);
		if(count($uriSplit) > 2) {
			array_shift($uriSplit);  // remvoes first slash
			$this->_apiVersion = array_shift($uriSplit);
			
			// detect and split off the format type
			$endOfUriSplit = end($uriSplit);
			$formatSplitPosition = strrpos($endOfUriSplit,'.');
			if($formatSplitPosition !== false) {
				$uriFormat = substr($endOfUriSplit,$formatSplitPosition+1);
				$endUriWithoutFormat = substr($endOfUriSplit,0,$formatSplitPosition);
				array_pop($uriSplit);
				$uriSplit[] = $endUriWithoutFormat;
				$this->_contentType = Com_Icodeecono_Api_ApiContentTypes::findContentTypeFromFormat($uriFormat);
				if(preg_match("/(\\d+)/",$endUriWithoutFormat)) {
					$this->_isKeyValOnEndOfURI = true;
				}
			} else if(preg_match("/(\\d+)/",$endOfUriSplit)) {
				$this->_isKeyValOnEndOfURI = true;
			}
			
			$this->_uriRoutableMethod = "/".implode("/",$uriSplit);
		}
	}
	
	private function requestVerityCheck() {
		if($this->_apiVersion == null) {
			$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
			$this->_rawReturnData = array("ERROR" => "API Version not set in URI.");
			return false;
		} else if($this->_contentType == Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_ERROR) {
			$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
			$this->_rawReturnData = array("ERROR" => "Response Format does not exist.");
			return false;
		} else if(empty($this->_routeMap)) {
			$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_500_INTERNAL_SERVER_ERROR;
			$this->_rawReturnData = array("ERROR" => "No Routes Exist.");
			return false;
		}
		
		return true;
	}
	
	public function matchIncomingURIToRoute() {
		foreach ($this->_routeMap as $routeKey => $routableClassInstance) {
			// if the current route has an :id pattern (one or more) in it, then extract them
			$idKeyPatternMatches = array();
			$idKeyPattern = '/\/\:([\w\-]+)([a-zA-Z0-9])(?=[\/|__])/';
			preg_match_all($idKeyPattern,$routeKey,$idKeyPatternMatches);
			// now in place of the :id pattern, let's look for an interger
			$urlPattern = '/^'.str_replace('/','\/',preg_replace($idKeyPattern , "/(\\d+)$3", $routeKey)).'$/';
			
			$routeMatches = array();
			$routeToMatch = $this->_uriRoutableMethod."__".$this->_requst_method;
			
			if(preg_match($urlPattern,$routeToMatch,$routeMatches)) {
				
				// cobble the :id keys back togeather
				$keyArray = array();
				if(count($idKeyPatternMatches) > 0) {
					for($i = 0; $i < count($idKeyPatternMatches[0]); $i++) {
						$keyArray[] = str_replace("/:","",$idKeyPatternMatches[0][$i]);
					}
				}
				
				// add values back to the :id keys
				$this->_incomingPassedParams = array();
				if(count($routeMatches) >= (count($keyArray)+1)) {
					for($i=0; $i < count($keyArray); $i++) {
						$keyName = $keyArray[$i];
						$this->_incomingPassedParams[$keyName] = $routeMatches[($i+1)];
					}
				}
				
				return $routableClassInstance;
			}
		}
		
		return false;
	}
	
	public function handleRequest() {
		if($this->requestVerityCheck()) {
			$routeMatchResult = $this->matchIncomingURIToRoute();
			if($routeMatchResult !== false && is_a($routeMatchResult,'Com_Icodeecono_Api_ApiRouteClass') ) {
				// now route the request to the correct dynamic class
				if(class_exists($routeMatchResult->className)) {
					$classHandler = new $routeMatchResult->className();
					switch($this->_requst_method) {
						case 'GET':
							// check if key is at the end
							if($this->_isKeyValOnEndOfURI) {
								$classHandler->httpGetItem($this,$this->_incomingPassedParams);
							} else {
								$classHandler->httpGetIndex($this,$this->_incomingPassedParams);	
							}
						break;
						case 'POST':
							if($this->_isKeyValOnEndOfURI) {
								$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
								$this->_rawReturnData = array("ERROR" => "You must NOT POST to this method with an ID value at the end of the URI");
							} else {
								$classHandler->httpPostCreate($this,$this->_incomingPassedParams);	
							}
						break;
						case 'PUT':
							if($this->_isKeyValOnEndOfURI) {
								$classHandler->httpPutUpdate($this,$this->_incomingPassedParams);
							} else {
								$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
								$this->_rawReturnData = array("ERROR" => "Missing ID value - You must PUT to this method with an ID value at the end of the URI");	
							}
						break;
						case 'DELETE':
							if($this->_isKeyValOnEndOfURI) {
								$classHandler->httpDelete($this,$this->_incomingPassedParams);
							} else {
								$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
								$this->_rawReturnData = array("ERROR" => "Missing ID value - You must PUT to this method with an ID value at the end of the URI");
							}
						break;
						default:
							$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
							$this->_rawReturnData = array("ERROR" => "Request method '".$this->_requst_method."' does not exist.");
						break;
					}
					
				} else {
					$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_500_INTERNAL_SERVER_ERROR;
					$this->_rawReturnData = array("ERROR" => "Handling class not found.");
				}
			} else { // route not found
				$this->_responseStatus = Com_Icodeecono_Api_ApiResponseStatuses::STATUS_400_BAD_REQUEST;
				$this->_rawReturnData = array("ERROR" => "Route Does not Exist.");
			}
		}
		
		header("HTTP/1.1 ".$this->_responseStatus);
		header("Content-type: ".$this->_contentType);
		
		// send out the formatted data
		$this->packageAndOutputData();
		flush();
	}
	
	private function packageAndOutputData() {
		if(!empty($this->_rawReturnData)) {
			if(is_array($this->_rawReturnData)) {
				switch($this->_contentType) {
					case Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_XML:
						echo Com_Icodeecono_Api_ResponseFormatter_XMLApiResponseFormatter::formatResponse($this->_rawReturnData);
					break;
					case Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_CSV:
						echo Com_Icodeecono_Api_ResponseFormatter_CSVApiResponseFormatter::formatResponse($this->_rawReturnData);
					break;
					case Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_JSON:
						echo Com_Icodeecono_Api_ResponseFormatter_JSONApiResponseFormatter::formatResponse($this->_rawReturnData);
					break;
					case Com_Icodeecono_Api_ApiContentTypes::CONTENT_TYPE_HTML:
						 echo Com_Icodeecono_Api_ResponseFormatter_HTMLApiResponseFormatter::formatResponse($this->_rawReturnData);
					break;
					default:
						foreach ($this->_rawReturnData as $dataKey => $dataval) {
							echo $dataKey." => ".$dataval.";";
						}
						
					break;
				}
			} else {
				echo $this->_rawReturnData;
			}
		}
	}
}

?>