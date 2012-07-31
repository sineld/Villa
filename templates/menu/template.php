<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>css/default.css" rel="stylesheet" media="screen">
<?php require_once('cocoasHead.js'); ?>
<?php require_once('templates/head.php'); ?>
</head>
<body>
<!--	<div id="wrapper">-->
<!-- start header -->
	<?php
	require_once($root.'header.php'); ?>
<!-- end header -->
<!-- start content -->
<div id="content">
<?php
	require_once($view);
?>
</div>
<!-- end content -->
<!-- start footer -->
	<?php require_once($root.'footer.php'); ?>
<!-- end footer -->
<!--</div>-->
</body>
</html>
