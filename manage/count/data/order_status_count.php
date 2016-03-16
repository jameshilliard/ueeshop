<?php
include('../../../inc/site_config.php');
include('../../../inc/set/ext_var.php');
include('../../../inc/fun/mysql.php');
include('../../../inc/function.php');
include('../../../inc/manage/config.php');
include('../../../inc/manage/do_check.php');

check_permit('order_status_count');

$where='1';
$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$OrderTime_S=$_GET['OrderTime_S'];
$OrderTime_E=$_GET['OrderTime_E'];

$FullName && $where.=" and concat(ShippingFirstName, ' ', ShippingLastName) like '%$FullName%'";
$Email && $where.=" and Email like '%$Email%'";
if($OrderTime_S!='' && $OrderTime_E!=''){
	$time_start=@strtotime($OrderTime_S);
	$time_end=@strtotime($OrderTime_E);
	$where.=" and OrderTime between $time_start and $time_end";
}

$count_rs=$db->query("select OrderStatus, count(*) as row_count from orders where $where group by OrderStatus");
$data_ary=array();
while($count_row=mysql_fetch_assoc($count_rs)){
	$data_ary[$count_row['OrderStatus']]=$count_row['row_count'];
}

$color=array('#FA6400', '#B0DE09', '#CD0D74', '#0D8ECF', '#0C098D', '#078F2D', '#DEB204');
$data='';
$j=0;
foreach($order_status_ary as $key=>$value){
	$data.="<slice title=\"$value\" pull_out=\"true\" color=\"{$color[$j]}\">".(int)$data_ary[$key]."</slice>\r\n";
	$j++;
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<pie><?=$data;?></pie>