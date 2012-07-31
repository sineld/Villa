<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php
			require_once ('cocoasHead.js');
		?>
		<?php
			require_once('templates/head.php');
		?>
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>css/bootstrap.css" rel="stylesheet" media="screen">
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>js/bootstrap.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>js/paginador.js"></script>
	</head>
	
	<body>
		<div class="container fondo-blanco">
		<!-- start header -->
		<?php
			require_once ($root . 'header.php');
		?>
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
