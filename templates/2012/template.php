<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php
			require_once ('cocoasHead.js');
		?>
		<?php
			require_once('templates/head.php');
		?>
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>assets/css/custom.css" rel="stylesheet" media="screen">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css' />
  		<link href='http://fonts.googleapis.com/css?family=Russo+One' rel='stylesheet' type='text/css' />
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/bootstrap.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/custom.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>js/paginador.js"></script>
	</head>
	
	<body>
		<?php
			require_once ($root . 'header.php');
		?>
		<div class="container borde-blanco">
		<!-- start header -->
		
		<!-- end header -->
		<!-- start nav -->
		<?php
			require_once ($root . 'nav.php');
		?>
		<!-- end nav -->
		<!-- start content -->

		
		<?php
			require_once ($view);
		?>
		</div>

		<!-- end content -->
		<!-- start footer -->
		<?php
			require_once ($root . 'footer.php');
		?>
		<!-- end footer -->

	</body>
</html>
