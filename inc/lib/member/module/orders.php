<?php
$query_string=query_string(array('page', 'do'));

$db->update('orders', "OrderStatus=5 and ShippingTime+15*3600*24<$service_time", array(
		'OrderStatus'	=>	6
	)
);

if($_POST['data']=='member_cancel_orders'){
	$OId=$_GET['OId'];
	$CancelReason=$_POST['CancelReason'];
	$db->update('orders', "$where and OId='$OId' and OrderStatus in(1, 3)", array(
			'OrderStatus'	=>	7,
			'CancelReason'	=>	$CancelReason
		)
	);
	js_location("$member_url?module=orders&OId=$OId&act=detail");
}

if($_GET['do']=='confirm_receiving'){
	$OId=$_GET['OId'];
	$db->update('orders', "$where and OId='$OId' and OrderStatus=5", array(
			'OrderStatus'	=>	6
		)
	);
	js_location("$member_url?$query_string");
}

$act=$_GET['act'];
$status=abs((int)$_GET['status']);
($status && !array_key_exists($status, $order_status_ary)) && $status=$order_default_status;
$act_ary=array('list', 'detail', 'cancel');
!in_array($act, $act_ary) && $act=$act_ary[0];
?>
<div id="lib_member_orders">
	<?php
	if($act=='list'){
		$page_count=20;
		$status && $where.=" and OrderStatus='$status'";
		$row_count=$db->get_row_count('orders', $where);
		$total_pages=ceil($row_count/$page_count);
		$page=(int)$_GET['page'];
		$page<1 && $page=1;
		$page>$total_pages && $page=1;
		$start_row=($page-1)*$page_count;
		$order_row=$db->get_limit('orders', $where, '*', 'OrderId desc', $start_row, $page_count);
	?>
		<div class="lib_member_title"><a href="<?=$member_url;?>?module=orders&act=list">Order History</a><?=$status?' - '.$order_status_ary[$status]:'';?></div>
		<table width="100%" border="0" cellpadding="0" cellspacing="1" class="item_list">
			<tr class="tb_title">
				<td width="16%" nowrap>Order Number</td>
				<td width="10%" nowrap>Item Costs</td>
				<td width="12%" nowrap>Shipping Charges</td>
				<td width="7%" nowrap>Fee</td>
				<td width="10%" nowrap>Grand Total</td>
				<td width="9%" nowrap>Order DT</td>
				<td width="18%" nowrap>Order Status</td>
				<?php if(($status==0 || $status==1 || $status==3) && count($order_row)){?><td width="18%"></td><?php }?>
			</tr>
			<?php
			for($i=0; $i<count($order_row); $i++){
			?>
			<tr class="item_list item_list_out" onmouseover="this.className='item_list item_list_over';" onmouseout="this.className='item_list item_list_out';" align="center">
				<td nowrap><a href="<?=$member_url;?>?module=orders&OId=<?=$order_row[$i]['OId'];?>&act=detail" class="detail_link"><?=$order_row[$i]['OId'];?></a></td>
				<td nowrap><?=iconv_price($order_row[$i]['TotalPrice']);?></td>
				<td nowrap><?=iconv_price($order_row[$i]['ShippingPrice']);?></td>
				<td nowrap><?=$order_row[$i]['PayAdditionalFee'];?>%</td>
				<td nowrap><?=iconv_price(($order_row[$i]['TotalPrice']+$order_row[$i]['ShippingPrice'])*(1+$order_row[$i]['PayAdditionalFee']/100));?></td>
				<td nowrap><?=date('d/m-Y', $order_row[$i]['OrderTime']);?></td>
				<td nowrap><?=$order_status_ary[$order_row[$i]['OrderStatus']];?></td>
				<?php if($status==0 || $status==1 || $status==3){?><td nowrap><?php if($order_row[$i]['OrderStatus']==1 || $order_row[$i]['OrderStatus']==3){?><a href="<?=$cart_url;?>?module=payment&OId=<?=$order_row[$i]['OId'];?>" class="qa_btn">payment</a><a href="<?=$member_url;?>?module=orders&OId=<?=$order_row[$i]['OId'];?>&act=cancel" class="qa_btn">cancel</a><?php }?></td><?php }?>
			</tr>
			<?php }?>
			<?php if(!count($order_row)){?>
			<tr>
				<td align="center" height="150" colspan="8" bgcolor="#ffffff">not found!</td>
			</tr>
			<?php }?>
		</table>
		<div id="turn_page"><div class="en"><?=turn_page($page, $total_pages, "?$query_string&page=", $row_count, '<<', '>>');?></div></div>
	<?php }elseif($act=='cancel'){?>
		<div class="lib_member_title"><a href="<?=$member_url;?>?module=orders&act=list">Order History</a> - Cancel Order</div>
		<?php
		$OId=$_GET['OId'];
		$order_row=$db->get_one('orders', "$where and OId='$OId' and OrderStatus in(1, 3)");
		!$order_row && js_location("$member_url?module=orders&act=list");
		?>
		<div class="blank12"></div>
		<div class="lib_member_item_card">
			<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_cancel_orders_form" OnSubmit="return checkForm(this);">
				<div class="cancel">
					<div class="info">
						Dear <?=htmlspecialchars($_SESSION['member_FirstName'].' '.$_SESSION['member_LastName']);?>:<br /><br />
						Thanks for your Order, Please tell us the reason you want to cancel:&nbsp;&nbsp;&nbsp;&nbsp;View order details
					</div>
					<textarea name="CancelReason" class="form_area" check="Sorry, the reason you want to cancel information is required!~*"></textarea>
					<div class="btn"><input name="Submit" type="submit" class="form_button form_button_130" value="Cancel Order !">&nbsp;&nbsp;&nbsp;<input type="button" name="return" value="<< Back" onclick="window.history.back(-1);" class="form_button" /></div>
				</div>
				<input type="hidden" name="data" value="member_cancel_orders" />
			</form>
		</div>
	<?php
	}else{
		$OId=$_GET['OId'];
		$order_row=$db->get_one('orders', "$where and OId='$OId'");
		!$order_row && js_location("$member_url?module=orders&act=list");
	?>
		<div class="lib_member_title"><a href="<?=$member_url;?>?module=orders&act=list">Order History</a> - Order Detail</div>
		<?php if($order_row['OrderStatus']==1 || $order_row['OrderStatus']==3){?>
			<div class="payment_tips">
				Your order status is in <strong><?=$order_status_ary[$order_row['OrderStatus']];?></strong> right now, not completed payment yet. Please <a href="<?=$cart_url;?>?module=payment&OId=<?=$order_row['OId'];?>">click here</a> to continue payment.<br />
				Without payment, this order will be cancelled<br />
				If you have any trouble paying, you can contact our teams.
			</div>
		<?php }?>
		<div class="order_index">PO#<?=$OId;?>&nbsp;&nbsp;<em>(Status: <strong><?=$order_status_ary[$order_row['OrderStatus']];?></strong>, DT: <strong><?=date('d/m-Y', $order_row['OrderTime']);?></strong>)</em></div>
		<div class="blank12"></div>
		<div class="detail">
			<div>Order Details</div>
		</div>
		<div class="detail_card">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
			  <tr>
				<td width="110">Order Number:</td>
				<td><?=$order_row['OId'];?></td>
			  </tr>
			  <tr>
				<td>Order DT:</td>
				<td><?=date('d/m-Y H:i:s', $order_row['OrderTime']);?></td>
			  </tr>
			  <tr>
				<td>Order Status:</td>
				<td><?=$order_status_ary[$order_row['OrderStatus']];?><?php if($order_row['OrderStatus']==5){?><a href="<?=$member_url.'?'.$query_string;?>&do=confirm_receiving" class="confirm_receiving" onClick="if(!confirm('Are you sure?')){return false;}else{return true;};">Confirm Receiving</a><?php }?></td>
			  </tr>
			  <tr>
				<td>Payment Method:</td>
				<td><?=$order_row['PaymentMethod'];?></td>
			  </tr>
			  <tr>
				<td>Shipping Method:</td>
				<td><?=$order_row['Express'];?></td>
			  </tr>
			  <?php if($order_row['OrderStatus']==5 || $order_row['OrderStatus']==6){?>
				  <tr>
					<td>Tracking Number:</td>
					<td><?=$order_row['TrackingNumber'];?> (<?=date('m/d-Y', $order_row['ShippingTime']);?>)</td>
				  </tr>
			  <?php }?>
			  <tr>
				<td>Item Costs:</td>
				<td><?=iconv_price($order_row['TotalPrice']);?></td>
			  </tr>
			  <tr>
				<td>Shipping Charges:</td>
				<td><?=iconv_price($order_row['ShippingPrice']);?></td>
			  </tr>
			  <tr>
				<td>Pay Additional Fee:</td>
				<td><?php if($order_row['PayAdditionalFee']!=0){?><?=iconv_price($order_row['TotalPrice']+$order_row['ShippingPrice']);?> * <?=$order_row['PayAdditionalFee'];?>% = <?=$order_row['PayAdditionalFee']<0?'-':'';?><?=iconv_price(($order_row['TotalPrice']+$order_row['ShippingPrice'])*(abs($order_row['PayAdditionalFee'])/100));?><?php }else{?><?=iconv_price(0);?><?php }?></td>
			  </tr>
			  <tr>
				<td>Grand Total:</td>
				<td><?=iconv_price(($order_row['TotalPrice']+$order_row['ShippingPrice'])*(1+$order_row['PayAdditionalFee']/100));?></td>
			  </tr>
			</table>
			<div class="blank20"></div>
			<div class="item_info">Shipping Method:</div>
			<div class="shipping"><strong><?=htmlspecialchars($order_row['Express']);?></strong> ( <?php if($product_weight==1){?>Parcel Weight: <?=$order_row['TotalWeight'];?> KG, <?php }?>Shipping Charges: <?=iconv_price($order_row['ShippingPrice']);?> )<?php if($product_weight==1){?><br /><div class="shipping_price">( Shipping Price: First <?=$order_row['FirstWeight'];?> KG : <?=iconv_price($order_row['FirstPrice']);?> / <?=$order_row['ExtWeight'];?> KG : <?=iconv_price($order_row['ExtPrice'])?> )</div><?php }?></div>
			<div class="blank20"></div>
			<div class="item_info">Special Instructions or Comments:</div>
			<div class="flh_180"><?=format_text($order_row['Comments']);?></div>
			<div class="blank20"></div>
			<div class="item_info">Order Items:</div>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="detail_item_list">
				<tr class="tb_title">
					<td width="14%">Pictures</td>
					<td width="50%">Product</td>
					<td width="12%">Price</td>
					<td width="12%">Quantity</td>
					<td width="12%" class="last">Total</td>
				</tr>
				<?php
				$pro_count=0;
				$item_row=$db->get_all('orders_product_list', "OrderId='{$order_row['OrderId']}'", '*', 'ProId desc, LId desc');
				for($i=0; $i<count($item_row); $i++){
					$pro_count+=$item_row[$i]['Qty'];
				?>
				<tr class="item_list item_list_out" onmouseover="this.className='item_list item_list_over';" onmouseout="this.className='item_list item_list_out';" align="center">
					<td valign="top"><table width="92" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td height="92" align="center" class="item_img"><a href="<?=$item_row[$i]['Url'];?>" target="_blank"><img src="<?=$item_row[$i]['PicPath'];?>" /></a></td></tr></table></td>
					<td align="left" class="flh_150">
						<a href="<?=$item_row[$i]['Url'];?>" target="_blank" class="proname"><?=$item_row[$i]['Name'];?></a><br />
						Item No.: <a href="<?=$item_row[$i]['Url'];?>" target="_blank" class="proname"><?=$item_row[$i]['ItemNumber'];?></a><br />
						<?php if($item_row[$i]['Color']){?>Color: <?=$item_row[$i]['Color'];?><br /><?php }?>
						<?php if($item_row[$i]['Size']){?>Size: <?=$item_row[$i]['Size'];?><br /><?php }?>
						<?php if($product_weight==1){?>Weight: <?=$item_row[$i]['Weight'];?> KG<br /><?php }?>
                        <?php if($item_row[$i]['Customize']){?><?=$item_row[$i]['Customize'];?><?php }?>
						Purchasing Remark: <?=htmlspecialchars($item_row[$i]['Remark']);?>
					</td>
					<td><?=iconv_price($item_row[$i]['Price']);?></td>
					<td><?=$item_row[$i]['Qty'];?></td>
					<td><?=iconv_price($item_row[$i]['Price']*$item_row[$i]['Qty']);?></td>
				</tr>
				<?php }?>
				<tr class="total">
					<td colspan="3">&nbsp;</td>
					<td><?=$pro_count;?></td>
					<td><?=iconv_price($order_row['TotalPrice']);?></td>
				</tr>
			</table>
		</div>
	<?php }?>
</div>