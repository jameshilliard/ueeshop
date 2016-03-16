<?php
if($_POST['data']=='feedback_cn' || $_POST['data']=='feedback_en'){
	$un_keyword_ary=array('www', 'http');	//带有本关键词的内容不存入数据库
	$Name=$_POST['Name'];
	$Company=$_POST['Company'];
	$Phone=$_POST['Phone'];
	$Mobile=$_POST['Mobile'];
	$Email=$_POST['Email'];
	$QQ=(int)$_POST['QQ'];
	$Face=(int)$_POST['Face'];
	$Subject=$_POST['Subject'];
	$Message=$_POST['Message'];
	$VCode=strtoupper($_POST['VCode']);
	$jump_url=substr($_POST['jump_url'], 0, 1)!='/'?'/':$_POST['jump_url'];
	$Site=$_POST['Site'];
	
	if($Site=='cn'){
		$vcode_tips='验证码错误，请正确填写验证码！';
		$success_tips='留言提交成功，谢谢您的支持！';
	}else{
		$vcode_tips='Sorry, Please type the characters you see in the picture!';
		$success_tips='Thanks from your submit Inquiry!';
		$Site='en';
	}
	
	$VCode!=$_SESSION[md5('feedback')] && js_back($vcode_tips);	//验证码错误
	unset($_SESSION[md5('feedback')]);
	
	$str=$Name.$Company.$Phone.$Mobile.$Email.$Subject.$Message;	//过滤内容
	$in=0;
	foreach($un_keyword_ary as $value){
		if(@substr_count($str, $value)){
			$in=1;
			break;
		}
	}
	
	($in==1 || $Name=='' || $Phone=='' || $Subject=='' || $Message=='') && js_location($jump_url);
	
	$db->insert('feedback', array(
			'Name'		=>	$Name,
			'Company'	=>	$Company,
			'Phone'		=>	$Phone,
			'Mobile'	=>	$Mobile,
			'Email'		=>	$Email,
			'QQ'		=>	$QQ,
			'Face'		=>	$Face,
			'Subject'	=>	$Subject,
			'Message'	=>	$Message,
			'Site'		=>	$Site,
			'Ip'		=>	get_ip(),
			'PostTime'	=>	$service_time
		)
	);
	
	js_location($jump_url, $success_tips);
}
?>