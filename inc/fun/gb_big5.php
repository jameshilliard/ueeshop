<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

include($site_root_path.'/inc/file/gb_big5_table.php');

function cn_convert($txt, $language='big5'){
	global $gb_table, $big5_table;
	
	$source=$language=='big5'?$gb_table:$big5_table;
	$dest=$language=='big5'?$big5_table:$gb_table;
	
	$str='';
	$len=@mb_strlen($txt, 'utf8');
	
	for($i=0; $i<$len; $i++){
		$char=@mb_substr($txt, $i, 1, 'utf8');
		$pos=@mb_strpos($source, $char, null, 'utf8');
		$str.=$pos!=false?@mb_substr($dest, $pos, 1, 'utf8'):$char;
	}
	return $str;
}
?>