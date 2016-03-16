<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('payment_method', 'payment_method.mod');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'payment_method/'.date('y_m_d/', $service_time);
	$PId=(int)$_POST['PId'];
	$ExtVar=$_POST['ExtVar'];
	$IsInvocation=(int)$_POST['IsInvocation'];
	$AdditionalFee=(float)$_POST['AdditionalFee'];
	$Description=save_remote_img($_POST['Description'], $save_dir);
	$S_LogoPath=$_POST['S_LogoPath'];
	
	if($BigLogoPath=up_file($_FILES['LogoPath'], $save_dir)){
		include('../../inc/fun/img_resize.php');
		$SmallLogoPath=img_resize($BigLogoPath, '', get_cfg('payment_method.logo_width'), get_cfg('payment_method.logo_height'));
		del_file($S_LogoPath);
		del_file(str_replace('s_', '', $S_LogoPath));
	}else{
		$SmallLogoPath=$S_LogoPath;
	}
	
	$db->update('payment_method', "PId='$PId'", array(
			'LogoPath'		=>	$SmallLogoPath,
			'AdditionalFee'	=>	$AdditionalFee,
			'Description'	=>	$Description,
			'IsInvocation'	=>	$IsInvocation
		)
	);
	
	if($ExtVar){
		$ext_var=@explode('|', $ExtVar);
		for($i=0; $i<count($ext_var); $i++){
			$db->update('payment_method', "PId='$PId'", array(
					$ext_var[$i]	=>	$_POST[$ext_var[$i]]
				)
			);
		}
	}
	
	save_manage_log('编辑付款方式');
	
	header("Location: index.php?$query_string");
	exit;
}

$PId=(int)$_GET['PId'];
$payment_method_row=$db->get_one('payment_method', "PId='$PId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('payment_method.payment_method_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.name');?>:</td>
		<td width="95%"><?=$payment_method_row['Name'];?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.invocation');?>:</td>
		<td><input type="checkbox" name="IsInvocation" value="1" <?=$payment_method_row['IsInvocation']==1?'checked':'';?> /></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('payment_method.additional_fee');?>:</td>
		<td><input type="text" name="AdditionalFee" size="5" maxlength="10" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="<?=htmlspecialchars($payment_method_row['AdditionalFee']);?>" class="form_input" />%</td>
	</tr>
	<?php
	if($payment_method_row['ExtVar']){
		$ext_var=@explode('|', $payment_method_row['ExtVar']);
		for($i=0; $i<count($ext_var); $i++){
	?>
		<tr>
			<td nowrap><?=get_lang('payment_method.'.strtolower($ext_var[$i]));?>:</td>
			<td><input name="<?=$ext_var[$i];?>" type="text" size="35" maxlength="100" value="<?=$payment_method_row[$ext_var[$i]];?>" class="form_input" /></td>
		</tr>
		<?php }?>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('ly200.logo');?>:</td>
		<td>
			<input name="LogoPath" type="file" size="50" class="form_input" contenteditable="false"><br>
			<?=get_lang('ly200.preview');?>:<br><?=creat_imgLink_by_sImg($payment_method_row['LogoPath']);?><input type='hidden' name='S_LogoPath' value='<?=$payment_method_row['LogoPath'];?>'>
		</td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.description');?>:</td>
		<td class="ck_editor"><textarea class="ckeditor" name="Description"><?=htmlspecialchars($payment_method_row['Description']);?></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"><a href="index.php" class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="PId" value="<?=$PId;?>"><input type="hidden" name="ExtVar" value="<?=htmlspecialchars($payment_method_row['ExtVar']);?>" /></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>