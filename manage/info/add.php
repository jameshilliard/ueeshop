<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('info', 'info.add');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'info/'.date('y_m_d/', $service_time);
	$Title=$_POST['Title'];
	$CateId=$db->get_row_count('info_category')>1?(int)$_POST['CateId']:$db->get_value('info_category', 1, 'CateId');
	$Language=(int)$_POST['Language'];
	$ExtUrl=$_POST['ExtUrl'];
	$Author=$_POST['Author'];
	$Provenance=$_POST['Provenance'];
	$Burden=$_POST['Burden'];
	$BriefDescription=$_POST['BriefDescription'];
	$IsInIndex=(int)$_POST['IsInIndex'];
	$IsHot=(int)$_POST['IsHot'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	$AccTime=@strtotime($_POST['AccTime']);
	$Contents=save_remote_img($_POST['Contents'], $save_dir);
	
	if(get_cfg('info.upload_pic')){
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('info.pic_width'), get_cfg('info.pic_height'));
			if(get_cfg('ly200.img_add_watermark')){
				include('../../inc/fun/img_add_watermark.php');
				img_add_watermark($BigPicPath);
			}
		}
	}
	
	$db->insert('info', array(
			'CateId'			=>	$CateId,
			'Title'				=>	$Title,
			'PicPath'			=>	$SmallPicPath,
			'ExtUrl'			=>	$ExtUrl,
			'Author'			=>	$Author,
			'Provenance'		=>	$Provenance,
			'Burden'			=>	$Burden,
			'BriefDescription'	=>	$BriefDescription,
			'IsInIndex'			=>	$IsInIndex,
			'IsHot'				=>	$IsHot,
			'SeoTitle'			=>	$SeoTitle,
			'SeoKeywords'		=>	$SeoKeywords,
			'SeoDescription'	=>	$SeoDescription,
			'AccTime'			=>	$AccTime,
			'Language'			=>	$Language
		)
	);
	
	$InfoId=$db->get_insert_id();
	$db->insert('info_contents', array(
			'InfoId'	=>	$InfoId,
			'Contents'	=>	$Contents
		)
	);
	
	set_page_url('info', "InfoId='$InfoId'", get_cfg('info.page_url'), 1);
	
	save_manage_log('添加文章:'.$Title);
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('info.info_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.title');?>:</td>
		<td width="95%"><input name="Title" type="text" value="" class="form_input" size="50" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.title');?>!~*"></td>
	</tr>
	<?php if($db->get_row_count('info_category')>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.category');?>:</td>
			<td><?=ouput_Category_to_Select('CateId', '', 'info_category', 'UId="0,"', 1, get_lang('ly200.select'));?></td>
		</tr>
	<?php }?>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select();?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td><input name="PicPath" type="file" class="form_input" size="50" contenteditable="false"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.ext_url')){?>
		<tr>
			<td nowrap><?=get_lang('info.ext_url');?>:</td>
			<td><input name="ExtUrl" type="text" value="" class="form_input" size='60' maxlength="100"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.author')){?>
		<tr>
			<td nowrap><?=get_lang('info.author');?>:</td>
			<td><input name="Author" type="text" value="" class="form_input" size="25" maxlength="50"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.provenance')){?>
		<tr>
			<td nowrap><?=get_lang('info.provenance');?>:</td>
			<td><input name="Provenance" type="text" value="" class="form_input" size="25" maxlength="50"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.burden')){?>
		<tr>
			<td nowrap><?=get_lang('info.burden');?>:</td>
			<td><input name="Burden" type="text" value="" class="form_input" size="25" maxlength="50"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.brief_description')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.brief_description');?>:</td>
			<td><textarea name="BriefDescription" rows="5" class="form_area" cols="60"></textarea></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.seo_tkd')){?>
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
	<?php if(get_cfg('info.acc_time')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.time');?>:</td>
			<td><input name="AccTime" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=date('Y-m-d', $service_time);?>" class="form_input" /></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.is_in_index') || get_cfg('info.is_hot')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.other_property');?>:</td>
			<td>
				<?php if(get_cfg('info.is_in_index')){?><input name="IsInIndex" type="checkbox" value="1"><?=get_lang('ly200.is_in_index');?><?php }?>
				<?php if(get_cfg('info.is_hot')){?><input name="IsHot" type="checkbox" value="1"><?=get_lang('info.is_hot');?><?php }?>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('ly200.contents');?>:</td>
		<td class="ck_editor"><textarea class="ckeditor" name="Contents"></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.add');?>" name="submit" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>