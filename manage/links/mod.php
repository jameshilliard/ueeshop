<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('links', 'links.mod');

if($_GET['action']=='delimg'){
	$LId=(int)$_POST['LId'];
	$LogoPath=$_GET['LogoPath'];
	
	del_file($LogoPath);
	del_file(str_replace('s_', '', $LogoPath));
	
	$db->update('links', "LId='$LId'", array(
			'LogoPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$LId=(int)$_POST['LId'];
	$query_string=$_POST['query_string'];
	$Name=$_POST['Name'];
	$Url=$_POST['Url'];
	$Language=count(get_cfg('ly200.lang_array'))?$_POST['Language']:get_cfg('ly200.lang_array.0');
	
	if(get_cfg('links.upload_logo')){
		$S_LogoPath=$_POST['S_LogoPath'];
		$save_dir=get_cfg('ly200.up_file_base_dir').'links/'.date('y_m_d/', $service_time);
		
		if($BigLogoPath=up_file($_FILES['LogoPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallLogoPath=img_resize($BigLogoPath, '', get_cfg('links.logo_width'), get_cfg('links.logo_height'));
			del_file($S_LogoPath);
			del_file(str_replace('s_', '', $S_LogoPath));
		}else{
			$SmallLogoPath=$S_LogoPath;
		}
	}
	
	$db->update('links', "LId='$LId'", array(
			'Name'		=>	$Name,
			'Url'		=>	$Url,
			'LogoPath'	=>	$SmallLogoPath,
			'Language'	=>	$Language
		)
	);
	
	save_manage_log('编辑友情链接:'.$Name);
	
	header("Location: index.php?$query_string");
	exit;
}

$LId=(int)$_GET['LId'];
$query_string=query_string('LId');

$links_row=$db->get_one('links', "LId='$LId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('links.links_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.name');?>:</td>
		<td width="95%"><input name="Name" type="text" value="<?=htmlspecialchars($links_row['Name']);?>" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.name');?>!~*"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('links.url');?>:</td>
		<td><input name="Url" type="text" value="<?=htmlspecialchars($links_row['Url']);?>" class="form_input" size='60' maxlength="100"></td>
	</tr>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select($links_row['Language']);?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('links.upload_logo')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.logo');?>:</td>
			<td>
				<input name="LogoPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$links_row['LogoPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $links_row['LogoPath']);?>" target="_blank"><img src="<?=$links_row['LogoPath'];?>" <?=img_width_height(70, 70, $links_row['LogoPath']);?> /></a><input type='hidden' name='S_LogoPath' value='<?=$links_row['LogoPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="mod.php?action=delimg&LId=<?=$LId;?>&LogoPath=<?=$links_row['LogoPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"><a href="index.php?<?=$query_string;?>" class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="query_string" value="<?=$query_string;?>"><input type="hidden" name="LId" value="<?=$LId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>