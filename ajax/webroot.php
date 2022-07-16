<?php
	$WEB_ROOT = "http://".$_SERVER['HTTP_HOST'];
	$WEB_ROOT .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>