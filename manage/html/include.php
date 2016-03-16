<?php
ob_start();
include($site_root_path.'/inc/header.php');
$include_header_info=ob_get_contents();
ob_end_clean();

ob_start();
include($site_root_path.'/inc/procate.php');
$include_lefter_info=ob_get_contents();
ob_end_clean();

ob_start();
include($site_root_path.'/inc/footer.php');
$include_footer_info=ob_get_contents();
ob_end_clean();
?>