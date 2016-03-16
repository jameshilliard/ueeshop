<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');
include('inc/lib/article/detail_lang_0.php');
	$AId=isset($_GET['AId'])?$_GET['AId']:1;
	$article_row=$db->get_one('article',"AId='$AId'");
	$column=$article_row['Title'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=seo_meta($article_row['SeoTitle'],$article_row['SeoKeywords'],$article_row['SeoDescription']);?>
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
			<div class="pro_hd"><b><?=$column?></b></div>
			<div class="art_bd"><?=$article_detail_lang_0?></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>


