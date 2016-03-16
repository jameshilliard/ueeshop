<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');
include('inc/lib/product/list_lang_0.php');
	$CateId=isset($_GET['CateId'])?$_GET['CateId']:1;
	$product_cat=$db->get_one('product_category',"CateId='$CateId'");
	$Name=$product_cat['Category'];
	$UId=$product_cat['UId'];
	$Cate=get_top_CateId_by_UId($UId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=seo_meta($product_cat['SeoTitle'],$product_cat['SeoKeywords'],$product_cat['SeoDescription']);?>
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
			<div class="pro_hd"><b><?=$Name?></b></div>
			<?php

				echo $product_list_lang_0;

			?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>

