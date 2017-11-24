<?php

class RoleModel extends PublicModel  {
	
	protected $_validate	 =	 array(
		    array('rolename','','角色名称已存在',0,'unique','add'),
	);

}

?>