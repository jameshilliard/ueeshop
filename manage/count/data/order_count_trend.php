<?php
include('../../../inc/site_config.php');
include('../../../inc/set/ext_var.php');
include('../../../inc/fun/mysql.php');
include('../../../inc/function.php');
include('../../../inc/manage/config.php');
include('../../../inc/manage/do_check.php');

check_permit('order_count_trend');

$where='1';
$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$CountType=(int)$_GET['CountType'];
$OrderTime_S=$_GET['OrderTime_S'];
$OrderTime_E=$_GET['OrderTime_E'];
$view_order_status_ary=count($_GET['OrderStatus'])?$_GET['OrderStatus']:array(5, 6);
$o_s=implode(',', $view_order_status_ary);
$count_type_ary=array('%Y-%m-%d', '%Y-%m', '%Y');

$FullName && $where.=" and concat(ShippingFirstName, ' ', ShippingLastName) like '%$FullName%'";
$Email && $where.=" and Email like '%$Email%'";
if($OrderTime_S!='' && $OrderTime_E!=''){
	$time_start=@strtotime($OrderTime_S);
	$time_end=@strtotime($OrderTime_E);
}else{
	if($CountType==0){	//按天
		$time_start=@strtotime(date('Y-m-d', $service_time-3600*24*30));
		$time_end=$service_time;
	}elseif($CountType==1){	//按月
		$time_end=@strtotime(date('Y-m-01', $service_time))+date('t', @strtotime(date('Y-m-01', $service_time)))*3600*24-1;
		$m=date('Y', $time_end)*12+date('m', $time_end)-18;
		$time_start=@strtotime(floor($m/12).'-'.($m%12).'-01');
	}else{	//按年
		$time_start=@strtotime((date('Y', $service_time)-10).'-01-01');	//10年
		$time_end=$service_time;
	}
}
$where.=" and OrderTime between $time_start and $time_end and OrderStatus in($o_s)";

$count_rs=$db->query("select FROM_UNIXTIME(OrderTime, '{$count_type_ary[$CountType]}') as OrderDate, count(*) as row_count from orders where $where group by OrderDate");
$data_ary=array();
while($count_row=mysql_fetch_assoc($count_rs)){
	$data_ary[$count_row['OrderDate']]=$count_row['row_count'];
}

if($CountType==0){	//按天
	for($i=$time_start; $i<=$time_end; $i+=3600*24){
		echo date('Y-m-d', $i).';'.(int)$data_ary[date('Y-m-d', $i)]."\r\n";
	}
}elseif($CountType==1){	//按月
	$s=date('Y', $time_start)*12+date('n', $time_start);
	$e=date('Y', $time_end)*12+date('n', $time_end);
	for($i=$s; $i<=$e; $i++){
		$d=floor($i/12).'-'.sprintf('%02d', $i%12);
		echo $d.';'.(int)$data_ary[$d]."\r\n";
	}
}else{	//按年
	$s=date('Y', $time_start);
	$e=date('Y', $time_end);
	for($i=$s; $i<=$e; $i++){
		echo $i.';'.(int)$data_ary[$i]."\r\n";
	}
}
?>