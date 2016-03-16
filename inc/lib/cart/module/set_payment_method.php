<?php
$OId=$_GET['OId'];
$PId=(int)$_GET['PId'];

$payment_method_row=$db->get_one('payment_method', "IsInvocation=1 and PId='$PId'", 'Name, AdditionalFee');
!$payment_method_row && exit();

$db->update('orders', "$where and OId='$OId' and OrderStatus in(1, 3)", array(
		'PayAdditionalFee'	=>	(float)$payment_method_row['AdditionalFee'],
		'PaymentMethod'		=>	addslashes($payment_method_row['Name'])
	)
);
?>