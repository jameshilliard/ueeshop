<?php
$query_string=query_string('ProId');

if($_POST){
	
	$ProId=(int)$_POST['ProId'];
	$Qty=abs((int)$_POST['Qty']);
	$Color=$_POST['Color'];
	$SizeId=(int)$_POST['SizeId'];
	$Integral=(int)$_POST['Integral'];
	$IsGift=(int)$_POST['IsGift'];
	$CustomizeID=@implode('|',$_POST['customize']);
	$Font=$_POST['Font'];
	$Txta=$_POST['Txta'];
	$Txtb=$_POST['Txtb'];
	$Customized=$_POST['Customized'];
	$Location=$_POST['Location'];
}else{
	$ProId=(int)$_GET['ProId'];
	$Qty=abs((int)$_GET['Qty']);
	$ColorId=(int)$_GET['ColorId'];
	$SizeId=(int)$_GET['SizeId'];
	$Integral=(int)$_GET['Integral'];
	$IsGift=(int)$_GET['IsGift'];
}

$SizeId && $Size=addslashes($db->get_value('product_size', "SId='$SizeId'", 'Size'));

$customize_list=$db->get_all("product_customize","CateId='$Customized'");
for($i=0;$i<count($customize_list);$i++)
{
	
	$name=$db->get_value('product_customize_item',"IId='{$_POST['customize'][$i]}'",'Name');
	
	if($customize_list[$i]['NoProduct'])
	{
		$Customize.='<span style="font-weight:bold">'.$customize_list[$i]['Name'].'</span><br/>';
		
		if($Customized==2)
		{
			if($Txta)
			{
				$Customize.='Text:'.$Txta.'<br/>';
			}
			if($Location)
			{
				$Customize.='Location:'.$Location.'<br/>';
			}
		}
		else
		{
			if($Txta && $Txtb)
			{
				$Customize.='Text:<br/>'.$Txta.'<br/>'.$Txtb.'<br/>';
			}
		}
		if($Font)
		{
			$Customize.='Font:'.$Font;
		}
		if($Color)
		{
			$Customize.='<br/>Color:<div style="width:20px; height:20px; background:'.$Color.'"></div>';
		}
	}
	else
	{
		$Customize.='<span style="font-weight:bold">'.$customize_list[$i]['Name'].':</span>'.$name.'<br/>';	
	}
	
}

$Qty<=0 && $Qty=1;
$product_row=$db->get_one('product', "ProId='$ProId'");
!$product_row && js_location("$cart_url?module=list");
$where.=" and ProId='$ProId' and CustomizeID='$CustomizeID' and Size='$Size'";



if(!$db->get_row_count('shopping_cart', $where)){
	$db->insert('shopping_cart', array(
			'MemberId'	=>	(int)$_SESSION['member_MemberId'],
			'SessionId'	=>	$cart_SessionId,
			'ProId'		=>	$ProId,
			'CateId'	=>	(int)$product_row['CateId'],
			'Color'		=>	$Color,
			'Size'		=>	$Size,
			'Name'		=>	addslashes($product_row['Name']),
			'ItemNumber'=>	addslashes($product_row['ItemNumber']),
			'Weight'	=>	(float)$product_row['Weight'],
			'PicPath'	=>	str_replace('s_', '90X90_', $product_row['PicPath_0']),
			'Price'		=>	pro_add_to_cart_price($product_row, $Qty),
			'Qty'		=>	$Qty,
			'Url'		=>	addslashes(get_url('product', $product_row)),
			'AddTime'	=>	$service_time,
			'Integral'	=>	(int)$product_row['Integral']*$Qty,
			'IsGift'	=>	(int)$product_row['IsGift'],
			'Customize' =>	$Customize,
			'Font'		=>	$Font,
			'CustomizeID'=>	$CustomizeID,
			'Txta'		=>	$Txta,
			'Txtb'		=>	$Txtb,
			'Customized'=>	$Customized,
			'Location'	=>	$Location
		)
	);
}else{
	$Qty=$db->get_value('shopping_cart', $where, 'Qty')+$Qty;
	$db->update('shopping_cart', $where, array(
			'Qty'	=>	$Qty,
			'Price'	=>	pro_add_to_cart_price($product_row, $Qty)
		)
	);
}
js_location("$cart_url?module=list");
?>