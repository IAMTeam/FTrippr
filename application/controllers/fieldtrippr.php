<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldtrippr extends CI_Controller {


	public function __construct(){
		parent::__construct();
		
		$_REQUEST = array_merge($_REQUEST,$_GET);
		
		#parse_str($_SERVER['QUERY_STRING'], $_GET);

		#$this->load->model('Bd_transact');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='home';
		$pagedata['title']='FieldTrippr &#8211; Home';
			
		$this->load->view('home',$pagedata);
	
	}
	
	public function credits(){
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='credits';
		$pagedata['title']='Credits';
		
		$this->load->view('credits',$pagedata);
	}
	
	public function landing()
	{

		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='landing';
		$pagedata['title']='Landing Page';

		$this->load->view('landing',$pagedata);
		
	}
	
	public function login()
	{
	
		$loginParams = array(
			'teamR00T' => 'awesome',
			'scope' => 'read_stream, user_checkins, publish_actions',
			'redirect_uri' => site_url('fblogin?fieldtrippr_next_page='.urlencode(site_url('nearbyvenues')))
		);
	
	
		if($this->mobile_detect->isMobile()) $loginParams['display'] = 'touch';
	
		$loginUrl = $this->facebook->getLoginUrl($loginParams);	

		$pagedata = array();
		$pagedata['loginUrl'] = $loginUrl;
		$pagedata['showmenu'] = false;
		$pagedata['page_slug']='login';
		$pagedata['title']='Login Page';

		$this->load->view('login',$pagedata);
	}
	
	public function nearbyvenues()
	{
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='nearbyvenues';
		$pagedata['title']='Nearby Venues';	
	
		$this->load->view('nearbyvenues',$pagedata);
	}
	
	public function nearbyvenues_map(){
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='nearbyvenues_map';
		$pagedata['title']='Nearby Venues Map';	
	
		$this->load->view('nearbyvenues_map',$pagedata);	}
	
	public function allvenues()
	{
	
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='allvenues';
		$pagedata['title']='All Venues';
	
		$this->load->view('allvenues',$pagedata);
	}
	
	public function venue()
	{
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='venue';
		$pagedata['title']='%Venue Title%';
		$this->load->view('venue',$pagedata);
	}
	
	public function account()
	{
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='account';
		$pagedata['title']='My Account';
		$this->load->view('account',$pagedata);
	}
	
	public function allbadges()
	{
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='allbadges';
		$pagedata['title']='My Badges';
		$this->load->view('allbadges',$pagedata);
	}

	public function badge()
	{
		$pagedata = array();
		$pagedata['showmenu'] = true;
		$pagedata['page_slug']='badge';
		$pagedata['title']='%Badge Title%';
		$this->load->view('badge',$pagedata);
	}
	
	public function locationjs(){
	
		$options = array();
		$this->load->view('max/locationjs',$options);
	
	}
	
	public function maxtest1(){
		#$this->Bd_transact->add_trash('1');
	
		$options = array();
		
		if($this->input->get('textAddress')){
			$options['textAddress'] = $this->input->get('textAddress');
			$options['doSearch'] = true;
			$options['numResults'] = 5;
		}else{
			$options['doSearch'] = false;
		}
		
		$this->load->view('max/maxtest1',$options);
	}
	
	public function maptest1(){
		$options = array();
		$options['numResults'] = 5;
		if($this->input->get('textAddress')){
			$options['textAddress'] = $this->input->get('textAddress');
			
			$geocoded = $this->locate->geocode($this->input->get('textAddress'));
			
			#print_r($geocoded);die();
			$this->locate->setLocation($geocoded->lat,$geocoded->lon);
			
			$options['doSearch'] = true;
			if($this->input->get('numResults')){
				$options['numResults'] = $this->input->get('numResults');
			}
			
			
		}else{
			$options['doSearch'] = false;
		}
		
		$this->load->view('max/maptest1',$options);
	
	}
	
	public function fbtest1(){
		$this->load->view('max/fbtest1');
	}
	
	public function facebook_auth(){
		$this->load->view('max/facebook_auth');
	}
	
	public function fblogin(){
#		ini_set('display_errors', 1);
#		ini_set('log_errors', 1);
#		ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
#		error_reporting(E_ALL);
		
		ini_set('display_errors', 1);
		ini_set('log_errors', 1);
		ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
		error_reporting(E_ALL);
		$next_page = $this->input->get('fieldtrippr_next_page');
		$fb_user_id = $this->facebook->getUser();
		
		if($fb_user_id === 0){
			redirect('login');
			//user is logged out.
		}else if(is_numeric($fb_user_id)){
			
			$user = new User(array('facebook_id'=>$fb_user_id));
			
			if(!$user->hasLoaded){
				$userid = $this->supermodel->create_user_from_facebook();
				$user = new User(array('local_id'=>$userid));
			}			

			//create_user_from_facebook
			$this->session->set_userdata('user_id',$user->id);
			redirect($next_page);
		}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */