<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="<?php echo $GLOBALS["keywords"]; ?>" >
<meta name="Language" content="<?php echo $GLOBALS["language"]; ?>" >
<script type="text/javascript" src="<?php echo $GLOBALS["baseURL"]; ?>assets/js/jquery.js"></script>
<?php
  if(isset($_REQUEST['view'])){
          if(isset($styles[$_REQUEST['view']]))
          echo '<link href="'.$GLOBALS["baseURL"].'css/'.$styles[$_REQUEST['view']].'" rel="stylesheet" type="text/css" media="screen" />';
  }else if(isset($_REQUEST['panel'])){
          if(isset($styles[$_REQUEST['panel']]))
          echo '<link href="'.$GLOBALS["baseURL"].'css/'.$styles[$_REQUEST['panel']].'" rel="stylesheet" type="text/css" media="screen" />';
  }
?>