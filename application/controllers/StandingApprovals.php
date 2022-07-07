<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StandingApprovals extends MY_Controller {

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

		$resolvMonContrib = $this->Public_Model->get_data_record('tbl_resolved_mon_contribution'
															     , " currency_id = ".$_SESSION['default_currency']->currency_id
																 , null, null
																		, '*');

		$data['title'] = 'Standing File';
		$data['resolvMonContrib'] = $resolvMonContrib;
		$data['navPillSelect'] = 'resolvedMonContr';

		$this->loadpage('standing-file',$data);
		
	}
	
	
}
