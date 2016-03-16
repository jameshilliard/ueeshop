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
	$Express=$_POST['Express'];
	$FreeShippingInvocation=(int)$_POST['FreeShippingInvocation'];
	$FreeShippingPrice=(float)$_POST['FreeShippingPrice'];
	$Explanation=$_POST['Explanation'];
	
	$db->update('shipping', "SId='$SId'", array(
			'Express'				=>	$Express,
			'FreeShippingInvocation'=>	$FreeShippingInvocation,
			'FreeShippingPrice'		=>	$FreeShippingPrice,
			'Explanation'			=>	$Explanation
		)
	);
	
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
	
	save_manage_log('更新快递方式:'.$Express);
	
	header('Location: index.php');
	exit;
}

$SId=(int)$_GET['SId'];
$shipping_row=$db->get_one('shipping', "SId='$SId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('shipping.shipping_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgshipping_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('shipping.express').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Express<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($shipping_row['Express'.lang_name($i, 1)]);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('shipping.express');?>!~*"></td>
		</tr>
	<?php }?>
	<tr>
		<td><?=get_lang('shipping.free_shipping_price');?>:</td>
		<td><?=get_lang('ly200.invocation');?><input type="checkbox" name="FreeShippingInvocation" <?=$shipping_row['FreeShippingInvocation']==1?'checked':'';?> onclick="if(this.checked==true){$_('free_shipping_span').style.display='';}else{$_('free_shipping_span').style.display='none';};" value="1" /><span id="free_shipping_span" style="display:<?=$shipping_row['FreeShippingInvocation']==1?'':'none';?>;"><?=get_lang('ly200.price_symbols');?><input name="FreeShippingPrice" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="<?=$shipping_row['FreeShippingPrice'];?>" type="text" class="form_input" size="5" maxlength="10"></span></td>
	</tr>
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td><?=get_lang('ly200.explanation').lang_name($i, 0);?>:</td>
			<td><input name="Explanation<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($shipping_row['Explanation'.lang_name($i, 1)]);?>" type="text" class="form_input" size="70" maxlength="250"></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="SId" value="<?=$SId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>