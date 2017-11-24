<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'>
<link href="/Public/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="message">
<TABLE class="message"  cellpadding=0 cellspacing=0 >
	<tr>
		<td height='5'  class="topTd" ></td>
	</tr>
	<TR class="row" >
		<th class="tCenter space"><?php echo ($msgTitle); ?></th>
	</TR>
	<?php if(isset($message)): ?><TR class="row">
		<TD style="color:blue"><?php echo ($message); ?></TD>
	</TR><?php endif; ?>
	<?php if(isset($error)): ?><TR class="row">
		<TD style="color:red"><?php echo ($error); ?></TD>
	</TR><?php endif; ?>
	<?php if(isset($closeWin)): ?><TR class="row">
		<TD>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 关闭</TD>
	</TR><?php endif; ?>
	<?php if(!isset($closeWin)): ?><TR class="row">
		<TD>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 跳转</TD>
	</TR><?php endif; ?>
	<tr>
		<td height='5' class="bottomTd"></td>
	</tr>
	</TABLE>
</div>
</body>
</html>