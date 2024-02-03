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

		$openingWW = 0.00;

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
																, '*');
		  
		$vw_cash_box_snap = $this->Public_Model->get_data_record('vw_cash_box_snap', " status = 'open' 
																				AND currency_id = ".$_SESSION['default_currency']->currency_id
																 , null, null, '*');
								
		$prevClosingDet = $this->Public_Model->get_data_record('tbl_closing_details', " period_id = ".$period_details->previouse_period_id." 
																 AND currency_id = ".$_SESSION['default_currency']->currency_id
																 , null, null, '*');
										
		$wwBranchInLedger = $this->Public_Model->get_data_record('tbl_ledger_s_26', " transaction_code_id = 10 AND period_id = ".$period_details->tbl_period_id
																 ." AND currency_id = ".(isset($_SESSION['default_currency']->currency_id)?$_SESSION['default_currency']->currency_id:1)
																 , null, null, '*');

		$openingWW = (!isset($wwBranchInLedger->amount))?(isset($prevClosingDet->ww_cary_fwd)?$prevClosingDet->ww_cary_fwd:$openingWW):$openingWW;
		
		
		
		$data['vw_cash_box_snap'] = $vw_cash_box_snap;
		$data['openingWW'] = $openingWW;
		 
		 $this->load->view('parts/html_header', $data);
		 $this->load->view('parts/navigation_side_bar');
		 $this->load->view('parts/page_header');	
		 $this->load->view('parts/navigation_top_bar');
         $this->load->view($pagename, $data); 
		 $this->load->view('parts/page_footer');
		 $this->load->view('parts/html_footer');
	}
}
