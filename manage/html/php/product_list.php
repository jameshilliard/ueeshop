<?php
$PageType_ary=array(
	'All'			=>	array('Product', '1', '/products/'),	//参数：栏目名称、搜索条件、保存文件夹
	'IsHot'			=>	array('Hot Sale', 'IsHot=1', '/products/hot/'),
	'IsRecommend'	=>	array('Recommended', 'IsRecommend=1', '/products/recommend/'),
	'IsNew'			=>	array('New Product', 'IsNew=1', '/products/new/'),
	'IsSpecialOffer'=>	array('Special Offer', 'IsSpecialOffer=1', '/products/special-offer/')
);
$PageType=$_GET['PageType'];
$page_count=20;	//每页显示的数量
$base_where='1';

foreach($PageType_ary as $PageType_key=>$PageType_value){
	if($PageType!='' && $PageType_key!=$PageType){	//指定了刷新哪些页面
		continue;
	}
	
	$where="$base_where and {$PageType_value[1]}";
	$row_count=$db->get_row_count('product', $where);
	$total_pages=ceil($row_count/$page_count);
	$total_pages==0 && $total_pages=1;	//最少一页
	
	for($page=1; $page<=$total_pages; $page++){
		//---------------------------------------------------------------------------------------------------------------------------------------------
		$start_row=($page-1)*$page_count;
		$product_row=$db->get_limit('product', $where, '*', 'MyOrder desc, ProId desc', $start_row, $page_count);
		include($site_root_path.'/inc/lib/product/list_lang_0.php');	//在适当的地方输出$product_list_lang_0即可
		//---------------------------------------------------------------------------------------------------------------------------------------------
		
		ob_start();
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
<?=$include_header_info;?>
<div id="main">
	
</div>
<?=$include_footer_info;?>
</body>
</html>
<?php
		$html_contents=ob_get_contents();
		ob_end_clean();
		$file=write_file($PageType_value[2], "page-$page.html", $html_contents, 1);
		$page==1 && write_file($PageType_value[2], 'index.html', $html_contents, 1);
		echo get_lang('html.write_success').": {$file}<br>";
	}
}
?>