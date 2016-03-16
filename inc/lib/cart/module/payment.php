<?php
$query_string=query_string();

$OId=$_GET['OId'];
$order_row=$db->get_one('orders', "$where and OId='$OId'");
if(!$order_row){
	js_location("$cart_url?module=list");
}

if($order_row['OrderStatus']!=1 && $order_row['OrderStatus']!=3){
	js_location("$cart_url?module=complete&OId=$OId");
}

$info_of_sender=$info_of_sender_1=$db->get_one('orders_payment_info', "OrderId='{$order_row['OrderId']}'");

if($_POST['data']=='make_payment'){
	$FirstName=$_POST['FirstName'];
	$LastName=$_POST['LastName'];
	$SentMoney=(float)$_POST['SentMoney'];
	$MTCNNumber=$_POST['MTCNNumber'];
	$Currency=$_POST['Currency'];
	$Country=$_POST['Country'];
	$SentTime=$_POST['SentTime'];
	$BankTransactionNumber=$_POST['BankTransactionNumber'];
	$Contents=$_POST['Contents'];
	
	$data=array(
		'OrderId'				=>	(int)$order_row['OrderId'],
		'FirstName'				=>	$FirstName,
		'LastName'				=>	$LastName,
		'SentMoney'				=>	$SentMoney,
		'MTCNNumber'			=>	$MTCNNumber,
		'Currency'				=>	$Currency,
		'Country'				=>	$Country,
		'SentTime'				=>	$SentTime,
		'BankTransactionNumber'	=>	$BankTransactionNumber,
		'Contents'				=>	$Contents,
		'PostTime'				=>	$service_time
	);
	
	if($info_of_sender){
		$db->update('orders_payment_info', "OrderId='{$order_row['OrderId']}'", $data);
	}else{
		$db->insert('orders_payment_info', $data);
	}
	
	$db->update('orders', "OId='$OId'", array(
			'OrderStatus'	=>	2
		)
	);
	
	js_location("$cart_url?module=complete&OId=$OId");
}

$info_of_sender['FirstName']=='' && $info_of_sender['FirstName']=$_SESSION['member_FirstName'];
$info_of_sender['LastName']=='' && $info_of_sender['LastName']=$_SESSION['member_LastName'];
$info_of_sender['Currency']=='' && $info_of_sender['Currency']=$mCfg['ExchangeRate']['Default'];
$info_of_sender['Country']=='' && $info_of_sender['Country']=$order_row['ShippingCountry'];
$info_of_sender['SentTime']=='' && $info_of_sender['SentTime']=date('m/d/Y', $service_time);

$payment_method=$db->get_all('payment_method', 'IsInvocation=1', '*', 'MyOrder desc, PId asc');
$grand_price=$order_row['TotalPrice']+$order_row['ShippingPrice'];	//订单总价
?>
<div id="lib_cart_station"><a href="/">Home</a> &gt; <a href="<?=$member_url;?>?module=orders&OId=<?=$OId;?>&act=detail">Order#<?=$OId;?></a> &gt; Payment</div>
<div id="lib_cart_guid"><img src="/images/lib/cart/guid_3.gif" /></div>
<div id="lib_order_payment">
	<div class="order_info">Order Number:<?=$OId;?>&nbsp;&nbsp;&nbsp;<em>Order DT:<?=date('m/d/Y H:i:s', $order_row['OrderTime']);?></em></div>
	<div class="blank12"></div>
	<div><strong>Choose your payment method:</strong></div>
	<div class="payment">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="250" valign="top">
				<?php
				$default_method=0;
				for($i=0; $i<count($payment_method); $i++){
					$order_row['PaymentMethod']==$payment_method[$i]['Name'] && $default_method=$i;
				?>
					<a href="javascript:void(0);" id="payment_method_a_<?=$i;?>" class="payment_method" onclick="this.blur(); change_payment_method(<?=$i;?>, <?=$payment_method[$i]['PId'];?>);"><img src="<?=$payment_method[$i]['LogoPath'];?>" align="absmiddle" /><?php if($payment_method[$i]['AdditionalFee']!=0){?><span class="<?=$payment_method[$i]['AdditionalFee']>0?'add':'less';?>">(<?=($payment_method[$i]['AdditionalFee']>0?'+':'').$payment_method[$i]['AdditionalFee'];?>% Fee)</span><?php }?></a>
				<?php }?>
			</td>
			<td valign="top" class="payment_info" height="380">
				<?php
				$total_price=array();
				for($i=0; $i<count($payment_method); $i++){
					$total_price=iconv_price($grand_price+$grand_price*$payment_method[$i]['AdditionalFee']/100, 2);
					if((!$info_of_sender_1 || $order_row['PaymentMethod']!=$payment_method[$i]['Name'])){
						$info_of_sender['SentMoney']=$total_price;
					}else{
						$info_of_sender['SentMoney']=$info_of_sender_1['SentMoney'];
					}
				?>
					<div class="contents" id="payment_info_contents_<?=$i;?>">
						<div class="title">Payment With <?=$payment_method[$i]['Name'];?></div>
						<div class="tips">Your need payments: <span><?=iconv_price($grand_price);?></span><?php if($payment_method[$i]['AdditionalFee']!=0){?> <?=$payment_method[$i]['AdditionalFee']>=0?'+':'-';?> <span><?=iconv_price($grand_price*abs($payment_method[$i]['AdditionalFee'])/100);?></span> (Pay Additional Fee) = <span><?=iconv_price(0, 1).$total_price;?></span><?php }?></div>
						<div class="txt"><?=$payment_method[$i]['Description'];?></div>
						<div class="ext">
						<?php if($payment_method[$i]['PId']==1){?>
							<a href="/inc/lib/gateway/index.php?gateway=Paypal&OId=<?=$OId;?>" class="paypal_button" target="_blank">Continue To Pay &gt;&gt;</a>
							<div class="tips">if you pay by paypal is unsuccessful, please use your credit card to payment via below Visa/Master Channel.</div>
						<?php }elseif($payment_method[$i]['PId']==2){?>
							  <a href="/inc/lib/gateway/index.php?gateway=globebill&OId=<?=$OId;?>" class="paypal_button" target="_blank">Continue To Pay &gt;&gt;</a>
							<div class="tips">if you pay by paypal is unsuccessful, please use your credit card to payment via below Visa/Master Channel.</div>
						<?php }elseif($payment_method[$i]['PId']==3){?>
							<a href="/inc/lib/gateway/index.php?gateway=globebill&OId=<?=$OId;?>" class="paypal_button" target="_blank">Continue To Pay &gt;&gt;</a>
							<div class="tips">if you pay by paypal is unsuccessful, please use your credit card to payment via below Visa/Master Channel.</div>
						<?php }elseif($payment_method[$i]['PId']==4){?>
							<div class="tips">If you have any trouble paying, you can contact our teams.</div>
							<form action="<?=$cart_url.'?'.$query_string;?>" method="post" name="make_payment_form" OnSubmit="return checkForm(this);">
								<div class="sender_title"><strong><?=$payment_method[$i]['Name'];?> info of Sender:</strong></div>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="sender_info">
									<tr>
										<td width="10%" nowrap>Sent Time: <font class="fc_red">*</font></td>
										<td width="30%" nowrap><input name="SentTime" type="text" onclick="SelectDate(this);" value="<?=htmlspecialchars($info_of_sender['SentTime']);?>" check="Sent Time is required!~*" class="form_input" maxlength="10" /></td>
										<td width="10%" nowrap>Bank Transaction No.: <font class="fc_red">*</font></td>
										<td width="50%" nowrap><input name="BankTransactionNumber" type="text" value="<?=htmlspecialchars($info_of_sender['BankTransactionNumber']);?>" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" check="Bank Transaction No. is required!~*" class="form_input" maxlength="20" /></td>
									</tr>
									<tr>
										<td nowrap>Sent Money: <font class="fc_red">*</font></td>
										<td nowrap><input name="SentMoney" type="text" value="<?=htmlspecialchars($info_of_sender['SentMoney']);?>" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" check="Sent Money is required!~*" class="form_input" maxlength="10" /></td>
										<td nowrap>Currency: <font class="fc_red">*</font></td>
										<td nowrap><?=exchange_rate_select('Currency', $info_of_sender['Currency']);?></td>
									</tr>
									<tr>
									</tr>
									<tr>
										<td>Contents:</td>
										<td colspan="3"><textarea name="Contents" class="form_area contents"><?=htmlspecialchars($info_of_sender['Contents']);?></textarea><br /><span id="Contents_tips"></span></td>
									</tr>
									<tr>
										<td></td>
										<td colspan="3"><input type="submit" name="submit" value="Complete Order Now" class="form_button form_button_130" /></td>
									</tr>
								</table>
								<input type="hidden" name="data" value="make_payment" />
							</form>
						<?php }?>
						</div>
					</div>
				<?php }?>
			</td>
		  </tr>
		</table>
	</div>
</div>
<script language="javascript">
var set_payment_method_obj=get_ajax_obj();

function change_payment_method(obj, PId){
	for(i=0; i<<?=count($payment_method);?>; i++){
		$_('payment_method_a_'+i).className='payment_method';
		$_('payment_info_contents_'+i).style.display='none';
	}
	$_('payment_method_a_'+obj).className='payment_method payment_method_on';
	$_('payment_info_contents_'+obj).style.display='block';
	
	if(PId){
		set_payment_method_obj.open('GET', '<?=$cart_url;?>?module=set_payment_method&PId='+PId+'&OId=<?=$OId;?>&r='+Math.random(), true);
		set_payment_method_obj.send(null);
	}
}

change_payment_method(<?=$default_method;?>, 0);
</script>