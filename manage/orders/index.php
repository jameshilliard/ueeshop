<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('orders');

$db->update('orders', "OrderStatus=5 and ShippingTime+15*3600*24<$service_time", array(
		'OrderStatus'	=>	6
	)
);

if($_POST['list_form_action']=='orders_del'){
	check_permit('', 'orders.del');
	if(count($_POST['select_OrderId'])){
		$OrderId=implode(',', $_POST['select_OrderId']);
		$row=$db->get_all('orders', "OrderId in($OrderId)", 'OId, OrderTime');
		
		for($i=0; $i<count($row); $i++){
			$ym=date('Y_m', $row[$i]['OrderTime']);
			del_dir("/images/orders/$ym/{$row[$i]['OId']}/");
		}
		
		$db->delete('orders', "OrderId in($OrderId)");
		$db->delete('orders_product_list', "OrderId in($OrderId)");
		$db->delete('orders_payment_info', "OrderId in($OrderId)");
	}
	save_manage_log('批量删除订单');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_GET['query_string']){
	$page=(int)$_GET['page'];
	header("Location: index.php?{$_GET['query_string']}&page=$page");
	exit;
}

//分页查询
$where=1;
$OId=$_GET['OId'];
$TrackingNumber=$_GET['TrackingNumber'];
$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$OrderStatus=(int)$_GET['OrderStatus'];
$OrderTimeS=$_GET['OrderTimeS'];
$OrderTimeE=$_GET['OrderTimeE'];

$OId && $where.=" and OId like '%$OId%'";
$TrackingNumber && $where.=" and TrackingNumber like '%$TrackingNumber%'";
$FullName && $where.=" and concat(ShippingFirstName, ' ', ShippingLastName) like '%$FullName%'";
$Email && $where.=" and Email like '%$Email%'";
$OrderStatus && $where.=" and OrderStatus='$OrderStatus'";
if($OrderTimeS && $OrderTimeE){
	$ts=@strtotime($OrderTimeS);
	$te=@strtotime($OrderTimeE);
	$te && $te+=86400;
	($ts && $te) && $where.=" and OrderTime between $ts and $te";
}

$order_ary=array(
	1=>'OId asc,',
	2=>'OId desc,',
	3=>'Email asc,',
	4=>'Email desc,',
	5=>'((TotalPrice+ShippingPrice)*(1+PayAdditionalFee/100)) asc,',
	6=>'((TotalPrice+ShippingPrice)*(1+PayAdditionalFee/100)) desc,',
	7=>'OrderStatus asc,',
	8=>'OrderStatus desc,',
	9=>'OrderTime asc,',
	10=>'OrderTime desc,'
);
$order=(int)$_GET['order'];

$row_count=$db->get_row_count('orders', $where);
$total_pages=ceil($row_count/get_cfg('orders.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('orders.page_count');
$orders_row=$db->get_limit('orders', $where, '*', "{$order_ary[$order]}OrderId desc", $start_row, get_cfg('orders.page_count'));

//获取页面跳转url参数
$query_string=query_string('page');
$query_string_no_order=query_string(array('page', 'order'));

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('orders.orders_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="manage_log_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('orders.order_number');?>:<input name="OId" class="form_input" type="text" size="20" maxlength='20'>
				<?=get_lang('orders.tracking_number');?>:<input name="TrackingNumber" class="form_input" type="text" size="20" maxlength='20'>
				<?=get_lang('ly200.full_name');?>:<input name="FullName" class="form_input" type="text" size="10" maxlength='50'>
				<?=get_lang('ly200.email');?>:<input name="Email" class="form_input" type="text" size="20" maxlength='200'>
				<?=get_lang('orders.order_status');?>:<select name="OrderStatus">
					<option value=''>--<?=get_lang('ly200.select');?>--</option>
					<?php foreach($order_status_ary as $key=>$value){?>
						<option value="<?=$key;?>"><?=$value;?></option>
					<?php }?>
				</select>
				<?=get_lang('ly200.time');?>:<input name="OrderTimeS" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />-<input name="OrderTimeE" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />
				<input type="submit" name="submit" value="<?=get_lang('ly200.search');?>" class="form_button" />
			</form>
		</td>
	</tr>
</table>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('orders.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<td width="10%" nowrap><strong><?=get_lang('orders.order_number');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=1"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=2"><img src="../images/desc.png" /></a></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.full_name');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.email');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=3"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=4"><img src="../images/desc.png" /></a></td>
		<td width="8%" nowrap><strong><?=get_lang('orders.grand_total');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=5"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=6"><img src="../images/desc.png" /></a></td>
		<td width="10%" nowrap><strong><?=get_lang('orders.order_status');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=7"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=8"><img src="../images/desc.png" /></a></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.time');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=9"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=10"><img src="../images/desc.png" /></a></td>
		<td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	for($i=0; $i<count($orders_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('orders.del')){?><td><input type="checkbox" name="select_OrderId[]" value="<?=$orders_row[$i]['OrderId'];?>"></td><?php }?>
		<td nowrap><?=htmlspecialchars($orders_row[$i]['OId']);?></td>
		<td nowrap><?=htmlspecialchars($orders_row[$i]['ShippingFirstName'].' '.$orders_row[$i]['ShippingLastName']);?></td>
		<td><?php if($menu['send_mail']){?><a href="javascript:void(0);" onclick="this.blur(); parent.openWindows('win_send_mail', '<?=get_lang('send_mail.send_mail_system');?>', 'send_mail/index.php?email=<?=urlencode($orders_row[$i]['Email'].'/'.$orders_row[$i]['ShippingFirstName'].' '.$orders_row[$i]['ShippingLastName']);?>');"><?=htmlspecialchars($orders_row[$i]['Email']);?></a><?php }else{?><?=htmlspecialchars($orders_row[$i]['Email']);?><?php }?></td>
		<td nowrap><?=get_lang('ly200.price_symbols').sprintf('%01.2f', ($orders_row[$i]['TotalPrice']+$orders_row[$i]['ShippingPrice'])*(1+$orders_row[$i]['PayAdditionalFee']/100));?></td>
		<td nowrap><?=$order_status_ary[$orders_row[$i]['OrderStatus']];?></td>
		<td nowrap><?=date(get_lang('ly200.time_format_full'), $orders_row[$i]['OrderTime']);?></td>
		<td><a href="view.php?<?=$query_string;?>&OrderId=<?=$orders_row[$i]['OrderId']?>"><img src="../images/view.gif" alt="<?=get_lang('ly200.view');?>" /></a></td>
	</tr>
	<?php }?>
	<?php if(get_cfg('orders.del') && count($orders_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("select_OrderId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<input name="orders_del" id="orders_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>">
			<input type="hidden" name="query_string" value="<?=urlencode($query_string);?>">
			<input type="hidden" name="page" value="<?=$page;?>">
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<?php include('../../inc/manage/footer.php');?>