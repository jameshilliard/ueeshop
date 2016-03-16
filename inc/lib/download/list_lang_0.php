<?php
$query_string=query_string('page');
$turn_page_query_string=$website_url_type==0?"?$query_string&page=":'page-';

if(!$download_row){
	$page_count=20;
	$where='Language=0';
	include($site_root_path.'/inc/lib/download/get_list_row.php');
}

ob_start();
?>
<ul id="lib_download_list">
	<?php
	for($i=0; $i<count($download_row); $i++){
	?>
	<li>
		<div class="name">&#8226; <?=$download_row[$i]['Name'];?></div>
		<div class="download"><img src="/images/lib/download.gif" align="absmiddle" /> <a href="/inc/lib/download/download.php?DId=<?=$download_row[$i]['DId'];?>">点击下载</a></div>
	</li>
	<?php }?>
</ul>
<div class="blank6"></div>
<div id="turn_page"><?=turn_page($page, $total_pages, $turn_page_query_string, $row_count, $turn_page_ary['lang_0'][0], $turn_page_ary['lang_0'][1], $website_url_type);?></div>
<?php
$download_list_lang_0=ob_get_contents();
ob_clean();
?>