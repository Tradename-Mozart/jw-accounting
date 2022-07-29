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

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
																, '*');
		
		$tc_details = $this->Public_Model->get_data_all('tbl_transaction_code', " type <> 'IO' and tbl_transaction_code_id <> 8 ", null, null
																, '*');		
																
		$account_details = $this->Public_Model->get_data_all('tbl_account', " 1 = 1 ", null, null
																, '*');
		
		
		$currency_details = $this->Public_Model->get_data_all('tblcurrency', " 1 = 1 "
																, null, null
																, '*');	
		
		$vw_to_62 = $this->Public_Model->get_data_all('vw_to_62', " currency_id = ".(isset($_SESSION['default_currency']->currency_id)?$_SESSION['default_currency']->currency_id:1).
															   " AND period_status = 'Open'  ", null, null
																, '*');

		$to62Ref = $this->Public_Model->get_data_record('tbl_to_62_reference', " period_id = ".$period_details->tbl_period_id
														." AND currency_id = ".(isset($_SESSION['default_currency']->currency_id)?$_SESSION['default_currency']->currency_id:1)
														, null, null, '*, DATE_FORMAT(transer_date, "%Y-%m-%d") AS transfer_date_form');

		$cashStand = $this->Public_Model->get_data_record('vw_cash_box_standing', " currency_id = ".(isset($_SESSION['default_currency']->currency_id)?$_SESSION['default_currency']->currency_id:1).
															   " AND status = 'Open'  ", null, null
																, '*');
		
		
		
		$data['title'] = 'dashboard';

		$data['congregation_name'] = $Congre_details->congregation_name;
		$data['city'] = $Congre_details->city;
		$data['province_state'] = $Congre_details->province_state;
		$data['sequenceno'] = $period_details->sequenceno;
		$data['tbl_period_id'] = $period_details->tbl_period_id;
		$data['tc_details'] = $tc_details;

		if(!isset($_SESSION['default_currency']))
		{
			$_SESSION['currencies'] = $currency_details;
			$_SESSION['default_currency'] = $_SESSION['currencies'][0];
		}

		$data['currency_details'] = $currency_details;
		$data['account_details'] = $account_details;		
		$data['vw_to_62'] = $vw_to_62;
		$data['to62Ref'] = $to62Ref;

		if($this->session->flashdata('navPillSelect'))
		{
			$data['navPillSelect'] = $this->session->flashdata('navPillSelect');
		}
		else
		{
			$data['navPillSelect'] = 'ledgers26';
		}

		$data['vw_to_62'] = $vw_to_62;
		$data['cashStand'] = $cashStand;

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

		 $this->form_validation->set_rules('descrip', 'Description Of Transanction', 'required');
		
		 $this->form_validation->set_rules('account', 'Account Type'
		 									, 'trim|required|callback_selection_check');
		 
		 $this->form_validation->set_rules('confirm_det', 'Confirm Details'
		 									, 'trim|required|callback_confirm_check');
		 
         
         if ($this->form_validation->run($this) == FALSE)
    {
		//die('failed');
		$this->session->set_flashdata('captureTransError', 'Posting Error');
		$this->session->set_flashdata('userError', 'Posting Error');
		$this->session->set_flashdata('navPillSelect', 'capture-trans');
		$this->allowAccess();
	}
	
	else
	{
		$size_running_analysis_acc = 0;
		$acc_runningNet[1] = 0.00;
		$acc_runningNet[2] = 0.00;
		$acc_runningNet[3] = 0.00;
		$amountForCongreUseThen = 0.00;

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
																, '*');

		$tc_details = $this->Public_Model->get_data_record('tbl_transaction_code', " tbl_transaction_code_id = ".$this->input->post('tc')
															, null, null, '*');
		
		$vw_cash_box_snap = $this->Public_Model->get_data_record('vw_cash_box_snap', " status = 'Open' 
																				AND currency_id = ".$_SESSION['default_currency']->currency_id
																 , null, null, '*');
													
		$vw_account_standing_p2p = $this->Public_Model->get_data_record('vw_account_standing_p2p', " status = 'Open' 
																		 AND currency_id = ".$_SESSION['default_currency']->currency_id
																		." AND tbl_account_id = ".$this->input->post('account')
																 		, null, null, '*');
		$sortby[] =  array('field'=>'trans_day'
							 , 'direction' => "asc");
		
		$sortby[] =  array('field'=>'createdate'
							 , 'direction' => "asc");

		$vw_running_analysis_acc = $this->Public_Model->get_data_all('vw_running_analysis_acc', " status = 'Open' 
																		 AND currency_id = ".$_SESSION['default_currency']->currency_id
																	 ." AND trans_day <= DAY('".$this->input->post('transdate')."')"
																 		, $sortby, null, '*');
		
										
		if(isset($vw_running_analysis_acc[0]->trans_day))
		{
			$size_running_analysis_acc = sizeof($vw_running_analysis_acc);
			$amountForCongreUseThen = $vw_running_analysis_acc[$size_running_analysis_acc-1]->running_amnt_for_congr_expense;
			$acc_runningNet[1] = $vw_running_analysis_acc[($size_running_analysis_acc-1)]->running_rec_net;
			$acc_runningNet[2] = $vw_running_analysis_acc[($size_running_analysis_acc-1)]->running_prim_net;
		}

		 $data = array( 
            'transanction_date' => $this->input->post('transdate'), 
            'description' => $this->input->post('descrip'), 
            'transaction_code_id' => $this->input->post('tc'), 
            'amount' => $this->input->post('amount'), 
            'currency_id' => $_SESSION['default_currency']->currency_id, 
            'account_id' => $this->input->post('account'), 
            'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time()),
			'period_id' => $period_details->tbl_period_id
         );
		 
		 // Handle Expenses and Amount Outflows
		 if($tc_details->transaction_code == 'E' && $this->input->post('account') != 2)
		 {
			$this->session->set_flashdata('captureTransError', 'Posting Error');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorDesc', "Expense Amount Cannot Be Captured On This Account");
			$this->session->set_flashdata('navPillSelect', 'capture-trans');
			$this->allowAccess();
			return;
		 }
		 else if($tc_details->transaction_code == 'E' && $this->input->post('account') == 2
		 		&& ($this->input->post('amount') > $vw_cash_box_snap->amount_in_cash_box_less_ww
					|| $this->input->post('amount') > $amountForCongreUseThen)
				)
		 {
			$this->session->set_flashdata('captureTransError', 'Posting Error');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorDesc', "Expense Amount ".$this->input->post('amount')." Is Greater Than Amount In  Cash Box For Congregation Use");
			$this->session->set_flashdata('navPillSelect', 'capture-trans');
			$this->allowAccess();
			return;
		 }
		 else if($tc_details->transaction_code == 'DO' && $this->input->post('account')  != 1)
		 {
			$this->session->set_flashdata('captureTransError', 'Posting Error');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorDesc', "You Cannot Deposit from This Account");
			$this->session->set_flashdata('navPillSelect', 'capture-trans');
			$this->allowAccess();
			return;
		 }
		 else if(($tc_details->transaction_code == 'FRXWO' || $tc_details->transaction_code == 'FRXCO' || $tc_details->transaction_code == 'FRXWI' || $tc_details->transaction_code == 'FRXCI') 
		 		 && $this->input->post('account')  != 2)
		 {
			$this->session->set_flashdata('captureTransError', 'Posting Error');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorDesc', "You Cannot Exchange Currency In This Account");
			$this->session->set_flashdata('navPillSelect', 'capture-trans');
			$this->allowAccess();
			return;
		 }
		 else if($tc_details->transaction_code == 'DI' && ($this->input->post('account')  == 1 || $this->input->post('account')  == 3 ))
		 {
			$this->session->set_flashdata('captureTransError', 'Posting Error');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorDesc', "You Cannot Deposit Into This Account");
			$this->session->set_flashdata('navPillSelect', 'capture-trans');
			$this->allowAccess();
			return;
		 }
		 else if($tc_details->type == 'O' && $tc_details->transaction_code != 'E'
		 		  && ( $this->input->post('amount') > $vw_account_standing_p2p->account_net_amount
				 	|| $this->input->post('amount') > $acc_runningNet[$this->input->post('account')])
				 )
		 {
			$this->session->set_flashdata('captureTransError', 'Posting Error');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorDesc', "Outbound Amount ".$this->input->post('amount')." Is Greater Than Amount In Account");
			$this->session->set_flashdata('navPillSelect', 'capture-trans');
			$this->allowAccess();
			return;
		 }

		 // EOF Handle Expenses and Amount Outflows
		 if($this->input->post('amount') > 0)
		 {
		 	$this->post_ID = $this->Public_Model->insert_and_return_key($data,'tbl_ledger_s_26');

			if($tc_details->transaction_code == 'DO')
			{
				$data = array( 
					'transanction_date' => $this->input->post('transdate'), 
					'description' => $this->input->post('descrip'), 
					'transaction_code_id' => 8, 
					'amount' => $this->input->post('amount'), 
					'currency_id' => $_SESSION['default_currency']->currency_id, 
					'account_id' => 2, 
					'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time()),
					'period_id' => $period_details->tbl_period_id
				 );

				 $this->Public_Model->insert($data,'tbl_ledger_s_26');
			}
		 }
		 						
		 $this->session->set_flashdata('userSuccess', 'Posting Successfull');
         redirect('JwAccounting');		 
	
    }
         
		
	  }

	public function delete_Transaction($date, $tc, $createdate, $ledgerID)
	{
		$createdate = rawurldecode($createdate);

		$ledgerRowDet = $this->Public_Model->get_data_record('tbl_ledger_s_26', " tbl_ledger_S_26_id = {$ledgerID}", null, null, '*');

		if($tc == 6 || $tc == 12 || $tc == 14 || $tc == 10)
		{
			$this->Public_Model->delete('tbl_ledger_s_26', 'tbl_ledger_S_26_id', $ledgerID);

			if($tc == 10)
			{
				$to62Ref = $this->Public_Model->get_data_record('tbl_to_62_reference', " period_id = ".$ledgerRowDet->period_id." AND currency_id = ".$ledgerRowDet->currency_id, null, null, '*');
				$this->Public_Model->delete('tbl_record_funds_trans_to_62', 'to_62_reference', $to62Ref->tbl_to_62_reference_id);
			}

			$this->session->set_flashdata('userSuccess', 'Delete Successfull');
         	redirect('JwAccounting');
		}
		else if($tc == 9)
		{
			$checkNegBalance = $this->Public_Model->get_data_all('vw_running_analysis_acc', " period_id = ".$ledgerRowDet->period_id
																	." AND currency_id = ".$ledgerRowDet->currency_id
																	." AND ( trans_day > {$date} OR (trans_day = {$date} AND createdate >= '$createdate') )"
																	." AND running_prim_net - '".$ledgerRowDet->amount."' < 0 "
																	, null, null, '*, running_prim_net -\''.$ledgerRowDet->amount.'\' AS money_post_del');

			if(isset($checkNegBalance[0]->trans_day))
			{
				$this->session->set_flashdata('userError', 'Delete Error');
				$this->session->set_flashdata('errorDesc', 'Deletion Will Cause Irregularities');
				$this->session->set_flashdata('error-ledger-s26', 'Deletion Will Cause Irregularities ');
				redirect('JwAccounting');
			}
			else
			{
				$this->Public_Model->delete('tbl_ledger_s_26', 'tbl_ledger_S_26_id', $ledgerID);
				$this->Public_Model->delete('tbl_ledger_s_26', 'tbl_ledger_S_26_id', $ledgerID+1);
				$this->session->set_flashdata('userSuccess', 'Delete Successfull');
         		redirect('JwAccounting');
			}
		}
		else if($tc == 1 || $tc == 2 || $tc == 11 || $tc == 13)
		{
			if($ledgerRowDet->account_id == 1)
			{
				$accountSelectType = 'running_rec_net';
			}
			else if($ledgerRowDet->account_id == 2)
			{
				$accountSelectType = 'running_prim_net';
			} 
			 
			$checkNegBalance = $this->Public_Model->get_data_all('vw_running_analysis_acc', " period_id = ".$ledgerRowDet->period_id
																	." AND currency_id = ".$ledgerRowDet->currency_id
																	." AND ( trans_day > {$date} OR (trans_day = {$date} AND createdate >= '$createdate') )"
																	." AND {$accountSelectType} - '".$ledgerRowDet->amount."' < 0 "
																	, null, null, "*, {$accountSelectType} - '".$ledgerRowDet->amount."' AS money_post_del");

			if(isset($checkNegBalance[0]->trans_day))
			{
				$this->session->set_flashdata('userError', 'Delete Error');
				$this->session->set_flashdata('errorDesc', 'Deletion Will Cause Irregularities');
				$this->session->set_flashdata('error-ledger-s26', 'Deletion Will Cause Irregularities ');
				redirect('JwAccounting');
			}
			else
			{
				$this->Public_Model->delete('tbl_ledger_s_26', 'tbl_ledger_S_26_id', $ledgerID);
				$this->session->set_flashdata('userSuccess', 'Delete Successfull');
         		redirect('JwAccounting');
			}
		}

		
	}
	  
	
	public function processTO62(){
		$amount = 0.00;
		$this->load->library('form_validation');

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
															   , '*');

		$TO62_TransType = $this->Public_Model->get_data_all('tbl_to_62_trans_type', " 1 = 1 ", null, null
																, '*');
							
		$vw_cash_box_snap = $this->Public_Model->get_data_record('vw_cash_box_snap', " status = 'Open' 
																				AND currency_id = ".$_SESSION['default_currency']->currency_id
																 , null, null, '*');

		$this->form_validation->set_rules('transdate', 'Date Of Transanction!'
											, 'trim|required|callback_transdate_check');
		$this->form_validation->set_rules('transMethod', 'Transanction Method'
											, 'trim|required|callback_selection_check');
		//$this->form_validation->set_rules('refno', 'Refference Number', 'required');

		foreach($TO62_TransType as $TO62_Each)
		{
			$this->form_validation->set_rules('input'.$TO62_Each->tbl_to_62_trans_type_id, 'Amount Of '.$TO62_Each->description, 'required');
		}
		
		
		if ($this->form_validation->run($this) == FALSE)
   {
	   //die('failed');
	   $this->session->set_flashdata('userError', 'Posting Error');
	   $this->session->set_flashdata('navPillSelect', 'process-TO62');
	   $this->session->set_flashdata('errorTO-62-Currency', 'Postng Error');
	   $this->allowAccess();
   }
   
   else
   {	

		$size_running_analysis_acc = 0;
		
		$acc_runningNet[2] = 0.00;

		$sortby[] =  array('field'=>'trans_day'
							 , 'direction' => "asc");
		
		$sortby[] =  array('field'=>'createdate'
							 , 'direction' => "asc");

		$vw_running_analysis_acc = $this->Public_Model->get_data_all('vw_running_analysis_acc', " status = 'Open' 
																		 AND currency_id = ".$_SESSION['default_currency']->currency_id
																	 ." AND trans_day <= DAY('".$this->input->post('transdate')."')"
																 		, $sortby, null, '*');

		
		if(isset($vw_running_analysis_acc[0]->trans_day))
		{
			$size_running_analysis_acc = sizeof($vw_running_analysis_acc);
		}
		
		if(isset($vw_running_analysis_acc[0]->trans_day))
		{
			$acc_runningNet[2] = $vw_running_analysis_acc[($size_running_analysis_acc-1)]->running_prim_net;
		}
		
		foreach($TO62_TransType as $TO62_Each)
		{
			$amount += $this->input->post('input'.$TO62_Each->tbl_to_62_trans_type_id);
		}

		//die($acc_runningNet[2]);

		if($amount > $vw_cash_box_snap->amount_in_cash_box || $amount > $acc_runningNet[2])
		{
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('navPillSelect', 'process-TO62');
	   		$this->session->set_flashdata('errorTO-62-Currency', "Amount {$amount} is greater than amount in cash box");
			$this->session->set_flashdata('errorDesc', "Amount {$amount} is greater than amount in cash box");
			$this->allowAccess();
		}
		else
		{
			// Handle tbl_to_62_reference
			$to62ReferenceID = $this->handleTblTO62Reference($this->input);
			
			// Handle recordOfFundTransfer
			$this->handleRecordOfFundTransfer($this->input, $to62ReferenceID);

			

			$ledegerS26 = $this->Public_Model->get_data_record('tbl_ledger_s_26', " period_id = ".$period_details->tbl_period_id
															    ." AND transaction_code_id  = 10 "
																, null, null, '*');

			$data = array( 
				'transanction_date' => $this->input->post('transdate'), 
				'description' => "To Branch Office - ".$this->input->post('refno'), 
				'transaction_code_id' => 10, 
				'amount' => $amount, 
				'currency_id' => $_SESSION['default_currency']->currency_id, 
				'account_id' => 2, 
				'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time()),
				'period_id' => $period_details->tbl_period_id
			 );
			 
			 if(isset($ledegerS26->tbl_ledger_S_26_id) && $this->input->post('refno') != '')
			 {
				$this->Public_Model->update($data,"tbl_ledger_S_26_id",$ledegerS26->tbl_ledger_S_26_id,"tbl_ledger_s_26" );
			 }
			 else if($amount > 0 && $this->input->post('refno') != '')
			 {
				$this->Public_Model->insert($data,'tbl_ledger_s_26');
			 }
			 // EOF Handle amountToS26
									 
			 $this->session->set_flashdata('userSuccess', 'Posting Successfull');
			 redirect('JwAccounting');
		}
	   
				 
   
   }
		
	   
	 }

	function handleTblTO62Reference($postData)
	{
		$insertedID = 0;
		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
																, '*');
														
		$to_62_reference_details = $this->Public_Model->get_data_record('tbl_to_62_reference', " period_id = ".$period_details->tbl_period_id
																		." AND currency_id = ".$_SESSION['default_currency']->currency_id
																		, null, null, '*');
		
		$data = array( 
				'currency_id' => $_SESSION['default_currency']->currency_id, 
				'period_id' => $period_details->tbl_period_id,
				'referrence_no' => $postData->post('refno'), 
				'transfer_method' => $postData->post('transMethod'), 
				'transer_date' => $postData->post('transdate'),
				'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time())
			 );

		if(isset($to_62_reference_details->tbl_to_62_reference_id))
		{
			$this->Public_Model->update($data,"tbl_to_62_reference_id",$to_62_reference_details->tbl_to_62_reference_id,"tbl_to_62_reference" );
		}
		else
		{
			$insertedID = $this->Public_Model->insert_and_return_key($data,'tbl_to_62_reference');
		}

		return isset($to_62_reference_details->tbl_to_62_reference_id)?$to_62_reference_details->tbl_to_62_reference_id:$insertedID;
	}

	function handleRecordOfFundTransfer($postData, $to62ReferenceID)
	{
		$insertedID = 0;
		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
																, '*');
			
		$vw_to_62 = $this->Public_Model->get_data_all('vw_to_62', " currency_id = ".$_SESSION['default_currency']->currency_id
															   ." AND period_status = 'Open'  ", null, null
																, '*');															
		foreach($vw_to_62 as $vw_to_62Each)
		{
			$data = array( 
				'amount' => $postData->post('input'.$vw_to_62Each->tbl_to_62_trans_type_id), 
				'to_62_reference' => $to62ReferenceID,
				'to_62_trans_type_id' => $vw_to_62Each->tbl_to_62_trans_type_id, 
				'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time())
			 );

			if(isset($vw_to_62Each->to_62_trans_type_id))
			{
				$this->Public_Model->update($data,"tbl_record_funds_trans_to_62_id",$vw_to_62Each->tbl_record_funds_trans_to_62_id,"tbl_record_funds_trans_to_62" );
			}
			else if($postData->post('input'.$vw_to_62Each->tbl_to_62_trans_type_id) > 0)
			{
				$insertedID = $this->Public_Model->insert_and_return_key($data,'tbl_record_funds_trans_to_62');
			}
		}												
		
	}

	public function processCashBoxStanding()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('cash_in_box', 'Amount In Cash Box', 'required');

		if ($this->form_validation->run($this) == FALSE)
		{
			//die('failed');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('navPillSelect', 'cashBoxStanding');
			$this->session->set_flashdata('error-cash-box-standing', 'cashBoxStanding');
			$this->allowAccess();
		}
		else
		{
			$cashStand = $this->Public_Model->get_data_record('vw_cash_box_standing', " currency_id = ".$_SESSION['default_currency']->currency_id.
															   " AND status = 'Open'  ", null, null
																, '*');

			$data = array( 
				'period_id' => $cashStand->tbl_period_id, 
				'currency_id' => $_SESSION['default_currency']->currency_id, 
				'cash_in_box' => $this->input->post('cash_in_box'), 
				'complete_pay_not_recorde' => $this->input->post('comp_pay_no_record'), 
				'cash_adv_not_clr' => $this->input->post('cash_adv_no_clr'),
				'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time())
			 );

			if(isset($cashStand->tbl_closing_details_id))
			{
				$this->Public_Model->update($data,"tbl_closing_details_id",$cashStand->tbl_closing_details_id,"tbl_closing_details" );
			}
			else
			{
				$this->Public_Model->insert($data,'tbl_closing_details');
			}

			$this->session->set_flashdata('userSuccess', 'Posting Successfull');
         	redirect('JwAccounting');	
		}
	}

	public function transdate_check($str)
	{ 
		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' 
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
