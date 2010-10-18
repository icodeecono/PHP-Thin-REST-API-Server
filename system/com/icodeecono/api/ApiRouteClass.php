<?php

class Com_Icodeecono_Api_ApiRouteClass {
	public $className = null;
	public $methodHTTPMethod = null;
	
	function __construct($className,$methodHTTPMethod) {
		$this->className = $className;
		$this->methodHTTPMethod = $methodHTTPMethod;
	}
}

?>