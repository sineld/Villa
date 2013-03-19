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
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>assets/css/select2.css" rel="stylesheet" media="screen">
	</head>
	
	<body>
		
		<?php
			require_once ($root . 'header.php');
		?>
		
		<!-- start header -->
		
		<!-- end header -->
		<!-- start nav -->
		<?php
			require_once ($root . 'nav.php');
			
		?>
		<!-- end nav -->
		<!-- start content -->
		<div class="container borde-blanco">
		
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
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/form.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/sha1.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>assets/js/jquery-validate/dist/jquery.validate.min.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/validations.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/jquery.bestupper.min.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/canis.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>js/jquery.tablesorter.min.js"></script>
		<?php
			if($_REQUEST['view']=='catalogo2012') {
		?>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/catalogo.js"></script>
		
		<?php
			} if($_REQUEST['view']=='pedidos') {
		?>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/pedidos.js"></script>
		<?php
			}
		?>	
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/bootstrap.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"];?>assets/js/select2.js"></script>
		<script type="text/javascript">
		vista = <?php echo '"'.$_REQUEST['view'].'";'?>
 		 var _gaq = _gaq || [];
  		_gaq.push(['_setAccount', 'UA-26376763-1']);
  		_gaq.push(['_trackPageview']);

  		(function() {
    		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  		})();

		</script>
		</body>
</html>
