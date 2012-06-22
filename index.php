<?php
# with trailing forward slash
$application_folder = "app/";
$system_folder = "system/";

//FULLE PATH AFTER asdf.com/ starting without slash and ending without slash
$self = "beta/splice";

$default_controller = "main";




// set to dev, test, or prod
$env = "dev";


##########################################
# END USER CONFIG
##########################################

switch($env){
	case "dev":
		ini_set('display_errors', 1); 
		ini_set('log_errors', 0); 
		error_reporting(E_ALL);	
		break;
	case "test":
	case "prod":
		ini_set('display_errors', 0); 
		ini_set('log_errors', 1); 
		error_reporting(0);	
		break;	
}





define('INDEX',$self);
define('ENV',$env);
define('APP_PATH',$application_folder);
define('SYS_PATH',$system_folder);

include_once($system_folder."core.php");

##########################################
# ROUTING
##########################################

$url = "http://".BASE_URL."$_SERVER[REQUEST_URI]";
$route = parse_url($url, PHP_URL_PATH);

define("ROUTE_URL",$url);
define("ROUTE_HOST",parse_url($url, PHP_URL_HOST));

/*
echo $self."<br>";
echo strpos($route,$self)."<br>";
echo strlen($self)."<br>";
*/

if($self == ""){
	define("ROUTE_PATH",$route);
	//$route="";
} else if(strpos($route,$self)===false){
	define("ROUTE_PATH",$route);
	$route="";
}else {
	define("ROUTE_PATH",substr($route,0,strpos($route,$self)));
	$route = substr($route,strpos($route,$self)+strlen($self));
	
}

//echo $route;
//die($route);
# remove trailing slashes
while(strrpos($route,"/") == strlen($route)-1){
	$route = substr($route,0,strlen($route)-1);
}

$route_path = explode("/",$route);

# load up all controller classes

foreach (glob($application_folder."controllers/*.php") as $controller_file)
{
    include $controller_file;
}

if(count($route_path)>=2){
	$route_class = $route_path[1];
	$controller_class = NULL;
	foreach ( get_declared_classes() as $c ) {
		if ( $route_class === strtolower($c) ) {
			$controller_class = $c;
		}
	}
	if($controller_class != NULL){
		$controller = new $controller_class();
		
		if(count($route_path)>=3){
			$route_method = $route_path[2];
			if(method_exists($controller,$route_method)){
				if(count($route_path)>=4){
					$route_params = array_slice($route_path,3);
					//call funct with params
					if(call_user_func_array(array($controller, $route_method), $route_params) === false){
						//there was an error calling that method with params
						four_oh_four();
					}
				} else {
					//call funct without params
					if(call_user_func(array($controller, $route_method)) === false){
						//there was an error calling that method
						four_oh_four();
					}
				}
			} else {
				//method does not exist
				four_oh_four();
			}
		} else {
			if(method_exists($controller,"index")){
				//call funct with params
				call_user_func(array($controller, "index"));
			} else {
				//method does not exist
				four_oh_four();
			}
		}
	} else {
		//controller not found
		four_oh_four();
	}
} else {
	$controller_class = NULL;
	foreach ( get_declared_classes() as $c ) {
		if ( strtolower($default_controller) === strtolower($c) ) {
			$controller_class = $c;
		}
	}
	$controller = new $controller_class();
	if(method_exists($controller,"index")){
		//call funct with params
		call_user_func(array($controller, "index"));
	} else {
		//method does not exist
		four_oh_four();
	}
	
	//call default controller
}

