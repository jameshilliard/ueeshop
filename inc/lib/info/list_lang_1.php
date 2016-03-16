<?php
$query_string=query_string('page');
$turn_page_query_string=$website_url_type==0?"?$query_string&page=":'page-';

if(!$info_row){
	$page_count=20;
	$where='Language=1';
	include($site_root_path.'/inc/lib/info/get_list_row.php');
}

ob_start();
?>
<ul id="lib_info_list">
	<?php
	for($i=0; $i<count($info_row); $i++){
	?>
		<li>&#8226; <a href="<?=$info_row[$i]['ExtUrl']?$info_row[$i]['ExtUrl']:get_url('info', $info_row[$i]);?>"><?=$info_row[$i]['Title'];?></a></li>
	<?php }?>
</ul>
<div class="blank6"></div>
<div id="turn_page"><div class="en"><?=turn_page($page, $total_pages, "?$query_string&page=", $row_count, $turn_page_ary['lang_1'][0], $turn_page_ary['lang_1'][1], $website_url_type);?></div></div>
<?php
$info_list_lang_1=ob_get_contents();
ob_clean();
?>