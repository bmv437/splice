<?php

function redirect($route){
	//needs to work for internal urls
	header("Location: http://".BASE_URL.ROUTE_PATH.INDEX."/".$route);
}

function load_view($view,$vars = NULL,$return_output = false){
	$output;
	if($vars != NULL)
		extract($vars);
	if($return_output){
		ob_start();
		include(APP_PATH."views/".$view.".php");
		$output = ob_get_clean();
	} else {
		include(APP_PATH."views/".$view.".php");
	}
	if($return_output)
		return $output;
}

function four_oh_four(){
	die("PAGE NOT DERP");
}