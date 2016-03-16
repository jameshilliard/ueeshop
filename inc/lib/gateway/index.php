<?php
include('../../site_config.php');
include('../../set/ext_var.php');
include('../../function.php');
include('../../fun/mysql.php');

$gateway=$_GET['gateway'];
$OId=$_GET['OId'];

$order_row=$db->get_one('orders', "OId='$OId' and OrderStatus in(1, 3)");
!$order_row && js_location("$cart_url?module=list");

$db->update('orders', "OId='$OId'", array(
		'PaymentOnlineCurrency'	=>	addslashes($_SESSION['Currency'])
	)
);

$domain=get_domain();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="save" content="history" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<?=seo_meta();?>
<link href="/css/global.css" rel="stylesheet" type="text/css" />
<link href="/css/lib.css" rel="stylesheet" type="text/css" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/lang/en.js"></script>
<script language="javascript" src="/js/global.js"></script>
<script language="javascript" src="/js/checkform.js"></script>
<script language="javascript" src="/js/swf_obj.js"></script>
<script language="javascript" src="/js/date.js"></script>
</head>

<body>
<?php
if($gateway=='Paypal'){
	$form_data = array( 
		'cmd'			=>	'_xclick',
		'business'		=>	$db->get_value('payment_method', 'PId=1', 'Account'),
		'item_name'		=>	$order_row['OId'],
		'amount'		=>	iconv_price(($order_row['TotalPrice']+$order_row['ShippingPrice'])*(1+$order_row['PayAdditionalFee']/100), 2),
		'currency_code'	=>	$_SESSION['Currency'],
		'return'		=>	"$domain$cart_url?module=complete&OId={$order_row['OId']}&act=payonline",
		'invoice'		=>	$order_row['OId'],
		'charset'		=>	'utf-8',
		'cancel_return'	=>	"$domain$member_url?module=orders&OId={$order_row['OId']}&act=detail",
		'notify_url'	=>	"$domain/inc/lib/gateway/paypal/notify.php?OId={$order_row['OId']}"
	);
?>
	<form id="paypal_form" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<?php foreach($form_data as $key=>$value){?>
			<input type="hidden" name="<?=$key;?>" value="<?=$value;?>" />
		<?php }?>
		<input type="submit" value="Submit" style="width:1px; height:1px;" />
	</form>
<?php }else{
	
     $MD5key = "CYik^AQg";				
     $MerNo = "886980";
	 /*
		where you need to modify
	 */
     $BillNo = $order_row['OId'];
     $Amount = iconv_price(($order_row['TotalPrice']+$order_row['ShippingPrice'])*(1+$order_row['PayAdditionalFee']/100), 2);
     $ReturnURL = "$domain/inc/lib/gateway/globebill/payresult.php";
	 $baseInfo="Allmypay|Tech|guangdongShenzhen|shenzhen|SZ|518000|tech@allmypay.com|0755-12345678|0|china bank|0";
	 /*
		Default
	 */
      $Currency = "15";					
     $Language = "2";					
	 $actionUrl="https://payment.ttopay.com/payment/Interface";
     $Remark = "php";  							
     $md5src = $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key;
     $MD5info = strtoupper(md5($md5src));

?>
	<form name="payForm" action="<?php echo $actionUrl ?>" method="post" id="paypal_form">
    <input type="hidden" name="MerNo" value="<?php echo $MerNo ?>">
    <input type="hidden" name="BillNo" value="<?php echo $BillNo ?>">
    <input type="hidden" name="Amount" value="<?php echo $Amount ?>">
    <input type="hidden" name="ReturnURL" value="<?php echo $ReturnURL ?>">
    <input type="hidden" name="Language" value="<?php echo $Language ?>">
    <input type="hidden" name="Currency" value="<?php echo $Currency ?>">
    <input type="hidden" name="MD5info" value="<?php echo $MD5info ?>">
    <input type="hidden" name="Remark" value="<?php echo $Remark ?>">
    <input type="submit" value="Submit" style="width:1px; height:1px;" />
	</form>
	<!--其他接口扩展-->
<?php }?>
<script language="javascript">
		$_('paypal_form').submit();
</script>
</body>
</html>