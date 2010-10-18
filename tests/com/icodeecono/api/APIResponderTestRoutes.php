<?php
require_once ROOT_DIR.'/../system/com/icodeecono/api/ApiResponder.php';
require_once ROOT_DIR.'/../tests/com/icodeecono/api/APIResponderTestRouteBaseClassOne.php';
require_once ROOT_DIR.'/../tests/com/icodeecono/api/APIResponderTestRouteBaseClassTwo.php';

$responder = new Com_Icodeecono_Api_ApiResponder($_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD']);

$responder->addRoute("/test","Com_Icodeecono_Api_APIResponderTestRouteBaseClassOne");
$responder->addRoute("/run/:run_id/test","Com_Icodeecono_Api_APIResponderTestRouteBaseClassTwo");

// $responder->printRoutes();

$responder->handleRequest();