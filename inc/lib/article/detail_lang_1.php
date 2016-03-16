<?php
!$article_row && include($site_root_path.'/inc/lib/article/get_detail_row.php');

ob_start();
?>
<div id="lib_article"><?=$article_row['Contents_lang_1'];?></div>
<?php
$article_detail_lang_1=ob_get_contents();
ob_clean();
?>