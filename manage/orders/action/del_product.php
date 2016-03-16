<?php
if(count($_POST['select_LId'])){
	$LId=implode(',', $_POST['select_LId']);
	$row=$db->get_all('orders_product_list', "LId in($LId)");
	for($i=0; $i<count($row); $i++){
		del_file($row[$i]['PicPath']);
	}
	
	$db->delete('orders_product_list', "LId in($LId)");
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

save_manage_log('删除订单产品信息');
js_location("view.php?OrderId=$OrderId&module=$module");
?>