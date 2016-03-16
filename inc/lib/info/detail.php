<?php
!$info_row && include($site_root_path.'/inc/lib/info/get_detail_row.php');

ob_start();
?>
<div id="lib_info_detail">
	<div class="title"><?=$info_row['Title'];?></div>
	<div class="contents"><?=$db->get_value('info_contents', "InfoId='{$info_row['InfoId']}'", 'Contents');?></div>
</div>
<?php
$info_detail=ob_get_contents();
ob_clean();
?>