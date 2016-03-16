<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('ad', 'ad.add');

if($_POST){
	$PageName=$_POST['PageName'];
	$AdPosition=$_POST['AdPosition'];
	$AdType=(int)$_POST['AdType'];
	$PicCount=(int)$_POST['PicCount'];
	$Width=(int)$_POST['Width'];
	$Height=(int)$_POST['Height'];
	
	$db->insert('ad', array(
			'PageName'	=>	$PageName,
			'AdPosition'=>	$AdPosition,
			'AdType'	=>	$AdType,
			'PicCount'	=>	$PicCount,
			'Width'		=>	$Width,
			'Height'	=>	$Height
		)
	);
	
	save_manage_log('添加广告图片');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('ad.ad_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('ad.pagename');?>:</td>
		<td width="95%"><input name="PageName" type="text" value="" class="form_input" size="25" maxlength="50" check="<?=get_lang('ly200.filled_out').get_lang('ad.pagename');?>!~*"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.ad_position');?>:</td>
		<td><input name="AdPosition" type="text" value="" class="form_input" size="25" maxlength="50" check="<?=get_lang('ly200.filled_out').get_lang('ad.ad_position');?>!~*"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.ad_type');?>:</td>
		<td><select name="AdType" onchange="change_ad_type(this.value);">
			<option value="0"><?=get_lang('ad.ad_type_ary.0');?></option>
			<option value="1"><?=get_lang('ad.ad_type_ary.1');?></option>
			<option value="2"><?=get_lang('ad.ad_type_ary.2');?></option>
		</select></td>
	</tr>
	<tr id="ad_pic_qty">
		<td nowrap><?=get_lang('ad.pic_qty');?>:</td>
		<td><select name="PicCount">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.width');?>:</td>
		<td><input name="Width" type="text" value="" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" class="form_input" size="5" maxlength="5"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.height');?>:</td>
		<td><input name="Height" type="text" value="" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" class="form_input" size="5" maxlength="5"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>