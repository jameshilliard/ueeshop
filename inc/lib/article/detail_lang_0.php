<?php
!$article_row && include($site_root_path.'/inc/lib/article/get_detail_row.php');

ob_start();
?>
<div id="lib_article" style="color:#fff;"><?=$article_row['Contents'];?></div>
<?php
$article_detail_lang_0=ob_get_contents();
ob_clean();
?>