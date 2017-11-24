<?php 
define('THINK_PATH', './PHP');
define('APP_PATH', './admin');
require(THINK_PATH."/ThinkPHP.php");
$App = new App(); 
$App->run();
?>