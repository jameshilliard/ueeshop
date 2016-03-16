<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
function_exists('set_magic_quotes_runtime') && set_magic_quotes_runtime(0);
function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT+0');
$InLy200MysqlDatabase!==true && filter_variable();
session_start();
header('Content-Type: text/html; charset=utf-8');

slashes_gpc($_GET);
slashes_gpc($_POST);
slashes_gpc($_COOKIE);

//********************************************************网站相关参数设置（开始）*******************************************************************
$site_root_path	=	substr(dirname(__FILE__), 0, -4);	//网站根目录
$service_time	=	time();		//服务器当前时间
$mCfg			=	load_mcfg();	//加载系统全局设置变量
//********************************************************网站相关参数设置（结束）*******************************************************************

//********************************************************数据库相关配置（开始）*********************************************************************
$db_host		=	'localhost';	//数据库地址
$db_port		=	'';				//数据库端口
$db_username	=	'C342018_macquins';			//数据库用户名
$db_database	=	'C342018_macquins';		//数据库名称
$db_password	=	'EdfO0121as';		//数据库密码
$db_char		=	'utf8';			//数据库编码
//********************************************************数据库相关配置（结束）*********************************************************************

//********************************************************此文件使用的相关函数（开始）****************************************************************
function filter_variable(){
	$allowed=array('GLOBALS'=>1, '_GET'=>1, '_POST'=>1, '_COOKIE'=>1, '_FILES'=>1, '_SERVER'=>1);
	foreach($GLOBALS as $key=>$value){
		if(!isset($allowed[$key])){
			$GLOBALS[$key]=null;
			unset($GLOBALS[$key]);
		}
	}
}

function slashes_gpc(&$array){
	foreach($array as $key=>$value){
		if(is_array($value)){
			slashes_gpc($array[$key]);
		}else{
			$array[$key]=trim($array[$key]);
			!get_magic_quotes_gpc() && $array[$key]=addslashes($array[$key]);
		}
	}
}

function load_mcfg(){	//加载系统全局设置变量
	global $site_root_path;
	@include($site_root_path.'/inc/set/exchange_rate.php');
	@include($site_root_path.'/inc/set/global.php');
	@include($site_root_path.'/inc/set/orders_print.php');
	@include($site_root_path.'/inc/set/send_mail.php');
	return $mCfg;
}
//********************************************************此文件使用的相关函数（结束）***************************************************************
?>