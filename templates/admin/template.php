<? ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php
			require_once ('cocoasHead.js');
			require_once('head.php');
		?>
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>assets/css/bootstrapadmin.css" rel="stylesheet" media="screen">
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>assets/css/custom.css" rel="stylesheet" media="screen">
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>assets/css/select2.css" rel="stylesheet" media="screen">
	</head>
	<body>
		<?php
			require_once ($root . 'header.php');
		?>
		<?php
			require_once ($root . 'nav.php');
		?>
		<div class="container">
		<div class="content">
				<?php
				require_once ($view);
				?>
		</div>
		</div>
		<?php
			require_once ($root . 'footer.php');
		?>
	</body>
	<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/form.js"></script>
	<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>assets/js/jquery-validate/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/select2.js"></script>
</html>
<? ob_flush(); ?>