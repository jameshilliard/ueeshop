<?php
include('../inc/site_config.php');
include('../inc/set/ext_var.php');
include('../inc/fun/mysql.php');
include('../inc/function.php');
include('../inc/manage/config.php');

if((int)$_GET['setTips']!=1){
	$_SESSION['ly_AdminLoginTips']=get_lang('login.tips');
}

if((int)$_SESSION['ly_AdminUserId']!=0){
	header('Location: main.php');
	exit;
}

if($_POST){
	$username=$_POST['username'];
	$password=password($_POST['password']);
	$log_excode=strtoupper($_POST['excode']);
	
	if($log_excode!=$_SESSION[md5('manage')]){
		$_SESSION['ly_AdminLoginTips']=get_lang('login.tips_v_code_error');
	}else{
		if($user_info=$db->get_one('userinfo', "UserName='$username'")){		
			if($user_info['Locked']){
				$_SESSION['ly_AdminLoginTips']=get_lang('login.tips_locked');
			}elseif($user_info['Password']==$password){
				$db->update('userinfo', "UserId='{$user_info['UserId']}'", array(
						'LastLoginTime'	=>	$service_time,
						'LastLoginIp'	=>	get_ip()
					)
				);
				
				$_SESSION['ly_AdminUserId']=(int)$user_info['UserId'];
				$_SESSION['ly_AdminUserName']=$user_info['UserName'];
				$_SESSION['ly_AdminPassword']=$user_info['Password'];
				$_SESSION['ly_AdminLastLoginTime']=$user_info['LastLoginTime']?$user_info['LastLoginTime']:$service_time;
				$_SESSION['ly_AdminLastLoginIp']=$user_info['LastLoginIp']?$user_info['LastLoginIp']:get_ip();
				$_SESSION['ly_AdminNowLoginTime']=$service_time;
				$_SESSION['ly_AdminGroupId']=(int)$user_info['GroupId'];
				
				save_manage_log(get_lang('login.success'));
				unset($_SESSION['ly_AdminLoginTips'], $_SESSION['tmp_username'], $_SESSION[md5('manage')]);
				
				header('Location: main.php');
				exit;
			}else{
				$_SESSION['ly_AdminLoginTips']=get_lang('login.tips_failed');
			}
		}else{
			$_SESSION['ly_AdminLoginTips']=get_lang('login.tips_failed');
		}
	}
	$_SESSION['tmp_username']=stripslashes($username);
	unset($_SESSION[md5('manage')]);
	
	save_manage_log("<font class='fc_red'>$username</font>尝试登录后台，返回状态:{$_SESSION['ly_AdminLoginTips']}");
	
	header('Location: index.php?setTips=1');
	exit;
}

include('../inc/fun/verification_code.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?=get_lang('ly200.system_title');?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">html,body{background:url(images/login_bg.jpg) top left repeat-x #2965A3; overflow:hidden;}</style>
<script language="javascript" src="../js/lang/manage.php"></script>
<script language="javascript" src="../js/global.js"></script>
<script language="javascript" src="../js/manage.js"></script>
</head>

<body onload="$_('login_form').username.focus();">
<div id="login">
	<div class="title"><img src="images/login_title.jpg" /></div>
	<div class="login_form_div">
		<form action="index.php" method="post" name="login_form" id="login_form" onSubmit="return login(this);">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
			  <tr>
				<td height="30"></td>
				<td colspan="2" class="fz_14px fc_red" valign="top"><?=$_SESSION['ly_AdminLoginTips'];?></td>
			  </tr>
			  <tr>
				<td width="1%" height="40" nowrap class="fz_14px f_gory"><strong><?=get_lang('login.username');?></strong></td>
				<td width="1%"><input name="username" id="username" type="text" maxlength="20" value="<?=htmlspecialchars($_SESSION['tmp_username'])?>" autocomplete="off" class="login_input" tabindex="1"></td>
				<td width="98%" height="40" rowspan="2"><input type="image" name="imageField" src="images/login.jpg" class="login_submit"></td>
			  </tr>
			  <tr>
				<td height="40" nowrap class="fz_14px f_gory"><strong><?=get_lang('login.password');?></strong></td>
				<td><input name="password" id="password" type="password" maxlength="20" value="" autocomplete="off" class="login_input" tabindex="2"></td>
			  </tr>
			  <tr>
				<td height="40" nowrap class="fz_14px f_gory"><strong><?=get_lang('login.v_code');?></strong></td>
				<td colspan="2"><input name="excode" id="excode" type="text" class="excode_input" maxlength="4" value="" autocomplete="off" tabindex="3"><input type="hidden" name="post" value="1">&nbsp;<a href='javascript:void(0);' onclick='this.blur(); obj=$_("<?=md5('manage');?>"); obj.src=obj.src+Math.random(); return false' class="blue"><?=get_lang('login.reload_v_code');?></a></td>
			  </tr>
			  <tr>
				<td height="45">&nbsp;</td>
				<td colspan="2"><?=verification_code('manage');?></td>
			  </tr>
		  </table>
		</form>
		<div class="cp"><img src="images/login_cp.jpg" /></div>
	</div>
</div>
</body>
</html>