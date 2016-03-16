<?php
/*
url可带参数：
CateId:类别ID
Keyword:搜索关键词（搜索案例名称）
ext:后台勾选框筛选，值对应搜索条件如ext_ary数组：
*/

//-------------------------------------------------------------------------------------------------------------------------------------------------

$ext_ary=array(1=>'IsInIndex=1', 2=>'IsClassic=1');
$CateId=(int)$_GET['CateId'];
$Keyword=$_GET['Keyword'];
$ext=(int)$_GET['ext'];

//-------------------------------------------------------------------------------------------------------------------------------------------------

$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'instance_category');
$Keyword && $where.=" and Name like '%$Keyword%'";
($ext && $ext_ary[$ext]) && $where.=" and {$ext_ary[$ext]}";

//-------------------------------------------------------------------------------------------------------------------------------------------------

$row_count=$db->get_row_count('instance', $where);
$total_pages=ceil($row_count/$page_count);
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*$page_count;
$instance_row=$db->get_limit('instance', $where, '*', 'MyOrder desc, CaseId desc', $start_row, $page_count);

//-------------------------------------------------------------------------------------------------------------------------------------------------
?>