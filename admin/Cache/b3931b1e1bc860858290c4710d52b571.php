<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML><head>
<title>信息列表</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK href="/Public/admin/stylen.css" type=text/css rel=stylesheet>
<script src="/Public/js/jquery.js" type="text/javascript"></script>
</head>
<body leftmargin="0" topmargin="0" bgcolor="#ffffff" marginheight="0" 
marginwidth="0">
<style>
/* 头部开始 */
a:link {color: #07519A; text-decoration: none} 
a:visited {color: #07519A; text-decoration: none}
a:hover {color: #FF0000; text-decoration:underline} 
a:active {color: #07519A; text-decoration: none}
</style>
<script language="javascript">
<!--//
function CheckAll(form)
{
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.Name != "chkAll")
       e.checked = form.chkAll.checked;
    }
}
function Checked()
{
	var j = 0
	for(i=0;i < document.form.elements.length;i++){
		if(document.form.elements[i].name == "adid[]"){
			if(document.form.elements[i].checked){
				j++;
			}
		}
	}
	return j;
}

function DelAll()
{
	if(Checked()  <= 0){
		alert("您至少选择1条信息!");
	}	
	else{
		if(confirm("确定要删除选择的信息吗？\n此操作不可以恢复！")){
			form.action="/index.php/Choice/delInfo";
			form.submit();
		}
	}
}
function changestate(obj,state,id){
	$.ajax({
	     type: "post",
	        dataType: "json",
	        url: "/index.php/Choice/upisshow",
	        data: {state:state,id:id},
	        complete :function(){ }, 
	        success: function(data){
	        	if(state==0){
	        		obj.src='/Public/admin/images/yes.gif';
	        		state=1;
	        	}else{
	        		obj.src='/Public/admin/images/no.gif';
	        		state=0;
	        	}
	        	obj.onclick=function(){changestate(obj,state,id);}
	        	
	        }
	    });
}
function update(con,id){
	$.ajax({
	     type: "post",
	        dataType: "json",
	        url: "/index.php/Choice/upSort",
	        data: {id:id,con:con},
	        complete :function(){ }, 
	        success: function(data){
	        }
	    });
}
//-->
</script>
<form name="form" method="post" id="form">
 <table bgcolor="C5D6E5" border="0" cellpadding="0" 
cellspacing="1" width="100%">
        <tbody><tr>
          <td style="color: rgb(51, 51, 51); text-indent: 10px;" 
background="/Public/images/u_bg.gif" height="34"><span id="posi_span">你当前的位置：[选修课模块]-[课程设置]</span>
</td>
          <td style="color: rgb(51, 51, 51); text-indent: 10px;" 
align="center" background="/Public/images/u_bg.gif" width="180">
<input  name="stateid" id="stateid" type="hidden">
<?php if($role == 6): ?><input title="发布内容" onClick="window.location.href='/index.php/Choice/sAdd/tid/<?php echo ($tid); ?>'" value="发布内容" type="button" />
<input title="删除" onClick="DelAll()" value="删除" name="Submit" type="button" /><?php endif; ?>
</td>
        </tr>
      </tbody></table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
          <td><table style="margin-top: 10px;" bordercolorlight="#000000" bordercolordark="#FFFFFF" align="center" bgcolor="C5D6E5" border="0" cellpadding="0" cellspacing="1" width="100%">
            <tbody><tr bgcolor="F1F1F1">
              <td bgcolor="F7FCFF" height="30" width="49"><p align="center">编号</p></td>
              <td bgcolor="F7FCFF" height="30" width="516" align="center">课程名称</td>
              <td align="center" bgcolor="F7FCFF" width="235">课程教师</td>
              <td align="center" bgcolor="F7FCFF" width="208">得分</td>
              <td width="115" align="center" bgcolor="F7FCFF">剩余人数</td>
              <td width="115" align="center" bgcolor="F7FCFF">审核情况</td>
              <td bgcolor="F7FCFF" width="115"><p align="center">操作</p></td>
              <td align="center" bgcolor="F7FCFF" width="61"><input id="chkAll" onClick="CheckAll(this.form)" value="checkbox" name="chkAll" type="checkbox"></td>
            </tr>
			
			<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$infols): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><tr style="" onMouseOver="this.style.backgroundColor='#FCF9EA'" onMouseOut="this.style.backgroundColor=''" bgcolor="#ffffff">
              <td height="30" ><p align="center"><?php echo ($i); ?></p></td>
              <td height="30"  >&nbsp;&nbsp;&nbsp;&nbsp;<a href="/index.php/Choice/sshow/id/<?php echo (is_array($infols)?$infols["id"]:$infols->id); ?>"><?php echo (strip_tags(is_array($infols)?$infols["kc"]:$infols->kc)); ?></a></td>
              <td align="center" ><?php echo ($infols['truename']['truename']); ?></td>
              <td align="center" height="30" ><?php echo (is_array($infols)?$infols["fen"]:$infols->fen); ?></td>
              <td align="center" ><?php echo (is_array($infols)?$infols["sy"]:$infols->sy); ?></td>
              <td align="center" >
              <?php if((is_array($infols)?$infols["sh"]:$infols->sh) == 1): ?>已审核
              <?php else: ?>
              <span style="color:#F00">未审核</span><?php endif; ?>
              </td>
              <td height="28" ><?php if((is_array($infols)?$infols["uid"]:$infols->uid) == $uid): ?><p align="center"><a href="/index.php/Choice/sEdit/id/<?php echo (is_array($infols)?$infols["id"]:$infols->id); ?>"><font color="green">修改</font></a></p><?php endif; ?></td>
              <td align="center" height="30"><?php if((is_array($infols)?$infols["uid"]:$infols->uid) == $uid): ?><input name="adid[]" value="<?php echo (is_array($infols)?$infols["id"]:$infols->id); ?>" onClick="Checked(form)" type="checkbox"><?php endif; ?></td>
            </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
          </tbody></table></td>
        </tr>
      </tbody></table>
        <br>        <table align="center" bgcolor="CCCCCC" border="0" cellpadding="0" cellspacing="1" width="100%">
          <tbody><tr>
            <td bgcolor="#ffffff" height="28" colspan="2" width="100%" align="center">
				<!-- 分页 --><?php echo ($pg); ?>
			</td>
            
          </tr>
        </tbody></table>
<?php if(C("TOKEN_ON")):?><input type="hidden" name="<?php echo C("TOKEN_NAME");?>" value="<?php echo Session::get(C("TOKEN_NAME")); ?>"/><?php endif;?></form> 
<div id="search_div" style="text-align:center;margin: 0 auto;">
<form name="myform" action="" method="get">	
<div id="d2">
关键字：<input name="key" id="key" value="<?php echo ($keys); ?>" />
 <input type="submit" value="搜索" /></div>
<?php if(C("TOKEN_ON")):?><input type="hidden" name="<?php echo C("TOKEN_NAME");?>" value="<?php echo Session::get(C("TOKEN_NAME")); ?>"/><?php endif;?></form>
</div>                                                            
</body></html>