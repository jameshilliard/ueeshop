<?php
$ShippingTitle=$_POST['ShippingTitle'];
$ShippingFirstName=$_POST['ShippingFirstName'];
$ShippingLastName=$_POST['ShippingLastName'];
$ShippingAddressLine1=$_POST['ShippingAddressLine1'];
$ShippingAddressLine2=$_POST['ShippingAddressLine2'];
$ShippingCity=$_POST['ShippingCity'];
$ShippingState=$_POST['ShippingState'];
$ShippingCountry=$_POST['ShippingCountry'];
$AutoUpdateShippingPrice=(int)$_POST['AutoUpdateShippingPrice'];
$ShippingPostalCode=$_POST['ShippingPostalCode'];
$ShippingPhone=$_POST['ShippingPhone'];
$AlsoBillingAddress=(int)$_POST['AlsoBillingAddress'];

($AutoUpdateShippingPrice==1 && $ShippingCountry!=$db->get_value('orders', $where, 'ShippingCountry')) && update_orders_shipping_info($OrderId, '', 1);

$db->update('orders', $where, array(
		'ShippingTitle '		=>	$ShippingTitle,
		'ShippingFirstName '	=>	$ShippingFirstName,
		'ShippingLastName '		=>	$ShippingLastName,
		'ShippingAddressLine1 '	=>	$ShippingAddressLine1,
		'ShippingAddressLine2 '	=>	$ShippingAddressLine2,
		'ShippingCity '			=>	$ShippingCity,
		'ShippingState '		=>	$ShippingState,
		'ShippingCountry '		=>	$ShippingCountry,
		'ShippingPostalCode '	=>	$ShippingPostalCode,
		'ShippingPhone '		=>	$ShippingPhone
	)
);

if($AlsoBillingAddress==1){
	$db->update('orders', $where, array(
			'BillingTitle '			=>	$ShippingTitle,
			'BillingFirstName '		=>	$ShippingFirstName,
			'BillingLastName '		=>	$ShippingLastName,
			'BillingAddressLine1 '	=>	$ShippingAddressLine1,
			'BillingAddressLine2 '	=>	$ShippingAddressLine2,
			'BillingCity '			=>	$ShippingCity,
			'BillingState '			=>	$ShippingState,
			'BillingCountry '		=>	$ShippingCountry,
			'BillingPostalCode '	=>	$ShippingPostalCode,
			'BillingPhone '			=>	$ShippingPhone
		)
	);
}

save_manage_log('修改订单收货地址');
js_location("view.php?OrderId=$OrderId&module=$module");
?>