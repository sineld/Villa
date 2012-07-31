 <?php
require_once 'plugins/facebook-platform/php/facebook.php';

$appapikey = 'c12762fe2b40910b7d99068d57784cdd';
$appsecret = 'aae5cb886d53eb2a908d3f8c6474f662';
$facebook = new Facebook($appapikey, $appsecret);
$user_id = $facebook->require_login();


?>