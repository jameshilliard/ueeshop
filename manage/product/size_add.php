<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_size', 'product.size.add');

if($_POST){
	$Size=$_POST['Size'];
	$db->insert('product_size', array(
			'Size'		=>	$Size
		)
	);
	
	save_manage_log('添加产品尺寸:'.$Size);
	
	header('Location: size.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="size.php"><?=get_lang('product.size_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="size_add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('product.size');?>:</td>
		<td width="95%"><input name="Size" type="text" value="" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('product.size');?>!~*"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><a href='size.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>