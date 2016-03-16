<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('download', 'download.add');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'download/'.date('y_m_d/', $service_time);
	$Name=$_POST['Name'];
	$FilePathExt=$_POST['FilePathExt'];
	if($FilePath=up_file($_FILES['FilePath'], $save_dir)){
		$FileName=basename($_FILES['FilePath']['name']);
	}else{
		$FilePath=$FilePathExt;
		$FileName=basename($FilePathExt);
	}
	$CateId=$db->get_row_count('download_category')>1?(int)$_POST['CateId']:$db->get_value('download_category', 1, 'CateId');
	$Language=(int)$_POST['Language'];
	$BriefDescription=$_POST['BriefDescription'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	
	if(get_cfg('download.upload_pic')){
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('download.pic_width'), get_cfg('download.pic_height'));
			if(get_cfg('ly200.img_add_watermark')){
				include('../../inc/fun/img_add_watermark.php');
				img_add_watermark($BigPicPath);
			}
		}
	}
	
	$db->insert('download', array(
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
	
	$DId=$db->get_insert_id();
	get_cfg('download.description') && $Description=save_remote_img($_POST['Description'], $save_dir);
	$db->insert('download_description', array(
			'DId'			=>	$DId,
			'Description'	=>	$Description
		)
	);
	
	set_page_url('download', "DId='$DId'", get_cfg('download.page_url'), 1);
	
	save_manage_log('添加下载文件:'.$Name);
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('download.download_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.name');?>:</td>
		<td width="95%"><input name="Name" type="text" value="" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.name');?>!~*"></td>
	</tr>
	<?php if($db->get_row_count('download_category')>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.category');?>:</td>
			<td><?=ouput_Category_to_Select('CateId', '', 'download_category', 'UId="0,"', 1, get_lang('ly200.select'));?></td>
		</tr>
	<?php }?>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select();?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('download.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td><input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"></td>
		</tr>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('download.file');?>:</td>
		<td>
			<?=get_lang('download.upload_file');?>:<input name="FilePath" class="form_input" type="file" size="50" contenteditable="false"><br />
			<?=get_lang('download.file_path');?>:<input name="FilePathExt" class="form_input" type="text" value="" size="50" maxlength="200">
		</td>
	</tr>
	<?php if(get_cfg('download.brief_description')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.brief_description');?>:</td>
			<td><textarea name="BriefDescription" rows="5" cols="60" class="form_area"></textarea></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('download.seo_tkd')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.seo.seo');?>:</td>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
					<td width="95%"><input name="SeoTitle" type="text" value="" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
					<td><input name="SeoKeywords" type="text" value="" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
					<td><input name="SeoDescription" type="text" value="" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				</table>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('download.description')){?>
		<tr>
			<td nowrap ><?=get_lang('ly200.contents');?>:</td>
			<td class="ck_editor"><textarea class="ckeditor" name="Description"></textarea></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.add');?>" name="submit" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>