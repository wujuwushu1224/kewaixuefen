<?php
class MessageAction extends IndexAction {
	public function mesList() {
		$repid=$_GET['repid'];
		$tid = $_GET['tid'];
		$sql=' 1=1 ';
		if ($repid==1) {
			$sql.=" and isrep=1";
		}
		if ($repid==2) {
			$sql.=" and isrep=0";
		}
		
		$sql.=" and tid=$tid ";
		
		$this->findPage($sql,'Message','isshow asc,pubtime desc');
		
		$column = $this->findObj("column", "id = $tid");
		$this->assign('column',$column);		
		$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
		
		$this->display();
	}
	
	/** 更新显示状态 */
	public function  upisshow(){
		$id=$_POST['id'];
		$state=$_POST['state'];
		if ($state==0) 
			$state=1;
		else 
			$state=0;
		echo json_encode($this->upsql("update px_message set isshow=$state where id=$id"));
	}
 	/** 删除留言 */
	public function delMessage() {
		$url=$_SERVER['HTTP_REFERER'];
		$this->assign('jumpUrl',$url);
		$infoids=$_POST['adid'];
		$ids='';
		foreach ($infoids as $infoid) {
			$ids.=','.$infoid;
		}
		if (!empty($ids)) {
			$ids=substr($ids,1);
			$dModel=new MessageModel();
		    if ($dModel->delete("id in ($ids)")){
		    	$this->success('删除成功');
		    }else {
		    	$this->error('删除失败');
		    }
		}else {
			$this->error('没有可以删除的记录');
		}
	}
    /** 留言回复 */
	public function repMessage() {
		if (empty($_POST['flag'])) {
			
			$id=$_GET['id'];
			$infobj=$this->findObj('Message',"id=$id");
		    $this->assign('infobj',$infobj);
			$tid = $infobj['tid'];
		    
		    $column = $this->findObj("column", "id = $tid");
			$this->assign('column',$column);		
			$this->assign("cate",$this->findObj('column', "id = {$column['cid']}"));
		    
		    $this->display();
		}else {
			$dModel=new MessageModel();
			if ($dModel->create()) {
				$dModel->reptime=$this->getTime();
				$dModel->isrep=1;
				if ($dModel->save()) {
					$this->assign('jumpUrl',__URL__.'/mesList/tid/'.$_POST['tid']);
					$this->success('回复成功');
				}else {
					$this->error('回复失败');
				}
			}else {
				$this->error($dModel->getError());
			}
		}
		
		
		
	}
}

?>