<?php

/**
** ͨ�÷�ҳ�ࡣֻ���ṩ����������ÿҳ��ʾ����
** ����ָ��URL�������ɳ������ɡ��������ڼ��������ҳ��

**/
class Pager extends Base{
          private $url;
          private $countall;
          private $page;
          private $thestr;
          private $backstr;
          private $nextstr;
          private $pg;
		  private $pgshow;
          //���캯����ʵ���������ʱ���Զ�ִ�иú���
          function Pager($countall,$countlist){
                  @$this->pg=sprintf("%d",$_GET["pg"]);
                  //��֤pg��δָ���������Ϊ�ӵ�1ҳ��ʼ
                  if ($this->pg==0){
                          $this->pg=1;
                  }
                  if (!isset($this->pg)){
                          $this->pg=1;
                  }
                  //��¼����ÿҳ��ʾ����������ʱ��ҳ��ȡ����1
                  $this->countall = $countall;
                  if ($this->countall%$countlist!=0){
                          $this->page=sprintf("%d",$this->countall/$countlist)+1;
                  }
                  else{
                          $this->page=$this->countall/$countlist;
                  }
                
                  //�õ���ǰ��URL������ʵ���뿴��ײ��ĺ���ʵ��
                  $this->url = Pager::getUrl();
                
                  //����12345��������ʽ�ķ�ҳ��
                  if ($this->page<=10){
                          for ($i=1;$i<$this->page+1;$i++){
                                  $this->thestr=$this->thestr.Pager::makepg($i,$this->pg);
                          }
                  }
                  else{
                          if ($this->pg<=5){
                                  for ($i=1;$i<10;$i++){
                                          $this->thestr=$this->thestr.Pager::makepg($i,$this->pg);
                                  }
                          }
                          else{
                                  if (6+$this->pg<=$this->page){
                                          for ($i=$this->pg-4;$i<$this->pg+6;$i++){
                                                  $this->thestr=$this->thestr.Pager::makepg($i,$this->pg);              

          
                                          }
                                  }
                                  else{
                                          for ($i=$this->pg-4;$i<$this->page+1;$i++){
                                                  $this->thestr=$this->thestr.Pager::makepg($i,$this->pg);
                                          }
                                        
                                  }
                          }
                  }
                  //������ҳ��ҳ����������
                  $this->backstr = Pager::gotoback($this->pg);
                  $this->nextstr = Pager::gotonext($this->pg,$this->page);
                  $this->pgshow=($this->backstr.$this->thestr.$this->nextstr." �� ".$this->countall." ��,ÿҳ ".$countlist." ������

 ".$this->page." ҳ");
          }
		  function showpgs(){
			  return $this->pgshow;
		  }
          //�������ַ�ҳ�ĸ�������
          function makepg($i,$pg){
                  if ($i==$pg){
                          return " <font color=red><b>".$i."</b></font>";
                  }
                  else{
                          return " <a href=javascript:findpg(".$i.")>"."[".$i."]"."</a>";
                  }
          }
          //������һҳ����Ϣ�ĺ���
          function gotoback($pg){
                  if ($pg-1>0){
                                  return $this->gotoback=" <a href=javascript:findpg(1)>��ҳ</a> <a 

href=javascript:findpg(".($this->pg-1).")>��ҳ</a>";
                  }
                  else{
                                  return          $this->gotoback="";
                  }

          }
          //������һҳ����Ϣ�ĺ���
          function gotonext($pg,$page){
                  if ($pg < $page){
                                  return "&nbsp<a href=javascript:findpg(".($this->pg+1).")>��ҳ</a>&nbsp<a 

href=javascript:findpg(".($this->page).")>βҳ</a>";
                  }
                  else{
                                  return "";
                  }
          }
        
          //����url��$pg�ķ���,�����Զ�����pg=x
          function replacepg($url,$flag,$i){
                  if ($flag == 1){ 
                          $temp_pg = $this->pg;
                          return str_replace("pg=".$temp_pg,"pg=".($this->pg+1),$url);
                  }
                  else if($flag == 2) {
                          $temp_pg = $this->pg;
                          return str_replace("pg=".$temp_pg,"pg=".($this->pg-1),$url);
                  }
                  else if($flag == 3) {
                          $temp_pg = $this->pg;
                          return str_replace("pg=".$temp_pg,"pg=1",$url);
                  }
                  else if($flag == 4){
                          $temp_pg = $this->pg;
                          return str_replace("pg=".$temp_pg,"pg=".$this->page,$url);
                  }
                  else if($flag == 5){
                          $temp_pg = $this->pg;
                          return str_replace("pg=".$temp_pg,"pg=".$i,$url);
                  }
                  else{
                          return $url;
                  }
          }
        
        
          //��õ�ǰURL�ķ���
          function    getUrl(){  
                  $url="http://".$_SERVER["HTTP_HOST"];  
                
                  if(isset($_SERVER["REQUEST_URI"])){  
                          $url.=$_SERVER["REQUEST_URI"];  
                  }  
                  else{  
                          $url.=$_SERVER["PHP_SELF"];  
                          if(!empty($_SERVER["QUERY_STRING"])){  
                                  $url.="?".$_SERVER["QUERY_STRING"];  
                          }  
                  }  
                  //�ڵ�ǰ��URL�����pg=x����
                  if (!ereg("(pg=|PG=|pG=|Pg=)", $url)){
                          if (!strpos($url,"?")){
                                  $url = $url."?pg=1";
                          }
                          else{
                                  $url = $url."&pg=1";
                          }
                  }       
                  return    $url;  
          }  
}

?>