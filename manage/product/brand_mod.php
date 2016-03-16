<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_brand', 'product.brand.mod');

if($_GET['action']=='delimg'){
	$BId=(int)$_POST['BId'];
	$LogoPath=$_GET['LogoPath'];
	
	del_file($LogoPath);
	del_file(str_replace('s_', '', $LogoPath));
	
	$db->update('product_brand', "BId='$BId'", array(
			'LogoPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'product/brand/'.date('y_m_d/', $service_time);
	$BId=(int)$_POST['BId'];	
	$Brand=$_POST['Brand'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	
	if(get_cfg('product.brand.upload_logo')){
		$S_LogoPath=$_POST['S_LogoPath'];
		if($BigLogoPath=up_file($_FILES['LogoPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallLogoPath=img_resize($BigLogoPath, '', get_cfg('product.brand.logo_width'), get_cfg('product.brand.logo_height'));
			del_file($S_LogoPath);
			del_file(str_replace('s_', '', $S_LogoPath));
		}else{
			$SmallLogoPath=$S_LogoPath;
		}
	}
	
	$db->update('product_brand', "BId='$BId'", array(
			'Brand'			=>	$Brand,
			'LogoPath'		=>	$SmallLogoPath,
			'SeoTitle'		=>	$SeoTitle,
			'SeoKeywords'	=>	$SeoKeywords,
			'SeoDescription'=>	$SeoDescription
		)
	);
	
	if(get_cfg('product.brand.description')){
		$Description=save_remote_img($_POST['Description'], $save_dir);
		
		$db->update('product_brand_description', "BId='$BId'", array(
				'Description'	=>	$Description
			)
		);
	}
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('product_brand', array('Brand', 'SeoTitle', 'SeoKeywords', 'SeoDescription'));
		add_lang_field('product_brand_description', 'Description');
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$BrandExt=$_POST['Brand'.$field_ext];
			$SeoTitleExt=$_POST['SeoTitle'.$field_ext];
			$SeoKeywordsExt=$_POST['SeoKeywords'.$field_ext];
			$SeoDescriptionExt=$_POST['SeoDescription'.$field_ext];
			$db->update('product_brand', "BId='$BId'", array(
					'Brand'.$field_ext			=>	$BrandExt,
					'SeoTitle'.$field_ext		=>	$SeoTitleExt,
					'SeoKeywords'.$field_ext	=>	$SeoKeywordsExt,
					'SeoDescription'.$field_ext	=>	$SeoDescriptionExt
				)
			);
			
			if(get_cfg('product.brand.description')){
				$DescriptionExt=save_remote_img($_POST['Description'.$field_ext], $save_dir);
				$db->update('product_brand_description', "BId='$BId'", array(
						'Description'.$field_ext	=>	$DescriptionExt
					)
				);
			}
		}
	}
	
	set_page_url('product_brand', "BId='$BId'", get_cfg('product.brand.page_url'), 2, 0);
	
	save_manage_log('更新产品品牌:'.$Brand);
	
	header('Location: brand.php');
	exit;
}

$BId=(int)$_GET['BId'];
$brand_row=$db->get_one('product_brand', "BId='$BId'");
$brand_description_row=$db->get_one('product_brand_description', "BId='$BId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="brand.php"><?=get_lang('product.brand_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="brand_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('product.brand').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Brand<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($brand_row['Brand'.lang_name($i, 1)]);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('ly200.brand_name');?>!~*"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('product.brand.upload_logo')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="LogoPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$brand_row['LogoPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $brand_row['LogoPath']);?>" target="_blank"><img src="<?=$brand_row['LogoPath'];?>" <?=img_width_height(70, 70, $brand_row['LogoPath']);?> /></a><input type='hidden' name='S_LogoPath' value='<?=$brand_row['LogoPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="brand_mod.php?action=delimg&BId=<?=$BId;?>&LogoPath=<?=$brand_row['LogoPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('product.brand.seo_tkd')){?>
		<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
			<tr>
				<td nowrap><?=get_lang('ly200.seo.seo').lang_name($i, 0);?>:</td>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
						<td width="95%"><input name="SeoTitle<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($brand_row['SeoTitle'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					  <tr>
						<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
						<td><input name="SeoKeywords<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($brand_row['SeoKeywords'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					  <tr>
						<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
						<td><input name="SeoDescription<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($brand_row['SeoDescription'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					</table>
				</td>
			</tr>
		<?php }?>
	<?php }?>
	<?php if(get_cfg('product.brand.description')){?>
		<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
			<tr>
				<td nowrap><?=get_lang('ly200.description').lang_name($i, 0);?>:</td>
				<td class="ck_editor"><textarea class="ckeditor" name="Description<?=lang_name($i, 1);?>"><?=htmlspecialchars($brand_description_row['Description'.lang_name($i, 1)]);?></textarea></td>
			</tr>
		<?php }?>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='brand.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="BId" value="<?=$BId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>