<?php

class FlinkAction extends IndexAction {
	public function linksList() {
		$tid = $_GET['tid'];
		$sql=" tid = $tid ";
		$this->findPage ( $sql, 'Links', 'sort,id' );
		$this->assign('tid',$tid);
		
		$column = $this->findObj("column", "id = $tid");
		$this->assign('column',$column);		
		$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
		
		$this->display();
	}
	public function addLinks() {
		if (empty($_POST['flag'])) {
			$tid = $_GET['tid'];
			$column = $this->findObj("column", "id = $tid");
			$this->assign('column',$column);		
			$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
			
			$this->assign('tid',$tid);
			
			$this->display();
		}else {
			$entity = new LinksModel ( );
			if ($entity->create ()) {
				if ($entity->add ()) {
					$this->assign('jumpUrl',__URL__.'/linksList/tid/'.$_POST['tid']);
					$this->success ( '增加成功' );
				} else {
					$this->error ( '增加失败' );
				}
			} else {
				$this->error ( $entity->getError () );
			}
		}
	}
	public function delLinks(){
	    $url = $_SERVER ['HTTP_REFERER'];
		$this->assign ( 'jumpUrl', $url );
		$infoids = $_POST ['adid'];
		$ids = '';
		foreach ( $infoids as $infoid ) {
			$ids .= ',' . $infoid;
		}
		if (! empty ( $ids )) {
			$ids = substr ( $ids, 1 );
			$dModel = new LinksModel ( );
			if ($dModel->delete ( "id in ($ids)" )) {
				$this->success ( '删除成功' );
			} else {
				$this->error ( '删除失败' );
			}
		} else {
			$this->error ( '没有可以删除的记录' );
		}
	}
	public function uplinkSort() {
		$id = $_POST ['id'];
		$numid = $_POST ['con'];
		echo json_encode ( $this->upsql ( "update px_links set sort=$numid where id=$id" ) );
	}
	public function editLinks(){
		if (empty($_POST['flag'])) {
			
			
		
			$id=$_GET['id'];
			$tid = $_GET['tid'];
			if (!empty($id)){
				$infobj = $this->findObj('Links',"id=$id");
				
				$url = $_SERVER ['HTTP_REFERER'];
				$this->assign ( 'jumpUrl', $url );
			}else{
				$infobj = $this->findObj('Links',"tid=$tid");
				if (!$infobj){//如果此外部链接不存在,则新增加
					$LinkModel = new LinksModel();
					$LinkModel->tid = $tid;
					$LinkModel->pubtime = date("Y-m-d H:i:s");
					$LinkModel->add();
				}
				$infobj = $this->findObj('Links',"tid=$tid");
				$this->assign ( 'jumpUrl', '' );
			}
			
			$this->assign('infobj',$infobj);
			
			$column = $this->findObj("column", "id = {$infobj['tid']}");
			$this->assign('column',$column);		
			$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
			
			$this->display();
		}else {
			$dModel = new LinksModel ( );
			if ($dModel->create ()) {
				if ($dModel->save ()) {
					$this->assign('jumpUrl',$_POST['jumpUrl']);
					$this->success ( '修改成功' );
				} else {
					$this->error ( '修改失败' );
				}
			} else {
				$this->error ( $dModel->getError () );
			}
		}
	}
}

?>