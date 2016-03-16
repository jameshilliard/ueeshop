<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('links', 'links.add');

if($_POST){
	$Name=$_POST['Name'];
	$Url=$_POST['Url'];
	$Language=count(get_cfg('ly200.lang_array'))?$_POST['Language']:get_cfg('ly200.lang_array.0');
	
	if(get_cfg('links.upload_logo')){
		$save_dir=get_cfg('ly200.up_file_base_dir').'links/'.date('y_m_d/', $service_time);
		if($BigLogoPath=up_file($_FILES['LogoPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallLogoPath=img_resize($BigLogoPath, '', get_cfg('links.logo_width'), get_cfg('links.logo_height'));
		}
	}
	
	$db->insert('links', array(
			'Name'		=>	$Name,
			'Url'		=>	$Url,
			'LogoPath'	=>	$SmallLogoPath,
			'Language'	=>	$Language
		)
	);
	
	save_manage_log('添加友情链接:'.$Name);
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('links.links_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.name');?>:</td>
		<td width="95%"><input name="Name" type="text" value="" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.name');?>!~*"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('links.url');?>:</td>
		<td><input name="Url" type="text" value="" class="form_input" size='60' maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('links.url');?>!~*"></td>
	</tr>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select();?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('links.upload_logo')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.logo');?>:</td>
			<td><input name="LogoPath" type="file" size="50" class="form_input" contenteditable="false"></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.add');?>" name="submit" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>