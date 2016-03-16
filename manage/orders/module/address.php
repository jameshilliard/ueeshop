<div class="act_form">
	<table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr align="center" class="act_form_title">
			<td width="50%" nowrap><strong><?=get_lang('orders.shipping_address');?></strong></td>
			<td width="50%" nowrap><strong><?=get_lang('orders.billing_address');?></strong></td>
		</tr>
		<tr>
			<td valign="top" style="padding:10px;" class="flh_150">
				<div id="shipping_address_info" style="display:;">
					<strong><?=htmlspecialchars($orders_row['ShippingTitle'].' '.$orders_row['ShippingFirstName'].' '.$orders_row['ShippingLastName']);?></strong><br />
					<?=htmlspecialchars($orders_row['ShippingAddressLine1']);?><br />
					<?=htmlspecialchars($orders_row['ShippingCity']);?><br />
					<?=htmlspecialchars($orders_row['ShippingState']);?> (Postal Code: <strong><?=htmlspecialchars($orders_row['ShippingPostalCode']);?></strong>)<br />
					<?=htmlspecialchars($orders_row['ShippingCountry']);?><br />
					<strong>Phone: </strong><?=htmlspecialchars($orders_row['ShippingPhone']);?><?php if(get_cfg('orders.mod')){?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="$_('shipping_address_info').style.display='none'; $_('shipping_address_form').style.display='';" class="red"><?=get_lang('ly200.mod');?></a><?php }?>
				</div>
				<div id="shipping_address_form" style="display:none;">
					<form action="view.php" method="post" name="mod_shipping_address_form" OnSubmit="return checkForm(this);">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="the_table">
						  <tr>
							<td width="5%" nowrap><?=get_lang('address_book.first_name');?>:</td>
							<td width="95%"><select name="ShippingTitle">
									<option value="Miss" <?=$orders_row['ShippingTitle']=='Miss'?'selected':'';?>>Miss</option>
									<option value="Mrs" <?=$orders_row['ShippingTitle']=='Mrs'?'selected':'';?>>Mrs</option>
									<option value="Ms" <?=$orders_row['ShippingTitle']=='Ms'?'selected':'';?>>Ms</option>
									<option value="Mr" <?=$orders_row['ShippingTitle']=='Mr'?'selected':'';?>>Mr</option>
								</select>&nbsp;<input name="ShippingFirstName" value="<?=htmlspecialchars($orders_row['ShippingFirstName']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.first_name');?>!~*" size="14" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.last_name');?>:</td>
							<td><input name="ShippingLastName" value="<?=htmlspecialchars($orders_row['ShippingLastName']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.last_name');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.address_line_1');?>:</td>
							<td><input name="ShippingAddressLine1" value="<?=htmlspecialchars($orders_row['ShippingAddressLine1']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.address_line_1');?>!~*" size="57" maxlength="200"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.address_line_2');?>:</td>
							<td><input name="ShippingAddressLine2" value="<?=htmlspecialchars($orders_row['ShippingAddressLine2']);?>" type="text" class="form_input" size="57" maxlength="200"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.city');?>:</td>
							<td><input name="ShippingCity" value="<?=htmlspecialchars($orders_row['ShippingCity']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.city');?>!~*" size="25" maxlength="50"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.state');?>:</td>
							<td><input name="ShippingState" value="<?=htmlspecialchars($orders_row['ShippingState']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.state');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.country');?>:</td>
							<td><?=ouput_table_to_select('country', 'Country', 'Country', 'ShippingCountry', 'Country asc, CId asc', 0, 1, $orders_row['ShippingCountry'], '', get_lang('ly200.select'), get_lang('ly200.filled_out').get_lang('address_book.country').'!~*');?>&nbsp;&nbsp;<input type="checkbox" name="AutoUpdateShippingPrice" value="1" checked="checked" /><?=get_lang('orders.auto_upd_ship_price');?></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.postal_code');?>:</td>
							<td><input name="ShippingPostalCode" value="<?=htmlspecialchars($orders_row['ShippingPostalCode']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.postal_code');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.phone');?>:</td>
							<td><input name="ShippingPhone" value="<?=htmlspecialchars($orders_row['ShippingPhone']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.phone');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
						  	<td nowrap></td>
						  	<td><input type="Submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="cancel" value="<?=get_lang('ly200.cancel');?>" class="form_button" onclick="$_('shipping_address_info').style.display=''; $_('shipping_address_form').style.display='none';">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="AlsoBillingAddress" value="1" /><?=get_lang('address_book.also_billing_address');?><input type="hidden" name="OrderId" value="<?=$OrderId;?>" /><input type="hidden" name="module" value="<?=$module;?>" /><input type="hidden" name="act" value="mod_shipping_address" /></td>
						  </tr>
						</table>
					</form>
				</div>
			</td>
			<td valign="top" style="padding:10px;" class="flh_150">
				<div id="billing_address_info" style="display:;">
					<strong><?=htmlspecialchars($orders_row['BillingTitle'].' '.$orders_row['BillingFirstName'].' '.$orders_row['BillingLastName']);?></strong><br />
					<?=htmlspecialchars($orders_row['BillingAddressLine1']);?><br />
					<?=htmlspecialchars($orders_row['BillingCity']);?><br />
					<?=htmlspecialchars($orders_row['BillingState']);?> (Postal Code: <strong><?=htmlspecialchars($orders_row['BillingPostalCode']);?></strong>)<br />
					<?=htmlspecialchars($orders_row['BillingCountry']);?><br />
					<strong>Phone: </strong><?=htmlspecialchars($orders_row['BillingPhone']);?><?php if(get_cfg('orders.mod')){?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="$_('billing_address_info').style.display='none'; $_('billing_address_form').style.display='';" class="red"><?=get_lang('ly200.mod');?></a><?php }?>
				</div>
				<div id="billing_address_form" style="display:none;">
					<form action="view.php" method="post" name="mod_billing_address_form" OnSubmit="return checkForm(this);">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="the_table">
						  <tr>
							<td width="5%" nowrap><?=get_lang('address_book.first_name');?>:</td>
							<td width="95%"><select name="BillingTitle">
									<option value="Miss" <?=$orders_row['BillingTitle']=='Miss'?'selected':'';?>>Miss</option>
									<option value="Mrs" <?=$orders_row['BillingTitle']=='Mrs'?'selected':'';?>>Mrs</option>
									<option value="Ms" <?=$orders_row['BillingTitle']=='Ms'?'selected':'';?>>Ms</option>
									<option value="Mr" <?=$orders_row['BillingTitle']=='Mr'?'selected':'';?>>Mr</option>
								</select>&nbsp;<input name="BillingFirstName" value="<?=htmlspecialchars($orders_row['BillingFirstName']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.first_name');?>!~*" size="14" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.last_name');?>:</td>
							<td><input name="BillingLastName" value="<?=htmlspecialchars($orders_row['BillingLastName']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.last_name');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.address_line_1');?>:</td>
							<td><input name="BillingAddressLine1" value="<?=htmlspecialchars($orders_row['BillingAddressLine1']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.address_line_1');?>!~*" size="57" maxlength="200"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.address_line_2');?>:</td>
							<td><input name="BillingAddressLine2" value="<?=htmlspecialchars($orders_row['BillingAddressLine2']);?>" type="text" class="form_input" size="57" maxlength="200"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.city');?>:</td>
							<td><input name="BillingCity" value="<?=htmlspecialchars($orders_row['BillingCity']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.city');?>!~*" size="25" maxlength="50"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.state');?>:</td>
							<td><input name="BillingState" value="<?=htmlspecialchars($orders_row['BillingState']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.state');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.country');?>:</td>
							<td class="flh_150"><?=ouput_table_to_select('country', 'Country', 'Country', 'BillingCountry', 'Country asc, CId asc', 0, 1, $orders_row['BillingCountry'], '', get_lang('ly200.select'), get_lang('ly200.filled_out').get_lang('address_book.country').'!~*');?><br /><input type="checkbox" name="AutoUpdateShippingPrice" value="1" checked="checked" /><?=get_lang('orders.auto_upd_ship_price_1');?></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.postal_code');?>:</td>
							<td><input name="BillingPostalCode" value="<?=htmlspecialchars($orders_row['BillingPostalCode']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.postal_code');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
							<td nowrap><?=get_lang('address_book.phone');?>:</td>
							<td><input name="BillingPhone" value="<?=htmlspecialchars($orders_row['BillingPhone']);?>" type="text" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('address_book.phone');?>!~*" size="22" maxlength="20"></td>
						  </tr>
						  <tr>
						  	<td nowrap></td>
						  	<td><input type="Submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="cancel" value="<?=get_lang('ly200.cancel');?>" class="form_button" onclick="$_('billing_address_info').style.display=''; $_('billing_address_form').style.display='none';">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="AlsoShippingAddress" value="1" /><?=get_lang('address_book.also_shipping_address');?><input type="hidden" name="OrderId" value="<?=$OrderId;?>" /><input type="hidden" name="module" value="<?=$module;?>" /><input type="hidden" name="act" value="mod_billing_address" /></td>
						  </tr>
						</table>
					</form>
				</div>
			</td>
		</tr>
	</table>
</div>