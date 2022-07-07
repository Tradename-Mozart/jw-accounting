<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JwAccounting extends MY_Controller {

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
      } 
	
	public function index()
	{
		//$this->load->view('welcome_message');
		
		if (!isset($_SESSION['user']->is_logged_in))
		{
			//redirect('/uhealthzim/');
			$this->loginView();
		}
		else
		{
			$this->allowAccess();
		}
		
		/*if($_SESSION['user']->is_logged_in =="" && $_SESSION['user']->is_logged_in == false){
			
			
		}*/
	}
	
	function loginView()
	{
		$this->load->view('login');
	}
	
	public function login()
	{
		$email_phone = NULL;
		$scramble = NULL;
		$suffix = NULL;
		$validCode = false;
		$dataForValidCode = NULL;
		$dataUser = NULL;
		$usedCodeUserData = NULL;
		$validCode = false;
		
		if (isset($_POST['login']))
		 {
			$email_phone = $_POST['phone_email'];
			
			$dataUser = $this->Public_Model->get_data_record('tbl_user'
															  , "username = '{$email_phone}'"
																 , null, null, '*');
			
			
			if(!isset($dataUser->username))
			{
				$this->session->set_flashdata('userError', 'Unknown Email, Phone Or Invalid Code');
				redirect('JwAccounting');
			}
			else if (password_verify($_POST['access_pin'], $dataUser->password)) 
			{
				$_SESSION['user'] = $dataUser;
				$_SESSION['user']->is_logged_in = true;
                $this->allowAccess();
			}
			else
			{
				//die($_POST['access_pin'].'   passworrd scrambe '.$dataUser->password);
				$this->session->set_flashdata('userError', 'Unknown Email, Phone Or Invalid Code');
				redirect('JwAccounting');
			} 			
						
		 }
		 else
		 {
			redirect('JwAccounting');
		 }
	}
	
	public function logout()
    {
        unset($_SESSION['user']);
		unset($_SESSION['default_currency']);
		unset($_SESSION['currencies']);
        redirect('JwAccounting');
    }
	
	
	function allowAccess()
	{
		if (!isset($_SESSION['user']->is_logged_in))
		{
			redirect('JwAccounting');
		}

		$Congre_details = $this->Public_Model->get_data_record('tbl_congregation_detail', " 1 = 1 ", null, null
																		, '*');

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'open' ", null, null
																, '*');
		
		$tc_details = $this->Public_Model->get_data_all('tbl_transaction_code', " type <> 'IO' ", null, null
																, '*');
		
		$currency_details = $this->Public_Model->get_data_all('tblcurrency', " 1 = 1 ", null, null
																, '*');			
																
		$account_details = $this->Public_Model->get_data_all('tbl_account', " 1 = 1 ", null, null
																, '*');	

		$TO62_Finalizing = $this->Public_Model->get_data_all('vw_currency_valid_for_to62', " 1 = 1 ", null, null
																, '*');	
		
		
		
		$data['title'] = 'dashboard';

		$data['congregation_name'] = $Congre_details->congregation_name;
		$data['city'] = $Congre_details->city;
		$data['province_state'] = $Congre_details->province_state;
		$data['sequenceno'] = $period_details->sequenceno;
		$data['tc_details'] = $tc_details;

		if(!isset($_SESSION['default_currency']))
		{
			$_SESSION['currencies'] = $currency_details;
			$_SESSION['default_currency'] = $_SESSION['currencies'][0];
		}

		$data['currency_details'] = $currency_details;
		$data['account_details'] = $account_details;
		$data['TO62_Finalizing'] = $TO62_Finalizing;


		foreach($TO62_Finalizing as $TO62each)
		{
			$TO62_Details = $this->Public_Model->get_data_all('vw_to_62', " currency_id = ".$TO62each->currency_id.
															   " AND period_status = 'Open'  ", null, null
																, '*');
			
			$vw_to_62['TO62_DET_'.$TO62each->currency] = $TO62_Details;
		}

		if($this->session->flashdata('navPillSelect'))
		{
			$data['navPillSelect'] = $this->session->flashdata('navPillSelect');
		}
		else
		{
			$data['navPillSelect'] = 'ledgers26';
		}

		$data['vw_to_62'] = $vw_to_62;

		$this->loadpage('dashboard',$data);
	}
	
	public function forgot()
	{
		if (!isset($_SESSION['user']->is_logged_in))
		{
			$this->load->view('forgot-access-pin');
		}
		else
		{
			$this->allowAccess();
		}
	}

	public function defaultingCurrency($currencyID){
	  
	    $_SESSION['default_currency'] = $_SESSION['currencies'][0];

		foreach($_SESSION['currencies'] as $eachCurrency)
		{
			if($eachCurrency->currency_id == $currencyID)
			{
				$_SESSION['default_currency'] = $eachCurrency;
			}
		}

		redirect('JwAccounting');
		
	  }
	
	public function capture_Transaction(){
	     $this->load->library('form_validation');
    	 $this->form_validation->set_rules('transdate', 'Date Of Transanction!'
		 									, 'trim|required|callback_transdate_check');
		 $this->form_validation->set_rules('amount', 'Amount Of Transanction', 'required');
		 $this->form_validation->set_rules('tc', 'Transanction Type'
		 									, 'trim|required|callback_selection_check');
		 $this->form_validation->set_rules('currency', 'Currency Type'
		 									, 'trim|required|callback_selection_check');

		 $this->form_validation->set_rules('descrip', 'Description Of Transanction', 'required');
		
		 $this->form_validation->set_rules('account', 'Account Type'
		 									, 'trim|required|callback_selection_check');
		 
		 $this->form_validation->set_rules('confirm_det', 'Confirm Details'
		 									, 'trim|required|callback_confirm_check');
		 
         
         if ($this->form_validation->run($this) == FALSE)
    {
		//die('failed');
		$this->session->set_flashdata('userError', 'Posting Error');
		$this->session->set_flashdata('navPillSelect', 'capture-trans');
		$this->allowAccess();
	}
	
	else
	{
		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'open' ", null, null
																, '*');
		
		 $data = array( 
            'transanction_date' => $this->input->post('transdate'), 
            'description' => $this->input->post('descrip'), 
            'transaction_code_id' => $this->input->post('tc'), 
            'amount' => $this->input->post('amount'), 
            'currency_id' => $this->input->post('currency'), 
            'account_id' => $this->input->post('account'), 
            'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time()),
			'period_id' => $period_details->tbl_period_id
         ); 
		 
		 $this->post_ID = $this->Public_Model->insert_and_return_key($data,'tbl_ledger_s_26');
		 						
		 $this->session->set_flashdata('userSuccess', 'Posting Successfull');
         redirect('JwAccounting');		 
	
    }
         
		
	  }

	
	  public function processTO62(){
		$amount = 0.00;
		$this->load->library('form_validation');

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'open' ", null, null
															   , '*');

		$TO62_TransType = $this->Public_Model->get_data_all('tbl_to_62_trans_type', " 1 = 1 ", null, null
																, '*');
		
		$to_62_reference = $this->Public_Model->get_data_record('tbl_to_62_reference', " period_id = ".$period_details->tbl_period_id
																." AND currency_id = ".$this->input->post('currID')
																, null, null, '*');

		$this->form_validation->set_rules('transdate'.$this->input->post('currName'), 'Date Of Transanction!'
											, 'trim|required|callback_transdate_check');
		$this->form_validation->set_rules('transMethod'.$this->input->post('currName'), 'Transanction Method'
											, 'trim|required|callback_selection_check');
		//$this->form_validation->set_rules('refno', 'Refference Number', 'required');

		foreach($TO62_TransType as $TO62_Each)
		{
			$this->form_validation->set_rules('input'.$TO62_Each->tbl_to_62_trans_type_id.$this->input->post('currName'), 'Amount Of '.$TO62_Each->description, 'required');
		}
		
		
		if ($this->form_validation->run($this) == FALSE)
   {
	   //die('failed');
	   $this->session->set_flashdata('userError', 'Posting Error');
	   $this->session->set_flashdata('navPillSelect', 'process-TO62');
	   $this->session->set_flashdata('TO-62'.$this->input->post('currName'), 'active');
	   $this->session->set_flashdata('errorTO-62-Currency', $this->input->post('currName'));
	   $this->allowAccess();
   }
   
   else
   {
	   
	   
		$data = array( 
		   'transanction_date' => $this->input->post('transdate'), 
		   'description' => $this->input->post('descrip'), 
		   'transaction_code_id' => $this->input->post('tc'), 
		   'amount' => $this->input->post('amount'), 
		   'currency_id' => $this->input->post('currency'), 
		   'account_id' => $this->input->post('account'), 
		   'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time()),
		   'period_id' => $period_details->tbl_period_id
		); 
		
		$this->post_ID = $this->Public_Model->insert_and_return_key($data,'tbl_ledger_s_26');
								
		$this->session->set_flashdata('userSuccess', 'Posting Successfull');
		redirect('JwAccounting');		 
   
   }
		
	   
	 }

	public function transdate_check($str)
	{ 
		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'open' 
																				AND startdate <= '{$str}'
																				AND enddate >= '{$str}'", null, null
																				, '*');
				
			if (!isset($period_details->sequenceno)) 
			{ $this->form_validation->set_message('transdate_check', "Transanction Date $str is not in open periods"); 
				return FALSE; 
			} 
			else { return TRUE; 
			}

	}

	public function selection_check($str)
	{ 

			if ($str == '0') 
			{ $this->form_validation->set_message('selection_check', "Selection 'select...' is not valid"); 
				return FALSE; 
			} 
			else { return TRUE; 
			}

	}

	public function confirm_check($str)
	{ 

			if ($str != 's1') 
			{ $this->form_validation->set_message('confirm_check', "Kindly Confirm Correctness Of Details"); 
				return FALSE; 
			} 
			else { return TRUE; 
			}

	}
	
	
}
