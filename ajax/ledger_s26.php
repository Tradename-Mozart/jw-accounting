<?php
require_once('db_config.php');
require_once('webroot.php');
//require_once(dirname(dirname(__FILE__)) . '/app.php');
/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 
	
	
	$period_id = $_GET['period_id'];
	$currency_id = $_GET['currency_id'];
	
	

  $aColumns = array(
	'trans_day',
    'description',
    'transaction_code',
    'rec_in',
    'rec_out',
    'prim_in',
    'prim_out',
	'createdate_actual_form',
	'tbl_ledger_S_26_id',
	'transaction_code_id'  
  );
    
   /* Indexed column (used for fast and accurate table cardinality) */
   $sIndexColumn = "createdate";
 
   /* DB table to use */
   $sTable = "vw_s26_data_dttables AS vwsd";
 
   $sJoin = "";
   $sWhere = "";
   // original sort order
   // $sOrder = "Order By trans_day ASC, CASE transaction_code_id WHEN 6 THEN 9.5 ELSE transaction_code_id END ASC, createdate ASC";
   $sOrder = "Order By trans_day ASC, createdate ASC, CASE transaction_code_id WHEN 6 THEN 9.5 ELSE transaction_code_id END ASC";

   
   // Joins
   /*$sJoin = 'LEFT JOIN procedure_fee pf ON pf.proc_id = pd.id 
   			 LEFT JOIN procedure_category pc ON pc.cat_id = pd.pro_category_id';*/
    
   // get the database credentials from the configfile
   
   //$db = get_class_vars(get_class($database));
    
     /* MySQL connection */

	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  dblink($gaSql);
	
	
	
	$gaSql['link'] = select_db($gaSql);
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysqli_real_escape_string( $gaSql['link'], $_GET['iDisplayStart'] ).", ".
			mysqli_real_escape_string( $gaSql['link'], $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		if($sOrder == "")
		{
			$sOrder = "ORDER BY  ";
		}
		else
		{
			$sOrder .= ", ";
		}
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysqli_real_escape_string( $gaSql['link'], $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	/*$sWhere = "WHERE pc.cat_status = 'active' AND pf.creation_date = (SELECT MAX(creation_date) 
																  FROM  procedure_fee
																  WHERE proc_id= pd.id)";*/
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'], $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ")";
		/*$sWhere .= ") AND pc.cat_status = 'active' AND pf.creation_date = (SELECT MAX(creation_date) 
																  FROM  procedure_fee
																  WHERE proc_id= pd.id)";*/
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'],$_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	if($sWhere == "")
	{
		$sWhere .= "WHERE  period_id = {$period_id} AND currency_id = {$currency_id}";
	}
	else
	{
		$sWhere .= " And (period_id = {$period_id}) AND currency_id = {$currency_id}";
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */


	if($sOrder == "")
	{
		$sOrder = "Order By trans_day ASC, createdate ASC";
		
	}

	$sQuery = "
		SELECT DISTINCT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sJoin 
		$sWhere
		$sOrder
		$sLimit
	";
	
	//echo $sQuery."<br/><br/>";
	
	$rResult = query($sQuery, $gaSql) or die(mysql_error());
	

	
	
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = query($sQuery, $gaSql) or die(mysql_error());
	$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = query($sQuery, $gaSql) or die(mysql_error());
	$aResultTotal = mysqli_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval((isset($_GET['sEcho']))?$_GET['sEcho']:0),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		
			foreach($row AS $r => $a)
			{		
				if($r == 0)
				{	
					if($row[0] != 52 && $row[2] != 'CTBSUP')
					{
						$row[7] = '<a onClick="deleteTransanction('.$row[0].','.$row[9].', \''.$row[7].'\',\''.$row[8].'\')" class=" d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
										class="fas fa-trash fa-sm text-white-50"></i></a>';
					}
					else
					{
						$row[7] = NULL;
					}
					
					if($row[0] == 52)
					{
						$row[0] = '';
					}
					
					if($row[2] == 'CTBSUP')
					{
						$row[2] = NULL;
						$row[0] = NULL;
						$row[1] = "&nbsp&nbsp&nbsp".$row[1];
					}

					$row[1] = '<small>'.$row[1].'</small>';
					$row[3] = '<small>'.$row[3].'</small>';
					$row[4] = '<small>'.$row[4].'</small>';
					$row[5] = '<small>'.$row[5].'</small>';
					$row[6] = '<small>'.$row[6].'</small>';
				}	
			}
		$output['aaData'][] = $row;
		
	}
	
	echo json_encode( $output );
?>