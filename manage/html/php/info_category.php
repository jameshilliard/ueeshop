<?php
$page_count=20;
$CateId=(int)$_GET['CateId'];
$where='1';
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'info_category');
$html_row=$db->get_all('info_category', $where, '*', 'MyOrder desc, CateId asc');

for($html=0; $html<count($html_row); $html++){
	$where=get_search_where_by_CateId($html_row[$html]['CateId'], 'info_category');
	$row_count=$db->get_row_count('info', $where);
	$total_pages=ceil($row_count/$page_count);
	$total_pages==0 && $total_pages=1;	//最少一页
	
	for($page=1; $page<=$total_pages; $page++){
		//---------------------------------------------------------------------------------------------------------------------------------------------
		$start_row=($page-1)*$page_count;
		$info_row=$db->get_limit('info', $where, '*', 'MyOrder desc, InfoId desc', $start_row, $page_count);
		include($site_root_path.'/inc/lib/info/list_lang_0.php');	//在适当的地方输出$info_list_lang_0即可
		//---------------------------------------------------------------------------------------------------------------------------------------------
		
		ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="save" content="history" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<?=seo_meta($html_row[$html]['SeoTitle'], $html_row[$html]['SeoKeywords'], $html_row[$html]['SeoDescription']);?>
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
		$file=write_file($html_row[$html]['PageUrl'], "page-$page.html", $html_contents, 1);
		$page==1 && write_file($html_row[$html]['PageUrl'], 'index.html', $html_contents, 1);
		echo get_lang('html.write_success').": {$file}<br>";
	}
}
?>