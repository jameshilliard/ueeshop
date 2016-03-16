<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('admin_update_pwd');

if($_POST){
	$OldPassword=password($_POST['OldPassword']);
	$NewPassword=password($_POST['NewPassword']);
	$OldPassword!=$_SESSION['ly200_AdminPassword'] && js_back(get_lang('admin.old_password_err'));
	
	$db->update('userinfo', "UserId='{$_SESSION['ly200_AdminUserId']}'", array(
			'Password'	=>	$NewPassword
		)
	);
	
	$_SESSION['ly200_AdminPassword']=$NewPassword;
	save_manage_log('修改密码');
	js_location('password.php', get_lang('admin.update_pwd_success'));
}

$UserId=(int)$_GET['UserId'];
$userinfo_row=$db->get_one('userinfo', "UserId='$UserId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="password.php"><?=get_lang('admin.update_password');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="password.php" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('admin.old_password');?>:</td>
		<td width="95%"><input name="OldPassword" type="password" value="" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('admin.old_password');?>!~*" size="25" maxlength="16"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('admin.new_password');?>:</td>
		<td><input name="NewPassword" type="password" value="" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('admin.new_password');?>!~8m|<?=get_lang('admin.new_pwd_left_len');?>*" size="25" maxlength="16"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('admin.re_password');?>:</td>
		<td><input name="ReNewPassword" type="password" value="" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('admin.re_password');?>!~=NewPassword|<?=get_lang('admin.repwd_dif_pwd');?>*" size="25" maxlength="16"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>