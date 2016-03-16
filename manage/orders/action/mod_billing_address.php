<?php
$BillingTitle=$_POST['BillingTitle'];
$BillingFirstName=$_POST['BillingFirstName'];
$BillingLastName=$_POST['BillingLastName'];
$BillingAddressLine1=$_POST['BillingAddressLine1'];
$BillingAddressLine2=$_POST['BillingAddressLine2'];
$BillingCity=$_POST['BillingCity'];
$BillingState=$_POST['BillingState'];
$BillingCountry=$_POST['BillingCountry'];
$BillingPostalCode=$_POST['BillingPostalCode'];
$BillingPhone=$_POST['BillingPhone'];
$AlsoShippingAddress=(int)$_POST['AlsoShippingAddress'];

$db->update('orders', $where, array(
		'BillingTitle '			=>	$BillingTitle,
		'BillingFirstName '		=>	$BillingFirstName,
		'BillingLastName '		=>	$BillingLastName,
		'BillingAddressLine1 '	=>	$BillingAddressLine1,
		'BillingAddressLine2 '	=>	$BillingAddressLine2,
		'BillingCity '			=>	$BillingCity,
		'BillingState '			=>	$BillingState,
		'BillingCountry '		=>	$BillingCountry,
		'BillingPostalCode '	=>	$BillingPostalCode,
		'BillingPhone '			=>	$BillingPhone
	)
);

if($AlsoShippingAddress==1){
	$AutoUpdateShippingPrice=(int)$_POST['AutoUpdateShippingPrice'];
	($AutoUpdateShippingPrice==1 && $BillingCountry!=$db->get_value('orders', $where, 'ShippingCountry')) && update_orders_shipping_info($OrderId, '', 1);
	
	$db->update('orders', $where, array(
			'ShippingTitle '		=>	$BillingTitle,
			'ShippingFirstName '	=>	$BillingFirstName,
			'ShippingLastName '		=>	$BillingLastName,
			'ShippingAddressLine1 '	=>	$BillingAddressLine1,
			'ShippingAddressLine2 '	=>	$BillingAddressLine2,
			'ShippingCity '			=>	$BillingCity,
			'ShippingState '		=>	$BillingState,
			'ShippingCountry '		=>	$BillingCountry,
			'ShippingPostalCode '	=>	$BillingPostalCode,
			'ShippingPhone '		=>	$BillingPhone
		)
	);
}

save_manage_log('修改订单账单地址');
js_location("view.php?OrderId=$OrderId&module=$module");
?>