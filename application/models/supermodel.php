<?php class Supermodel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
       # $this->session->set_userdata('userid',43);
       # $this->session->set_userdata('user',new User($this->session->userdata('userid')));
        
    }
    
    
    public function all_ids_from_facebook_id($facebook_user_id){
  
    	$query = $this->db->query("SELECT users.id as local_id,users.oauth_id as facebook_id, users.bigdoor_id as bigdoor_id FROM users WHERE oauth_type='facebook' AND oauth_id=?",array($facebook_user_id));
    	
    	if($query->num_rows()>0){
	    	$row = $query->row();
    	
	    	$ids = new stdClass();
    		$ids->facebook_id = $row->facebook_id;
   	 		$ids->bigdoor_id = $row->bigdoor_id;
    		$ids->local_id = $row->local_id;
    	
    		return $ids;
    	}else{
    		return false;
    	}
    }
    
    public function local_id_from_facebook_id($facebook_user_id){
    	$ids = $this->all_ids_from_facebook_id($facebook_user_id);
    	if(!$ids) return false;
    	else return $ids->local_id;
    
    }
    
    public function bigdoor_id_from_facebook_id($facebook_user_id){
    	$ids = $this->all_ids_from_facebook_id($facebook_user_id);
    	if(!$ids) return false;
    	else return $ids->bigdoor_id;    
    }
    	    
    public function all_ids_from_bigdoor_id($bigdoor_user_id){
        	
    	$query = $this->db->query("SELECT users.id as local_id,users.oauth_id as facebook_id, users.bigdoor_id as bigdoor_id FROM users WHERE bigdoor_id=?",array((string)$bigdoor_user_id));
    	
    	if($query->num_rows()>0){
	    	$row = $query->row();
    	
	    	$ids = new stdClass();
    		$ids->facebook_id = $row->facebook_id;
    		$ids->bigdoor_id = $row->bigdoor_id;
    		$ids->local_id = $row->local_id;
    	
    		return $ids;
    	}else{
    		return false;
    	}   	
    }

    public function local_id_from_bigdoor_id($bigdoor_user_id){
    	$ids = $this->all_ids_from_bigdoor_id($bigdoor_user_id);
    	if(!$ids) return false;
    	else return $ids->local_id;
    
    }
    
    public function facebook_id_from_bigdoor_id($bigdoor_user_id){
    	$ids = $this->all_ids_from_facebook_id($facebook_user_id);
    	if(!$ids) return false;
    	else return $ids->facebook_id;    
    }
    
    public function create_user_from_facebook(){
    	$fb_user_id = $this->facebook->getUser();		
		$ids = $this->all_ids_from_facebook_id($fb_user_id);
		
		if($ids){
			echo "<script>alert('')</script>";die();
			return $ids->local_id;
		}else{
			$user_profile = $this->facebook->api('/me');			
			$realname = $this->security->xss_clean($user_profile['name']);
			$sql = "INSERT INTO users (oauth_type,oauth_id,oauth_realname,date_registered,bigdoor_id	) VALUES (?,?,?,NOW(),'-1')";	
			$query = $this->db->query($sql,array('facebook',$fb_user_id,$realname));
			$local_id = $this->db->insert_id();
			
			$sql = "UPDATE users SET bigdoor_id = ? WHERE id = ?";
			$this->db->query($sql,array('local_id:'.$local_id,$local_id));
			return $local_id;
		}

    	die();
    }

} 

?>