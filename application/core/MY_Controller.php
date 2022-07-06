<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	function __construct() { 
         parent::__construct(); 
         $this->load->helper('url');
		 $this->load->helper('html');
		 $this->load->helper('form');
		 $this->load->helper('date');
		 $this->load->library('session');
		 $this->load->library('calendar');		  
         $this->load->database(); 
		$this->load->Model('Public_Model');
		//$this->load->Model('Cd_easyrwd_Model');
      } 
	
	public function index()
	{
		
	}
	
	
	function getEmailorPhone($txt)
	{
		return $this->getEmail($txt) ?: $this->getPhone($txt);
	}
	
	function getEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  			return NULL;
			}
		return $email;
	}
	
	function getPhone($phone)
	{
		if(!is_numeric($phone))
		{
			return NULL;
		}
		else if(substr($phone,0, 2) != '07')
		{
			return NULL;
		}
		else if(strlen($phone) != 10)
		{
			return NULL;
		}
		
		return $phone;
	}
	
	
	
	public function loadpage($pagename, $data = NULL){
		  
		 
		 $this->load->view('parts/html_header', $data);
		 $this->load->view('parts/navigation_side_bar');
		 $this->load->view('parts/page_header');	
		 $this->load->view('parts/navigation_top_bar');
         $this->load->view($pagename, $data); 
		 $this->load->view('parts/page_footer');
		 $this->load->view('parts/html_footer');
	}
}
