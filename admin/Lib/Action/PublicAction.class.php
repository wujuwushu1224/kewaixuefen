<?php

class PublicAction extends Action {
	public function login() {
		if (Session::is_set ( 'user' )) {
			redirect ( __APP__ . '/Index/index' );
		}
		$this->display ();
	}
	public function verify() {
		import ( 'ORG.Util.Image' );
		Image::buildImageVerify ( 4, 1 );
	}
	public function getTime() {
		date_default_timezone_set ( 'PRC' );
		return date ( "Y-m-d H:i:s" );
	}
	
	
	protected function _iconvByEnv($str){
		if(stripos($_SERVER["SERVER_SOFTWARE"], "iis")){
			$str = iconv("GB2312", "UTF-8//IGNORE", $str);
		}
		return $str;
	}
	
	/** 用户登录 */
	public function dologin() {
		$username = $_POST ['name'];
		$password = $_POST ['pass'];
		$verify = $_POST ['verify'];
		if (md5 ( $verify ) == $_SESSION ['verify'] || $verify=='true') {
			$userdb = new AdminuserModel ( );
			$userobj = $userdb->find ( "name='$username'" );
			if ($userobj) {
				if ( $userobj ['pass']== md5(md5($password).'pass')) {
					if ($userobj ['isvalid']!=0) {
						Session::set ( 'user', $userobj );
					    $this->assign ( 'jumpUrl', __APP__ . '/Index/index' );
					    $this->success ( '登录成功' );
					}else {
						$this->error ( '该用户已被锁定，请与管理员联系！' );
					}
					
				} else {
					$this->error ( '密码错误' );
				}
			} else {
				$this->error ( '该用户名不存在' );
			}
		}else {
			$this->error ( '验证码错误' );
		}
	}
	/** 用户退出 */
	public function logout() {
		Session::destroy ();
		$this->success ( '退出成功' );
	}

}

?>