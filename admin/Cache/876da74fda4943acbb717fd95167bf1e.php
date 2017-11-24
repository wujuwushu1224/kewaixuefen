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
			form.action="/index.php/Required/delInfo";
			form.submit();
		}
	}
}
function changestate(obj,state,id){
	$.ajax({
	     type: "post",
	        dataType: "json",
	        url: "/index.php/Required/upisshow",
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
	        url: "/index.php/Required/upSort",
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
background="/Public/images/u_bg.gif" height="34"><span id="posi_span">你当前的位置：[必选课模块]-[学分管理]</span>
</td>
          <td style="color: rgb(51, 51, 51); text-indent: 10px;" 
align="center" background="/Public/images/u_bg.gif" width="180">&nbsp;</td>
        </tr>
      </tbody></table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
          <td><table style="margin-top: 10px;" bordercolorlight="#000000" bordercolordark="#FFFFFF" align="center" bgcolor="C5D6E5" border="0" cellpadding="0" cellspacing="1" width="100%">
            <tbody><tr bgcolor="F1F1F1">
              <td bgcolor="F7FCFF" height="30" width="103"><p align="center">编号</p></td>
              <td bgcolor="F7FCFF" height="30" width="1085" align="center">班级</td>
              </tr>
			
			<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$infols): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><tr style="" onMouseOver="this.style.backgroundColor='#FCF9EA'" onMouseOut="this.style.backgroundColor=''" bgcolor="#ffffff">
              <td height="30" ><p align="center"><?php echo ($i); ?></p></td>
              <td height="30" align="center"  ><a href="/index.php/Required/mlist_b/class/<?php echo (is_array($infols)?$infols["class"]:$infols->class); ?>"><?php echo (strip_tags(is_array($infols)?$infols["class"]:$infols->class)); ?></a></td>
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