<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="no-js" xmlns="http://www.w3.org/1999/xhtml">
		<?php
			require_once('phputils/Browser.php');
			$browser = new Browser();
			switch($browser->getBrowser()){
				case 'Internet Explorer':
					if($browser->getVersion()<8){
						header('Location: '.$GLOBALS["baseURL"].'administracion');
					}else {
					}
					break;
				case 'Chrome':
					break;
				case 'Firefox':
					break;
				Default:
					echo $browser->getBrowser();
			}
		?>
	<head>
		<link type="text/css" href="<?php echo $GLOBALS["baseURL"];?>css/default.css" rel="stylesheet" media="screen">
		<?php
			require_once ('cocoasHead.js');
		?>
		<?php
			require_once('templates/head.php');
		?>
	</head>
	
	<body>
		<div id="wrapper" class="borde_radial">
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
		<div id="content" style="margin-top:5px;">
			<div id="frame_izq">
				<div id="top_izq"></div>
				<div id="contenido_izq">
				<?php
				require_once ($view);
				?>
				</div>
				<div id="bot_izq"></div>
			</div>
			<div id="frame_der">
				<div id="der_slide">
						<div class="olores"><p>Lim&oacute;n</p></div>
						<div class="olores"><p>Manzana</p></div>
						<div class="olores"><p>Natural</p></div>
				</div>
				<script type="text/javascript">
				$(document).ready(function() {
					$("#der_slide").cycle({
						fx: 		'scrollLeft',
						timeout: 	500,

					});
					
				});
				</script>
			
			</div>
		</div>
		<!-- end content -->
		<!-- start footer -->
		<?php
			require_once ($root . 'footer.php');
		?>
		<!-- end footer -->
		</div>
	</body>
</html>
