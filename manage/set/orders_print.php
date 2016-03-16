<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('orders_print_set');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'orders_print/';
	if($Path=up_file($_FILES['LogoPath'], $save_dir)){
		$LogoPath=$save_dir.'logo.'.get_ext_name($Path);
		del_file($LogoPath);
		@rename($site_root_path.$Path, $site_root_path.$LogoPath);
	}else{
		$LogoPath=$mCfg['OrdersPrint']['LogoPath'];
	}
	
	$Company=format_post_value($_POST['Company'], 1);
	$Address=format_post_value($_POST['Address'], 1);
	$Phone=format_post_value($_POST['Phone'], 1);
	$Fax=format_post_value($_POST['Fax'], 1);
	
	$php_contents=str_replace("\t", '', "<?php
	//订单打印设置
	\$mCfg['OrdersPrint']['LogoPath']='$LogoPath';
	\$mCfg['OrdersPrint']['Company']='$Company';
	\$mCfg['OrdersPrint']['Address']='$Address';
	\$mCfg['OrdersPrint']['Phone']='$Phone';
	\$mCfg['OrdersPrint']['Fax']='$Fax';
	?>");
	
	write_file('/inc/set/', 'orders_print.php', $php_contents);
	
	save_manage_log('订单打印设置');
	
	header('Location: orders_print.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="orders_print.php"><?=get_lang('set.orders_print.set');?></a></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="orders_print.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('ly200.logo');?>:</td>
		<td width="95%">
			<input name="LogoPath" type="file" size="50" class="form_input" contenteditable="false"><br>
			<?=get_lang('ly200.preview');?>:<br><img src="<?=$mCfg['OrdersPrint']['LogoPath'];?>" />
		</td>
	</tr>
	<tr> 
		<td nowrap><?=get_lang('set.orders_print.company');?>:</td>
		<td><input name="Company" type="text" value="<?=htmlspecialchars($mCfg['OrdersPrint']['Company']);?>" class="form_input" size="50" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('set.orders_print.company');?>!~*"></td>
	</tr>
	<tr> 
		<td nowrap><?=get_lang('set.orders_print.address');?>:</td>
		<td><input name="Address" type="text" value="<?=htmlspecialchars($mCfg['OrdersPrint']['Address']);?>" class="form_input" size="50" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('set.orders_print.address');?>!~*"></td>
	</tr>
	<tr> 
		<td nowrap><?=get_lang('set.orders_print.phone');?>:</td>
		<td><input name="Phone" type="text" value="<?=htmlspecialchars($mCfg['OrdersPrint']['Phone']);?>" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('set.orders_print.phone');?>!~*"></td>
	</tr>
	<tr> 
		<td nowrap><?=get_lang('set.orders_print.fax');?>:</td>
		<td><input name="Fax" type="text" value="<?=htmlspecialchars($mCfg['OrdersPrint']['Fax']);?>" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('set.orders_print.fax');?>!~*"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>