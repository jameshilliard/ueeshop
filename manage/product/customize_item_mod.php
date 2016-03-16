<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

if($_POST)
{
	$Name=$_POST['Name'];
	$CId=$_POST['CId'];
	$IId=$_POST['IId'];
	
		$save_dir=get_cfg('ly200.up_file_base_dir').'product/customize/'.date('y_m_d/', $service_time);
		$S_PicPath=$_POST['S_PicPath'];
		
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('product.customize.pic_width'), get_cfg('product.customize.pic_height'));
			del_file($S_PicPath);
			del_file(str_replace('s_', '', $S_PicPath));
		}else{
			$SmallPicPath=$S_PicPath;
		}

	
	$db->update('product_customize_item', "IId='$IId'", array(
			'Name'		=>	$Name,
			'PicPath'	=>	$SmallPicPath
		)
	);
	save_manage_log('编辑定制项目:'.$Name);
	header('Location: customize_item_list.php?CId='.$CId);
	exit;
}


$IId=(int)$_GET['IId'];
$item_row=$db->get_one('product_customize_item', "IId='$IId'");
$CId=$item_row['CId'];
$row=$db->get_one('product_customize',"CId='$CId'");
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_item_list.php?CId=<?=$row['CId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
    <div class="float_right"><a href="customize_add.php?CateId=<?=$CateId?>"><?=get_lang('ly200.add');?></a></div>
</div>
<form method="post" name="act_form" id="act_form" class="act_form" action="customize_item_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
		<tr>
			<td width="5%" nowrap>名称:</td>
			<td width="95%"><input name="Name" type="text" value="<?=$item_row['Name']?>" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out');?>!~*"></td>
		</tr>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$item_row['PicPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $item_row['PicPath']);?>" target="_blank"><img src="<?=$item_row['PicPath'];?>" <?=img_width_height(70, 70, $item_row['PicPath']);?> /></a><input type='hidden' name='S_PicPath' value='<?=$item_row['PicPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="color_mod.php?action=delimg&CId=<?=$CId;?>&PicPath=<?=$item_row['PicPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><input type="hidden" name="IId" value="<?=$item_row['IId']?>" /><input type="hidden" name="CId" value="<?=$CId?>" /><a href='customize_item_list.php.php?CId=<?=$CId?>' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>