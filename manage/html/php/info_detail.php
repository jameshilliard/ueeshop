<?php
$CateId=(int)$_GET['CateId'];
$start_row=(int)$_GET['start_row'];
$limit_count=50;	//每批次生成的文件数量
$where='1';
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'info_category');
$html_row=$db->get_limit('info', $where, '*', 'MyOrder desc, CateId asc', $start_row, $limit_count);
$html_row_count=$db->get_row_count('info', $where);	//总共需处理的记录数

for($html=0; $html<count($html_row); $html++){
	$start_row++;	//已处理的记录数
	//---------------------------------------------------------------------------------------------------------------------------------------------
	$info_row=$html_row[$html];
	include($site_root_path.'/inc/lib/info/detail.php');	//在适当的地方输出$info_detail即可
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
	$file=write_file(dirname($html_row[$html]['PageUrl']).'/', basename($html_row[$html]['PageUrl']), $html_contents, 1);
	echo get_lang('html.write_success').": {$file}<br>";
}

if($start_row<$html_row_count){	//分批次处理，未处理完
	echo "<meta http-equiv='refresh' content='1; URL=html.php?filename=info_detail&CateId=$CateId&start_row=$start_row'><br>";
	echo sprintf(get_lang('html.step_info'), $html_row_count, $start_row, "html.php?filename=info_detail&CateId=$CateId&start_row=$start_row");
	exit;
}
?>