<?php
$OId=$_GET['OId'];
?>
<div id="lib_order_place">
	<div>
		<strong>Your Order#<?=$OId;?> has placed, starting redirected to the payment.</strong><br><br><br><br><br><br>
		<img src="/images/lib/cart/loading.gif"><br><br><br><br>
		If this page appears longer than 3 seconds, <a href="<?=$cart_url;?>?module=payment&OId=<?=$OId;?>">click here</a> to continue payment.
	</div>
</div>
<script language="javascript">
setTimeout('window.location="<?=$cart_url;?>?module=payment&OId=<?=$OId;?>"', 3000);
</script>