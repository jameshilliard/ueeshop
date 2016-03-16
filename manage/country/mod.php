<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('country', 'country.mod');

if($_POST){
	$CId=(int)$_POST['CId'];
	$Country=$_POST['Country'];
	
	$db->get_row_count('country', "Country='$Country' and CId!='$CId'") && js_back(get_lang('country.country_exist'));
	
	$db->update('country', "CId='$CId'", array(
			'Country'	=>	$Country
		)
	);
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('country', 'Country');
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$CountryExt=$_POST['Country'.$field_ext];
			$db->update('country', "CId='$CId'", array(
					'Country'.$field_ext	=>	$CountryExt
				)
			);
		}
	}
	
	save_manage_log('更新国家地区:'.$Country);
	
	header('Location: index.php');
	exit;
}

$CId=(int)$_GET['CId'];
$country_row=$db->get_one('country', "CId='$CId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('country.country_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('country.country').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Country<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($country_row['Country'.lang_name($i, 1)]);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('country.country');?>!~*"></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="CId" value="<?=$CId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>