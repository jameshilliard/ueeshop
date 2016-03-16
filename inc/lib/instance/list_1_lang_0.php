<?php
$instance_img_width=160;	//成功案例图片宽度
$instance_img_height=120;	//成功案例图片高度
$contents_width=720;	//显示内容的div的宽度
$query_string=query_string('page');
$turn_page_query_string=$website_url_type==0?"?$query_string&page=":'page-';

if(!$instance_row){
	$page_count=10;
	$where='Language=0';	//基本搜索条件
	include($site_root_path.'/inc/lib/instance/get_list_row.php');
}

ob_start();
?>
<div id="lib_instance_list_1">
	<?php
	for($i=0; $i<count($instance_row); $i++){
		$url=get_url('instance', $instance_row[$i]);
	?>
	<div class="item" style="width:<?=$contents_width;?>px;">
		<div class="img" style="width:<?=$instance_img_width;?>px; height:<?=$instance_img_height;?>px"><div><a href="<?=$url;?>" target="_blank"><img src="<?=$instance_row[$i]['PicPath_0'];?>"/></a></div></div>
		<div class="info" style="width:<?=$contents_width-$instance_img_width-15;?>px;">
			<div class="proname"><a href="<?=$url;?>" target="_blank"><?=$instance_row[$i]['Name'];?></a></div>
			<div class="flh_180"><?=format_text($instance_row[$i]['BriefDescription']);?></div>
		</div>
		<div class="cline"><div class="line"></div></div>
	</div>
	<?php }?>
</div>
<div class="blank6"></div>
<div id="turn_page"><?=turn_page($page, $total_pages, $turn_page_query_string, $row_count, $turn_page_ary['lang_0'][0], $turn_page_ary['lang_0'][1], $website_url_type);?></div>
<?php
$instance_list_1_lang_0=ob_get_contents();
ob_clean();
?>