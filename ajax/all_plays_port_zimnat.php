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
	 
	$hascontact = $_GET['hascontact'];

	//$isVendorBuyer = 1;
	 
	$aColumns = array(
	   'createdate',
       'email_phone',
       'points_gained',
       'move_limit',
  	 );

	   $ColumnsForQuery = array(
		'createdate',
		'email_phone',
		'points_gained',
		'move_limit',
		);

	// $aColumns = array(
	// 	'tbl_user_id',
    //    'uni_user_id',
    //    'user_status',
  	//  );
    
   /* Indexed column (used for fast and accurate table cardinality) */
   $sIndexColumn = "tbl_gm_spp.createdate";

   //$sIndexColumn = "tbl_user_id";
 
   /* DB table to use */
   $sTable = "tbl_gm_sld_pzl_pzl AS tbl_gm_spp";

   //$sTable = "tbl_user";
 
   $sJoin = "";
   $sWhere = "";
   $sOrder = "";
 
   // Joins
   $sJoin = '';
    
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
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				//echo 'to sort '.$aColumns[ intval( $_GET['iSortCol_'.$i] ) ].'  |  '.mysqli_real_escape_string( $gaSql['link'], $_GET['sSortDir_'.$i] ).'<br/>';
				//handle nulls here
				if($aColumns[ intval( $_GET['iSortCol_'.$i] ) ] == 'points_gained'
					|| $aColumns[ intval( $_GET['iSortCol_'.$i] ) ] == 'running_score'
					|| $aColumns[ intval( $_GET['iSortCol_'.$i] ) ] == 'highest_score'
					|| $aColumns[ intval( $_GET['iSortCol_'.$i] ) ] == 'current_dense_rank')
				{
					
					if(mysqli_real_escape_string( $gaSql['link'], $_GET['sSortDir_'.$i] ) == 'asc')
					{
						$sOrder .= "-".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." desc, ";
					}
					else if(mysqli_real_escape_string( $gaSql['link'], $_GET['sSortDir_'.$i] ) == 'desc')
					{
						$sOrder .= "-COALESCE(".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ].", -2) asc, ";
					}
				}
				else
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysqli_real_escape_string( $gaSql['link'], $_GET['sSortDir_'.$i] ) .", ";
				}
				
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
	
	
	
	if($sOrder == "")
	{
		$sOrder = 'ORDER BY -current_dense_rank desc';
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */


	if($sWhere == "")
	{
		$sWhere = " WHERE prospect = 'zimnat' AND createdate > '20220420'  ";
	}
	else
	{
		$sWhere .= " AND  prospect = 'zimnat' AND createdate > '20220420' AND email_phone <> '0710000000' ";
	}

	if($hascontact == 1)
	{
		$sWhere .= " AND  (email_phone IS NOT NULL OR email_phone != '') ";
	}

	$sQuery = "
		SELECT DISTINCT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $ColumnsForQuery))."
		FROM   $sTable
		$sJoin 
		$sWhere
		$sOrder
		$sLimit;";
	
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
		
			// foreach($row AS $r => $a)
			// {
			// 	if($r == '1')
			// 	{
			// 		$row[1] = "<img class='product-image' src='{$WEB_ROOT}attachments/shop_images/" .$row[1]."' alt=''>";
			// 	}
			// }

		$output['aaData'][] = $row;
		
	}
	
	echo json_encode( $output );
?>