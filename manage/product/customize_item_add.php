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
	
	$save_dir=get_cfg('ly200.up_file_base_dir').'product/customize/'.date('y_m_d/', $service_time);
	if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
		include('../../inc/fun/img_resize.php');
		$SmallPicPath=img_resize($BigPicPath, '', get_cfg('product.customize.pic_width'), get_cfg('product.customize.pic_height'));
	}
	$db->insert('product_customize_item', array(
			'Name'		=>	$Name,
			'PicPath'	=>	$SmallPicPath,
			'CId'		=>	$CId
			)
	);
	save_manage_log('添加定制项目:'.$Name);
	header('Location: customize_item_list.php?CId='.$CId);
	exit;
}

$CId=(int)$_GET['CId'];
$row=$db->get_one('product_customize',"CId='$CId'");
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_item_list.php?CId=<?=$row['CId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
    <div class="float_right"><a href="customize_add.php?CateId=<?=$CateId?>"><?=get_lang('ly200.add');?></a></div>
</div>
<form method="post" name="act_form" id="act_form" class="act_form" action="customize_item_add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
		<tr>
			<td width="5%" nowrap>名称:</td>
			<td width="95%"><input name="Name" type="text" value="" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out');?>!~*"></td>
		</tr>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td><input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"></td>
		</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><input type="hidden" name="CId" value="<?=$CId?>" /><a href='customize_item_list.php.php?CId=<?=$CId?>' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>