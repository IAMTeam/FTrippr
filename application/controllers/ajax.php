<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_CONTROLLER{

	function index(){
	
	}
	
	function nearbyLocations(){
	
	}
	
	function set_location(){
	
		(bool)$returnOk = false;
	
		$return = array();
		
		#stage.weareroot.org/ajax/set_location?latlon=41.9287960,-87.6455660
		$latlon = $this->input->get('latlon');
		
		
		#http://stage.weareroot.org/ajax/set_location?latlon=41.9287960,-87.6455660
		$street = $this->input->get('street');
		
		if($latlon){
			#echo "<script>alert('1')</script>";
			$split = explode(',',$latlon);
			$lat = $split[0];
			$lon = $split[1];
			$returnOk = true;
		}else if($street ){
			#echo "<script>alert('2')</script>";
			$geocode = $this->locate->geocode($street);
			$lat = $geocode->lat;
			$lon = $geocode->lon;
			$returnOk = true;
		}else{
			#echo "<script>alert('3')</script>";
			$returnOk = false;
		}
		
		if($returnOk){
			$return['status'] = 'ok';
			$return['lat'] = $lat;
			$return['lon'] = $lon;
			
			$this->locate->setLocation($lat,$lon);
		}else{
			$return['status'] = 'fail';
		}

		
		echo json_encode($return);
		
	
	}
	
	function get_location(){
		
		#print_r($_SESSION);
	
		$location = $this->locate->getLocation();
		
		$return = array();
		
		if($location){
			$return['status'] = 'ok';
			$return['lat'] = $location->lat;
			$return['lon'] = $location->lon;
		}else{
			$return['status'] = 'fail';
		}
		
		echo json_encode($return);
	
	}









}