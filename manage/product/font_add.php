<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_font', 'product.font.add');

if($_POST){
	$Font=$_POST['Font'];
	$CId=$_POST['CId'];
	if(get_cfg('product.font.upload_pic')){
		$save_dir=get_cfg('ly200.up_file_base_dir').'product/font/'.date('y_m_d/', $service_time);
		
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('product.font.pic_width'), get_cfg('product.font.pic_height'));
		}
	}
	
	$db->insert('product_font', array(
			'Font'		=>	$Font,
			'PicPath'	=>	$SmallPicPath,
			'CId'	=>	$CId
		)
	);

	save_manage_log('添加字体:'.$Font);
	
	header('Location: font.php?CId='.$CId);
	exit;
}
$CId=(int)$_GET['CId'];
$row=$db->get_one('product_customize',"CId='$CId'");
include('../../inc/manage/header.php');
?>
<div class="header">
<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CId=<?=$row['CId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<a href="font.php?CId=<?=$row['CId']?>"><?=get_lang('product.font');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
</div>
<form method="post" name="act_form" id="act_form" class="act_form" action="font_add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgfont_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('product.font').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Font<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('product.font');?>!~*" id="font" onClick="intofont('font')">
            <div id="fontpanel" style="position: absolute;"></div>
            </td>
		</tr>
	<?php }?>
	<?php if(get_cfg('product.font.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td><input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><a href='font.php?CId=<?=$CId?>' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="CId" value="<?=$CId?>"  /></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>