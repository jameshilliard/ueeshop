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
	$Contents=$_POST['Contents'];
	$CateId=$_POST['CateId'];
	$CId=$_POST['CId'];
	$GetFont=(int)$_POST['GetFont'];
	$GetColor=(int)$_POST['GetColor'];
	$NoProduct=(int)$_POST['NoProduct'];
	/*
	'Name'		=>	$Name,
	'GetFont'	=>	$GetFont,
	'GetColor'	=>	$GetColor,
	'NoProduct'	=>	$NoProduct
	*/
	$db->update('product_customize',"CId='$CId'",array('Contents'	=>	$Contents)
	);
	save_manage_log('编辑定制项目:'.$Name);
	header('Location: customize_list.php?CateId='.$CateId);
	exit;
}

$CId=(int)$_GET['CId'];
$row=$db->get_one('product_customize',"CId='$CId'");
$CateId=$row['CateId'];
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$CateId?>"><?=$customize_aty[$CateId]?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
    <div class="float_right"><a href="customize_add.php?CateId=<?=$CateId?>"><?=get_lang('ly200.add');?></a></div>
</div>
<form method="post" name="act_form" id="act_form" class="act_form" action="customize_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap>名称:</td>
		<td width="95%"><input name="Name" type="text" value="<?=$row['Name']?>" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out')?>名称!~*" readonly></td>
	</tr>
<!--    <tr>
		<td width="5%" nowrap>Font:</td>
		<td width="95%"><input type="checkbox" name="GetFont" value="1"  <?=$row['GetFont']==1?'checked="checked"':''?> /></td>
	</tr>
    <tr>
		<td width="5%" nowrap>Color:</td>
		<td width="95%"><input type="checkbox" name="GetColor" value="1" <?=$row['GetColor']==1?'checked="checked"':''?> /></td>
	</tr>
    <tr>
		<td width="5%" nowrap>特殊设置:</td>
		<td width="95%"><input type="checkbox" name="NoProduct" value="1" <?=$row['NoProduct']==1?'checked="checked"':''?> /></td>
	</tr>-->
    <tr>
		<td width="5%" nowrap>文字说明:</td>
		<td width="95%"><textarea name="Contents" class="ckeditor" ><?=$row['Contents']?></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='customize_list.php?CateId=<?=$CateId?>' class="return"><?=get_lang('ly200.return');?></a>
        <input type="hidden" name="CId" value="<?=$CId?>" />
        <input type="hidden" name="CateId" value="<?=$CateId?>" />
        </td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>