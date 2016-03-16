<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

$_SESSION['ly200_AdminUserId']=$_SESSION['ly200_AdminUserName']=$_SESSION['ly200_AdminPassword']=$_SESSION['ly200_AdminLastLoginTime']=$_SESSION['ly200_AdminLastLoginIp']=$_SESSION['ly200_AdminNowLoginTime']=$_SESSION['ly200_AdminGroupId']='';
unset($_SESSION['ly200_AdminUserId'], $_SESSION['ly200_AdminUserName'], $_SESSION['ly200_AdminPassword'], $_SESSION['ly200_AdminLastLoginTime'], $_SESSION['ly200_AdminLastLoginIp'], $_SESSION['ly200_AdminNowLoginTime'], $_SESSION['ly200_AdminGroupId']);

save_manage_log('退出登录');

header('Location: ../');
?>