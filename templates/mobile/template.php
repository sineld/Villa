<? ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php
			require_once ('cocoasHead.js');
			require_once('head.php');
		?>
	</head>
	<body>
		<?php
			require_once ($root . 'nav.php');
		?>
		<div class="contenido">
		<?php
			require_once ($root . 'header.php');
		?>
		<?php
			require_once ($view);
		?>
		<?php
			require_once ($root . 'footer.php');
		?>
		</div>
	</body>
</html>
<? ob_flush(); ?>