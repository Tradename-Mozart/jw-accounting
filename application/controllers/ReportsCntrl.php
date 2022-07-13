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

		$data['title'] = 'Reports';

		$this->loadpage('reports',$data);
		
	}

	public function getReportTest()
	{
		//echo 'App Path name '.APPPATH;

		//require_once 'vendor/autoload.php';

		

		$data = [

			'900_1_Text_C' => '3',
			'901_1_S26Value' => '9',
			'902_1_S26Value' => '15',
			'903_1_S26Value' => '21',
			'900_2_Text_C' => '27'
		];


		$pdf = new GeneratePdf;
		$response = $pdf->customGenerate($data, APPPATH.'libraries/fillpdf/S-26_CA_Test.pdf', APPPATH.'libraries/fillpdf/completed/');

		echo $response;
			}

	public function populatingS26($periodID, $currency_id = NULL)
	{
		$sum_rec_in = $sum_rec_out = $sum_prim_in = $sum_prim_out = $sum_seca_in = $sum_seca_out = 0.00;
		$currFieldS26 = 1;

		$s26CAMapping = $this->PDFKit_Model->s26CAMapping();
		$s26CASpreadSheetMapping = $this->PDFKit_Model->s26CASpreadSheetMapping();

		$congrDet = $this->Public_Model->get_data_record('tbl_congregation_detail', " 1 = 1 ", null, null, '*');
		$periodDet = $this->Public_Model->get_data_record('tbl_period', " tbl_period_id =  ".$periodID, null, null, '*, MONTHNAME(startdate) AS month_name');
		$closeDate = $this->Public_Model->get_data_record('vw_extract_date', " tbl_period_id =  ".$periodID, null, null, '*');

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
		$data[$s26CAMapping['75']] = $congrDet->month_name;
		$data[$s26CAMapping['99']] = $congrDet->year;

		foreach($vw_s26_data as $vw_s26_each)
		{
			$data[$s26CAMapping[$s26CASpreadSheetMapping['D'.$currFieldS26]]] = $vw_s26_each->trans_day;
			$data[$s26CAMapping[$s26CASpreadSheetMapping['DSCR'.$currFieldS26]]] = $vw_s26_each->description;
			$data[$s26CAMapping[$s26CASpreadSheetMapping['TC'.$currFieldS26]]] = $vw_s26_each->transaction_code;
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
		}

		$data[$s26CAMapping['1251']] = $sum_rec_in;
		$data[$s26CAMapping['2523']] = $sum_rec_out;
		$data[$s26CAMapping['1257']] = $sum_prim_in;
		$data[$s26CAMapping['2529']] = $sum_prim_out;
		$data[$s26CAMapping['1263']] = $sum_seca_in;
		$data[$s26CAMapping['2535']] = $sum_seca_out;

		
	}
	
}
