<?php

class RequiredAction extends IndexAction {
	
private $pagesize = 10; //每页显示的记录数
	
	/*
	  1  文章类
      2 新闻类
      3 友情链接
      4 留言板
      5 图片展示
      6 外部链接
	*/
	public function sList() {
		$sql="1=1";
		$keys = $this->_iconvByEnv($_GET['key']);

		if (! empty ( $keys )) {
		    $keyst = preg_replace ( '/(\s+)/', ' ', $keys );
			$keylist = explode ( ' ', $keyst );
			$hql = '';
			foreach ( $keylist as $keyls ) {
				if (! empty ( $keyls )) {
					$hql .= "|| title like '%$keyls%' ";
				}
			}
		    if (! empty ( $hql )) {
				$hql = substr ( $hql, 2 );
				$hql = '(' . $hql . ')';
				$sql .= " and $hql ";
			}
		}
		
		
		$this->findPage($sql,'xfsz',"id asc");
		
		$this->assign('keys',$keys);

		$this->display();
	}
	
    /** 信息编辑页面 */
	public function sEdit() {
		$id=$_GET['id'];

			$url=$_SERVER['HTTP_REFERER'];
			$this->assign('jumpUrl',$url);
			$infobj=$this->findObj('xfsz',"id=$id");
			$this->assign('infobj',$infobj);

		
		$this->display();
	}
	
	/** 信息添加 */
	public function sAdd() {
		$flag=$_POST['flag'];
		if (empty($flag)) {

			$this->display();
		} else {
			$this->assign('jumpUrl',__URL__.'/sList');
			$entity=new XfszModel();
			if($entity->create()){

				if ($entity->add()) {
					$this->success('增加成功');
				}else {
					$this->error('增加失败');
				}
			}else {
				$this->error($entity->getError());
			}
			
		}
	}
	
	
	
	
	/** 信息编辑操作 */
	public function doEdit() {
		$url=$_POST['jumpUrl'];
		$dModel=new XfszModel();
		if ($dModel->create()) {
			
			if ($dModel->save()) {
				$this->assign('jumpUrl',$url);
				$this->success('修改成功');
			}else {
				$this->error('修改失败');
			}
		}else {
			$this->error($dModel->getError());
		}
	}
    /** 删除信息 */
	public function delInfo() {
		$url=$_SERVER['HTTP_REFERER'];
		$this->assign('jumpUrl',$url);
		$infoids=$_POST['adid'];
		$ids='';
		foreach ($infoids as $infoid) {
			$ids.=','.$infoid;
		}
		if (!empty($ids)) {
			$ids=substr($ids,1);
			$dModel=new XfszModel();
		    if ($dModel->delete("id in ($ids)")){
		    	$this->success('删除成功');
		    }else {
		    	$this->error('删除失败');
		    }
		}else {
			$this->error('没有可以删除的记录');
		}
	}
	
	
	public function mList() {
		$sql="1=1 and college<>''";
		$keys = $this->_iconvByEnv($_GET['key']);

		if (! empty ( $keys )) {
		    $keyst = preg_replace ( '/(\s+)/', ' ', $keys );
			$keylist = explode ( ' ', $keyst );
			$hql = '';
			foreach ( $keylist as $keyls ) {
				if (! empty ( $keyls )) {
					$hql .= "|| college like '%$keyls%' ";
				}
			}
		    if (! empty ( $hql )) {
				$hql = substr ( $hql, 2 );
				$hql = '(' . $hql . ')';
				$sql .= " and $hql ";
			}
		}
		
		$college = $_SESSION['user']['college'];
		if($_SESSION['user']['role']!=1){
			$sql.=" and college = '$college'";
		}

		$this->findPage($sql,'adminuser',"id asc","college");
		

		$this->assign('keys',$keys);

		$this->display();
	}
	
	public function mList_a() {
		$college = $this->_iconvByEnv($_GET['college']);
		$class = $_SESSION['user']['class'];
		if($class){
		$sql= " college = '$college' and class = '$class'";
		}
		else{
		$sql= " college = '$college' and class<>''";
		}
		
		$keys = $this->_iconvByEnv($_GET['key']);

		if (! empty ( $keys )) {
		    $keyst = preg_replace ( '/(\s+)/', ' ', $keys );
			$keylist = explode ( ' ', $keyst );
			$hql = '';
			foreach ( $keylist as $keyls ) {
				if (! empty ( $keyls )) {
					$hql .= "|| class like '%$keyls%' ";
				}
			}
		    if (! empty ( $hql )) {
				$hql = substr ( $hql, 2 );
				$hql = '(' . $hql . ')';
				$sql .= " and $hql ";
			}
		}
		
		
		$this->findPage($sql,'adminuser',"class asc","class");
		

		$this->assign('keys',$keys);

		$this->display();
	}
	
	public function mList_b() {
		$class = $this->_iconvByEnv($_GET['class']);
		$name = $_SESSION['user']['name'];
		if($_SESSION['user']['role']==3){
		$sql= " class = '$class' and name = '$name'";
		}
		else{
		$sql= " class = '$class' and role = 3 ";
		}
		
		$keys = $this->_iconvByEnv($_GET['key']);

		if (! empty ( $keys )) {
		    $keyst = preg_replace ( '/(\s+)/', ' ', $keys );
			$keylist = explode ( ' ', $keyst );
			$hql = '';
			foreach ( $keylist as $keyls ) {
				if (! empty ( $keyls )) {
					$hql .= "|| truename like '%$keyls%' ";
				}
			}
		    if (! empty ( $hql )) {
				$hql = substr ( $hql, 2 );
				$hql = '(' . $hql . ')';
				$sql .= " and $hql ";
			}
		}
		
		
		$list = $this->findPage($sql,'adminuser',"name asc");
		
		if($list){
			$a = count($list);
			
			for($i=0;$i<$a;$i++){
				$id=$list[$i]['id'];
				$xf = $this->findObj('xfgl',"id=$id");
				$list[$i]['fen'] = $xf['fen'];
			}
		}
		$this->assign('role',$_SESSION['user']['role']);
		
		$this->assign('list',$list);
		
		$this->assign('keys',$keys);

		$this->display();
	}
	
	public function mEdit() {
			$id=$_GET['id'];
			
			$this->findPage("1=1",'xfsz',"id asc");
		
			$url=$_SERVER['HTTP_REFERER'];
			$this->assign('jumpUrl',$url);
			
			$infobj=$this->findObj('adminuser',"id=$id");
			$this->assign('infobj',$infobj);
			
			$infobja=$this->findObj('xfgl',"uid=$id");
			$this->assign('infobja',$infobja);
			
			$robj=$this->findObj('xfgl',"id=$id");
			
			$mids=$robj['qk'];

			if (!empty($mids)) {
				$mids=explode(',',$mids);
			}
			
			$this->assign("mids", $mids);

		
		$this->display();
	}
	
	public function domEdit() {
		$url=$_POST['jumpUrl'];
		$id = $_POST['id'];
		
			$ids=$_POST['qk'];
			$idss='';
			if (!empty($ids)) {
			    foreach ($ids as $ida) {
				    $idss.=','.$ida;
			    }
			    $idss=substr($idss,1);
			}

		$dModel=new XfglModel();
		if ($dModel->create()) {
			$infobj=$this->findObj('xfgl',"id=$id");
			if($infobj){
				$dModel->qk=$idss;
				if ($idss){
					$aa = substr_count($idss,',');
					$bb = ($aa+1)*0.5;
					$dModel->fen=$bb;
				}
			if ($dModel->save()) {
				$this->assign('jumpUrl',$url);
				$this->success('修改成功');
			}else {
				$this->error('修改失败');
			}
			}
			else{
				$dModel->qk=$idss;
				if ($idss){
					$aa = substr_count($idss,',');
					$bb = ($aa+1)*0.5;
					$dModel->fen=$bb;
					dump($bb);
				}
			if ($dModel->add()) {
				$this->assign('jumpUrl',$url);
				$this->success('修改成功');
			}else {
				$this->error('修改失败');
			}
			}
		}else {
			$this->error($dModel->getError());
		}
	}
	
	public function  upisshow(){
		$id=$_POST['id'];
		$state=$_POST['state'];
		if ($state==0) 
			$state=1;
		else 
			$state=0;
		echo json_encode($this->upsql("update px_detail set isshow=$state where id=$id"));
	}
	
	public function upSort() {
		$id = $_POST ['id'];
		$numid = $_POST ['con'];
		echo json_encode ( $this->upsql ( "update px_detail set sort=$numid where id=$id" ) );
	}
	
}

?>