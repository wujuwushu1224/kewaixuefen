<?php

class BaseModel extends Model {
	/** 获取当前时间 */
    public function getTime()
	{
		date_default_timezone_set('PRC');
		return date("Y-m-d H:i:s"); 
	}
    public function getEndtime()
	{
		$currtime=$this->getTime();
		$valid = $_POST ['validate'];
		return date ( 'Y-m-d H:i:s', strtotime ( "$currtime +" . $valid . " day" ) ); //当前日期加上有效期后的日期
	}
	public function getDate() {
		$startime=$this->getTime();
		return substr($startime,0,10);
	}
    public function getDates() {
		$endtime=$this->getEndtime();
		return substr($endtime,0,10);
	}
	

}

?>