<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_size', 'product.size.mod');

if($_POST){
	$SId=(int)$_POST['SId'];	
	$Size=$_POST['Size'];
	
	$db->update('product_size', "SId='$SId'", array(
			'Size'	=>	$Size
		)
	);
	
	save_manage_log('更新产品尺寸:'.$Size);
	
	header('Location: size.php');
	exit;
}

$SId=(int)$_GET['SId'];
$size_row=$db->get_one('product_size', "SId='$SId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="size.php"><?=get_lang('product.size_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="size_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('product.size');?>:</td>
		<td width="95%"><input name="Size" value="<?=htmlspecialchars($size_row['Size']);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('product.size');?>!~*"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='size.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="SId" value="<?=$SId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>