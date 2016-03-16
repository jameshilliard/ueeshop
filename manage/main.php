<?php
include('../inc/site_config.php');
include('../inc/set/ext_var.php');
include('../inc/fun/mysql.php');
include('../inc/function.php');
include('../inc/manage/config.php');
include('../inc/manage/do_check.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?=get_lang('ly200.system_title');?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">html,body{overflow:hidden;}</style>
<script language="javascript" src="../js/lang/manage.php"></script>
<script language="javascript" src="../js/global.js"></script>
<script language="javascript" src="../js/manage.js"></script>
</head>

<body>
<div id="loading"><noscript><?=get_lang('home.noscript');?></noscript><br /><br /><img src="images/loading.gif" /></div>
<div id="ly200">
	<div id="topMenu">
		<div id="top">
			<div id="service_tel">售后服务热线:020-83226791</div>
		</div>
	</div>
	<div id="loginInfo">
		<div id="member"><?=@sprintf(get_lang('admin.welcome'), $_SESSION['ly_AdminUserName'], $_SESSION['ly_AdminLastLoginIp'], @date(get_lang('ly200.time_format_full'), $_SESSION['ly_AdminLastLoginTime']), get_ip());?></div>
		<div id="link">
			<a class="q0" href="/" target="_blank"><?=get_lang('home.website_home');?></a>
			<?php if($menu['admin_update_pwd']){?><a class="q1" href="javascript:void(0);" onclick="this.blur(); openWindows('win_admin_update_pwd', this.innerHTML, 'admin/password.php');"><?=get_lang('admin.update_password');?></a><?php }?>
			<a class="q2" href="admin/logout.php"><?=get_lang('admin.logout');?></a>
		</div>
	</div>
	<div id="contents">
		<div id="leftMenu">
			<div id="menu">
				<div id="menu_index"><strong><?=get_lang('menu.menu_index');?></strong></div>
				<?php
				$i=0;
				foreach($manage_menu as $group_key=>$group){
					if(implode($group)==''){
						continue;
					}
				?>
					<div id="dmenu" onclick="show_hidden_menu_list(<?=$i;?>, this)"><strong><?=get_lang('menu.'.$group_key);?></strong></div>
					<ul id="menu_list_<?=$i++;?>">
						<?php
						foreach($group as $key=>$value){
							if(!is_array($value)){
								continue;
							}
						?>
						  	<?php if($value[2]!=1){?>
								<li><span class="span" onclick="this.blur(); openWindows('win_<?=$key;?>', this.innerHTML, '<?=$value[0];?>')" onmouseover="this.className='span_hover';" onmouseout="this.className='span';"><?=get_lang($value[1]);?></span></li>
							<?php }else{?>
								<li><a href="<?=$value[0];?>" target="_blank"><?=get_lang($value[1]);?></a></li>
							<?php }?>
						<?php }?>
					</ul>
				<?php }?>
			</div>
		</div>
		<div id="rightContents">			
			<div id="windowsNav"><div id="windowsList"></div></div>
			<div id="desktop"><div id="workWindows"></div></div>
		</div>
	</div>
	<div id="footer"><div id="copyright"><a href="http://www.ly200.com/" target="_blank">广州联雅网络科技有限公司 &copy; 版权所有</a></div></div>
</div>
<script language="javascript">
window.onresize=ly200_init;

window.onload=function(){
	ly200_init();
	openWindows('win_manage_index_page', '<?=get_lang('home.system_home');?>', 'system/index.php');
}
</script>
</body>
</html>