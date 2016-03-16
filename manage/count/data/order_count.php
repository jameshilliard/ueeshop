<?php
include('../../../inc/site_config.php');
include('../../../inc/set/ext_var.php');
include('../../../inc/fun/mysql.php');
include('../../../inc/function.php');
include('../../../inc/manage/config.php');
include('../../../inc/manage/do_check.php');

check_permit('order_count');

$where='1';
$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$CountType=(int)$_GET['CountType'];
$OrderTime_S=$_GET['OrderTime_S'];
$OrderTime_E=$_GET['OrderTime_E'];
$view_order_status_ary=count($_GET['OrderStatus'])?$_GET['OrderStatus']:array(5, 6);
$count_type_ary=array('%Y-%m-%d', '%Y-%m', '%Y');

$FullName && $where.=" and concat(ShippingFirstName, ' ', ShippingLastName) like '%$FullName%'";
$Email && $where.=" and Email like '%$Email%'";
if($OrderTime_S!='' && $OrderTime_E!=''){
	$time_start=@strtotime($OrderTime_S);
	$time_end=@strtotime($OrderTime_E);
}else{
	if($CountType==0){	//按天
		$time_start=@strtotime(date('Y-m-d', $service_time-3600*24*15));
		$time_end=$service_time;
	}elseif($CountType==1){	//按月
		$time_end=@strtotime(date('Y-m-01', $service_time))+date('t', @strtotime(date('Y-m-01', $service_time)))*3600*24-1;
		$m=date('Y', $time_end)*12+date('m', $time_end)-11;
		$time_start=@strtotime(floor($m/12).'-'.($m%12).'-01');
	}else{	//按年
		$time_start=@strtotime((date('Y', $service_time)-10).'-01-01');	//10年
		$time_end=$service_time;
	}
}
$where.=" and OrderTime between $time_start and $time_end";
$o_s=implode(',', $view_order_status_ary);
$where.=" and OrderStatus in($o_s)";

$count_rs=$db->query("select FROM_UNIXTIME(OrderTime, '{$count_type_ary[$CountType]}') as OrderDate, count(*) as row_count from orders where $where group by OrderDate");
$data_ary=array();
while($count_row=mysql_fetch_assoc($count_rs)){
	$data_ary[$count_row['OrderDate']]=$count_row['row_count'];
}

$j=1;
$data_1=$data_2='';
if($CountType==0){	//按天
	for($i=$time_start; $i<=$time_end; $i+=3600*24){
		$d=date('Y-m-d', $i);
		$d1=date('m-d', $i);
		$data_1.="<value xid=\"$j\">$d1</value>\r\n";
		$data_2.="<value xid=\"$j\" color=\"FF0F00\">".(sprintf('%01.2f', $data_ary[$d]))."</value>\r\n";
		$j++;
	}
}elseif($CountType==1){	//按月
	$s=date('Y', $time_start)*12+date('n', $time_start);
	$e=date('Y', $time_end)*12+date('n', $time_end);
	for($i=$s; $i<=$e; $i++){
		$d=floor($i/12).'-'.sprintf('%02d', $i%12);
		$data_1.="<value xid=\"$j\">$d</value>\r\n";
		$data_2.="<value xid=\"$j\" color=\"FF0F00\">".(sprintf('%01.2f', $data_ary[$d]))."</value>\r\n";
		$j++;
	}
}else{	//按年
	$s=date('Y', $time_start);
	$e=date('Y', $time_end);
	for($i=$s; $i<=$e; $i++){
		$data_1.="<value xid=\"$j\">$i</value>\r\n";
		$data_2.="<value xid=\"$j\" color=\"FF0F00\">".(sprintf('%01.2f', $data_ary[$i]))."</value>\r\n";
		$j++;
	}
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<chart>
	<series><?=$data_1;?></series>
	<graphs>
		<graph gid="1"><?=$data_2;?></graph>
	</graphs>
</chart>