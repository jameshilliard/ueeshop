<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('set');

if($_POST){
	$SeoTitle=format_post_value($_POST['SeoTitle']);
	$SeoKeywords=format_post_value($_POST['SeoKeywords']);
	$SeoDescription=format_post_value($_POST['SeoDescription']);
	$php_contents="\$mCfg['SeoTitle']='$SeoTitle';\r\n\$mCfg['SeoKeywords']='$SeoKeywords';\r\n\$mCfg['SeoDescription']='$SeoDescription';\r\n\r\n";
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$SeoTitleExt=format_post_value($_POST['SeoTitle'.$field_ext]);
			$SeoKeywordsExt=format_post_value($_POST['SeoKeywords'.$field_ext]);
			$SeoDescriptionExt=format_post_value($_POST['SeoDescription'.$field_ext]);
			$php_contents.="\$mCfg['SeoTitle{$field_ext}']='$SeoTitleExt';\r\n\$mCfg['SeoKeywords{$field_ext}']='$SeoKeywordsExt';\r\n\$mCfg['SeoDescription{$field_ext}']='$SeoDescriptionExt';\r\n\r\n";
		}
	}
	
	//----------------------------------------------------------------------------------------------------------------------------------------------------------
	
	$FlowStatisticsInvocation=(int)$_POST['FlowStatisticsInvocation'];
	$FlowStatisticsCode=format_post_value($_POST['FlowStatisticsCode'], 0);
	$php_contents.="\$mCfg['FlowStatisticsInvocation']=$FlowStatisticsInvocation;\r\n\$mCfg['FlowStatisticsCode']='$FlowStatisticsCode';\r\n\r\n";
	
	//----------------------------------------------------------------------------------------------------------------------------------------------------------
	
	$_53KFInvocation=(int)$_POST['53KFInvocation'];
	$_53KFCode=format_post_value($_POST['53KFCode'], 0);
	
	$php_contents.="\$mCfg['53KFInvocation']=$_53KFInvocation;\r\n\$mCfg['53KFCode']='$_53KFCode';\r\n\r\n";
	
	//----------------------------------------------------------------------------------------------------------------------------------------------------------
	
	write_file('/inc/set/', 'global.php', "<?php\r\n$php_contents?>");
	
	save_manage_log('系统全局设置');
	
	header('Location: global.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="global.php"><?=get_lang('set.global');?></a></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="global.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('ly200.seo.seo').lang_name($i, 0);?>:</td>
			<td width="95%">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
					<td width="95%"><input name="SeoTitle<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($mCfg['SeoTitle'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200" check="<?=get_lang('ly200.filled_out').get_lang('ly200.seo.title');?>!~*"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
					<td><input name="SeoKeywords<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($mCfg['SeoKeywords'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200" check="<?=get_lang('ly200.filled_out').get_lang('ly200.seo.keywords');?>!~*"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
					<td><input name="SeoDescription<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($mCfg['SeoDescription'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200" check="<?=get_lang('ly200.filled_out').get_lang('ly200.seo.description');?>!~*"></td>
				  </tr>
				</table>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('set.flow_statistics');?>:</td>
		<td><input type="checkbox" value="1" name="FlowStatisticsInvocation" <?=$mCfg['FlowStatisticsInvocation']==1?'checked':'';?> /><?=get_lang('ly200.invocation');?><br /><textarea name="FlowStatisticsCode" rows="8" cols="100" class="form_area"><?=htmlspecialchars($mCfg['FlowStatisticsCode']);?></textarea><?php if($mCfg['FlowStatisticsCode']){?><br /><?=$mCfg['FlowStatisticsCode'];?><?php }?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('set.53kf');?>:</td>
		<td><input type="checkbox" value="1" name="53KFInvocation" <?=$mCfg['53KFInvocation']==1?'checked':'';?> /><?=get_lang('ly200.invocation');?><br /><textarea name="53KFCode" rows="8" cols="100" class="form_area"><?=htmlspecialchars($mCfg['53KFCode']);?></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.submit');?>" name="submit" class="form_button"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>