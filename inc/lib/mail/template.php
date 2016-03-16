<?php
ob_start();
?>
<div style="width:650px; margin:10px auto;">
	<!--<div style="background:#ccc; height:80px;"></div>-->
	<div style="padding:10px; font-size:12px; color:#333; line-height:180%; font-family:Arial, Helvetica, sans-serif;"><?=$mail_contents;?></div>
	<!--<div style="background:#ccc; height:60px;"></div>-->
</div>
<?php
$mail_contents=ob_get_contents();
ob_end_clean();
?>