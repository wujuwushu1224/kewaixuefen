<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<SCRIPT language=javascript src='/public/js/jquery.js'></SCRIPT>
<style>
*{ margin:0; padding:0}
.clr{ clear:both}
.menu { width:186px; margin-top:30px; font-size:12px;}
.menu img{ border:0}
.menu a{ text-decoration: none; float:right}
.menu ul li{ width:100%; list-style:none; text-indent:50px;}
.menu ul li > a{ width:176px; height:36px; margin-top:5px; display:block; float:right; line-height:32px; color:#fff; background:url(/public/admin/images/menu_abg.png) no-repeat}
.menu ul li > a:hover{ color:#F7D352}
.menu ul li div { float:right; width:100%; display:none}
.menu ul li div a{ width:100%; height:25px; float:left; line-height:25px; color:#fff;}
</style>
</HEAD>
<BODY  style=" background:url(/public/admin/images/left_bg.jpg) no-repeat fixed;">
<div class='menu'>
	<ul>
		<?php if(is_array($webs)): ?><?php $i = 0;?><?php $__LIST__ = $webs?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$websls): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><li>
			<a href="javascript:;"><?php echo ($websls['title']); ?></a>
			<div>
				<?php if(in_array($websls['id'],$sysid)): ?><a href="/index.php/System/website/wid/<?php echo (is_array($websls)?$websls["id"]:$websls->id); ?>" target=main style='color:#ffa1f7'>网站设置</a>
				<a href="/index.php/System/Column/wid/<?php echo (is_array($websls)?$websls["id"]:$websls->id); ?>" target=main style='color:#ffa1f7'>网站栏目管理</a><?php endif; ?>
				
				<?php if(is_array(is_array($websls)?$websls["cols"]:$websls->cols)): ?><?php $i = 0;?><?php $__LIST__ = is_array($websls)?$websls["cols"]:$websls->cols?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$cls): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><a href="javascript:;" target=main><IMG src="/Public/images/menu_icon.gif" width=9 height=9>&nbsp;<?php echo ($cls['title']); ?></a>
					<?php if(is_array(is_array($cls)?$cls["sec"]:$cls->sec)): ?><?php $i = 0;?><?php $__LIST__ = is_array($cls)?$cls["sec"]:$cls->sec?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$sls): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><?php if($sls['type'] == 1){ ?>
					  <A class=menuChild href="/index.php/Infomanage/infoEdit/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else if($sls['type'] == 3){ ?>
					  <A class=menuChild href="/index.php/flink/linksList/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else if($sls['type'] == 4){ ?>
					  <A class=menuChild href="/index.php/message/meslist/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else if($sls['type'] == 6){ ?>
					  <A class=menuChild href="/index.php/flink/editLinks/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else if($sls['type'] == 7){ ?>
					  <A class=menuChild href="/index.php/baoming/meslist/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else if($sls['type'] == 8){ ?>
					  <A class=menuChild href="/index.php/company/meslist/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else if($sls['type'] == 9){ ?>
					  <A class=menuChild href="/index.php/Kebiao/KebiaoCate/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php }else{ ?>
					  <A class=menuChild href="/index.php/Infomanage/infoList/tid/<?php echo $sls['id']; ?>" target=main>&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="/Public/images/menu_icon2.gif" width=9 height=9>&nbsp;<?php echo $sls['title']; ?></A>
					  <?php } ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
				
				<div class='clr'></div>
			</div>
		</li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>	
		<?php if(is_array($menulist)): ?><?php $k = 0;?><?php $__LIST__ = $menulist?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$menuls): ?><?php ++$k;?><?php $mod = (($k % 2 )==0)?><?php if($menuls['seclist'] != ''): ?><li>
			<a href="javascript:;"><?php echo ($menuls['menu_name']); ?></a>
			<div>
				<?php if(is_array(is_array($menuls)?$menuls["seclist"]:$menuls->seclist)): ?><?php $i = 0;?><?php $__LIST__ = is_array($menuls)?$menuls["seclist"]:$menuls->seclist?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$secls): ?><?php ++$i;?><?php $mod = (($i % 2 )==0)?><?php if($secls['is_target'] == 0): ?><a href="/index.php/<?php echo (is_array($secls)?$secls["menu_url"]:$secls->menu_url); ?>" target=main><?php echo ($secls['menu_name']); ?></a>
				<?php else: ?>
				<a href="<?php echo (is_array($secls)?$secls["menu_url"]:$secls->menu_url); ?>" target='_blank'><?php echo ($secls['menu_name']); ?></a><?php endif; ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
				<div class='clr'></div>
			</div>
			</li><?php endif; ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	</ul>
</div>
<script type="text/javascript">
$(function(){
	$(".menu ul li > a").click(function(){
		if ($(this).parent().children('div').css('display') == 'none'){
			$(this).parent().children('div').show();
		}else{
			$(this).parent().children('div').hide();
		}
	})
})
</script>

</BODY></HTML>