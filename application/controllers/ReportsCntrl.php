<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('ACCESSCHECK', TRUE);

require_once(APPPATH.'libraries/fillpdf/vendor/autoload.php');
use Classes\GeneratePDF;

class ReportsCntrl extends MY_Controller {

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
		 $this->load->Model('PDFKit_Model'); 
      } 
	
	public function index()
	{
		if (!isset($_SESSION['user']->is_logged_in))
		{
			redirect('JwAccounting');
		}

		$period_details = $this->Public_Model->get_data_record('tbl_period', " status = 'Open' ", null, null
																, '*');

		$data['sequenceno'] = $period_details->sequenceno;
		$data['tbl_period_id'] = $period_details->tbl_period_id;

		$data['title'] = 'Reports';

		$this->loadpage('reports',$data);
		
	}

	public function getReportTest()
	{
		//echo 'App Path name '.APPPATH;

		//require_once 'vendor/autoload.php';

		

		$data = [

			'900_1_Text' => '1',
			'900_2_Text' => '2',
			'901_1_S30BOM' => '3',
			'900_3_Text' => '4',
			'900_4_Text' => '5'			

		];


		$pdf = new GeneratePdf;
		$response = $pdf->customGenerate($data, APPPATH.'libraries/fillpdf/S-30_CA_Popu.pdf', APPPATH.'libraries/fillpdf/completed/');

		echo $response;
			}

	public function populatingS26($periodID, $currency_id = NULL)
	{
		$res = new stdClass();
		$sum_rec_in = $sum_rec_out = $sum_prim_in = $sum_prim_out = $sum_seca_in = $sum_seca_out = 0.00;
		$currFieldS26 = 1;

		$s26CAMapping = $this->PDFKit_Model->s26CAMapping();
		$s26CASpreadSheetMapping = $this->PDFKit_Model->s26CASpreadSheetMapping();

		$congrDet = $this->Public_Model->get_data_record('tbl_congregation_detail', " 1 = 1 ", null, null, '*');
		$periodDet = $this->Public_Model->get_data_record('tbl_period', " tbl_period_id =  ".$periodID, null, null, "*, MONTHNAME(startdate) AS month_name, CONCAT(DAY(enddate), ' ', MONTHNAME(enddate), ' ', YEAR(enddate)) AS end_date_display");
		$closeDate = $this->Public_Model->get_data_record('vw_extract_date', " tbl_period_id =  ".$periodID, null, null, '*');
		$cashBox = $this->Public_Model->get_data_record('tbl_closing_details', " period_id =  ".$periodID." AND currency_id = ".$_SESSION['default_currency']->currency_id, null, null, '*');
		$vw_account_standing_rec = $this->Public_Model->get_data_record('vw_account_standing_p2p', " tbl_period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND tbl_account_id = 1"
																		, null, null, '*');
						
		$vw_account_standing_prim = $this->Public_Model->get_data_record('vw_account_standing_p2p', " tbl_period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND tbl_account_id = 2"
																		, null, null, '*');
		//$currencyDet = $this->Public_Model->get_data_record('tblcurrency', " 1 = 1 ", null, null, '*');

		$sortby[] =  array('field'=>'trans_day'
							 , 'direction' => "asc");
		
		$sortby[] =  array('field'=>'createdate'
							 , 'direction' => "asc");

		$vw_s26_data = $this->Public_Model->get_data_all('vw_s26_data', " currency_id = ".$_SESSION['default_currency']->currency_id
															   ." AND period_id = {$periodID}", $sortby, null
																, '*');
		
		$data[$s26CAMapping['3']] = $congrDet->congregation_name;
		$data[$s26CAMapping['27']] = $congrDet->city;
		$data[$s26CAMapping['51']] = $congrDet->province_state;
		$data[$s26CAMapping['75']] = $periodDet->month_name;
		$data[$s26CAMapping['99']] = $periodDet->year;

		if(isset($vw_s26_data[0]->trans_day))
		{
			foreach($vw_s26_data as $vw_s26_each)
			{
				$data[$s26CAMapping[$s26CASpreadSheetMapping['D'.$currFieldS26]]] = ($vw_s26_each->transaction_code!='CTBSUP')?$vw_s26_each->trans_day:NULL;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['DSCR'.$currFieldS26]]] = $vw_s26_each->description;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['TC'.$currFieldS26]]] = ($vw_s26_each->transaction_code!='CTBSUP')?$vw_s26_each->transaction_code:NULL;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['RCI'.$currFieldS26]]] = $vw_s26_each->rec_in;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['RCO'.$currFieldS26]]] = $vw_s26_each->rec_out;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['PI'.$currFieldS26]]] = $vw_s26_each->prim_in;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['PO'.$currFieldS26]]] = $vw_s26_each->prim_out;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['SAI'.$currFieldS26]]] = $vw_s26_each->seca_in;
				$data[$s26CAMapping[$s26CASpreadSheetMapping['SAO'.$currFieldS26]]] = $vw_s26_each->seca_out;

				if(isset($vw_s26_each->rec_in))
				{
					$sum_rec_in += $vw_s26_each->rec_in;
				}
				if(isset($vw_s26_each->rec_out))
				{
					$sum_rec_out += $vw_s26_each->rec_out;
				}
				if(isset($vw_s26_each->prim_in))
				{
					$sum_prim_in += $vw_s26_each->prim_in;
				}
				if(isset($vw_s26_each->prim_out))
				{
					$sum_prim_out +=$vw_s26_each->prim_out;
				}
				if(isset($vw_s26_each->seca_in))
				{
					$sum_seca_in += $vw_s26_each->seca_in;
				}
				if(isset($vw_s26_each->seca_out))
				{
					$sum_seca_out += $vw_s26_each->seca_out;
				}

				$currFieldS26++;
			}
		}

		$data[$s26CAMapping['1251']] = number_format($sum_rec_in,2);
		$data[$s26CAMapping['2523']] = number_format($sum_rec_out,2);
		$data[$s26CAMapping['1257']] = number_format($sum_prim_in,2);
		$data[$s26CAMapping['2529']] = number_format($sum_prim_out,2);
		$data[$s26CAMapping['1263']] = number_format($sum_seca_in,2);
		$data[$s26CAMapping['2535']] = number_format($sum_seca_out,2);

		$data[$s26CAMapping['2877']] = $closeDate->extract_date;

		if(isset($cashBox->tbl_closing_details_id))
		{
			$data[$s26CAMapping['3015']] = $cashBox->cash_in_box;
			$data[$s26CAMapping['3021']] = $cashBox->complete_pay_not_recorde;
			$data[$s26CAMapping['3027']] = $cashBox->cash_adv_not_clr;

			$data[$s26CAMapping['3033']] = (isset($cashBox->cash_in_box)?$cashBox->cash_in_box:0.00)
										   + (isset($cashBox->complete_pay_not_recorde)?$cashBox->complete_pay_not_recorde:0.00)
										   + (isset($cashBox->cash_adv_not_clr)?$cashBox->cash_adv_not_clr:0.00);
			
			$data[$s26CAMapping['3033']] = number_format($data[$s26CAMapping['3033']],2);


		}

		$data[$s26CAMapping['3039']] = $periodDet->end_date_display;

		if(isset($vw_account_standing_rec->tbl_period_id))
		{
			$data[$s26CAMapping['3045']] = $vw_account_standing_rec->amount_closing_previouse;
			$data[$s26CAMapping['3051']] = $vw_account_standing_rec->income_amount_curr_mon;
			$data[$s26CAMapping['3057']] =  number_format(abs($vw_account_standing_rec->outbound_amount_curr_mon),2);
			$data[$s26CAMapping['3063']] = $vw_account_standing_rec->account_net_amount;
		}

		if(isset($vw_account_standing_prim->tbl_period_id))
		{
			$data[$s26CAMapping['3069']] = $vw_account_standing_prim->amount_closing_previouse;
			$data[$s26CAMapping['3075']] = $vw_account_standing_prim->income_amount_curr_mon;
			$data[$s26CAMapping['3081']] = number_format(abs($vw_account_standing_prim->outbound_amount_curr_mon),2);
			$data[$s26CAMapping['3087']] = $vw_account_standing_prim->account_net_amount;
		}
		
		$data[$s26CAMapping['3117']] = (isset($vw_account_standing_rec->account_net_amount)?($vw_account_standing_rec->account_net_amount):0.00)
									   +(isset($vw_account_standing_prim->account_net_amount)?($vw_account_standing_prim->account_net_amount):0.00);

		$data[$s26CAMapping['3117']] = number_format($data[$s26CAMapping['3117']],2);

		
		$pdf = new GeneratePdf;
		$response = $pdf->customGenerate($data, APPPATH.'libraries/fillpdf/S-26_CA_Test.pdf', FCPATH.'static/pdf_extract/', 'S-26_CA_'.$periodDet->sequenceno.'_'.$_SESSION['default_currency']->currency.'.pdf');

		$res->status = 'true';
		$res->response = $response;
		
		echo json_encode($res);
		
	}

	public function populatingTO62($periodID, $currency_id = NULL)
	{
		$res = new stdClass();
		$periodDet = $this->Public_Model->get_data_record('tbl_period', " tbl_period_id =  ".$periodID, null, null, "*, MONTHNAME(startdate) AS month_name, CONCAT(DAY(enddate), ' ', MONTHNAME(enddate), ' ', YEAR(enddate)) AS end_date_display");
		$congrDet = $this->Public_Model->get_data_record('tbl_congregation_detail', " 1 = 1 ", null, null, '*');

		$sortby[] =  array('field'=>'tbl_to_62_trans_type_id'
							 , 'direction' => "asc");
		

		$TO62_data = $this->Public_Model->get_data_all('vw_for_to_62_pdf_report', " currency_id = ".$_SESSION['default_currency']->currency_id
															   ." AND tbl_period_id = {$periodID}", $sortby, null
																, '*');

		$TO62_trans_Det = $this->Public_Model->get_data_record('tbl_to_62_reference', " currency_id = ".$_SESSION['default_currency']->currency_id
															   ." AND period_id = {$periodID}", null, null
																, '*');

		$data['900_1_CheckBox'] = 'Yes';
		$data['900_3_Text'] = $congrDet->congregation_name;
		$data['900_5_CheckBox'] = 'Yes';
		
		$data['901_1_TO62Donate'] = $TO62_data[0]->amount;
		$data['901_2_TO62Donate'] = $TO62_data[1]->amount;

		$data['901_9_TO62TotalDonate'] = (isset($TO62_data[0]->amount)?$TO62_data[0]->amount:0.00)
										 +(isset($TO62_data[1]->amount)?$TO62_data[1]->amount:0.00);

		$data['901_9_TO62TotalDonate'] = number_format($data['901_9_TO62TotalDonate'], 2);
		
		$data['901_11_TO62TotalFunds'] = (isset($TO62_data[0]->amount)?$TO62_data[0]->amount:0.00)
										 +(isset($TO62_data[1]->amount)?$TO62_data[1]->amount:0.00);
		
		$data['901_11_TO62TotalFunds'] = number_format($data['901_11_TO62TotalFunds'], 2);

		$data['900_12_Text'] = isset($TO62_trans_Det->referrence_no)?$TO62_trans_Det->referrence_no:'';

		$data['900_16_Text_C'] = 'T Marufu';
		$data['900_17_Text_C'] = 'T Mandebvu';

		$pdf = new GeneratePdf;
		$response = $pdf->customGenerate($data, APPPATH.'libraries/fillpdf/TO-62_CA_Popu.pdf', FCPATH.'static/pdf_extract/', 'TO-62_CA_'.$periodDet->sequenceno.'_'.$_SESSION['default_currency']->currency.'.pdf');

		$res->status = 'true';
		$res->response = $response;
		
		echo json_encode($res);
	}

	public function populatingS30($periodID, $currency_id = NULL)
	{
		$res = new stdClass();
		$periodDet = $this->Public_Model->get_data_record('tbl_period', " tbl_period_id =  ".$periodID, null, null, "*, MONTHNAME(startdate) AS month_name, CONCAT(DAY(enddate), ' ', MONTHNAME(enddate), ' ', YEAR(enddate)) AS end_date_display");
		$congrDet = $this->Public_Model->get_data_record('tbl_congregation_detail', " 1 = 1 ", null, null, '*');

		$sortby[] =  array('field'=>'tbl_to_62_trans_type_id'
							 , 'direction' => "asc");

		$TO62_data = $this->Public_Model->get_data_all('vw_for_to_62_pdf_report', " currency_id = ".$_SESSION['default_currency']->currency_id
															   ." AND tbl_period_id = {$periodID}", $sortby, null
																, '*');

		$sortby[] =  array('field'=>'tbl_to_62_trans_type_id'
							 , 'direction' => "asc");
		

		$TO62_data = $this->Public_Model->get_data_all('vw_for_to_62_pdf_report', " currency_id = ".$_SESSION['default_currency']->currency_id
															   ." AND tbl_period_id = {$periodID}", $sortby, null
																, '*');
		
		$vw_account_standing_rec = $this->Public_Model->get_data_record('vw_account_standing_p2p', " tbl_period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND tbl_account_id = 1"
																		, null, null, '*');
						
		$vw_account_standing_prim = $this->Public_Model->get_data_record('vw_account_standing_p2p', " tbl_period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND tbl_account_id = 2"
																		, null, null, '*');

		$ldgerS26CContrib = $this->Public_Model->get_data_record('tbl_ledger_s_26', " period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND transaction_code_id IN (2,13) "
																		, null, null, 'SUM(amount) AS congre_contrib');

		$ldgerS26WWContrib = $this->Public_Model->get_data_record('tbl_ledger_s_26', " period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND transaction_code_id IN (1,11) "
																		, null, null, 'SUM(amount) AS ww_contrib');

		$ldgerS26ExpensesCongre = $this->Public_Model->get_data_record('tbl_ledger_s_26', " period_id =  ".$periodID." AND currency_id = "
																		.$_SESSION['default_currency']->currency_id." AND transaction_code_id IN (6,12,14) "
																		, null, null, 'SUM(amount) AS contribExpense');

		$data['900_1_Text'] = $congrDet->congregation_name;
		$data['900_2_Text'] = $periodDet->month_name.'/'.$periodDet->year;
		
		$data['901_1_S30BOM'] = (isset($vw_account_standing_rec->amount_closing_previouse)?$vw_account_standing_rec->amount_closing_previouse:0.00)
									   +(isset($vw_account_standing_prim->amount_closing_previouse)?$vw_account_standing_prim->amount_closing_previouse:0.00);

		$data['901_1_S30BOM'] = number_format($data['901_1_S30BOM'], 2);

		$data['901_2_S30CongRec'] = $ldgerS26CContrib->congre_contrib;
		$data['901_6_S30TotalCongRec'] = $ldgerS26CContrib->congre_contrib;
		
		$data['901_7_S30OtherRec'] = $ldgerS26WWContrib->ww_contrib;
		$data['901_10_S30TotalOtherRec'] = $ldgerS26WWContrib->ww_contrib;

		$data['901_11_S30TotalRec'] = (isset($ldgerS26WWContrib->ww_contrib)?$ldgerS26WWContrib->ww_contrib:0.00)
													 + (isset($ldgerS26CContrib->congre_contrib)?$ldgerS26CContrib->congre_contrib:0.00);
		$data['901_11_S30TotalRec'] = number_format($data['901_11_S30TotalRec'], 2);

		
		$data['901_13_S30CongEx'] = $TO62_data[1]->amount;
		$data['900_7_Text'] = 'Kindom Hall Expenses and Other Expenses From Receipts';
		$data['901_17_S30CongEx'] = $ldgerS26ExpensesCongre->contribExpense;

		$data['901_19_S30TotalCongEx'] = (isset($TO62_data[1]->amount)?$TO62_data[1]->amount:0.00)
											+ (isset($ldgerS26ExpensesCongre->contribExpense)?$ldgerS26ExpensesCongre->contribExpense:0.00);

		$data['901_19_S30TotalCongEx'] = number_format($data['901_19_S30TotalCongEx'], 2);

		$data['901_20_S30OtherDis'] = $TO62_data[0]->amount;
		$data['901_23_S30TotalOtherDis'] = $TO62_data[0]->amount;
		$data['901_24_S30TotalDisburse'] = (isset($TO62_data[1]->amount)?$TO62_data[1]->amount:0.00)
											+ (isset($ldgerS26ExpensesCongre->contribExpense)?$ldgerS26ExpensesCongre->contribExpense:0.00)
											+ (isset($TO62_data[0]->amount)?$TO62_data[0]->amount:0.00);
		

		$data['901_24_S30TotalDisburse'] = number_format($data['901_24_S30TotalDisburse'],2);

		$data['901_25_S30SurDef'] = ((isset($ldgerS26WWContrib->ww_contrib)?$ldgerS26WWContrib->ww_contrib:0.00)
										+ (isset($ldgerS26CContrib->congre_contrib)?$ldgerS26CContrib->congre_contrib:0.00))
									 - ((isset($TO62_data[1]->amount)?$TO62_data[1]->amount:0.00)
									 + (isset($ldgerS26ExpensesCongre->contribExpense)?$ldgerS26ExpensesCongre->contribExpense:0.00)
									 + (isset($TO62_data[0]->amount)?$TO62_data[0]->amount:0.00));

		
		$data['901_25_S30SurDef'] = number_format($data['901_25_S30SurDef'],2);
		$data['901_26_S30TotalEOM'] = number_format(($data['901_1_S30BOM'] + $data['901_25_S30SurDef']),2); 							 
		$data['901_30_S30TotalFunds'] = $data['901_1_S30BOM'] + $data['901_25_S30SurDef'];
		$data['901_30_S30TotalFunds'] = number_format($data['901_30_S30TotalFunds'],2);
		$data['900_13_Text_C'] = 'Tatenda Marufu';

		$data['900_14_Text_C'] = $periodDet->month_name;
		$data['901_31_S30TotalDonation'] = $data['901_6_S30TotalCongRec'];
		$data['901_32_S30TotalExpense'] = $data['901_19_S30TotalCongEx'];
		$data['901_33_S30MonthEnd'] = $data['901_26_S30TotalEOM'];
		$data['901_34_S30ToWWW'] = $data['901_23_S30TotalOtherDis'];

		$pdf = new GeneratePdf;
		$response = $pdf->customGenerate($data, APPPATH.'libraries/fillpdf/S-30_CA_Popu.pdf', FCPATH.'static/pdf_extract/', 'S-30_CA_'.$periodDet->sequenceno.'_'.$_SESSION['default_currency']->currency.'.pdf');

		$res->status = 'true';
		$res->response = $response;
		
		echo json_encode($res);
	}
	
}
