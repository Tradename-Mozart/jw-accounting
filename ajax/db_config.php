<?php

	$gaSql['user']       = "root";
	$gaSql['password']   = "";
	$gaSql['db']         = "jw_accounting";//"uhealthzim";
	$gaSql['server']     = "localhost";

// Live Prod
	/*$gaSql['user']       = "gxbglmir_root";
	$gaSql['password']   = "fCcf~654";
	$gaSql['db']         = "gxbglmir_eonrahealth";//"uhealthzim";
	$gaSql['server']     = "localhost";*/
// EOF Live Prod
	
	//$currefer = uencode(strval($_SERVER['REQUEST_URI']));
	
	function dblink ($gaSql)
	{
		return mysqli_connect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
		
		}
		
	function select_db($gaSql)
		{
			$link =  mysqli_connect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
			
			mysqli_select_db($link,$gaSql['db']) or 
		die( 'Could not select database '. $gaSql['db']);
			
			return $link;
			
			}
			
	function query($sQuery, $gaSql)
		{
			
		$gaSql['result'] =  mysqli_query( $gaSql['link'],$sQuery );
		
		if(!$gaSql['result'])
		{
		die(mysqli_error($gaSql['link']));
		}
		
		return $gaSql['result'];
			}
?>