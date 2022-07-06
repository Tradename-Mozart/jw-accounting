<!DOCTYPE html>
<html lang="en"><head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>

    <title><?= $title; ?></title>
	
	<link rel="icon" href="<?= base_url('static/img/green-chart-arrow.png') ?>" sizes="32x26" type="image/png">

    <!-- Custom fonts for this template-->
    <link href="<?= base_url() ?>static/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Bootstrap styles -->
    <link href="<?= base_url() ?>static/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url() ?>static/css/sb-admin-2.min.css" rel="stylesheet">
	
	 <!-- DataTables CSS -->
     <?php echo link_tag('static/vendor/datatables/dataTables.bootstrap4.min.css'); ?>
     <?php //echo link_tag('static/vendor/datatables/buttons.dataTables.min.css'); ?>

      
	
	<style>

    .isselected {
    background-color:#90ee90 !important;
    }

   </style>
	
	<script type="text/javascript">
	var ci_base_url_snl = "<?= base_url() ?>static/phsgame/snl/";
	var ci_base_url = "<?= base_url() ?>";
    var ci_hostName_url = "<?= (substr($_SERVER['HTTP_HOST'], 0, 5) == 'local' || substr($_SERVER['HTTP_HOST'], -4) == '8080')?'http': 'https' ?>://<?= $_SERVER['HTTP_HOST']  ?>/";
    //var hoster_dttable = "<?= (substr($_SERVER['HTTP_HOST'], 0, 5) == 'local' || substr($_SERVER['HTTP_HOST'], -4) == '8080')?'easyrwds': 'https' ?>";
    var datatable_url = "<?= (substr($_SERVER['HTTP_HOST'], 0, 5) == 'local' || substr($_SERVER['HTTP_HOST'], -4) == '8080')?'http': 'https' ?>://<?= $_SERVER['HTTP_HOST']  ?>/easyrwds";
    <?php if(isset($latestID)) {?>
    var campaignListID = <?=$latestID ?>;
    var latestEndDte = '<?=$latestEndDate ?>';
    <?php } ?>

    
    </script>
	
	<!-- EOF For Snakes and Ladders -->
	
	

</head>

	<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
