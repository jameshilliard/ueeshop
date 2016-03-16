<?php
$item_row=$db->get_all('orders_product_list', $where, '*', 'LId desc');
for($i=0; $i<count($item_row); $i++){
	$_Price=(float)$_POST['Price'][$i];
	$_Qty=(int)$_POST['Qty'][$i];
	$_Remark=$_POST['Remark'][$i];
	
	if($_Price!=$item_row[$i]['Price'] || $_Qty!=$item_row[$i]['Qty'] || $_Remark!=$item_row[$i]['Remark']){
		$db->update('orders_product_list', "LId='{$item_row[$i]['LId']}'", array(
				'Price'	=>	$_Price,
				'Qty'	=>	$_Qty,
				'Remark'=>	$_Remark
			)
		);
	}
}

if($product_weight==1){
	$db->update('orders', $where, array(
			'TotalWeight'	=>	$db->get_sum('orders_product_list', $where, 'Qty*Weight')
		)
	);
}

if((int)$_POST['AutoUpdatePrice']==1){	//更新运费及订单总价格
	$db->update('orders', $where, array(
			'TotalPrice'	=>	$db->get_sum('orders_product_list', $where, 'Qty*Price')
		)
	);
	
	update_orders_shipping_info($OrderId);
}

save_manage_log('修改订单产品信息');
js_location("view.php?OrderId=$OrderId&module=$module");
?>