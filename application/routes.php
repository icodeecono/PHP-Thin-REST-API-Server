<?php
require_once ROOT_DIR.'/../system/com/icodeecono/api/ApiResponder.php';
// Include your classes here
// EXAMPLE: require_once ROOT_DIR.'/../application/path/to/class/MyClass.php';

$responder = new Com_Icodeecono_Api_ApiResponder($_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD']);

// add routes here
// EXAMPLE: $responder->addRoute("/test","MyClass");

$responder->handleRequest();