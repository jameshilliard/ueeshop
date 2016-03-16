<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('orders');

include('../../inc/fun/ip_to_area.php');

$OrderId=(int)$_GET['OrderId'];
!$OrderId && $OrderId=(int)$_POST['OrderId'];
$tmpOrderStatus=(int)$_GET['tmpOrderStatus'];
$module=$_GET['module']?$_GET['module']:$_POST['module'];
$where="OrderId='$OrderId'";

//-----------------------------------------------------------------------------------提交数据(Start Here)-----------------------------------------------------------------------
$act_ary=array('mod_express', 'mod_total_weight', 'mod_order_price', 'mod_order_status', 'mod_shipping_address', 'mod_billing_address', 'mod_product', 'del_product', 'add_product');
$act=$_GET['act']?$_GET['act']:$_POST['act'];
if($act && in_array($act, $act_ary)){
	check_permit('', 'orders.mod');
	include("action/$act.php");
}
//-----------------------------------------------------------------------------------提交数据(End Here)-------------------------------------------------------------------------

$module_ary=array('base', 'status', 'product_list', 'product_add', 'print', 'export');	//模块列表
!in_array($module, $module_ary) && $module=$module_ary[0];

$orders_row=$db->get_one('orders', $where);
!$orders_row && js_location('index.php');

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

ob_start();
if($module=='base' || $module=='status'){	//加载“基本信息”和“订单状态”共用的模块
	include('include/payment_info_detail.php');
	include('include/mod_express_link.php');
	include('include/mod_weight_link.php');
}
include("module/$module.php");
$html_contents=ob_get_contents();
ob_end_clean();

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('orders.orders_manage');?></a>&nbsp;-&gt;&nbsp;<a href="view.php?OrderId=<?=$OrderId;?>"><?=$orders_row['OId'];?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.view');?></div>
<?php include('include/menu.php');?>
<?=$html_contents;?>
<?php include('../../inc/manage/footer.php');?>