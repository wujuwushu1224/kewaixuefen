<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>信息编辑</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="/Public/admin/stylen.css" type=text/css rel=stylesheet>
</HEAD>
<BODY bgColor=#ffffff leftMargin=0 topMargin=0 MARGINHEIGHT="0" MARGINWIDTH="0">
<script src="/Public/js/jquery.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="/Public/js/my97/WdatePicker.js"></script>
<SCRIPT language=JavaScript>
<!--//
function uppic(first,second){
	$('#'+first).hide();
	$('#'+second).show();
	$('#'+second).html('<input name="'+first.substr(1)+'" type="file" size="50" contenteditable="false" />&nbsp;&nbsp;<a href="javascript:qxpic(\''+first+'\',\''+second+'\')" >取消</a>');
}
function qxpic(first,second){
	$('#'+first).show();
	$('#'+second).hide();
}
//改变图片大小
function resizepic(thispic)
{
if(thispic.width>500) thispic.width=500;
}
//无级缩放图片大小
function bbimg(o)
{
var zoom=parseInt(o.style.zoom, 10)||100;
zoom+=event.wheelDelta/12;
if (zoom>0) o.style.zoom=zoom+'%';
return false;
}

function checkadd()
{   
    if (document.postart.title.value.length<1)
	{
        alert("请填写标题！");
        document.postart.title.focus();
        return false;
    }
}

maxLen = 1500;
function checkMaxInput(form) {
if (form.moreinfo.value.length > maxLen)
form.remLen.value = 0;
else form.remLen.value = maxLen - form.moreinfo.value.length;
}
//-->
</SCRIPT>
<FORM name=postart onSubmit="return checkadd()" action="/index.php/Choice/dosEdit" method=post  enctype="multipart/form-data">
<TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#c5d6e5 
        border=0><TBODY>
        <TR>
          <TD style="COLOR: #333333; TEXT-INDENT: 10px" 
          background=/Public/images/u_bg.gif height=34>
你当前的位置：[选修课模块]-[课程设置]</TD></TR></TBODY></TABLE>
      <TABLE style="MARGIN-TOP: 10px" cellSpacing=0 cellPadding=0 width="100%" 
      border=0>
        <TBODY>
        <TR>
          <TD>
            <TABLE cellSpacing=1 cellPadding=3 width="100%" bgColor=#c5d6e5 
            border=0>
              <TBODY>
              <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>职工号：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30><?php echo (is_array($infobj)?$infobj["zgh"]:$infobj->zgh); ?></TD></TR>
            

             <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>姓名：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["xm"]:$infobj->xm); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>性别：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30><?php echo (is_array($infobj)?$infobj["xb"]:$infobj->xb); ?></TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>课程名称：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["kc"]:$infobj->kc); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>起止周：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["qz"]:$infobj->qz); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>学分：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["fen"]:$infobj->fen); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>容量：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["rl"]:$infobj->rl); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>上课时间：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["sj"]:$infobj->sj); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>上课地点：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["dd"]:$infobj->dd); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>场地要求：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30><?php echo (is_array($infobj)?$infobj["cd"]:$infobj->cd); ?></TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>课程简介(限1000字)：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["moreinfo"]:$infobj->moreinfo); ?>
                </TD></TR>
                
                <TR bgColor=#ffffff>
                <TD align=right width=148 bgColor=#f7fcff height=30>申请说明(限50字)：</TD>
                <TD class=pl10 width=1028 bgColor=#f5f8fb height=30>
                	<?php echo (is_array($infobj)?$infobj["sq"]:$infobj->sq); ?>
                </TD></TR>

              <TR bgColor=#ffffff>
                <TD align=middle bgColor=#f5f8fb colSpan=2 height=30>
                <input type="hidden" name="id" id="id" value="<?php echo (is_array($infobj)?$infobj["id"]:$infobj->id); ?>" />
                <input type="hidden" name="jumpUrl" id="jumpUrl" value="<?php echo ($jumpUrl); ?>" />
                <?php if($role == 1): ?><input type="hidden" name="sh" id="sh" value="1" /><INPUT type=submit value="审核" name=submit><?php endif; ?><?php if($role == 30): ?><input type="hidden" name="buid" id="buid" value="<?php echo (is_array($infobj)?$infobj["buid"]:$infobj->buid); ?>" /><input type="hidden" name="rl" id="rl" value="<?php echo (is_array($infobj)?$infobj["rl"]:$infobj->rl); ?>" /><input type="hidden" name="name" id="name" value="<?php echo ($name); ?>" /><?php if($xk < 2): ?><INPUT type=submit value="确认选课" name=submit><?php endif; ?><?php endif; ?> &nbsp; <INPUT onclick=javascript:history.back() type=button value=返回 name=back>
                </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<?php if(C("TOKEN_ON")):?><input type="hidden" name="<?php echo C("TOKEN_NAME");?>" value="<?php echo Session::get(C("TOKEN_NAME")); ?>"/><?php endif;?></form>
</BODY></HTML>