<?php
/*
url可带参数：
AId:信息页ID
*/

$AId=(int)$_GET['AId'];
$article_row=$db->get_one('article', "AId='$AId'");
?>