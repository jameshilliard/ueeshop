<?php
ob_start();

if($orders_row['PaymentMethod']=='Western Union' || $orders_row['PaymentMethod']=='Money Gram'){
	$payment_info=$db->get_one('orders_payment_info', $where);
	if($payment_info){
?>
	<tr>
		<td><?=get_lang('orders.payment_info');?>:</td>
		<td style="padding:0;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="sec_table">
			  <tr>
				<td width="5%" nowrap>First Name:</td>
				<td width="95%"><?=htmlspecialchars($payment_info['FirstName']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Last Name:</td>
				<td><?=htmlspecialchars($payment_info['LastName']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Sent Money:</td>
				<td><?=htmlspecialchars($payment_info['SentMoney']);?></td>
			  </tr>
			  <tr>
				<td nowrap>MTCN# No.:</td>
				<td><?=htmlspecialchars($payment_info['MTCNNumber']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Currency:</td>
				<td><?=htmlspecialchars($payment_info['Currency']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Country:</td>
				<td><?=htmlspecialchars($payment_info['Country']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Contents:</td>
				<td><?=format_text($payment_info['Contents']);?></td>
			  </tr>
		  </table>
		</td>
	</tr>
<?php
	}
}elseif($orders_row['PaymentMethod']=='Bank Transfer'){
	$payment_info=$db->get_one('orders_payment_info', $where);
	if($payment_info){
?>
	<tr>
		<td><?=get_lang('orders.payment_info');?>:</td>
		<td style="padding:0;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="sec_table">
			  <tr>
				<td width="5%" nowrap>Sent Time:</td>
				<td width="95%"><?=htmlspecialchars($payment_info['SentTime']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Bank Transaction No.:</td>
				<td><?=htmlspecialchars($payment_info['BankTransactionNumber']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Sent Money:</td>
				<td><?=htmlspecialchars($payment_info['SentMoney']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Currency:</td>
				<td><?=htmlspecialchars($payment_info['Currency']);?></td>
			  </tr>
			  <tr>
				<td nowrap>Contents:</td>
				<td><?=format_text($payment_info['Contents']);?></td>
			  </tr>
		  </table>
		</td>
	</tr>
<?php
	}
}

$payment_info_detail=ob_get_contents();
ob_end_clean();
?>