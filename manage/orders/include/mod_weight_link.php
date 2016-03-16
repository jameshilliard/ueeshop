<?php
ob_start();
?>
<div id="weight_value" style="display:;">
	<?=$orders_row['TotalWeight'];?> KG&nbsp;&nbsp;&nbsp;
	<?php if(get_cfg('orders.mod')){?><a href="#" class="red" onclick="$_('weight_value').style.display='none'; $_('change_weight_form').style.display='';"><?=get_lang('ly200.mod');?></a><?php }?>
</div>
<div id="change_weight_form" style="display:none;">
	<input type="text" name="TotalWeight" id="TotalWeightInput" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="<?=$orders_row['TotalWeight'];?>" size="5" maxlength="10" class="form_input" />KG&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="AutoUpdateShippingPrice" id="AutoUpdateShippingPrice" value="1" checked="checked" /><?=get_lang('orders.auto_upd_ship_price');?>&nbsp;&nbsp;
	<input type="button" name="submit_weight" value="<?=get_lang('ly200.submit');?>" class="form_button" onclick="window.location='view.php?OrderId=<?=$OrderId;?>&module=<?=$module;?>&AutoUpdateShippingPrice='+($_('AutoUpdateShippingPrice').checked?1:0)+'&TotalWeight='+$_('TotalWeightInput').value+'&tmpOrderStatus='+$_('OrderStatus').value+'&act=mod_total_weight&r='+Math.random();">&nbsp;&nbsp;&nbsp;
	<input type="button" name="cancel" value="<?=get_lang('ly200.cancel');?>" class="form_button" onclick="$_('weight_value').style.display=''; $_('change_weight_form').style.display='none';">
</div>
<?php
$upd_weight_link=ob_get_contents();
ob_end_clean();
?>