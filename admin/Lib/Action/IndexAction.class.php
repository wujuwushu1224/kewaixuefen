<?php
class IndexAction extends PublicAction {
	private $pagesize =15;
	private $tid='';
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		if (! Session::is_set ( 'user' )) {
			redirect ( __APP__ . '/Public/login' );
		}else {
			$user=$this->getUser();
			$roleid=$user['role'];
			$rmMod=new RolemenuModel();
			$riles=$rmMod->find("roleid=$roleid");
			$rarray=explode(',',$riles['menuid']);
			$menuMod=new AdminmenuModel();
			$mn=MODULE_NAME;
			$mid=$menuMod->find("menu_url='$mn'");
			/*
			if(!in_array($mid['id'],$rarray)){
				if ($mn!='Index' && $mn !='System' && $mn !='Daoru') {
					$this->error('您没有此权限'.$mn);
				}
			}
			*/

		}
	}

    public function index(){
        $this->display();
    }
    /** 动态生成导航树菜单 */
    public function menu(){
    	$userObj=Session::get('user');
    	$roleid=$userObj['role'];
    	$roleObj=$this->findObj('Rolemenu',"roleid=$roleid");
    	$menuid = $roleObj['menuid'];
    	$lanmuid = $roleObj['lanmuid'];
    	$this->assign("menuid", explode(",", $menuid));
    	
    	$webid = explode(",", $roleObj['webid']);
    	$sysid = explode(",", $roleObj['sysid']);
    	$this->assign('sysid',$sysid);
    	//dump($lanmuid);
		//dump($roleObj);
		
    	
    	$roleids=$roleObj['menuid'];
    	$menuModel=new AdminmenuModel();
    	$menulist=$menuModel->order("sort asc")->findAll("menu_tid=0 and id in ($roleids) and isshow=1");
		//dump($menulist);
    	$menucount=count($menulist);
    	for ($i = 0; $i< $menucount; $i++) {
    		$mid=$menulist[$i]['id'];

    			$seclist=$menuModel->order("sort,id")->findAll("menu_tid=$mid and id in ($roleids) and isshow=1");

    		$menulist[$i]['seclist']=$seclist;
    	}
    	$this->assign('menulist',$menulist);
		//dump($menulist);

		
    	$webs = $this->findList("Webs", "", "sort asc, id asc");
		for($i=0,$c=count($webs); $i<$c; $i++){
			$isvalid = 0;
			$webs[$i]['cols'] = $this->findList("Column", "cid=0 and wid=".$webs[$i]['id'], "sort asc, id asc");
			for($j=0,$cc=count($webs[$i]['cols']); $j<$cc; $j++){
				$webs[$i]['cols'][$j]['sec'] = $this->findList("Column", "cid=".$webs[$i]['cols'][$j]['id']." and id in ($lanmuid)", "sort asc, id asc");
				if ($webs[$i]['cols'][$j]['sec']){ //子栏目不为空,则标记为1
					$isvalid = 1;
				}else{ //如果子栏目为空,则去掉父级的栏目
					unset($webs[$i]['cols'][$j]);
				}
			}
			if (!$isvalid){ //如果没有子栏目,即标记为0,则去掉此站点
				unset($webs[$i]);
			}
		}
		$this->assign("webs", $webs);

    	/*
    	//栏目列表
    	$column = $this->findList("column", "cid = 0", "sort,id");
    	foreach ($column as $k=>$ls){
    		$column[$k]['sed'] = $this->findList("column", "cid = {$ls['id']}", "sort,id");
    	}
    	$this->assign("column",$column);
		//dump($column);
		 */
		
    	$this->display();
    }
    
    /** 当前时间显示 */
    public function main() {
    	date_default_timezone_set('PRC');
		$nowtime=date("Y-m-d H:i:s"); 
		$this->assign('nowtime',$nowtime);
		$this->display();
    }
    /** 查找二级分类(AJAX调用) */
    public function findSecond(){
    	$subid=$_POST['subid'];
		$tModel=new TypeModel();
		$sublist=$tModel->findAll("subid=$subid");
		echo json_encode($sublist);
    }
	
	
	
/** 增加方法 */
	protected function addInfo($model) {
		$newModel = D($model);
		if ($newModel->create ()) {
			$newModel->logid=$this->getUid();
			if ($newModel->add ()) {
				$this->success ( '增加成功' );
			} else {
				$this->error ( $newModel->getError () );
			}
		} else {
			$this->error ( $newModel->getError () );
		}
	}
	/** 更新方法 */
	protected function upInfo($model,$url) {
		if (!empty($url)) {
			$this->assign('jumpUrl',$url);
		}
		
		$newModel = D($model);
		if ($newModel->create ()) {
			if ($newModel->save ()) {
				$this->success ( '更新成功' );
			} else {
				$this->error ( '更新失败' );
			}
		} else {
			$this->error ( $newModel->getError () );
		}
	}
	/** 根据sql更新数据 */
	protected function upsql($sql) {
		$md=new Model();
		return $md->execute($sql);
	}
	
	/** 删除方法 */
	protected function delInfo($model,$id) {
		$flag=false;
		$newModel = D($model);
		if (! empty ( $id )) {
			if ($newModel->delete ( $id )) {
				$flag=true;
			} else {
				$flag=false;
			}
		} else {
			$flag=false;
		}
		return $flag;
	}
	/** 根据sql删除数据 */
	protected function delsql($model,$sql) {
		$newModel = D($model);
		if (! empty ( $sql )) {
			if ($newModel->delete ( $sql )) {
				$this->success( '删除成功' );
			} else {
				$this->error ( '删除失败' );
			}
		} else {
			$this->error ( '没有此条信息' );
		}
	}
	/** 根据ID查找对象 */
	protected function findObj($model,$sql){
		$newModel = D($model);
		$obj=$newModel->find($sql);
		return $obj;
	}
	/** 根据sql查询数据 */
	protected function findList($model,$sql,$order){
		$newModel = D($model);
		return $newModel->order($order)->findall($sql);
	}
	
	/** 分页 */
	protected function datePage() {
		$start_time = strtotime('2014-06-01');  //开始时间
		$now_time = time(); //当前时间
		$pgsize = $this->pagesize;
		$pg = $_GET ['PB_page'];
		$limitFrom = $this->starNum ( $pg, $pgsize );
		import ( "@.Model.Page" );
		$ct = ceil(($now_time - $start_time)/(3600 * 24)); //当前时间 - 开始时间 = 已经过去了几天了
		if (! empty ( $ct )) {
			$page = new page ( array ('total' => $ct, 'perpage' => $pgsize ) );
			$psh = $page->show ( 4 );
			for ($i=0;$i<$pgsize;$i++){
				$time1 = $now_time - ($i + $limitFrom) * 3600 * 24;
				if ($start_time <= $time1 && $now_time >= $time1){ //如果在规定的时间段内的,则全部显示出来
					$list[] = date("Y-m-d",$time1);
				}
			}
			$this->assign ( 'list', $list );
			$this->assign ( 'pg', $psh );
			return $list;
		}
	}
	
	/** 查询+分页 */
	protected function findPage($sql,$model,$order,$group) {
		$entity=D($model);
		$pg = $_GET['PB_page'];
		$limitFrom = $this->starNum ( $pg, $this->pagesize );
		import ( "@.Model.Page" );
		$ct = count($entity->group($group)->findAll ( $sql ));

		$page=new page(array('total'=>$ct,'perpage'=>$this->pagesize )); 
		$psh='';
		$list='';
		if (!empty($ct)) {
			$psh = $page->show(4);
			$list=$entity->group($group)->order($order)->limit ( "$limitFrom,$this->pagesize" )->findAll($sql);
		}
		
		
		
		if (!empty($list) && $model=='Detail' || $model=='Product' || $model=='Single' || $model=='Down') {
			if (!empty($list)) {
				//$list=$this->listParm($list,$model);
			}
		}
		if (!empty($list) && $model=='Adminuser') {
			$ks=count($list);
			for ($k=0;$k<$ks;$k++){
				$roleid=$list[$k]['role'];
				$robj=$this->findObj('Role',$roleid);
				$list[$k]['rolename']=$robj['rolename'];
			}
		}
	if (!empty($list) && $model=='Role') {
			$ks=count($list);
			for ($k=0;$k<$ks;$k++){
				$roleid=$list[$k]['id'];
				$robj=$this->findObj('Rolemenu',"roleid=$roleid");
				$menuids=$robj['menuid'];
				$mids=explode(',',$menuids);
				$mnames='';
				if (!empty($mids)) {
					foreach ($mids as $mid) {
						if (!empty($mid)){
							$mobj=$this->findObj('Adminmenu',"id = $mid and menu_tid = 0");
							$mnames.=$mobj['menu_name'].'&nbsp;&nbsp;';
						}
					}
					$list[$k]['rolenames']=$mnames;
				}
				$webids=$robj['webid'];
				$wids=explode(',',$webids);
				$webs='';
				if (!empty($wids)) {
					foreach ($wids as $wid) {
						if (!empty($wid)){
							$wobj=$this->findObj('webs',$wid);
							$webs.=$wobj['title'].'&nbsp;&nbsp;';
						}
					}
					$list[$k]['webnames']=$webs;
				}
			}
		}
		$this->assign('list',$list);
		$this->assign('pg',$psh);
		return $list;
	}
	protected function listParm($list,$model) {
		$tids='';
		$kk='';
		$counts=count($list);
		for ($i=0;$i<$counts;$i++){
			$this->tid=$list[$i]['tid'];
			if ($list[$i]['tid']!='') {
				for($k=0;$k<100;$k++){
					if ($model=='Single') {
						$obj=$this->findObj('Part',$this->tid);
					}else {
						$obj=$this->findObj('Type',$this->tid);
					}
				    if ($kk==1  ) {
				    	if ( empty($obj['subid']) ) {
				    		$kk=1;
				    	}
				        break;
				    }else {
				    	if (!$obj) {
				    		break;
				    	}
				    	$md='';
				    	if($model=='Detail'){
				    		$md='infoList';
				    	}
				        if($model=='Product'){
				    		$md='ProductList';
				    	}
				        if($model=='Single'){
				    		$md='sinList';
				    	}
				        if($model=='Down'){
				    		$md='downList';
				    	}
				    	$tids='<a href="'.__URL__.'/'.$md.'/id/'.$obj['id'].'">'.$obj['typename'].'</a>->'.$tids;
				        $this->tid=$obj['subid'];
				    }
				}
			}
			$tids=substr($tids,0,-2);
			$list[$i]['typenames']=$tids;
			$tids='';	
	    }
	    return $list;
	}
	
	protected function userParam($obj) {
		if (!empty($obj)) {
			$userobj=$this->findObj('User',$obj['userid']);
			$typeobj=$this->findObj('Subcate',$obj['typeid']);
			$obj['name']=$userobj['name'];
			$obj['typename']=$typeobj['name'];
		}
		return $obj;
	}
	
	/** 从第几条开始查询 */
	protected  function starNum($pg, $total) { 
		if (empty ( $pg )) {
			$pg = 0;
		}
		$limitFrom = 0;
		if ($pg > 1) {
			$limitFrom = ($total) * ($pg - 1);
		} else {
			$limitFrom = 0;
		}
		return $limitFrom;
	}
	/** 查找指定条数的信息 */
	protected function findTop($num,$model,$sql,$order) {
		$entity=D($model);
		return $entity->order($order)->topN($num,$sql);
		
	}
	protected function getUser() {
		return Session::get('user');
	}
	protected function getUid() {
		 $uObj=Session::get('user');
		 return $uObj['id'];
	}

    protected function upload_Pic() { //上传图片的方法
		$piclist = '';
		import ( "ORG.Net.UploadFile" );
		$upload = new UploadFile ( );
		$path = "./Public/upload/";
		$upload->savePath = $path;
		$upload->maxSize = 5120000;
		$upload->allowExts = explode ( ',', 'jpg,gif,png,jpeg' );
		$upload->saveRule = 'uniqid';
		if ($objj['issmall']==1) {
			$upload->thumb = true;
		    $upload->thumbPrefix   ='px';
		    $upload->thumbSuffix = '';
		}else {
			$upload->thumb = false;
		}
		if (! $upload->upload ()) {
			$errorinfo = $upload->getErrorMsg ();
			$piclist ['flag'] = 'error';
			$piclist ['info'] = $errorinfo;
		} else {
			$uploadList = $upload->getUploadFileInfo ();
			$piclist ['info'] = $uploadList;
			$piclist ['flag'] = 'success';
		
		}
		return $piclist;
	}
    protected function upload_file() { //上传文件的方法
		$piclist = '';
		import ( "ORG.Net.UploadFile" );
		$upload = new UploadFile ( );
		$path = "../Public/file/";
		$upload->savePath = $path;
		$upload->saveRule = 'uniqid';
		if (! $upload->upload ()) {
			$errorinfo = $upload->getErrorMsg ();
			$piclist ['flag'] = false;
			$piclist ['info'] = $errorinfo;
		} else {
			$uploadList = $upload->getUploadFileInfo ();
			$piclist ['info'] = $uploadList;
			$piclist ['flag'] = true;
		
		}
		return $piclist;
	}
	protected function fckHtml($name='',$val='')
   {
	    vendor("FCKeditor.fckeditor");
		$editor= new FCKeditor();   
		$editor->Width='800';
		$editor->Height='400';
		$editor->Value=$val;
		$editor->InstanceName=$name;
		$html=$editor->Createhtml();
		return $html;

   }
   
   /** 权限管理 */
   protected function need_login($role){
	   	$roles = explode(",", $role);
	   	$user_role = $_SESSION['user']['role'];

	   	if (!in_array($user_role, $roles) && $user_role != 1) { //判断是否在规定的权限中
	   		$this->error ( '无此权限!' );
	   	}
   }
   
	protected function _add($model, $url=""){
		$url = $url == "" ? $_SERVER['HTTP_REFERER'] : $url;
		if(empty($_POST)){
			$this->display();
		}else{
			$d = D($model);
			if($d->create()){
				if($d->add()){
					$this->assign('jumpUrl', $url);
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}else{
				$this->error($d->getError());
			}
		}
	}
	protected function _edit($model, $url){
		$url = $url == "" ? $_SERVER['HTTP_REFERER'] : $url;
		if(empty($_POST)){
			$this->assign("o", $this->findObj($model, "id=".$_GET['id']));
			$this->display();
		}else{
			$d = D($model);
			if($d->create()){
				if($d->save()){
					$this->assign('jumpUrl', $url);
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
				}
			}else{
				$this->error($d->getError());
			}
		}
	}
	protected function _delAll($model){
		$url = $_SERVER['HTTP_REFERER'];
		$this->assign('jumpUrl', $url);
		$infoids = $_POST['adid'];
		$ids = '';
		foreach($infoids as $infoid){
			$ids .= ',' . $infoid;
		}
		if(!empty($ids)){
			$ids = substr($ids, 1);
			$dModel = D($model);
			if($dModel->delete("id in ($ids)")){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}else{
			$this->error('没有可以删除的记录');
		}
	}
}
?>