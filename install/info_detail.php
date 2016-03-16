<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');
include('inc/lib/info/detail.php');
	$InfoId=isset($_GET['InfoId'])?$_GET['InfoId']:1;
	$info_row=$db->get_one('info',"InfoId='$InfoId'");
	$column=$info_row['Title'];
	$CateId=$info_row['CateId'];
	$info_cat=$db->get_one('info_category',"CateId='$CateId'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=seo_meta($info_row['SeoTitle'],$info_row['SeoKeywords'],$info_row['SeoDescription']);?>
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
<div id="wrap">
	<?php include('inc/header.php'); ?>
	<div id="inside">
		<div class="proR">
			<div class="pro_hd"><h6>News</h6></div>
			<div class="art_bd"><?=$info_detail?></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>



