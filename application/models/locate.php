<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locate extends CI_Model {

	public function __construct(){
		parent::__construct();
		
		$this->currentLat = $this->session->userdata('currentLat');
		$this->currentLon = $this->session->userdata('currentLon');
		$this->locationUpdateTime = $this->session->userdata('locationUpdateTime');
	}

	public $currentLon;
	public $currentLat;
	
	function setLocation($latIn = false,$lonIn= false){
		if($lonIn && $latIn){
			$this->session->set_userdata('currentLon',$lonIn);
			$this->session->set_userdata('currentLat',$latIn);			
			$this->currentLon = $lonIn;
			$this->currentLat = $latIn;
			$locationUpdateTime = time();
			$this->session->set_userdata('locationUpdateTime',$locationUpdateTime);
			$this->locationUpdateTime = $locationUpdateTime;
		}
		/*elseif($this->session->userdata('currentLon') && $this->session->userdata('currentLat')){
			$this->currentLon = $this->session->userdata('currentLon');
			$this->currentLat = $this->session->userdata('currentLat');
		}*/
		else{
			return false;
		}
		
		return $this->getLocation();
	}
	
	function getLocation(){
		if($this->currentLat && $this->currentLon){
			return $this->latlon($this->currentLat,$this->currentLon);
		}else{

			return false;
		}
		
	}
		
	function latlon($lat,$lon){
		$ret = new stdClass();
		$ret->lat = $lat;
		$ret->lon = $lon;
		return $ret;
	}
	
	function nearbyVenues($lat = false, $lon = false, $count = 10){
		if($lat && $lon){
			$location = $this->latlon($lat,$lon);
		}else{
			$location = $this->getLocation();		
		}
		if(!$location) return false;
		
		#$location = $this->latlon(41.86598147470959, -87.62420654296875);
		
		$theradius = "100";
		$radiusstr = " HAVING distance < $theradius ";
	
		$querySQL = "SELECT venues.*,((ACOS(SIN(myLocation.latitude * PI() / 180) * SIN(venues.latitude * PI() / 180) + COS(myLocation.latitude * PI() / 180) * COS(venues.latitude * PI() / 180) * COS((myLocation.longitude - venues.longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance FROM venues,(SELECT ? as latitude, ? as longitude FROM dual) AS myLocation $radiusstr ORDER BY distance ASC LIMIT 0,$count;";
		
		//$querySQL = "SELECT * FROM venues"; //test query returns all locations, unsorted
				
		$query = $this->db->query($querySQL,array((float)$location->lat,(float)$location->lon));
		
		return $query->result();
		
	}
	
	function geocode($addressString = false){
	
		if($addressString){
		
		   #echo "<script>alert('$addressString')</script>";die();
		
		   			
		   $url = "http://maps.google.com/maps/geo?q=".urlencode($addressString)."&output=csv&key=".GMAPKEY;
		   
		   $cinit = curl_init();
		   curl_setopt($cinit, CURLOPT_URL, $url);
		   curl_setopt($cinit, CURLOPT_HEADER,0);
		   curl_setopt($cinit, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		   #curl_setopt($cinit, CURLOPT_FOLLOWLOCATION, 1);
		   curl_setopt($cinit, CURLOPT_RETURNTRANSFER, 1);
		   $data = $response = curl_exec($cinit);
		   curl_close($cinit);
		   
		   if(strstr($data,"200")) {
		   $data = explode(",",$data);
		   $zoom = $data[1];
		   $lat = $data[2];
		   $lon = $data[3];
		   	return $this->latlon($lat,$lon);
		   }else{
		   	return false;
		   }
		
		}else{
			echo "<script>alert('problem?')</script>";
		   return false;
		}	
		
		

	}

	#http://code.google.com/apis/maps/documentation/geocoding/#ReverseGeocoding	
	function reverseGeocode($lat,$lon){
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&sensor=false";

		$cinit = curl_init();
		curl_setopt($cinit, CURLOPT_URL, $url);
		curl_setopt($cinit, CURLOPT_HEADER,0);
		curl_setopt($cinit, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		#curl_setopt($cinit, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cinit, CURLOPT_RETURNTRANSFER, 1);
		$data = $response = curl_exec($cinit);
		curl_close($cinit);

		
		if(strstr($data,"results")) {
		   $data = json_decode($data);
		    #return $data;
		    if(@isset($data->results[0]->formatted_address)){
		    	return $data->results[0]->formatted_address;
		    }else{
		    	return false;
		    }
		}else{
		    return false;
		}
	
	}
	
}


?>