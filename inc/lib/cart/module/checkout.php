<?php
$query_string=query_string('act');

$cart_row=$db->get_all('shopping_cart', $where, '*', 'ProId desc, CId desc');
!$cart_row && js_location("$cart_url?module=list");

$AId=(int)$_GET['AId']?(int)$_GET['AId']:(int)$_POST['AId'];
/*
if($AId){
	$shipping_address=$db->get_one('member_address_book', "MemberId='{$_SESSION['member_MemberId']}' and AddressType=0 and AId='$AId'");
}else{
	$shipping_address=$db->get_one('member_address_book', "MemberId='{$_SESSION['member_MemberId']}' and AddressType=0 and IsDefault=1");
}
$billing_address=$db->get_one('member_address_book', "MemberId='{$_SESSION['member_MemberId']}' and AddressType=1");*/

$total_price=$db->get_sum('shopping_cart', $where, 'Qty*Price');	//商品总价格
$product_weight==1 && $total_weight=$db->get_sum('shopping_cart', $where, 'Qty*Weight');	//商品总重量
$Integral=$db->get_sum('shopping_cart', $where, 'Qty*Integral');		//商品总积分
$IsGift=$db->get_sum('shopping_cart', $where, 'IsGift');		//是否赠品

$member_one=$db->get_one('member', "MemberId='{$_SESSION['member_MemberId']}'");

if($_POST['data']=='cart_checkout'){

	//$SId=(int)$_POST['SId'];	//送货方式
	$Comments=$_POST['Comments'];	//订单留言
	
	//(!$SId) && js_location("$cart_url?module=checkout");	//提交数据不完整.....
	
	if($IsGift==1){
		($member_one['Integral']<$Integral) && js_location("$cart_url?module=checkout");	//积分少于会员积分.....
	}
	
	//---------------------------------------------------------------------------------------生成订单号------------------------------------------------------------------------
	while(1){
		$OId=date('YmdHis', $service_time).rand(10, 99);
		if(!$db->get_row_count('orders', "OId='$OId'")){
			break;
		}
	}
	//---------------------------------------------------------------------------------------生成订单号------------------------------------------------------------------------
	
	$payment_method_row=$db->get_one('payment_method', 'IsInvocation=1', 'Name, AdditionalFee', 'MyOrder desc, PId asc');
	
	$db->insert('orders', array(
			'OId'					=>	$OId,
			'MemberId'				=>	$_SESSION['member_MemberId'],
			'Email'					=>	addslashes($_SESSION['member_Email']),
			'TotalPrice'			=>	(float)$total_price,
			'PayAdditionalFee'		=>	(float)$payment_method_row['AdditionalFee'],
			'ShippingTitle'			=>	addslashes($shipping_address['Title']),
			'ShippingFirstName'		=>	addslashes($shipping_address['FirstName']),
			'ShippingLastName'		=>	addslashes($shipping_address['LastName']),
			'ShippingAddressLine1'	=>	addslashes($shipping_address['AddressLine1']),
			'ShippingAddressLine2'	=>	addslashes($shipping_address['AddressLine2']),
			'ShippingCity'			=>	addslashes($shipping_address['City']),
			'ShippingState'			=>	addslashes($shipping_address['State']),
			'ShippingCountry'		=>	addslashes($shipping_address['Country']),
			'ShippingPostalCode'	=>	addslashes($shipping_address['PostalCode']),
			'ShippingPhone'			=>	addslashes($shipping_address['Phone']),
			'BillingTitle'			=>	addslashes($billing_address['Title']),
			'BillingFirstName'		=>	addslashes($billing_address['FirstName']),
			'BillingLastName'		=>	addslashes($billing_address['LastName']),
			'BillingAddressLine1'	=>	addslashes($billing_address['AddressLine1']),
			'BillingAddressLine2'	=>	addslashes($billing_address['AddressLine2']),
			'BillingCity'			=>	addslashes($billing_address['City']),
			'BillingState'			=>	addslashes($billing_address['State']),
			'BillingCountry'		=>	addslashes($billing_address['Country']),
			'BillingPostalCode'		=>	addslashes($billing_address['PostalCode']),
			'BillingPhone'			=>	addslashes($billing_address['Phone']),
			//'Express'				=>	addslashes($db->get_value('shipping', "SId='$SId'", 'Express')),
			'TotalWeight'			=>	(float)$total_weight,
			'Comments'				=>	$Comments,
			'PaymentMethod'			=>	addslashes($payment_method_row['Name']),
			'OrderTime'				=>	$service_time,
			'OrderStatus'			=>	(int)$order_default_status,
			'Integral'				=>	(int)$Integral,
			'IsGift'				=>	(int)$IsGift
		)
	);
	
	$img_dir=mk_dir('/images/orders/'.date('Y_m/', $service_time).$OId.'/');
	$OrderId=$db->get_insert_id();
	
	update_orders_shipping_info($OrderId, '', 1);
	
	for($i=0; $i<count($cart_row); $i++){
		$img_path=$img_dir.basename($cart_row[$i]['PicPath']);
		@copy($site_root_path.$cart_row[$i]['PicPath'], $site_root_path.$img_path);
		
		$db->insert('orders_product_list', array(
				'OrderId'	=>	$OrderId,
				'ProId'		=>	(int)$cart_row[$i]['ProId'],
				'CateId'	=>	(int)$cart_row[$i]['CateId'],
				'Color'		=>	addslashes($cart_row[$i]['Color']),
				'Size'		=>	addslashes($cart_row[$i]['Size']),
				'Name'		=>	addslashes($cart_row[$i]['Name']),
				'ItemNumber'=>	addslashes($cart_row[$i]['ItemNumber']),
				'Weight'	=>	(float)$cart_row[$i]['Weight'],
				'PicPath'	=>	addslashes($img_path),
				'Price'		=>	(float)$cart_row[$i]['Price'],
				'Qty'		=>	(int)$cart_row[$i]['Qty'],
				'Url'		=>	addslashes($cart_row[$i]['Url']),
				'Remark'	=>	addslashes($cart_row[$i]['Remark']),
				'Integral'	=>	(int)$cart_row[$i]['Integral']*(int)$cart_row[$i]['Qty'],
				'Customize'	=>	$cart_row[$i]['Customize'],
				'Font'		=>	$cart_row[$i]['Font'],
				'Txta'		=>	$cart_row[$i]['Txta'],
				'Txtb'		=>	$cart_row[$i]['Txtb'],
				'Location'	=>	$cart_row[$i]['Location']
			)
		);
	}
	
	if($IsGift==1){
		$db->query("update member set Integral=Integral-'$Integral' where MemberId='$_SESSION[member_MemberId]'");
	}
	
	$db->delete('shopping_cart', "MemberId='{$_SESSION['member_MemberId']}'");	//删除购物车的物品
	js_location("$cart_url?module=place&OId=$OId");
	
	
}
?>
<div id="lib_cart_station"><a href="/">Home</a> &gt; <a href="<?=$cart_url;?>?module=list">Shopping Cart</a> &gt; Checkout</div>
<div id="lib_cart_guid"><img src="/images/lib/cart/guid_2.gif" /></div>
<div id="lib_cart_checkout">
	<form action="<?=$cart_url.'?'.$query_string;?>" method="post" name="cart_checkout_form" OnSubmit="return checkForm(this);">
		<div class="title">To place an order, please complete the below form:</div>
		<div class="blank20"></div>
		<div class="comments">
			<div class="item_title">Special Instructions or Comments:</div>
			<div class="info">If you have special instructions for your order, please let us know!</div>
			<div><textarea class="form_area" name="Comments"></textarea></div>
		</div>
		<div class="blank15"></div>
		<div class="payment_method">
			<div class="item_title">Order Items Review:</div>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="item_list_table">
				<tr class="tb_title">
					<td width="15%">Item No.</td>
					<td width="40%">Product Name</td>
					<td width="15%">Price</td>
					<td width="15%">Quantity</td>
					<td width="15%" class="last">Total</td>
				</tr>
				<?php
				$pro_count=0;
				for($i=0; $i<count($cart_row); $i++){
					$pro_count+=$cart_row[$i]['Qty'];
				?>
				<tr class="item_list item_list_out" onmouseover="this.className='item_list item_list_over';" onmouseout="this.className='item_list item_list_out';" align="center">
					<td><a href="<?=$cart_row[$i]['Url'];?>" target="_blank" class="proname"><?=$cart_row[$i]['ItemNumber'];?></a>&nbsp;</td>
					<td align="left"><a href="<?=$cart_row[$i]['Url'];?>" target="_blank" class="proname"><?=$cart_row[$i]['Name'];?></a></td>
					<td><?=iconv_price($cart_row[$i]['Price']);?></td>
					<td><?=$cart_row[$i]['Qty'];?></td>
					<td><?=iconv_price($cart_row[$i]['Price']*$cart_row[$i]['Qty']);?></td>
				</tr>
				<?php }?>
				<tr class="total">
					<td colspan="3"></td>
					<td><?=$pro_count;?></td>
					<td><?=iconv_price($total_price);?></td>
				</tr>
			</table>
		</div>
		<div class="blank15"></div>
		<div class="place_order">
			<ul>
				<li>
					<div class="price"><span><?=iconv_price($total_price, 1);?></span><span id="subtotal_span"><?=iconv_price($total_price, 2);?></span></div>
					<div>Subtotal:</div>
				</li>
				<li>
					<div class="price"><span><?=iconv_price($total_price, 1);?></span><span id="grand_total_span"><?=iconv_price($total_price, 2);?></span></div>
					<div>Grand Total:</div>
				</li>
			</ul>
			<div class="place_order_btn"><input type="image" name="imageField" src="/images/lib/cart/btn_place.gif" /></div>
		</div>
		<input type="hidden" name="AId" value="<?=$shipping_address['AId'];?>" />
		<input type="hidden" name="data" value="cart_checkout" />
	</form>
</div>
<script language="javascript">
function change_shipping_method(shipping_price){
	$_('shipping_charges_span').innerHTML=shipping_price.toFixed(2);
	$_('grand_total_span').innerHTML=(parseFloat($_('subtotal_span').innerHTML)+shipping_price).toFixed(2);
}
</script>