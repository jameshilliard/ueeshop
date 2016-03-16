<?php
if(count($_POST['select_ProId'])){
	$ProId=implode(',', $_POST['select_ProId']);
	$row=$db->get_all('product', "ProId in($ProId)");
	$img_dir=mk_dir('/images/orders/'.date('Y_m/', $service_time).($db->get_value('orders', $where, 'OId')).'/');
	
	for($i=0; $i<count($row); $i++){
		$Qty=(int)$_POST['Qty_'.$row[$i]['ProId']];
		$Qty<=0 && $Qty=1;
		$Color=$_POST['Color_'.$row[$i]['ProId']];
		$Size=$_POST['Size_'.$row[$i]['ProId']];
		$Integral=$row[$i]['Integral'];
		
		if(!$db->get_row_count('orders_product_list', "$where and ProId='{$row[$i]['ProId']}' and Color='$Color' and Size='$Size'")){
			$img_path=$img_dir.basename(str_replace('s_', '90X90_', $row[$i]['PicPath_0']));
			@copy($site_root_path.str_replace('s_', '90X90_', $row[$i]['PicPath_0']), $site_root_path.$img_path);
			
			$db->insert('orders_product_list', array(
					'OrderId'	=>	$OrderId,
					'ProId'		=>	(int)$row[$i]['ProId'],
					'CateId'	=>	(int)$row[$i]['CateId'],
					'Color'		=>	$Color,
					'Size'		=>	$Size,
					'Name'		=>	addslashes($row[$i]['Name']),
					'ItemNumber'=>	addslashes($row[$i]['ItemNumber']),
					'Weight'	=>	(float)$row[$i]['Weight'],
					'PicPath'	=>	$img_path,
					'Price'		=>	pro_add_to_cart_price($row[$i], $Qty),
					'Qty'		=>	$Qty,
					'Url'		=>	addslashes(get_url('product', $row[$i])),
					'Integral'	=>	$Integral
				)
			);
		}	//end if
	}	//enf for
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

save_manage_log('订单添加产品信息');
js_location("view.php?OrderId=$OrderId&module=product_list");
?>