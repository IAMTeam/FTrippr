<?php

Class User{

	public $id;
#	public $bigdoorUserId;
#	public $foursquareUserId;

	protected $CI;
	
	function unsetCI(){
		unset($this->CI);
	}

	function __construct($params){
		
		$this->CI =& get_instance();
	
		if(is_array($params)){
			if(array_key_exists('facebook_id',$params)){
				//pull user based on facebook id
				$local_id = $this->CI->supermodel->local_id_from_facebook_id($params['facebook_id']);
			}else if(array_key_exists('local_id',$params)||is_numeric($params)){
				//pull user based on local id
				if(array_key_exists('local_id',$params)) $local_id = $params['local_id'];
				elseif(is_numeric($params)) $local_id = $params['local_id'];
				
				#unneccessary?
				#$local_id = $this->CI->supermodel->local_id_from_local_id($local_id);
				
			}else if(array_key_exists('bigdoor_id',$params)){
				//pull user based on bigdoor id
				$local_id = $this->CI->supermodel->local_id_from_bigdoor_id($params['facebook_id']);
			}
		}else if(is_numeric($params)){
			$local_id = $params;
		}
		
		$this->hasLoaded = false;
		
		if(!$this->load($local_id)){
			
		}
		//SELECT localUserId FROM users WHERE foursquareUserId = ?	
		
		#print_r($bigdoor);
	}
	
	function load($local_id = false){
		if(is_numeric($local_id)){
			$this->id = $this->local_id = $local_id;
			$sql = "SELECT *  FROM users WHERE id = ?";			
			$query = $this->CI->db->query($sql,array($local_id));		
			$row = $query->row();
			
			if($row->oauth_type == 'facebook'){
				$this->oauth_type = $row->oauth_type;
				$this->facebook_id = $this->oauth_id = $row->oauth_id;
			}
			
			$this->bigdoor_id = $row->bigdoor_id;
			$this->realname = $this->oath_realname = $row->oauth_realname;
			$this->date_registered = $row->date_registered;
			$this->date_last_login = $row->date_last_login;
			#unset($this->CI);print_r($this);die();	
			
			$this->hasLoaded = true;
			
			return true;
			
		}else if(!$local_id){
			return false;				
		}else{
			return false;			
		}			
	
	}
	
	function shoutout(){
	
		return 'Hello my username is '.$this->username.' and my local user id is '.$this->id;
	
	}
	
	function registerBigdoorUser(){
		$register = $this->CI->bigdoor->put('/end_user/'.$this->bigdoor_id);
	}

	private $bigdoorUserCache;
	private $bigdoorUserCacheIsCurrent;
	function getBigdoorUser(){
	
		//if bigdoorUserCache is current, just return the cache. this is the php cache, not a database cache
		#if(isset($this->bigdoorUserCacheIsCurrent)&&$this->bigdoorUserCacheIsCurrent){
		#	return $this->bigdoorUserCache;
		#}
	
		//try to get the user
		$bduser = $this->CI->bigdoor->get('/end_user/'.$this->id);		
		
		#print_r($bduser); die();
		
		if($bduser['http_status_code'] == 200){
			//if user is found, save to bigdoorUserCache
			$this->bigdoorUserCache = $bduser;
			$this->bigdoorUserCacheIsCurrent = true;
			
			$data = json_decode($this->bigdoorUserCache['data']);

			$data = $data[0];

			#$data = $bduser;

			print_r($data);die();
			
			return $data;
			
			

			
		}elseif($bduser['http_status_code'] == 404){
			//if user is not found, create user and then try this method again.
			$this->registerBigdoorUser();
			
			#return $this->getBigDoorUser();
			
			echo 'ERROR CREATING NEW USER';die();
		}else{
			//if not found and not unfound, freak out and throw critical error;
			echo "<script>alert('FATAL ERROR- COULD NOT FIND BIGDOOR USER')</script>";die();
			return null;
		}
			
	}
	
	public $award_summaries;
	
	
	


}


?>