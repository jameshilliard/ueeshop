<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('send_mail_set');

if($_POST){
	$Module=(int)$_POST['Module'];
	$FromEmail=format_post_value($_POST['FromEmail'], 1);
	$FromName=format_post_value($_POST['FromName'], 1);
	$Smtp=format_post_value($_POST['Smtp'], 1);
	$Port=format_post_value($_POST['Port'], 1);
	$Email=format_post_value($_POST['Email'], 1);
	$Password=format_post_value($_POST['Password'], 1);
	
	$php_contents=str_replace("\t", '', "<?php
	//邮件发送设置
	\$mCfg['SendMail']['Module']=$Module;
	\$mCfg['SendMail']['FromEmail']='$FromEmail';
	\$mCfg['SendMail']['FromName']='$FromName';
	\$mCfg['SendMail']['Smtp']='$Smtp';
	\$mCfg['SendMail']['Port']='$Port';
	\$mCfg['SendMail']['Email']='$Email';
	\$mCfg['SendMail']['Password']='$Password';
	?>");
	
	write_file('/inc/set/', 'send_mail.php', $php_contents);
	
	save_manage_log('邮件发送设置');
	
	header('Location: send_mail.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="send_mail.php"><?=get_lang('set.send_mail.set');?></a></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="send_mail.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td nowrap width="5%"><?=get_lang('set.send_mail.module');?>:</td>
		<td width="95%">
			<input name="Module" <?=$mCfg['SendMail']['Module']==0?'checked':'';?> type="radio" value="0" onclick="change_module(this.value);"><?=get_lang('set.send_mail.default');?>
			<input name="Module" <?=$mCfg['SendMail']['Module']==1?'checked':'';?> type="radio" value="1" onclick="change_module(this.value);"><?=get_lang('set.send_mail.custom_set');?>
		</td>
	</tr>
	<tr> 
		<td nowrap><?=get_lang('set.send_mail.from_email');?>:</td>
		<td><input name="FromEmail" type="text" value="<?=htmlspecialchars($mCfg['SendMail']['FromEmail']);?>" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('set.send_mail.from_email');?>!~*"></td>
	</tr>
	<tr> 
		<td nowrap><?=get_lang('set.send_mail.from_name');?>:</td>
		<td><input name="FromName" type="text" value="<?=htmlspecialchars($mCfg['SendMail']['FromName']);?>" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('set.send_mail.from_name');?>!~*"></td>
	</tr>
	<tr id="send_mail_input_0"> 
		<td nowrap><?=get_lang('set.send_mail.smtp');?>:</td>
		<td><input name="Smtp" type="text" value="<?=htmlspecialchars($mCfg['SendMail']['Smtp']);?>" class="form_input" size="50" maxlength="100"></td>
	</tr>
	<tr id="send_mail_input_1"> 
		<td nowrap><?=get_lang('set.send_mail.port');?>:</td>
		<td><input name="Port" type="text" value="<?=htmlspecialchars($mCfg['SendMail']['Port']);?>" class="form_input" size="5" maxlength="5"></td>
	</tr>
	<tr id="send_mail_input_2"> 
		<td nowrap><?=get_lang('set.send_mail.email');?>:</td>
		<td><input name="Email" type="text" value="<?=htmlspecialchars($mCfg['SendMail']['Email']);?>" class="form_input" size="30" maxlength="100"></td>
	</tr>
	<tr id="send_mail_input_3"> 
		<td nowrap><?=get_lang('set.send_mail.password');?>:</td>
		<td><input name="Password" type="text" value="<?=htmlspecialchars($mCfg['SendMail']['Password']);?>" class="form_input" size="30" maxlength="100"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"></td>
	</tr>
</table>
</form>
<script language="javascript">
function change_module(v){
	if(v==0){
		$_('send_mail_input_0').style.display=$_('send_mail_input_1').style.display=$_('send_mail_input_2').style.display=$_('send_mail_input_3').style.display='none';
	}else{
		$_('send_mail_input_0').style.display=$_('send_mail_input_1').style.display=$_('send_mail_input_2').style.display=$_('send_mail_input_3').style.display='';
	}
}
change_module(<?=(int)$mCfg['SendMail']['Module'];?>);
</script>
<?php include('../../inc/manage/footer.php');?>