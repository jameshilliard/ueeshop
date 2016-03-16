<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('instance', 'instance.add');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'instance/'.date('y_m_d/', $service_time);
	$Name=$_POST['Name'];
	$CateId=$db->get_row_count('instance_category')>1?(int)$_POST['CateId']:$db->get_value('instance_category', 1, 'CateId');
	$Language=(int)$_POST['Language'];
	$BriefDescription=$_POST['BriefDescription'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	$IsInIndex=(int)$_POST['IsInIndex'];
	$IsClassic=(int)$_POST['IsClassic'];
	
	$BigPicPath=$SmallPicPath=$PicAlt=array();	//$SmallPicPath存入数据库的
	if(get_cfg('instance.pic_count')){
		include('../../inc/fun/img_resize.php');
		for($i=0; $i<get_cfg('instance.pic_count'); $i++){
			$PicAlt[]=$_POST['Alt_'.$i];
			if($tmp_path=up_file($_FILES['PicPath_'.$i], $save_dir)){
				$BigPicPath[$i]=$SmallPicPath[$i]=$tmp_path;
				foreach(get_cfg('instance.pic_size') as $key=>$value){
					$w_h=@explode('X', $value);
					$filename="$key"=='default'?'':dirname($tmp_path).'/'.$value.'_'.basename($tmp_path);
					$path=img_resize($SmallPicPath[$i], $filename, (int)$w_h[0], (int)$w_h[1]);
					"$key"=='default' && $SmallPicPath[$i]=$path;
				}
			}
		}
		if(get_cfg('ly200.img_add_watermark')){
			include('../../inc/fun/img_add_watermark.php');
			foreach($BigPicPath as $value){
				img_add_watermark($value);
			}
		}
	}
	
	$db->insert('instance', array(
			'CateId'			=>	$CateId,
			'Name'				=>	$Name,
			'PicPath_0'			=>	$SmallPicPath[0],
			'PicPath_1'			=>	$SmallPicPath[1],
			'PicPath_2'			=>	$SmallPicPath[2],
			'PicPath_3'			=>	$SmallPicPath[3],
			'PicPath_4'			=>	$SmallPicPath[4],
			'Alt_0'				=>	$PicAlt[0],
			'Alt_1'				=>	$PicAlt[1],
			'Alt_2'				=>	$PicAlt[2],
			'Alt_3'				=>	$PicAlt[3],
			'Alt_4'				=>	$PicAlt[4],
			'BriefDescription'	=>	$BriefDescription,
			'SeoTitle'			=>	$SeoTitle,
			'SeoKeywords'		=>	$SeoKeywords,
			'SeoDescription'	=>	$SeoDescription,
			'IsInIndex'			=>	$IsInIndex,
			'IsClassic'			=>	$IsClassic,
			'UpdateTime'		=>	$service_time,
			'Language'			=>	$Language
		)
	);
	
	$CaseId=$db->get_insert_id();
	get_cfg('instance.description') && $Description=save_remote_img($_POST['Description'], $save_dir);
	$db->insert('instance_description', array(
			'CaseId'		=>	$CaseId,
			'Description'	=>	$Description
		)
	);
	
	//保存另外的语言版本的数据
	if(get_cfg('instance.add_mode')){
		if(count(get_cfg('ly200.lang_array'))>1){
			add_lang_field('instance', array('Name', 'BriefDescription', 'SeoTitle', 'SeoKeywords', 'SeoDescription'));
			add_lang_field('instance_description', 'Description');
			
			for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
				$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
				$NameExt=$_POST['Name'.$field_ext];
				$BriefDescriptionExt=$_POST['BriefDescription'.$field_ext];
				$SeoTitleExt=$_POST['SeoTitle'.$field_ext];
				$SeoKeywordsExt=$_POST['SeoKeywords'.$field_ext];
				$SeoDescriptionExt=$_POST['SeoDescription'.$field_ext];
				$db->update('instance', "CaseId='$CaseId'", array(
						'Name'.$field_ext				=>	$NameExt,
						'BriefDescription'.$field_ext	=>	$BriefDescriptionExt,
						'SeoTitle'.$field_ext			=>	$SeoTitleExt,
						'SeoKeywords'.$field_ext		=>	$SeoKeywordsExt,
						'SeoDescription'.$field_ext		=>	$SeoDescriptionExt
					)
				);
				
				if(get_cfg('instance.description')){
					$DescriptionExt=save_remote_img($_POST['Description'.$field_ext], $save_dir);
					$db->update('instance_description', "CaseId='$CaseId'", array(
							'Description'.$field_ext	=>	$DescriptionExt
						)
					);
				}
			}
		}
	}
	
	set_page_url('instance', "CaseId='$CaseId'", get_cfg('instance.page_url'), 1);
	
	save_manage_log('添加成功案例:'.$Name);
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('instance.instance_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php if(get_cfg('instance.add_mode')){?>
		<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
			<tr> 
				<td width="5%" nowrap><?=get_lang('ly200.name').lang_name($i, 0);?>:</td>
				<td width="95%"><input name="Name<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.name');?>!~*"></td>
			</tr>
		<?php }?>
	<?php }else{?>
		<tr> 
			<td width="5%" nowrap><?=get_lang('ly200.name');?>:</td>
			<td width="95%"><input name="Name" type="text" value="" class="form_input" size="30" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.name');?>!~*"></td>
		</tr>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?>
			<tr>
				<td nowrap><?=get_lang('ly200.language');?>:</td>
				<td><?=output_language_select();?></td>
			</tr>
		<?php }?>
	<?php }?>
	<?php if($db->get_row_count('instance_category')>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.category');?>:</td>
			<td><?=ouput_Category_to_Select('CateId', '', 'instance_category', 'UId="0,"', 1, get_lang('ly200.select'));?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.pic_count')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<?php for($i=0; $i<get_cfg('instance.pic_count'); $i++){?>
					<?=get_cfg('instance.pic_count')>1?($i+1).'. ':'';?><input name="PicPath_<?=$i;?>" type="file" size="50" class="form_input" contenteditable="false" <?php if($i==0){?>check="<?=get_lang('ly200.correct_upload').get_lang('ly200.photo');?>!~*"<?php }?>><?php if(get_cfg('instance.pic_alt')){?>&nbsp;&nbsp;<?=get_lang('ly200.alt');?>:<input name="Alt_<?=$i;?>" class="form_input" type="text" value="" size="25" maxlength="100"><?php }?><br>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.brief_description')){?>
		<?php if(get_cfg('instance.add_mode')){?>
			<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
				<tr>
					<td nowrap><?=get_lang('ly200.brief_description').lang_name($i, 0);?>:</td>
					<td><textarea name="BriefDescription<?=lang_name($i, 1);?>" class="form_area" rows="5" cols="60"></textarea></td>
				</tr>
			<?php }?>
		<?php }else{?>
			<tr>
				<td nowrap><?=get_lang('ly200.brief_description');?>:</td>
				<td><textarea name="BriefDescription" class="form_area" rows="5" cols="60"></textarea></td>
			</tr>
		<?php }?>
	<?php }?>
	<?php if(get_cfg('instance.category.seo_tkd')){?>
		<?php if(get_cfg('instance.add_mode')){?>
			<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
				<tr>
					<td nowrap><?=get_lang('ly200.seo.seo').lang_name($i, 0);?>:</td>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
							<td width="95%"><input name="SeoTitle<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="70" maxlength="200"></td>
						  </tr>
						  <tr>
							<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
							<td><input name="SeoKeywords<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="70" maxlength="200"></td>
						  </tr>
						  <tr>
							<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
							<td><input name="SeoDescription<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="70" maxlength="200"></td>
						  </tr>
						</table>
					</td>
				</tr>
			<?php }?>
		<?php }else{?>
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
	<?php }?>
	<?php if(get_cfg('instance.is_in_index') || get_cfg('instance.is_classic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.other_property');?>:</td>
			<td>
				<?php if(get_cfg('instance.is_in_index')){?><input name="IsInIndex" type="checkbox" value="1"><?=get_lang('ly200.is_in_index');?><?php }?>
				<?php if(get_cfg('instance.is_classic')){?><input name="IsClassic" type="checkbox" value="1"><?=get_lang('instance.is_classic');?><?php }?>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.description')){?>
		<?php if(get_cfg('instance.add_mode')){?>
			<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
				<tr>
					<td nowrap><?=get_lang('ly200.description').lang_name($i, 0);?>:</td>
					<td class="ck_editor"><textarea class="ckeditor" name="Description<?=lang_name($i, 1);?>"></textarea></td>
				</tr>
			<?php }?>
		<?php }else{?>
			<tr>
				<td nowrap><?=get_lang('ly200.description');?>:</td>
				<td class="ck_editor"><textarea class="ckeditor" name="Description"></textarea></td>
			</tr>
		<?php }?>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.add');?>" name="submit" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>