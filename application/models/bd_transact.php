<?php class Bd_transact extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
       # $this->session->set_userdata('userid',43);
       # $this->session->set_userdata('user',new User($this->session->userdata('userid')));
        
    }
    
    function add_trash($amount){
    	#echo "<script>alert('$amount')</script>";
    	$user = $this->session->userdata('user');
    	
    }
    
    

} 

?>