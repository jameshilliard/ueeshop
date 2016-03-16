<?php
$query_string=query_string();

$OId=$_GET['OId'];
$order_row=$db->get_one('orders', "$where and OId='$OId'");
!$order_row && js_location("$cart_url?module=list");
(($order_row['OrderStatus']==1 || $order_row['OrderStatus']==3) && $_GET['act']!='payonline') && js_location("$cart_url?module=payment&OId=$OId");
?>
<div id="lib_cart_station"><a href="/">Home</a> &gt; <a href="<?=$member_url;?>?module=orders&OId=<?=$OId;?>&act=detail">Order#<?=$OId;?></a> &gt; Complete</div>
<div id="lib_cart_guid"><img src="/images/lib/cart/guid_4.gif" /></div>
<div id="lib_order_complete">
	<div class="order_info">Order Number:<?=$OId;?>&nbsp;&nbsp;&nbsp;<em>Order DT:<?=date('m/d/Y H:i:s', $order_row['OrderTime']);?></em></div>
	<div class="blank12"></div>
	<div class="contents">
		<div class="title">Dear <?=htmlspecialchars($_SESSION['member_FirstName'].' '.$_SESSION['member_LastName']);?>:</div><br />
		Thank you for your <a href="<?=$member_url;?>?module=orders&OId=<?=$OId;?>&act=detail">order#<?=$OId;?></a> with our online store!<br /><br />
        You currently have a pending order in your account. After payment is completed, your payment will be confirmed and we will start working on your order right away, so please signing in to your account and check your order. Our online store makes paying for your orders easy by providing a range of convenient payment options.<br /><br />
        If you have any other questions please contact our Customer Services!<br /><br />
        You also can log on to our Live-Chat. Our online sales operator will be more than happy to assist you.<br /><br />
        Have fun shopping!
	</div>
</div>