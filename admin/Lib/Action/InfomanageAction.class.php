<?php
class InfomanageAction extends IndexAction   {

	private $pagesize = 10; //每页显示的记录数
	
	/*
	  1  文章类
      2 新闻类
      3 友情链接
      4 留言板
      5 图片展示
      6 外部链接
	*/
	public function infoList() {
		$sql="1=1";
		$keys=$_GET['key'];
		$keys=iconv('gb2312','utf-8',$keys);
		$isshow=$_GET['isshow'];
		$tid = $_GET['tid'];
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
		if ($isshow!='') {
			$sql.=" and isshow=$isshow ";
		}
		
		$sql.=" and tid=$tid ";
		
		$this->findPage($sql,'Detail',"pubtime desc,sort,id desc");
		
		$column = $this->findObj("column", "id = $tid");
		$this->assign('column',$column);		
		$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
		
		$this->assign('tid',$tid);
		$this->assign('keys',$keys);
		$this->assign('isshow',$isshow);
		$this->display();
	}
	
    /** 信息编辑页面 */
	public function infoEdit() {
		
		$tid = $_GET['tid'];
		$id=$_GET['id'];
		if (!empty($id)){
			$url=$_SERVER['HTTP_REFERER'];
			$this->assign('jumpUrl',$url);
			$infobj=$this->findObj('Detail',"id=$id");
			$this->assign('infobj',$infobj);
			$this->assign('htmls',$this->fckHtml('moreinfo',$infobj['moreinfo']));
		}else{
			$this->assign('jumpUrl','');
			$infobj=$this->findObj('Detail',"tid=$tid");
			if (!$infobj){//如果此外部链接不存在,则新增加
				$DetailModel = new DetailModel();
				$DetailModel->tid = $tid;
				$DetailModel->pubtime = date("Y-m-d H:i:s");
				$DetailModel->add();
			}
			$infobj=$this->findObj('Detail',"tid=$tid");
			$this->assign('infobj',$infobj);
			$this->assign('htmls',$this->fckHtml('moreinfo',$infobj['moreinfo']));
		}
		
		
		
		$tid = $infobj['tid'];
		$column = $this->findObj("column", "id = $tid");
		$this->assign('column',$column);		
		$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
		$this->display();
	}
	
	/** 信息添加 */
	public function infoAdd() {
		$flag=$_POST['flag'];
		if (empty($flag)) {
			$tid = $_GET['tid'];
			$column = $this->findObj("column", "id = $tid");
			$this->assign('column',$column);		
			$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
			
		    $this->assign('htmls',$this->fckHtml('moreinfo',''));
		    $this->assign('pubtime',date("Y-m-d"));
		    $this->assign('tid',$tid);
			$this->display();
		} else {
			$tid = $_POST['tid'];
			$this->assign('jumpUrl',__URL__.'/infoList/tid/'.$tid);
			$entity=new DetailModel();
			if($entity->create()){
				$pubtime = $_POST['pubtime'];
				$pubtime = !empty($pubtime)?$pubtime:date("Y-m-d H:i:s");
				$entity->pubtime=$pubtime;
				
/* upload_Pic() */
				$pic_info = $this->upload_Pic ();
				if ($pic_info ['flag'] == 'success') {
					$piclist = $pic_info ['info'];
					$entity->picture = $piclist [0] ['savename'];
				} else {
					if ($pic_info ['info'] != '没有选择上传文件') {
						$this->error ( $pic_info ['info'] );
					}
				}
/* upload_Pic() end */
				
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
		$dModel=new DetailModel();
		if ($dModel->create()) {
			
/* upload_Pic() */
			$pic_info = $this->upload_Pic ();
			if ($pic_info ['flag'] == 'success') {
				$piclist = $pic_info ['info'];
				$dModel->picture = $piclist [0] ['savename'];
			} else {
				if ($pic_info ['info'] != '没有选择上传文件') {
					$this->error ( $pic_info ['info'] );
				}
			}
/* upload_Pic() end */
			
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
			$dModel=new DetailModel();
		    if ($dModel->delete("id in ($ids)")){
		    	$this->success('删除成功');
		    }else {
		    	$this->error('删除失败');
		    }
		}else {
			$this->error('没有可以删除的记录');
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