<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');

/*
后台用的。。。。。。
把语言转换成js对象，访问方法：Ly200JsLang._下标
如：Ly200JsLang._ly200.price_symbols对应$Ly200JsLang['ly200']['price_symbols']
*/
?>var Ly200JsLang={
	<?php
	lang_to_jsLangObj($Ly200JsLang);
	?>'about':'http://www.ly200.com/'
};