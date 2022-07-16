<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClosingPeriod extends MY_Controller {

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
		if (!isset($_SESSION['user']->is_logged_in))
		{
			redirect('JwAccounting');
		}

		$countErrors = 0;

		$vw_error_warning = $this->Public_Model->get_data_all('vw_error_warning', " 1 = 1 ", 'type', null, '*');

		if(isset($vw_error_warning[0]->type))
			{
			foreach($vw_error_warning as $errorEach)
			{
			
				if($errorEach->type == 'Error')
				{
					$countErrors++;
				}
			}
		}

		$data['title'] = 'Close Period';
		$data['vw_error_warning'] = $vw_error_warning;
		$data['navPillSelect'] = 'resolvedMonContr';
		$data['countErrors'] = $countErrors;

		$this->loadpage('close-period',$data);
		
	}

	public function processClosePeriod()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm_det', 'Confirm Closing Of Period', 'trim|required|callback_confirm_check');

		if ($this->form_validation->run($this) == FALSE)
		{
			//die('failed');
			$this->session->set_flashdata('userError', 'Posting Error');
			$this->session->set_flashdata('errorClosingPeriod', 'errorClosingPeriod');
			$this->index();
		}
		else
		{
			$periodDet = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null, '*');

			$periodNext = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
															    , 'DATE_ADD(startdate, INTERVAL 1 month ) AS startdate,
																LAST_DAY(DATE_ADD(enddate, INTERVAL 1 MONTH )) AS enddate,
																CONCAT(YEAR(DATE_ADD(startdate, INTERVAL 1 MONTH )),LPAD(MONTH(DATE_ADD(startdate, INTERVAL 1 MONTH )),2,0)) AS sequenceno,
																YEAR(DATE_ADD(startdate, INTERVAL 1 month )) AS  year,
																MONTH(DATE_ADD(startdate, INTERVAL 1 month )) AS  mon,
																tbl_period_id AS previouse_period_id,
																status'
															);
			
			$vw_account_standing_p2p = $this->Public_Model->get_data_all('vw_account_standing_p2p', " status = 'Open' " 
																." AND ( account_net_amount > 0 OR ABS(income_amount_curr_mon) + ABS(outbound_amount_curr_mon) > 0 ) ", null, null, '*');
		

		foreach($vw_account_standing_p2p as $eachClosing)
		{
			$data = array( 
				'period_id' => $eachClosing->tbl_period_id, 
				'account_id' => $eachClosing->tbl_account_id,  
				'currency_id' => $eachClosing->currency_id,  
				'amount_in' => $eachClosing->income_amount_curr_mon, 
				'amount_out' => $eachClosing->outbound_amount_curr_mon,  
				'amount_closing' => $eachClosing->account_net_amount,  
				'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time())
			 );

			 $this->Public_Model->insert($data,'tbl_acc_closing_det');

		}

		
		// close period

		$data = array( 
		'status' => 'Closed'
		);

		$this->Public_Model->update($data,"tbl_period_id",$periodDet->tbl_period_id,"tbl_period" );

		// Open new period

		$data = array( 
			'startdate' => $periodNext->startdate, 
			'enddate' => $periodNext->enddate,  
			'sequenceno' => $periodNext->sequenceno,  
			'year' => $periodNext->year, 
			'mon' => $periodNext->mon,  
			'previouse_period_id' => $periodNext->previouse_period_id,
			'status' => $periodNext->status, 
			'createdate' =>  mdate('%Y-%m-%d %h:%i:%s', time())
		 );

		$this->Public_Model->insert($data,'tbl_period');

		$this->session->set_flashdata('userSuccess', 'Period Successfully Closed. New Period Opened');
         redirect('JwAccounting');

		}
	}

	public function confirm_check($str)
	{ 

			if ($str != 's1') 
			{ $this->form_validation->set_message('confirm_check', "Kindly Confirm Closing Of Period"); 
				return FALSE; 
			} 
			else { return TRUE; 
			}

	}
	
	
}
