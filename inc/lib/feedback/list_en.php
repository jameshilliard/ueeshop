<?php
$query_string=query_string('page');

if(!$feedback_row){
	$page_count=10;
	$where='Site="en"';
	include($site_root_path.'/inc/lib/feedback/get_list_row.php');
}

ob_start();
?>
<div id="lib_feedback_list">
	<?php
	for($i=0; $i<count($feedback_row); $i++){
	?>
	<div class="list">
		<div class="face"><img src="/images/lib/feedback_face/<?=$feedback_row[$i]['Face'];?>.gif" vspace="8"><br><?=htmlspecialchars($feedback_row[$i]['Name']);?></div>
		<div class="txt">
			<div class="subject"><?=htmlspecialchars($feedback_row[$i]['Subject']);?></div>
			<div class="message">[ <font>Message</font> ]<br /><?=format_text($feedback_row[$i]['Message']);?></div>
			<div class="reply">Reply:<br /><?=format_text($feedback_row[$i]['Reply']);?></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php }?>
</div>
<div id="turn_page"><div class="en"><?=turn_page($page, $total_pages, "?$query_string&page=", $row_count, '<<', '>>');?></div></div>
<?php
$feedback_list_en=ob_get_contents();
ob_clean();
?>