<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('instance_category', 'instance.category.add');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'instance/'.date('y_m_d/', $service_time);
	$UnderTheCateId=(int)$_POST['UnderTheCateId'];
	$Category=$_POST['Category'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	
	if(get_cfg('instance.category.upload_pic')){
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('instance.category.pic_width'), get_cfg('instance.category.pic_height'));	
		}
	}
	
	if($UnderTheCateId==0){
		$UId='0,';
		$Dept=1;
	}else{
		$UId=get_UId_by_CateId($UnderTheCateId, 'instance_category');
		$Dept=substr_count($UId, ',');
	}
	
	$db->insert('instance_category', array(
			'UId'			=>	$UId,
			'Category'		=>	$Category,
			'PicPath'		=>	$SmallPicPath,
			'SeoTitle'		=>	$SeoTitle,
			'SeoKeywords'	=>	$SeoKeywords,
			'SeoDescription'=>	$SeoDescription,
			'Dept'			=>	$Dept
		)
	);
	
	$CateId=$db->get_insert_id();
	get_cfg('instance.category.description') && $Description=save_remote_img($_POST['Description'], $save_dir);
	$db->insert('instance_category_description', array(
			'CateId'		=>	$CateId,
			'Description'	=>	$Description
		)
	);
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('instance_category', array('Category', 'SeoTitle', 'SeoKeywords', 'SeoDescription'));
		add_lang_field('instance_category_description', 'Description');
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$CategoryExt=$_POST['Category'.$field_ext];
			$SeoTitleExt=$_POST['SeoTitle'.$field_ext];
			$SeoKeywordsExt=$_POST['SeoKeywords'.$field_ext];
			$SeoDescriptionExt=$_POST['SeoDescription'.$field_ext];
			$db->update('instance_category', "CateId='$CateId'", array(
					'Category'.$field_ext		=>	$CategoryExt,
					'SeoTitle'.$field_ext		=>	$SeoTitleExt,
					'SeoKeywords'.$field_ext	=>	$SeoKeywordsExt,
					'SeoDescription'.$field_ext	=>	$SeoDescriptionExt
				)
			);
			
			if(get_cfg('instance.category.description')){
				$DescriptionExt=save_remote_img($_POST['Description'.$field_ext], $save_dir);
				$db->update('instance_category_description', "CateId='$CateId'", array(
						'Description'.$field_ext	=>	$DescriptionExt
					)
				);
			}
		}
	}
	
	category_subcate_statistic('instance_category');
	set_page_url('instance_category', "CateId='$CateId'", get_cfg('instance.category.page_url'), 0);
	
	save_manage_log('添加成功案例类别:'.$Category);
	
	header('Location: category.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="category.php"><?=get_lang('instance.category_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="category_add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('ly200.category_name').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Category<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('ly200.category_name');?>!~*"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.category.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td><input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.category.dept')>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.under_the');?>:</td>
			<td><?=ouput_Category_to_Select('UnderTheCateId', '', 'instance_category', 'UId="0,"', 'Dept<'.get_cfg('instance.category.dept'), get_lang('ly200.select'));?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.category.seo_tkd')){?>
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
	<?php }?>
	<?php if(get_cfg('instance.category.description')){?>
		<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
			<tr>
				<td nowrap><?=get_lang('ly200.description').lang_name($i, 0);?>:</td>
				<td class="ck_editor"><textarea class="ckeditor" name="Description<?=lang_name($i, 1);?>"></textarea></td>
			</tr>
		<?php }?>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><a href='category.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>