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

function update(con,id,tid){
	$.ajax({
	     type: "post",
	        dataType: "json",
	        url: "/index.php/Choice/upfen",
	        data: {id:id,con:con,tid:tid},
	        complete :function(){ }, 
	        success: function(data){
	        }
	    });
}

function updatea(con,id,tid){
	$.ajax({
	     type: "post",
	        dataType: "json",
	        url: "/index.php/Choice/uppfen",
	        data: {id:id,con:con,tid:tid},
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
          <td height="34" 
background="/Public/images/u_bg.gif" style="color: rgb(51, 51, 51); text-indent: 10px;"><span id="posi_span">你当前的位置：<span style="COLOR: #333333; TEXT-INDENT: 10px">[选修课模块]-[课程学分管理]</span></span>
</td>
<td style="color: rgb(51, 51, 51); text-indent: 10px;" 
align="center" background="/Public/images/u_bg.gif" width="180">
<input title="导出学员信息" onClick="window.location.href='/index.php/Choice/mList_a_dc/id/<?php echo ($_GET['id']); ?>'" value="导出学员信息" type="button" />
</td>
          </tr>
      </tbody></table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
          <td><table style="margin-top: 10px;" bordercolorlight="#000000" bordercolordark="#FFFFFF" align="center" bgcolor="C5D6E5" border="0" cellpadding="0" cellspacing="1" width="100%">
            <tbody><tr bgcolor="F1F1F1">
              <td bgcolor="F7FCFF" height="30" width="60"><p align="center">编号</p></td>
              <td bgcolor="F7FCFF" height="30" width="550" align="center">姓名</td>
              <td bgcolor="F7FCFF" height="30" width="550" align="center">学号</td>
              <td align="center" bgcolor="F7FCFF" width="342">得分</td>
              <td bgcolor="F7FCFF" width="157"><p align="center">管理操作</p></td>
              </tr>
			
			<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$infols): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><tr style="" onMouseOver="this.style.backgroundColor='#FCF9EA'" onMouseOut="this.style.backgroundColor=''" bgcolor="#ffffff">
              <td height="30" ><p align="center"><?php echo ($i); ?></p></td>
              <td height="30" align="center"  ><?php echo (strip_tags(is_array($infols)?$infols["truename"]:$infols->truename)); ?></td>
              <td height="30" align="center"  ><?php echo (strip_tags(is_array($infols)?$infols["name"]:$infols->name)); ?></td>
              <td align="center" height="30" >
              <?php if($role == 6): ?><input type="text" size="5" maxlength="5" value="<?php echo (is_array($infols)?$infols["fen"]:$infols->fen); ?>" onchange="update(this.value,<?php echo (is_array($infols)?$infols["id"]:$infols->id); ?>,<?php echo (is_array($_GET)?$_GET["id"]:$_GET->id); ?>);">&nbsp;&nbsp;
              <select name="pfen" id="pfen<?php echo ($i); ?>" onchange="updatea(this.value,<?php echo (is_array($infols)?$infols["id"]:$infols->id); ?>,<?php echo (is_array($_GET)?$_GET["id"]:$_GET->id); ?>);">
                  <option value=""></option>
                  <option value="优秀">优秀</option>
                  <option value="良好">良好</option>
                  <option value="中等">中等</option>
                  <option value="及格">及格</option>
                  <option value="不及格">不及格</option>
                </select>
              </td>
              <script>
                $(function(){
					$('#pfen<?php echo ($i); ?>').val('<?php echo (is_array($infols)?$infols["pfen"]:$infols->pfen); ?>')		   
				})
                </script>
                <?php else: ?>
                <?php echo (is_array($infols)?$infols["fen"]:$infols->fen); ?><?php if((is_array($infols)?$infols["pfen"]:$infols->pfen) != ''): ?>(<?php echo (is_array($infols)?$infols["pfen"]:$infols->pfen); ?>)<?php endif; ?><?php endif; ?>
              <td height="30" align="center"><!--  <?php if($role == 6): ?><a href="/index.php/Choice/mlist_a_edit/id/<?php echo (is_array($infols)?$infols["id"]:$infols->id); ?>/tid/<?php echo (is_array($_GET)?$_GET["id"]:$_GET->id); ?>/truename/<?php echo (is_array($infols)?$infols["truename"]:$infols->truename); ?>"><font color="green" >修改</font></a><?php else: ?><a href="/index.php/Choice/mlist_a_del/tid/<?php echo (is_array($_GET)?$_GET["id"]:$_GET->id); ?>"><font color="green">退选</font></a><?php endif; ?> --></td>
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