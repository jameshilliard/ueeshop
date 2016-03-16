<?php
ob_start();
?>
<div id="express_value" style="display:;">
	<?=htmlspecialchars($orders_row['Express']);?>&nbsp;&nbsp;&nbsp;
	<?php if($product_weight==1){?>
		<?=get_lang('shipping.first_weight');?>: <?=$orders_row['FirstWeight'];?> KG / <?=get_lang('ly200.price_symbols').$orders_row['FirstPrice'];?>&nbsp;&nbsp;&nbsp;
		<?=get_lang('shipping.ext_weight');?>: <?=$orders_row['ExtWeight'];?> KG / <?=get_lang('ly200.price_symbols').$orders_row['ExtPrice'];?>&nbsp;&nbsp;&nbsp;
	<?php }?>
	<?php if(get_cfg('orders.mod')){?><a href="#" class="red" onclick="$_('express_value').style.display='none'; $_('change_express_form').style.display='';"><?=get_lang('ly200.mod');?></a><?php }?>
</div>
<div id="change_express_form" style="display:none;">
	<?=str_replace('<select', '<select id="ExpressSelect"', ouput_table_to_select('shipping', 'Express', 'Express', 'Express', 'MyOrder desc,SId asc', 1, 1, $orders_row['Express']));?>&nbsp;&nbsp;
	<input type="checkbox" name="AutoUpdateShippingPrice" id="AutoUpdateShippingPrice" value="1" checked="checked" /><?=get_lang('orders.auto_upd_ship_price');?>&nbsp;&nbsp;
	<input type="button" name="submit_express" value="<?=get_lang('ly200.submit');?>" class="form_button" onclick="window.location='view.php?OrderId=<?=$OrderId;?>&module=<?=$module;?>&AutoUpdateShippingPrice='+($_('AutoUpdateShippingPrice').checked?1:0)+'&Express='+$_('ExpressSelect').value+'&tmpOrderStatus='+$_('OrderStatus').value+'&act=mod_express&r='+Math.random();">&nbsp;&nbsp;&nbsp;
	<input type="button" name="cancel" value="<?=get_lang('ly200.cancel');?>" class="form_button" onclick="$_('express_value').style.display=''; $_('change_express_form').style.display='none';">
</div>
<?php
$upd_express_link=ob_get_contents();
ob_end_clean();
?>