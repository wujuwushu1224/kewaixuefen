<?php

class PublicModel extends Model{
	protected function  todat()
	{
		date_default_timezone_set('PRC');
		return date("Y-m-d H:i:s"); 
	}
	protected function addip(){
		return $_SERVER["REMOTE_ADDR"]; 
	}
	protected function verifys() {
		return  md5($_POST['verify']) == $_SESSION['verify'];
	}

}

?>