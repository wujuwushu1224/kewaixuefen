<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>绍兴文理学院 元培学院学生课外学分认定与管理系统</TITLE>
<LINK href="/public/admin/style.css" type=text/css rel=stylesheet>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="MSHTML 6.00.6000.16674" name=GENERATOR>
<script type="text/javascript">
function changimg(obj) {
	var id=Math.random()+Math.random();
	obj.src="/index.php/Public/verify/id/"+id;
}
function checkform(fm){
	var info='';
	if(trims(fm.name.value)==''){
		info+='用户名不能为空\n';
	}
	if(trims(fm.pass.value)==''){
		info+='请输入密码\n';
	}
	if(trims(fm.verify.value).length!=4){
		info+='验证码必须为4位\n';
	}
	if(info==''){
		return true;
	}else{
		alert(info);
		return false;
	}
}
//去左空格;
function ltrim(s){
    return s.replace( /^\s*/, "");
}
//去右空格;
function rtrim(s){
    return s.replace( /\s*$/, "");
}
//去左右空格;

function trims(s)
{
  return rtrim(ltrim(s));
}

</script>
</HEAD>
<BODY>
<form action="/index.php/Public/dologin" method="post" onSubmit="return checkform(this)" >

<div class="box">
	<div class="logo"></div>
	<div class="login">
		<div class="input"><div>用户名：</div><INPUT id=name maxLength=20 name=name></div>
		<div class="input"><div>密&nbsp;&nbsp;&nbsp;&nbsp;码：</div><INPUT id=pass type=password name=pass></div>
		<div class="input"><div>验证码：</div><INPUT id=verify maxLength=4 size="4"  name=verify style="width:67px"> <img src="/index.php/Public/verify" style="border: 0px;cursor: pointer;" onClick="changimg(this)" ></div>
		<div class="btn">
			<input type="submit" value='' />
		</div>
	</div>
</div>

<?php if(C("TOKEN_ON")):?><input type="hidden" name="<?php echo C("TOKEN_NAME");?>" value="<?php echo Session::get(C("TOKEN_NAME")); ?>"/><?php endif;?></form>
</BODY></HTML>