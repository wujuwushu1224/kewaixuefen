<?php
class SystemAction extends IndexAction {
	//网站设置
	public function website() {
		$flag=$_POST['flag'];
		if (empty($flag)) {
			$wid = intval($_GET['wid']);
			$obj=$this->findObj('Website',"wid = $wid");
			$this->assign('obj',$obj);
			$this->assign('wid',$wid);
			$this->display();
		}else {
			$web=new WebsiteModel();
			if ($web->create()) {
				if (empty($_POST['id'])){
					$bool=$web->add();
				}else{
					$bool=$web->save();
				}
				if ($bool) {
					$this->success('更新成功');
				}else {
					$this->error('更新失败');
				}
			}else {
				$this->error($web->getError());
			}
		}
	}

	//网站栏目列表
	public function column(){
		if (empty($_POST)){
			$wid = intval($_GET['wid']);
			$list = $this->findList("column", "cid = 0 and wid = $wid", "sort ,id ");
			$this->assign("list",$list);
			$this->assign('wid',$wid);
			$this->display();
		}else{
			$columnModel = new ColumnModel();
			$columnModel->create();
			if ($columnModel->add()){
				$this->success('添加成功!');
			}else{
				$this->error('添加失败');
			}
		}
	}
	
	//网站二级栏目列表
	public function column_xl(){
		if (empty($_POST)){
			$cid = $_GET['cid'];
			$wid = $_GET['wid'];
			$list = $this->findList("column", "cid = $cid and wid = $wid", "sort ,id ");
			$this->assign("list",$list);
			
			$this->assign("column_top",$this->findList("column", "cid = 0 and wid = $wid", "sort ,id "));
			
			$this->assign("cid",$cid);
			$this->assign("wid",$wid);
			
			$this->display();
		}else{
			$columnModel = new ColumnModel();
			$columnModel->create();
			if ($columnModel->add()){
				$this->success('添加成功!');
			}else{
				$this->error('添加失败');
			}
		}
	}
	
	//删除栏目
	public function column_del(){
		$id = $_GET['id'];
		$column = $this->findObj("column", "id = $id");
		$model = D();
		
		if ($column['cid'] == 0){
			$columns = $this->findObj("column", "cid = $id");
			if (!$columns){ //如果下级栏目不存在,则删除此顶级栏目
				$model->query("delete from px_column where id = $id"); //删除 栏目
				$this->assign('jumpUrl',__URL__.'/Column');
				$this->success('删除成功!');
			}else{
				$this->assign('jumpUrl',__URL__.'/Column');
				$this->error('删除失败!此栏目还有二级栏目,请先删除二级栏目,再删除顶级栏目!');
			}
		}else{
			switch ($column['type']){
				case 1:  //文章类
					$table = 'detail';
					$sp = '1';
					break;
				case 2:  //新闻类
					$table = 'detail';
					break;
				case 3:  //友情链接
					$table = 'links';
					break;
				case 4:  //留言板
					$table = 'message';
					break;
				case 5:  //图片展示
					$table = 'detail';
					break;
				case 6:  //外部链接
					$table = 'links';
					$sp = '1';
					break;
			}
			$info = $this->findObj($table, "tid = $id");
			if (!$info || !empty($sp)){ //如果此栏目下的内容不存在,则删除此栏目
				$model->query("delete from px_column where id = $id"); //删除栏目
				$this->assign('jumpUrl',__URL__.'/column_xl/cid/'.$column['cid']);
				$this->success('删除成功!');
			}else{
				$this->assign('jumpUrl',__URL__.'/column_xl/cid/'.$column['cid']);
				$this->error('删除失败!此二级栏目下还有内容,请先删除栏目下的内容,再删除此栏目!');
			}
		}
	}
	
	//AJAX 修改栏目
	public function update_column(){
		$field = $_POST['field'];
		$val = $_POST['val'];
		$id = $_POST['id'];
		if (!empty($val) || $val == 0){
			$model = D();
			$model->query("update px_column set $field = '$val' where id = $id ");
		}
	}
	
	public function douppass() {
		$id = $_POST ['id'];
		$pass = $_POST ['pass'];
		$npass = $_POST ['npass'];
		$qnpass = $_POST ['qnpass'];
		if ($qnpass != $npass) {
			$this->error ( '两次密码输入不一致' );
		} else {
			$user = new AdminuserModel ( );
			$obj = $user->find ( $id );
			if ($obj ['pass'] != md5( md5($pass).'pass')) {
				$this->error ( '原密码错误' );
			} else {
				$user->pass = md5( md5($npass).'pass');
				if ($user->save ()) {
					$this->success ( '修改成功' );
				} else {
					$this->error ( '修改失败' );
				}
			
			}
		
		}
	}
}

?>