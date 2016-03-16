<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('download', 'download.mod');

if($_GET['action']=='delimg'){
	$DId=(int)$_POST['DId'];
	$PicPath=$_GET['PicPath'];
	
	del_file($PicPath);
	del_file(str_replace('s_', '', $PicPath));
	
	$db->update('download', "DId='$DId'", array(
			'PicPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'download/'.date('y_m_d/', $service_time);
	$DId=(int)$_POST['DId'];
	$query_string=$_POST['query_string'];
	$Name=$_POST['Name'];
	$FilePathExt=$_POST['FilePathExt'];
	$S_FilePath=$_POST['S_FilePath'];
	$S_FileName=$_POST['S_FileName'];
	if($FilePath=up_file($_FILES['FilePath'], $save_dir)){
		$FileName=basename($_FILES['FilePath']['name']);
		del_file($S_FilePath);
	}else{
		$FilePath=$FilePathExt;
		$FileName=$S_FileName;
		$S_FilePath!=$FilePath && $FileName=basename($FilePath);
	}
	$CateId=$db->get_row_count('download_category')>1?(int)$_POST['CateId']:$db->get_value('download_category', 1, 'CateId');
	$Language=(int)$_POST['Language'];
	$BriefDescription=$_POST['BriefDescription'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	
	if(get_cfg('download.upload_pic')){
		$S_PicPath=$_POST['S_PicPath'];
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('download.pic_width'), get_cfg('download.pic_height'));
			if(get_cfg('ly200.img_add_watermark')){
				include('../../inc/fun/img_add_watermark.php');
				img_add_watermark($BigPicPath);
			}
			del_file($S_PicPath);
			del_file(str_replace('s_', '', $S_PicPath));
		}else{
			$SmallPicPath=$S_PicPath;
		}
	}
	
	$db->update('download', "DId='$DId'", array(
			'CateId'			=>	$CateId,
			'Name'				=>	$Name,
			'PicPath'			=>	$SmallPicPath,
			'FilePath'			=>	$FilePath,
			'FileName'			=>	$FileName,
			'BriefDescription'	=>	$BriefDescription,
			'SeoTitle'			=>	$SeoTitle,
			'SeoKeywords'		=>	$SeoKeywords,
			'SeoDescription'	=>	$SeoDescription,
			'Language'			=>	$Language
		)
	);
	
	if(get_cfg('download.description')){
		$Description=save_remote_img($_POST['Description'], $save_dir);
		$db->update('download_description', "DId='$DId'", array(
				'Description'	=>	$Description
			)
		);
	}
	
	set_page_url('download', "DId='$DId'", get_cfg('download.page_url'), 1);
	
	save_manage_log('编辑下载文件:'.$Name);
	
	header("Location: index.php?$query_string");
	exit;
}

$DId=(int)$_GET['DId'];
$query_string=query_string('DId');

$download_row=$db->get_one('download', "DId='$DId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('download.download_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.name');?>:</td>
		<td width="95%"><input name="Name" type="text" value="<?=htmlspecialchars($download_row['Name'])?>" class="form_input" size="50" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.name');?>!~*"></td>
	</tr>
	<?php if($db->get_row_count('download_category')>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.category');?>:</td>
			<td><?=ouput_Category_to_Select('CateId', $download_row['CateId'], 'download_category', 'UId="0,"', 1, get_lang('ly200.select'));?></td>
		</tr>
	<?php }?>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select($download_row['Language']);?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('download.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$download_row['PicPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $download_row['PicPath']);?>" target="_blank"><img src="<?=$download_row['PicPath'];?>" <?=img_width_height(70, 70, $download_row['PicPath']);?> /></a><input type='hidden' name='S_PicPath' value='<?=$download_row['PicPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="mod.php?action=delimg&DId=<?=$DId;?>&PicPath=<?=$download_row['PicPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('download.file');?>:</td>
		<td>
			<?=get_lang('download.upload_file');?>:<input name="FilePath" class="form_input" type="file" size="50" contenteditable="false"><br />
			<?=get_lang('download.file_path');?>:<input name="FilePathExt" class="form_input" type="text" value="<?=htmlspecialchars($download_row['FilePath']);?>" size="50" maxlength="200">
			<input type='hidden' name='S_FilePath' value='<?=htmlspecialchars($download_row['FilePath']);?>'>
			<input type='hidden' name='S_FileName' value='<?=htmlspecialchars($download_row['FileName']);?>'>
		</td>
	</tr>
	<?php if(get_cfg('download.brief_description')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.brief_description');?>:</td>
			<td><textarea name="BriefDescription" rows="5" cols="60" class="form_area"><?=htmlspecialchars($download_row['BriefDescription'])?></textarea></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('download.seo_tkd')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.seo.seo');?>:</td>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
					<td width="95%"><input name="SeoTitle" type="text" value="<?=htmlspecialchars($download_row['SeoTitle'])?>" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
					<td><input name="SeoKeywords" type="text" value="<?=htmlspecialchars($download_row['SeoKeywords'])?>" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
					<td><input name="SeoDescription" type="text" value="<?=htmlspecialchars($download_row['SeoDescription'])?>" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				</table>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('download.description')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.description');?>:</td>
			<td class="ck_editor"><textarea class="ckeditor" name="Description"><?=htmlspecialchars($db->get_value('download_description', "DId='$DId'", 'Description'));?></textarea></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"><a href="index.php?<?=$query_string;?>" class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="query_string" value="<?=$query_string;?>"><input type="hidden" name="DId" value="<?=$DId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>