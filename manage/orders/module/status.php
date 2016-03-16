<form method="post" name="act_form" id="act_form" class="act_form" action="view.php" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('orders.payment_method');?>:</td>
		<td width="95%"><?=$orders_row['PaymentMethod'];?></td>
	</tr>
	<?=$payment_info_detail;?>
	<tr>
		<td nowrap><?=get_lang('orders.order_status');?>:</td>
		<td><?php if(get_cfg('orders.mod')){?><select name="OrderStatus" id="OrderStatus" onchange="change_order_status(this.value);">
			<?php
			foreach($order_status_ary as $key=>$value){
				$s=($tmpOrderStatus?$tmpOrderStatus:$orders_row['OrderStatus'])==$key?'selected':'';
			?>
				<option value="<?=$key;?>" <?=$s;?>><?=$value;?></option>
			<?php }?>
		</select><?php }else{?><?=$order_status_ary[$orders_row['OrderStatus']];?><?php }?></td>
	</tr>
	<?php if($product_weight==1){?>
		<tr id="shipping_info_0" style="display:none;">
			<td nowrap><?=get_lang('orders.weight');?>:</td>
			<td><?=$upd_weight_link;?></td>
		</tr>
	<?php }?>
	<tr id="shipping_info_1" style="display:none;">
		<td nowrap><?=get_lang('orders.shipping_method');?>:</td>
		<td><?=$upd_express_link;?></td>
	</tr>
	<tr id="shipping_info_2" style="display:none;">
		<td nowrap><?=get_lang('orders.shipping_charges');?>:</td>
		<td><?=get_lang('ly200.price_symbols').$orders_row['ShippingPrice'];?></td>
	</tr>
	<tr id="shipping_info_3" style="display:none;">
		<td nowrap><?=get_lang('orders.tracking_number');?>:</td>
		<td><?php if(get_cfg('orders.mod')){?><input type="text" name="TrackingNumber" id="tracking_number_input" value="<?=htmlspecialchars($orders_row['TrackingNumber']);?>" size="20" maxlength="20" class="form_input" check_tmp="<?=get_lang('ly200.filled_out').get_lang('orders.tracking_number');?>!~*" /><?php }else{?><?=htmlspecialchars($orders_row['TrackingNumber']);?><?php }?></td>
	</tr>
	<tr id="shipping_info_4" style="display:none;">
		<td nowrap><?=get_lang('orders.shipping_time');?>:</td>
		<td><?php if(get_cfg('orders.mod')){?><input name="ShippingTime" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=date('Y-m-d', $orders_row['ShippingTime']?$orders_row['ShippingTime']:$service_time);?>" class="form_input" /><?php }else{?><?=date('Y-m-d', $orders_row['ShippingTime']);?><?php }?></td>
	</tr>
	<?php if(get_cfg('orders.mod')){?>
		<tr>
			<td>&nbsp;</td>
			<td><input type="Submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><input type="hidden" name="OrderId" value="<?=$OrderId;?>" /><input type="hidden" name="module" value="<?=$module;?>" /><input type="hidden" name="act" value="mod_order_status" /></td>
		</tr>
	<?php }?>
	<input type="hidden" name="MemberId" value="<?=$orders_row['MemberId'];?>" />
	<input type="hidden" name="Integral" value="<?=$orders_row['Integral'];?>" />
	<input type="hidden" name="IsGift" value="<?=$orders_row['IsGift'];?>" />
</table>
<script language="javascript">change_order_status(<?=$tmpOrderStatus?$tmpOrderStatus:$orders_row['OrderStatus'];?>);</script>
</form>
