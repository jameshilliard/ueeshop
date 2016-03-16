<?php
$TotalPrice=(float)$_POST['TotalPrice'];
$ShippingPrice=(float)$_POST['ShippingPrice'];
$PayAdditionalFee=(float)$_POST['PayAdditionalFee'];

$db->update('orders', $where, array(
		'TotalPrice'		=>	$TotalPrice,
		'ShippingPrice'		=>	$ShippingPrice,
		'PayAdditionalFee'	=>	$PayAdditionalFee
	)
);

save_manage_log('修改订单基本信息');
js_location("view.php?OrderId=$OrderId&module=$module");
?>