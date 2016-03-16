<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('shipping', 'shipping.mod');

if($_POST){
	$SId=(int)$_POST['SId'];
	
	for($i=0; $i<count($_POST['CId']); $i++){
		$_FirstPrice=(float)$_POST['FirstPrice'][$i];
		$_FirstWeight=(float)$_POST['FirstWeight'][$i];
		$_ExtWeight=(float)$_POST['ExtWeight'][$i];
		$_ExtPrice=(float)$_POST['ExtPrice'][$i];
		$_CId=(float)$_POST['CId'][$i];
		
		$db->update('shipping_price', "SId='$SId' and CId='$_CId'", array(
				'FirstPrice'	=>	$_FirstPrice,
				'FirstWeight'	=>	$_FirstWeight,
				'ExtWeight'		=>	$_ExtWeight,
				'ExtPrice'		=>	$_ExtPrice
			)
		);
	}
	
	save_manage_log('设置运费');
	
	header('Location: shipping_price.php?SId='.$SId);
	exit;
}

$SId=(int)$_GET['SId'];
$shipping_row=$db->get_one('shipping', "SId='$SId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('shipping.shipping_manage');?></a>&nbsp;-&gt;&nbsp;<a href="shipping_price.php?SId=<?=$SId;?>"><?=list_all_lang_data($shipping_row, 'Express');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('shipping.set_shipping_price');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="shipping_price.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='act_form_title'>
	<tr align="center" class="act_form_title" id="act_form_title">
		<td nowrap width="10%"><strong><?=get_lang('ly200.number');?></strong></td>
		<td nowrap width="25%"><strong><?=get_lang('country.country');?></strong></td>
		<td nowrap width="20%"><strong><?=get_lang('shipping.first_price');?></strong></td>
		<?php if(get_cfg('shipping.s_price_by_weight')){?>
			<td nowrap width="20%"><strong><?=get_lang('shipping.first_weight');?></strong></td>
			<td nowrap width="25%"><strong><?=get_lang('shipping.ext_weight');?></strong></td>
		<?php }?>
	</tr>
	<?php
	$i=1;
	$country_rs=$db->query("select country.*,FirstPrice,FirstWeight,ExtWeight,ExtPrice from country left join shipping_price on shipping_price.CId=country.CId and shipping_price.SId='$SId' order by country.Country asc, country.CId asc");
	while($country_row=mysql_fetch_assoc($country_rs)){
	?>
	<tr align="center">
		<td><?=$i++;?></td>
		<td nowrap><?=list_all_lang_data($country_row, 'Country');?></td>
		<td><?=get_lang('ly200.price_symbols');?><input name="FirstPrice[]" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value='<?=$country_row['FirstPrice']>0?$country_row['FirstPrice']:'';?>' type="text" class="form_input" size="5" maxlength="10"></td>
		<?php if(get_cfg('shipping.s_price_by_weight')){?>
			<td><input name="FirstWeight[]" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value='<?=$country_row['FirstWeight']>0?$country_row['FirstWeight']:'';?>' type="text" class="form_input" size="5" maxlength="10">KG</td>
			<td nowrap><input name="ExtWeight[]" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value='<?=$country_row['ExtWeight']>0?$country_row['ExtWeight']:'';?>' type="text" class="form_input" size="5" maxlength="10">KG<font class="fc_red"> / </font><?=get_lang('ly200.price_symbols');?><input name="ExtPrice[]" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value='<?=$country_row['ExtPrice']>0?$country_row['ExtPrice']:'';?>' type="text" class="form_input" size="5" maxlength="10"><input type='hidden' name='CId[]' value='<?=$country_row['CId'];?>'></td>
		<?php }?>
	</tr>
	<?php }?>
	<tr>
		<td colspan="5" class="bottom_act"><input type="submit" name="submit" value="<?=get_lang('ly200.submit');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="SId" value="<?=$SId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>