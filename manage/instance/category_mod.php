<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('instance_category', 'instance.category.mod');

if($_GET['action']=='delimg'){
	$CateId=(int)$_POST['CateId'];
	$PicPath=$_GET['PicPath'];
	
	del_file($PicPath);
	del_file(str_replace('s_', '', $PicPath));
	
	$db->update('instance_category', "CateId='$CateId'", array(
			'PicPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'instance/'.date('y_m_d/', $service_time);
	$CateId=(int)$_POST['CateId'];
	$UnderTheCateId=(int)$_POST['UnderTheCateId'];
	$Category=$_POST['Category'];
	!$Category && $Category=$_POST['S_Category'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	
	if(get_cfg('instance.category.upload_pic')){
		$S_PicPath=$_POST['S_PicPath'];
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('instance.category.pic_width'), get_cfg('instance.category.pic_height'));
			del_file($S_PicPath);
			del_file(str_replace('s_', '', $S_PicPath));
		}else{
			$SmallPicPath=$S_PicPath;
		}
	}
	
	if($UnderTheCateId==0){
		$UId='0,';
		$Dept=1;
	}else{
		$UId=get_UId_by_CateId($UnderTheCateId, 'instance_category');
		$Dept=substr_count($UId, ',');
	}
	
	$SubUId=get_UId_by_CateId($CateId, 'instance_category');	//下级类别的UId
	$CurUId=$db->get_value('instance_category', "CateId='$CateId'", 'UId');
	$db->query("update instance_category set UId=replace(UId, '$CurUId', '$UId') where UId like '{$SubUId}%'");
	
	$db->update('instance_category', "CateId='$CateId'", array(
			'UId'			=>	$UId,
			'Category'		=>	$Category,
			'PicPath'		=>	$SmallPicPath,
			'SeoTitle'		=>	$SeoTitle,
			'SeoKeywords'	=>	$SeoKeywords,
			'SeoDescription'=>	$SeoDescription,
			'Dept'			=>	$Dept
		)
	);
	
	if(get_cfg('instance.category.description')){
		$Description=save_remote_img($_POST['Description'], $save_dir);
		
		$db->update('instance_category_description', "CateId='$CateId'", array(
				'Description'	=>	$Description
			)
		);
	}
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('instance_category', array('Category', 'SeoTitle', 'SeoKeywords', 'SeoDescription'));
		add_lang_field('instance_category_description', 'Description');
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$CategoryExt=$_POST['Category'.$field_ext];
			!$CategoryExt && $CategoryExt=$_POST['S_Category'.$field_ext];
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
	
	save_manage_log('更新成功案例类别:'.$Category);
	
	header('Location: category.php');
	exit;
}

$CateId=(int)$_GET['CateId'];
$category_row=$db->get_one('instance_category', "CateId='$CateId'");
$category_description_row=$db->get_one('instance_category_description', "CateId='$CateId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="category.php"><?=get_lang('instance.category_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="category_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('ly200.category_name').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Category<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($category_row['Category'.lang_name($i, 1)]);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('ly200.category_name');?>!~*" <?=get_cfg('instance.category.mod_name')?'':'disabled';?>><input type="hidden" name="S_Category<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($category_row['Category'.lang_name($i, 1)]);?>" /></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('instance.category.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$category_row['PicPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $category_row['PicPath']);?>" target="_blank"><img src="<?=$category_row['PicPath'];?>" <?=img_width_height(70, 70, $category_row['PicPath']);?> /></a><input type='hidden' name='S_PicPath' value='<?=$category_row['PicPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="category_mod.php?action=delimg&CateId=<?=$CateId;?>&PicPath=<?=$category_row['PicPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<?php
	if(get_cfg('instance.category.dept')>1){
		$o_dept=$category_row['Dept']-($db->get_max('instance_category', "UId like '{$category_row['UId']}{$category_row['CateId']},%'", 'Dept')-get_cfg('instance.category.dept'));	//剩余可用的级数
		$e_where="CateId!='{$category_row['CateId']}' and Dept<".($category_row['SubCate']?$o_dept:get_cfg('instance.category.dept'));
	?>
		<tr>
			<td nowrap><?=get_lang('ly200.under_the');?>:</td>
			<td><?=ouput_Category_to_Select('UnderTheCateId', get_CateId_by_UId($category_row['UId']), 'instance_category', "UId='0,' and $e_where", $e_where, get_lang('ly200.select'));?></td>
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
						<td width="95%"><input name="SeoTitle<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($category_row['SeoTitle'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					  <tr>
						<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
						<td><input name="SeoKeywords<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($category_row['SeoKeywords'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					  <tr>
						<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
						<td><input name="SeoDescription<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($category_row['SeoDescription'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
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
				<td class="ck_editor"><textarea class="ckeditor" name="Description<?=lang_name($i, 1);?>"><?=htmlspecialchars($category_description_row['Description'.lang_name($i, 1)]);?></textarea></td>
			</tr>
		<?php }?>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='category.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="CateId" value="<?=$CateId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>