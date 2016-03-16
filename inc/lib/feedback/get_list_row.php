<?php
$row_count=$db->get_row_count('feedback', $where);
$total_pages=ceil($row_count/$page_count);
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*$page_count;
$feedback_row=$db->get_limit('feedback', $where, '*', 'FId desc', $start_row, $page_count);
?>