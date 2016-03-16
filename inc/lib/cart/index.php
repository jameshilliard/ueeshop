<?php
$module=$_GET['module']?$_GET['module']:$_POST['module'];
$login_module_ary=array('checkout', 'place', 'payment', 'complete', 'set_payment_method');	//需要登录的模块列表
$un_login_module_ary=array('list', 'add');	//不需要登录的模块列表

if((int)$_SESSION['member_MemberId']){	//已登录
	$module_ary=array_merge($un_login_module_ary, $login_module_ary);
	$where="MemberId='{$_SESSION['member_MemberId']}'";
}else{	//未登录
	in_array($module, $login_module_ary) && js_location("{$member_url}?module=login&jump_url=".urlencode($_SERVER['PHP_SELF'].'?'.query_string()));	//访问需要登录的模块但用户并未登录
	$module_ary=$un_login_module_ary;
	$where="SessionId='$cart_SessionId'";
}

!in_array($module, $module_ary) && $module=$module_ary[0];

ob_start();
include($site_root_path."/inc/lib/cart/module/$module.php");
$cart_page_contents=ob_get_contents();
ob_end_clean();
?>