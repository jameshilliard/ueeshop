<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

//********************************************************随机验证码配置（开始）*********************************************************************
$verification_code=array(
	'width' 		=>	35,		//每个字所占的宽度
	'height'		=>	35,		//图片高度
	'noise_count'	=>	mt_rand(20, 40),	//杂点数量
	'line_count'	=>	mt_rand(2, 4),	//干扰线数量
	'ttf'			=>	'../file/simfang.ttf',	//随机验证码使用的字体
	'length'		=>	4,	//随机验证码默认长度
	'char'			=>	'en'	//随机验证码默认使用的字符集
);
//********************************************************随机验证码配置（结束）*********************************************************************

if((int)$_GET['code_creat']==1){
	$code_name=$_GET['code_name'];
	$code_length=(int)$_GET['code_length'];
	if($code_name=='' || $code_length<=0){
		exit;
	}
	$code_char=$_GET['code_char'];
	($code_length<1 || $code_length>6) && $code_length=$verification_code['length'];
	!in_array($code_char, array('en', 'cn', 'num')) && $code_char=$verification_code['char'];
	
	verification_code_creat($code_name, $code_length, $code_char);
}

function verification_code($code_name='ly200', $code_length=0, $code_char=''){
	global $verification_code;
	$verification_code_id=md5($code_name);
	!$code_length && $code_length=$verification_code['length'];
	!$code_char && $code_char=$verification_code['char'];
	
	return "<a href='javascript:void(0);' onclick='this.blur(); obj=document.getElementById(\"$verification_code_id\"); obj.src=obj.src+Math.random(); return true;'><img src='/inc/fun/verification_code.php?code_name=$code_name&code_length=$code_length&code_char=$code_char&code_creat=1&rand_code=".rand_code()."' align='absmiddle' id='$verification_code_id'></a>";
}

function verification_code_creat($code_name, $code_length, $code_char){
	global $verification_code;
	include('../file/verification_code_table.php');
	
	@session_start();
	$_SESSION[md5($code_name)]='';
	$verification_code_img_width=$code_length*$verification_code['width'];	//图片宽度
	
	$image=imagecreate($verification_code_img_width, $verification_code['height']);
	imagecolorallocate($image, 0xff, 0xff, 0xff);		//设定背景颜色
	imagerectangle($image, 0, 0, $verification_code_img_width-1, $verification_code['height']-1, imagecolorallocate($image, 0xcc, 0xcc, 0xcc));   //加个边框
	
	for($i=0; $i<$verification_code['noise_count']; $i++){	//加入杂点
		imagesetpixel($image, mt_rand(0, $verification_code_img_width), mt_rand(0, $verification_code['height']), rand_color($image));
	}
	
	for($i=0; $i<$verification_code['line_count']; $i++){	//加入干扰线
		imageline($image, mt_rand(0, $verification_code_img_width), mt_rand(0, $verification_code['height']), mt_rand(0, $verification_code_img_width), mt_rand(0, $verification_code['height']), rand_color($image));
	}
	
	$posX=$verification_code['width']/2-10;	//posX，posY代表了首字符的基本点，即第一个字符的左下角
	$posY=$verification_code['width']/2+10;
	$one_code_length=$code_char=='cn'?3:1;	//每个字符的长度，一个中文utf8码长度为3字节
	$code_char_length=strlen($code_char_table[$code_char])/$one_code_length;
	
	for($i=0; $i<$code_length; $i++){
		$verification_code_font_size=mt_rand(16, 22);	//文字大小
		$code=substr($code_char_table[$code_char], mt_rand(0, $code_char_length-1)*$one_code_length, $one_code_length);
		imagettftext($image, $verification_code_font_size, mt_rand(-30, 30), $posX, $posY, rand_color($image), $verification_code['ttf'], $code);
		$posX+=$verification_code['width'];
		$_SESSION[md5($code_name)].=$code;
	}
	
	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image);
}

function rand_color($image){	
	$R=mt_rand(0, 255);
	$G=mt_rand(0, 255);
	$B=mt_rand(0, 255);
	($R>200 && $G>200 && $B>200) && $R=$G=$B=0;
	return imagecolorallocate($image, $R, $G, $B);
}
?>