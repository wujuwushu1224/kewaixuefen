<?php

class ChoiceAction extends IndexAction {
	
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
		
		$uid = $_SESSION['user']['id'];
		if($_SESSION['user']['role']==6){
			$sql .= " and uid='$uid'";
		}
		
		if($_SESSION['user']['role']!=6 && $_SESSION['user']['role']!=1){
			$sql .= " and sh=1";
		}
		
		
		$list = $this->findList('kcsz',$sql,"sh asc");
		if($list){
			$a = count($list);
			
			for($i=0;$i<$a;$i++){
				$id=$list[$i]['uid'];
				$list[$i]['truename'] = $this->findObj('adminuser',"id=$id");
				
				if ($list[$i]['buid']){
					$aa=$list[$i]['rl'] - substr_count($list[$i]['buid'],',');
				}
				else {
					$aa = $list[$i]['rl'];
				}
				$list[$i]['sy'] = $aa;
			}
		}
		
		$this->assign('uid',$_SESSION['user']['id']);
		$this->assign('role',$_SESSION['user']['role']);
		
		$this->assign('list',$list);
		
		$this->assign('keys',$keys);

		$this->display();
	}
	
    /** 信息编辑页面 */
	public function sEdit() {
		$id=$_GET['id'];			
			$infobj=$this->findObj('kcsz',"id=$id");
			$this->assign('infobj',$infobj);
			$this->assign('moreinfo',$this->fckHtml('moreinfo',$infobj['moreinfo']));

			
		
		$this->display();
	}
	
	public function sshow() {
		$id=$_GET['id'];
			
			$url=$_SERVER['HTTP_REFERER'];
			$this->assign('jumpUrl',$url);
			$infobj=$this->findObj('kcsz',"id=$id");
			$this->assign('infobj',$infobj);
			$name = $_SESSION['user']['name'];
			$this->assign('xk',count($this->findList('kcsz',"buid like '%$name%'")));
			$this->assign('role',$_SESSION['user']['role']);
			$this->assign('name',$_SESSION['user']['name']);
			$this->assign('moreinfo',$this->fckHtml('moreinfo',$infobj['moreinfo']));

		
		$this->display();
	}
	
	/** 信息添加 */
	public function sAdd() {
		$flag=$_POST['flag'];
		if (empty($flag)) {
			$this->assign('moreinfo',$this->fckHtml('moreinfo',''));
			$this->display();
		} else {
			$this->assign('jumpUrl',__URL__.'/sList');
			$entity=new KcszModel();
			if($entity->create()){
				$entity -> uid = $_SESSION['user']['id'];		
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
		$dModel=new kcszModel();
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
	
	public function dosEdit() {
		$url=$_POST['jumpUrl'];
		$dModel=new kcszModel();
		if($_POST['sh']){
		if ($dModel->create()) {
			$dModel->sh=1;
			if ($dModel->save()) {
				$this->assign('jumpUrl',$url);
				$this->success('审核成功');
			}else {
				$this->error('审核失败');
			}
		}else {
			$this->error($dModel->getError());
		}
		}
		if($_POST['name']){
		$buid = $_POST['buid'];
		$name = $_POST['name'];
		$rl = $_POST['rl'];

		if(strpos($buid, $name)){
			$this->assign('jumpUrl',$url);
			$this->success('已经选课，不需重复确认');
		}
		else {
			$aa = $rl - substr_count($buid,',')-1;
			if ($aa > -1){
			if ($dModel->create()) {
			$dModel->buid=$buid.",".$name;
			if ($dModel->save()) {
				$this->assign('jumpUrl',$url);
				$this->success('选课成功');
			}else {
				$this->error('选课失败');
			}
			}else {
				$this->error($dModel->getError());
			}
		}
		else {
			$this->assign('jumpUrl',$url);
			$this->success('人员已满，选课失败');
		}
		}
		
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
			$dModel=new kcszModel();
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
		$sql="1=1 and sh=1";
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
		
		$uid = $_SESSION['user']['id'];
		if($_SESSION['user']['role']==6){
			$sql .= " and uid='$uid'";
		}
		
		$name = $_SESSION['user']['name'];
		$length = strlen($name);
		$str =  substr($name,1,($length-1));

		if($_SESSION['user']['role']==3){
			$sql .= " and buid like '%$str%'";
		}
		
		
		$list = $this->findPage($sql,'kcsz',"id asc");
		if($list){
			$a = count($list);
			
			for($i=0;$i<$a;$i++){
				$id=$list[$i]['uid'];
				
				$list[$i]['truename'] = $this->findObj('adminuser',"id=$id");

			}
		}
		
		$this->assign('list',$list);
		
		$this->assign('keys',$keys);

		$this->display();
	}
	
	public function mList_a() {
		$id = $_GET['id'];
		$moreinfo = $this->findObj('kcsz',"id=$id");
		$buid = $moreinfo['buid'];
		
		$name = $_SESSION['user']['name'];
		if($_SESSION['user']['role']==3){
			$sql= " name = '$name'";
		}
		else{
		$sql= " name in (1$buid) and name <>0";
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
		
		$list = $this->findPage($sql,'adminuser',"id asc");
		if($list){
			$a = count($list);
			
			for($i=0;$i<$a;$i++){
				$id=$list[$i]['id'];
				$tid=$_GET['id'];
				$xf = $this->findObj('kcxf',"uid='$id' and tid='$tid'");
				$list[$i]['fen'] = $xf['fen'];
				$list[$i]['pfen'] = $xf['pfen'];
			}
		}
		
		$this->assign('list',$list);
		$this->assign('keys',$keys);
		$this->assign('role',$_SESSION['user']['role']);

		$this->display();
	}	
	
	public function mList_a_dca(){
		$root = str_replace("\\", "/", dirname(dirname(dirname(dirname(__FILE__)))));
		require_once $root.'/admin/Lib/Classes/PHPExcel.php';
		require_once $root.'/admin/Lib/Classes/PHPExcel/Writer/Excel5.php';
		$objExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		$objProps = $objExcel->getProperties();  
		
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();
		$objActSheet->setCellValue('A1', '系部');
		$objActSheet->setCellValue('B1', '班级');
		$objActSheet->setCellValue('C1', '姓名');
		$objActSheet->setCellValue('D1', '学号 ');
		$objActSheet->setCellValue('E1', '学号1 ');

		$id = $_GET['id'];
		$moreinfo = $this->findObj('kcsz',"id=$id");
		$buid = $moreinfo['buid'];
		$sql= " name in (1$buid) ";
		$list = $this->findList("adminuser", $sql, "id desc");

		if($list){
			for($i=2; $i<count($list)+2; $i++){
				
				$id=$list[$i]['id'];
				$tid=$_GET['id'];
				$xf = $this->findObj('kcxf',"uid='$id' and tid='$tid'");
				$list[$i]['fen'] = $xf['fen'];
				
				$objActSheet->setCellValue('A'.$i, $list[$i]['college']);
				$objActSheet->setCellValue('B'.$i, $list[$i]['class']);
				$objActSheet->setCellValue('C'.$i, $list[$i]['truename']);
				$objActSheet->setCellValue('D'.$i, $list[$i]['name']);
				$objActSheet->setCellValue('E'.$i, '1');
			}
		}else{
			$this->error("空表");
		}
		ob_end_clean();
		
		$outputFileName = "xx.xls";
		header("Content-Type: application/force-download");  
		header("Content-Type: application/octet-stream");  
		header("Content-Type: application/download");  
		header('Content-Disposition:inline;filename="'.$outputFileName.'"');  
		header("Content-Transfer-Encoding: binary");  
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
		header("Pragma: no-cache");  
		$objWriter->save('php://output');
	}
	
	
	public function mList_a_dc(){
		require_once './admin/Lib/PHPExcel/PHPExcel.php';
		require_once './admin/Lib/PHPExcel/PHPExcel/Writer/Excel5.php';
		
		$objExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		$objProps = $objExcel->getProperties();  
		

		
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();	
				
	
		$objActSheet->setCellValue('A1', '系部');
		$objActSheet->setCellValue('B1', '班级');
		$objActSheet->setCellValue('C1', '姓名');
		$objActSheet->setCellValue('D1', '学号');
		$objActSheet->setCellValue('E1', '得分');
			
			$id = $_GET['id'];
			$moreinfo = $this->findObj('kcsz',"id=$id");
			$buid = $moreinfo['buid'];
			$sql= " name in (1$buid) ";
			$list = $this->findList("adminuser", $sql, "id desc");

				
		$i = 2;
		foreach ($list as $ls){
				$i++;
				
				$id=$list[$i]['id'];
				$tid=$_GET['id'];
				$xf = $this->findObj('kcxf',"uid='$id' and tid='$tid'");
				$list[$i]['fen'] = $xf['fen'];
				
				$objActSheet->setCellValue('A'.$i, $ls['college']);
				$objActSheet->setCellValue('B'.$i, $ls['class']);
				$objActSheet->setCellValue('C'.$i, $ls['truename']);
				$objActSheet->setCellValue('D'.$i, $ls['name']);
				$objActSheet->setCellValue('E'.$i, $ls['fen']);
		}
		
		
		$outputFileName = "学员信息.xls";
		$outputFileName = iconv("utf-8", 'gbk', $outputFileName);
		header("Content-Type: application/force-download");  
		header("Content-Type: application/octet-stream");  
		header("Content-Type: application/download");  
		header('Content-Disposition:inline;filename="'.$outputFileName.'"');  
		header("Content-Transfer-Encoding: binary");  
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
		header("Pragma: no-cache");  
		$objWriter->save('php://output');
	}
	
	
	public function mList_a_Edit() {
		$url=$_POST['jumpUrl'];
		$id = $_GET['id'];
		$tid = $_GET['tid'];
		
		$this->assign('name',$this->_iconvByEnv($_GET['truename']));
		
		$infobj=$this->findObj('kcxf',"uid='$id' and tid='$tid'");
		$this->assign('infobj',$infobj);

		if($_POST['flag']){
		$dModel=new kcxfModel();
		if ($dModel->create()) {
			
			if($infobj){

			if ($dModel->save()) {
				$this->assign('jumpUrl',$url);
				$this->success('修改成功');
			}else {
				$this->error('修改失败');
			}
			}
			else{

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
	$this->display();
	}
	
	public function mlist_a_del(){
		$name = $_SESSION['user']['name'];
		$length = strlen($name);
		$str =  substr($name,1,($length-1));
		$name = ",".$str;
		$tid = $_GET['tid'];
		$fobj = $this->findObj('kcsz',"id = $tid");
		$str = $fobj['buid'];
		$str = str_replace("$name","",$str);
		$new = new KcszModel();
			$data['buid']=$str;
			if ($new->where("id = $tid")->save($data)) {
				$this->assign('jumpUrl',__URL__.'/mlist');
				$this->success('退选成功');
			}else {
				$this->error('退选失败');
			}
		}
	
	
	public function kList() {
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
	
	public function kList_a() {
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
	
	public function kList_b() {
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
		
		
		$this->findPage($sql,'adminuser',"name asc");
		

		$this->assign('keys',$keys);

		$this->display();
	}
	
	public function kList_c() {
		$id = $_GET['id'];
		$sql="1=1 and uid=$id";
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
		
		
		$this->findPage($sql,'kykz',"id desc");
		

		$this->assign('keys',$keys);

		$this->display();
	}
	
	public function kAdd() {
		$flag=$_POST['flag'];
		$url = $_POST['jumpUrl'];
		if (empty($flag)) {
			$this->assign('moreinfo',$this->fckHtml('moreinfo',''));
			$this->display();
		} else {
			$this->assign('jumpUrl',$url);
			$entity=new kykzModel();
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
	
	public function kedit() {
		$flag=$_POST['flag'];
		$id = $_GET['id'];
		$url=$_POST['url'];
		if (empty($flag)) {
			$info = $this->findObj('kykz',"id = '$id'");
			$this->assign('info',$info);
			$this->assign('moreinfo',$this->fckHtml('moreinfo',$info['moreinfo']));
			$this->display();
		} else {
			

			$entity=new kykzModel();
			if($entity->create()){

				if ($entity->save()) {
					$this->assign('jumpUrl',$url);
					$this->success('修改成功');
				}else {
					$this->assign('jumpUrl',$url);
					$this->error('修改失败');
				}
			}else {
				$this->error($entity->getError());
			}
			
		}
	}
	
	public function delmInfo() {
		$url=$_SERVER['HTTP_REFERER'];
		$this->assign('jumpUrl',$url);
		$infoids=$_POST['adid'];
		$ids='';
		foreach ($infoids as $infoid) {
			$ids.=','.$infoid;
		}
		if (!empty($ids)) {
			$ids=substr($ids,1);
			$dModel=new kykzModel();
		    if ($dModel->delete("id in ($ids)")){
		    	$this->success('删除成功');
		    }else {
		    	$this->error('删除失败');
		    }
		}else {
			$this->error('没有可以删除的记录');
		}
	}
	
	public function upfen() {
		$id = $_POST ['id'];
		$tid = $_POST ['tid'];
		$fen = $_POST ['con'];
		
		$info = $this->findObj('kcxf',"uid = '$id' and tid = '$tid'");
		
		if($info){
			$this->upsql ("update px_kcxf set fen=$fen where uid=$id  and tid = '$tid'");
		}
		else {
			mysql_query ("INSERT INTO px_kcxf (fen, uid, tid) VALUES ($fen, $id, $tid)");
		}

	}
	
	public function uppfen() {
		$id = $_POST ['id'];
		$tid = $_POST ['tid'];
		$fen = $_POST ['con'];
		
		$info = $this->findObj('kcxf',"uid = '$id' and tid = '$tid'");
		
		if($info){
			$this->upsql ("update px_kcxf set pfen='$fen' where uid=$id  and tid = '$tid'");
		}
		else {
			mysql_query ("INSERT INTO px_kcxf (pfen, uid, tid) VALUES ('$fen', '$id', '$tid'')");
		}

	}
	
}

?>