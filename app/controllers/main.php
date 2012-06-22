<?php



class Main {
	
	function index(){
		echo "index function";
	}
	
	function derp(){
		echo "derp";
		
	}
	
	function herp($yerp=NULL){
		if($yerp == NULL){
			echo "you didnt give me a yerp.";
		} else {
			echo $yerp;
		}
	}
		
	function redir(){
		redirect("main/derp");
	}
	
	function demo(){
		load_view("hero");
	}
	
}