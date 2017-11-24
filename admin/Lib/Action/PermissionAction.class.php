<?php
class PermissionAction extends IndexAction {
    public function userList() {
    	$college = $_SESSION['user']['college'];
    	if($_SESSION['user']['role']!=1){
    	$sql = "college = '$college'";
    	}
    	else{
    	$sql = "1=1";
    	}
		
		$keys = $this->_iconvByEnv($_GET['key']);
		$isshow = $_GET ['isshow'];
		if (! empty ( $keys )) {
			$keyst = preg_replace ( '/(\s+)/', ' ', $keys );
			$keylist = explode ( ' ', $keyst );
			$hql = '';
			foreach ( $keylist as $keyls ) {
				if (! empty ( $keyls )) {
					$hql .= "|| name like '%$keyls%' || truename like '%$keyls%' ";
				}
			}
			if (! empty ( $hql )) {
				$hql = substr ( $hql, 2 );
				$hql = '(' . $hql . ')';
				$sql .= " and $hql ";
			}
		}
		if ($isshow != '') {
			$sql .= " and isvalid=$isshow ";
		}

		$list = $this->findPage ( $sql, 'Adminuser', 'isvalid desc,startime desc' );
    	if($list){
			$a = count($list);
			for($i=0;$i<$a;$i++){
				$id=$list[$i]['id'];
				$xf = $this->findObj('xfgl',"id=$id");
					$kykz = $this->findList('kykz',"uid = $id");
						$s = count($kykz);
						$sf = "0";
						for($ii=0;$ii<$s;$ii++){
							$sf = $sf+$kykz[$ii]['fen'];
						}
						
					$kcxf = $this->findList('kcxf',"uid = $id");
						$sfd="0";
						$d = count($kcxf);
						for($is=0;$is<$d;$is++){
							$sfd=$sfd+$kcxf[$is]['fen'];
						}					
				
				$list[$i]['fen'] = $xf['fen']+$sf+$sfd;

			}
		}
		$this->assign ( 'list', $list );

		$this->assign ( 'keys', $keys );
		$this->assign ( 'isshow', $isshow );
		$this->display ();
	}
    public function delUser() {
		$url = $_SERVER ['HTTP_REFERER'];
		$this->assign ( 'jumpUrl', $url );
		$infoids = $_POST ['adid'];
		$ids = '';
		foreach ( $infoids as $infoid ) {
			$ids .= ',' . $infoid;
		}
		if (! empty ( $ids )) {
			$ids = substr ( $ids, 1 );
			$dModel = new AdminuserModel ( );
			if ($dModel->delete ( "id in ($ids)" )) {
				$this->success ( '删除成功' );
			} else {
				$this->error ( '删除失败' );
			}
		} else {
			$this->error ( '没有可以删除的记录' );
		}
	}
    public function delRole() {
		$url = $_SERVER ['HTTP_REFERER'];
		$this->assign ( 'jumpUrl', $url );
		$infoids = $_POST ['adid'];
		$ids = '';
		foreach ( $infoids as $infoid ) {
			$ids .= ',' . $infoid;
		}
		if (! empty ( $ids )) {
			$ids = substr ( $ids, 1 );
			$dModel = new RoleModel ( );
			if ($dModel->delete ( "id in ($ids)" )) {
				$this->success ( '删除成功' );
			} else {
				$this->error ( '删除失败' );
			}
		} else {
			$this->error ( '没有可以删除的记录' );
		}
	}
	public function userEdit() {
		$flag = $_POST ['flag'];
		if (empty ( $flag )) {
			$id = $_GET ['id'];
			$menModel = new AdminuserModel ( );
			$menobj = $menModel->find ( $id );
			$this->assign ( 'menobj', $menobj );
			$list=$this->findList('Role');
			$this->assign('list',$list);
			$this->assign('jump_url',$_SERVER['HTTP_REFERER']);
			$this->display ();
		} else {
			$menms = new AdminuserModel ( );
			$id=$_POST['id'];
			if (empty($id)) {
				$this->error ( '操作出现错误' );
			}
			$objs=$menms->find($id);
			$data['truename'] = $_POST['truename'];
			$data['college'] = $_POST['college'];
			$data['class'] = $_POST['class'];
			$data['role'] = $_POST['role'];
			$data['id'] = $_POST['id'];

			if (!empty($_POST['pass'])) {
				$data['pass'] = md5( md5($_POST['pass']).'pass');
			}

			$menModel=new AdminuserModel();
			if ($menModel->save ($data)) {
				$this->assign ( 'jumpUrl',$_POST['jump_url']);
				$this->success ( '修改成功' );
			} else {
				$this->error ( '修改失败' );
			}
		}
	}
	public function addUser() {
		$flag = $_POST ['flag'];
		if (empty ( $flag )) {
			$list=$this->findList('Role');
			$this->assign('list',$list);
			$this->display ();
		} else {
			$menModel = new AdminuserModel ( );
			if ($menModel->create ()) {
				$menid=$menModel->add ();
				if ($menid) {
					$this->assign ( 'jumpUrl', __URL__ . '/userList' );
					$this->success ( '添加成功' );
				} else {
					$this->error ( '添加失败' );
				}
			
			} else {
				$this->error ( $menModel->getError () );
			}
		}
	}
	public function upvalid() {
		$id = $_POST ['id'];
		$state = $_POST ['state'];
		if ($state == 0)
			$state = 1;
		else
			$state = 0;
		echo json_encode ( $this->upsql ( "update px_adminuser set isvalid=$state where id=$id" ) );
	
	}
	public function roleList() {
		$this->findPage('','Role');
		$this->display();
	}
	public function addRole() {
		$flag = $_POST ['flag'];
		if (empty ( $flag )) {
			
			$list=$this->findList('Adminmenu',"menu_tid=0 and isshow = 1",'sort,id');
			foreach ($list as $k => $ls){
				$list[$k]['sed'] = $this->findList("Adminmenu", "menu_tid = {$ls['id']} and isshow = 1", 'sort,id');
			}
			$this->assign('list',$list);
			
			$webs = $this->findList("Webs", "", "sort asc, id asc");
			for($i=0,$c=count($webs); $i<$c; $i++){
				$webs[$i]['cols'] = $this->findList("Column", "cid=0 and wid=".$webs[$i]['id'], "sort asc, id asc");
				for($j=0,$cc=count($webs[$i]['cols']); $j<$cc; $j++){
					$webs[$i]['cols'][$j]['sec'] = $this->findList("Column", "cid=".$webs[$i]['cols'][$j]['id'], "sort asc, id asc");
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
		}else {
			$is_bxy = intval($_POST['is_bxy']);

			$ids=$_POST['adid'];
			$idss='';
			if (!empty($ids)) {
			    foreach ($ids as $id) {
				    $idss.=','.$id;
			    }
			    $idss=substr($idss,1);
			}
			
			$webids=$_POST['webid'];
			$webids2='';
			if (!empty($webids)) {
			    foreach ($webids as $id2) {
				    $webids2.=','.$id2;
			    }
			    $webids2=substr($webids2,1);
			}
			
			$ids2=$_POST['adid2'];
			$idss2='';
			if (!empty($ids2)) {
			    foreach ($ids2 as $id2) {
				    $idss2.=','.$id2;
			    }
			    $idss2=substr($idss2,1);
			}
			$sysids=$_POST['adid3'];
			$sysids2='';
			if (!empty($sysids)) {
			    foreach ($sysids as $id2) {
				    $sysids2.=','.$id2;
			    }
			    $sysids2=substr($sysids2,1);
			}
			$rModel=new RoleModel();
			if ($rModel->create()) {
				$rid=$rModel->add();
				if ($rid) {
					$rmModel=new RolemenuModel();
					$rmModel->roleid=$rid;
					$rmModel->menuid=$idss;
					$rmModel->lanmuid=$idss2;
					$rmModel->webid=$webids2;
					$rmModel->sysid=$sysids2;
					$rmModel->is_bxy=$is_bxy;
					$rmModel->add();
					$this->assign('jumpUrl',__URL__.'/roleList');
					$this->success('增加成功');
				}else {
					$this->error('增加失败');
				}
			}else {
				$this->error($rModel->getError());
			}
			
			
			
		}
		
	}
	public function editRole() {
		if (empty($_POST['flag'])) {
			$id=$_GET['id'];
			$obj=$this->findObj('Role',$id);
			$this->assign('obj',$obj);

			$roleObj=$this->findObj('Rolemenu',"roleid=$id");
			$this->assign('roleObj',$roleObj);

			$list=$this->findList('Adminmenu',"menu_tid=0 and isshow = 1",'sort,id');
			foreach ($list as $k => $ls){
				$list[$k]['sed'] = $this->findList("Adminmenu", "menu_tid = {$ls['id']} and isshow = 1", 'sort,id');
			}
			$robj=$this->findObj('Rolemenu',"roleid=$id");
			
			$mids=$robj['menuid'];
			if (!empty($mids)) {
				$mids=explode(',',$mids);
			}
			
			$lids = $robj['lanmuid'];
			if($lids != ''){
				$lids = explode(',', $lids);
			}
			
			$wids = $robj['webid'];
			if($wids != ''){
				$wids = explode(',', $wids);
			}
			
			$sids = $robj['sysid'];
			if($sids != ''){
				$sids = explode(',', $sids);
			}
			
			/*
			$boxs='';
			foreach ($list as $ls) {
				$boxs.='&nbsp;&nbsp;&nbsp;&nbsp;';
				if (in_array($ls['id'],$mids)) {
					$boxs.='<input name="adid[]" checked value="'.$ls['id'].'" onclick="Checked(form)" type="checkbox">'.$ls['menu_name'].'<br/>';
				}else {
					$boxs.='<input name="adid[]"  value="'.$ls['id'].'" onclick="Checked(form)" type="checkbox">'.$ls['menu_name'].'<br/>';
				}
			}
			
			$this->assign('boxs',$boxs);
			*/
			
			$this->assign('list',$list);

			$this->assign("mids", $mids);
			$this->assign("lids", $lids);
			$this->assign("wids", $wids);
			$this->assign("sids", $sids);
			

			/*
			//栏目列表
			$column = $this->findList("column", "cid = 0", "sort,id");
			foreach ($column as $k=>$ls){
				$column[$k]['sed'] = $this->findList("column", "cid = {$ls['id']}", "sort,id");
			}
			$this->assign("column",$column);
			//dump($column);
			*/
			$webs = $this->findList("Webs", "", "sort asc, id asc");
			for($i=0,$c=count($webs); $i<$c; $i++){
				$webs[$i]['cols'] = $this->findList("Column", "cid=0 and wid=".$webs[$i]['id'], "sort asc, id asc");
				for($j=0,$cc=count($webs[$i]['cols']); $j<$cc; $j++){
					$webs[$i]['cols'][$j]['sec'] = $this->findList("Column", "cid=".$webs[$i]['cols'][$j]['id'], "sort asc, id asc");
				}
			}
			$this->assign("webs", $webs);
			

			$this->display();
		}else {
			$is_bxy = intval($_POST['is_bxy']);
			$rModel=new RoleModel();
			if ($rModel->create()) {
				if ($rModel->save()) {
					$adid=$_POST['adid'];
					$adids='';
					if (!empty($adid)) {
						foreach ($adid as $ids) {
							$adids.=','.$ids;
						}
					}
					if (!empty($adids)) {
						$adids=substr($adids,1);
					}
			$ids2=$_POST['adid2'];
			$idss2='';
			if (!empty($ids2)) {
			    foreach ($ids2 as $id2) {
				    $idss2.=','.$id2;
			    }
			    $idss2=substr($idss2,1);
			}
			
			$webids=$_POST['webid'];
			$webids2='';
			if (!empty($webids)) {
			    foreach ($webids as $id2) {
				    $webids2.=','.$id2;
			    }
			    $webids2=substr($webids2,1);
			}
			
			$sysids=$_POST['adid3'];
			$sysids2='';
			if (!empty($sysids)) {
			    foreach ($sysids as $id2) {
				    $sysids2.=','.$id2;
			    }
			    $sysids2=substr($sysids2,1);
			}
			
					$rmModel=new RolemenuModel();
					$roleid=$_POST['id'];
					if($rmModel->find("roleid=$roleid")){
						$rmModel->menuid=$adids;
						$rmModel->lanmuid=$idss2;
						$rmModel->webid=$webids2;
						$rmModel->sysid=$sysids2;
						$rmModel->is_bxy=$is_bxy;
						$rmModel->save();
					}else {
						$rmModel->roleid=$roleid;
					    $rmModel->menuid=$adids;
						$rmModel->lanmuid=$idss2;
						$rmModel->sysid=$sysids2;
						$rmModel->is_bxy=$is_bxy;
						$rmModel->add();
					}
					$this->assign('jumpUrl',__URL__.'/roleList');
					$this->success('更新成功');
				}else {
					$this->error('更新失败');
				}
			}
			
			
		}
		
	}
	


	public function websList() {
		$this->findPage('','Webs',"sort asc, id asc");
		$this->display();
	}
	public function addWebs() {
		$this->_add("Webs", __URL__."/websList");
	}
	public function editWebs() {
		$this->_edit("Webs", __URL__."/websList");
	}
    public function delWebs() {
		$this->_delAll("Webs");
	}
	

}

?>