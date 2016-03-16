<?php
/*
url可带参数：
CaseId:案例ID
*/

$CaseId=(int)$_GET['CaseId'];
$instance_row=$db->get_one('instance', "CaseId='$CaseId'");
?>