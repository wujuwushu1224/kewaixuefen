<?php

class AdminuserModel extends PublicModel {

		protected $_validate	 =	 array(
		    array('name','','该用户已存在',0,'unique','add'),
		);
		protected $_auto	 =	 array(
		    array('startime','todat','ADD','callback'),
		    array('lasttime','todat','ADD','callback'),
		    array('lastip','addip','ADD','callback'),
		    array('pass','addpass','ALL','callback'),
		);
       public  function addpass()
	   {
		    return md5(md5($_POST['pass']).'pass');
	    }
}

?>