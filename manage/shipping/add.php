<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('shipping', 'shipping.add');

if($_POST){
	$Express=$_POST['Express'];
	$FreeShippingInvocation=(int)$_POST['FreeShippingInvocation'];
	$FreeShippingPrice=(float)$_POST['FreeShippingPrice'];
	$Explanation=$_POST['Explanation'];
	
	$db->insert('shipping', array(
			'Express'				=>	$Express,
			'FreeShippingInvocation'=>	$FreeShippingInvocation,
			'FreeShippingPrice'		=>	$FreeShippingPrice,
			'Explanation'			=>	$Explanation
		)
	);
	
	$SId=$db->get_insert_id();
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('shipping', array('Express', 'Explanation'));
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$ExpressExt=$_POST['Express'.$field_ext];
			$ExplanationExt=$_POST['Explanation'.$field_ext];
			$db->update('shipping', "SId='$SId'", array(
					'Express'.$field_ext		=>	$ExpressExt,
					'Explanation'.$field_ext	=>	$ExplanationExt
				)
			);
		}
	}
	
	$FirstPrice=(float)$_POST['FirstPrice'];
	$FirstWeight=(float)$_POST['FirstWeight'];
	$ExtWeight=(float)$_POST['ExtWeight'];
	$ExtPrice=(float)$_POST['ExtPrice'];
	
	$country_row=$db->get_all('country', 1, '*', 'Country asc, CId asc');
	for($i=0; $i<count($country_row); $i++){
		$db->insert('shipping_price', array(
				'SId'			=>	$SId,
				'CId'			=>	$country_row[$i]['CId'],
				'FirstPrice'	=>	$FirstPrice,
				'FirstWeight'	=>	$FirstWeight,
				'ExtWeight'		=>	$ExtWeight,
				'ExtPrice'		=>	$ExtPrice
			)
		);
	}
	
	save_manage_log('添加快递方式:'.$Express);
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('shipping.shipping_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgshipping_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('shipping.express').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Express<?=lang_name($i, 1);?>" type="text" value="" class="form_input" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('shipping.express');?>!~*"></td>
		</tr>
	<?php }?>
	<tr>
		<td><?=get_lang('shipping.first_price');?>:</td>
		<td><?=get_lang('ly200.price_symbols');?><input name="FirstPrice" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="" type="text" class="form_input" size="5" maxlength="10"></td>
	</tr>
	<?php if(get_cfg('shipping.s_price_by_weight')){?>
		<tr>
			<td><?=get_lang('shipping.first_weight');?>:</td>
			<td><input name="FirstWeight" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="" type="text" class="form_input" size="5" maxlength="10">KG</td>
		</tr>
		<tr>
			<td><?=get_lang('shipping.ext_weight');?>:</td>
			<td><input name="ExtWeight" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="" type="text" class="form_input" size="5" maxlength="10">KG<font class="fc_red"> / </font><?=get_lang('ly200.price_symbols');?><input name="ExtPrice" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="" type="text" class="form_input" size="5" maxlength="10"></td>
		</tr>
	<?php }?>
	<tr>
		<td><?=get_lang('shipping.free_shipping_price');?>:</td>
		<td><?=get_lang('ly200.invocation');?><input type="checkbox" name="FreeShippingInvocation" onclick="if(this.checked==true){$_('free_shipping_span').style.display='';}else{$_('free_shipping_span').style.display='none';};" value="1" /><span id="free_shipping_span" style="display:none;"><?=get_lang('ly200.price_symbols');?><input name="FreeShippingPrice" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="" type="text" class="form_input" size="5" maxlength="10"></span></td>
	</tr>
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td><?=get_lang('ly200.explanation').lang_name($i, 0);?>:</td>
			<td><input name="Explanation<?=lang_name($i, 1);?>" value="" type="text" class="form_input" size="70" maxlength="250"></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>