<?php

global $bigdoor;

function get_bigdoor(){
	global $bigdoor;
	if(!isset($bigdoor)){
		$bigdoor = new BDM_Client();
		$bigdoor->init(BD_APP_SECRET, BD_APP_KEY, BD_APP_HOST); 
		return $bigdoor;
	}else{
		return $bigdoor;
	}
}

global $db;

function get_db(){

}



?>