<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?=get_lang('ly200.system_title');?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
$config=array();
$config['file_type']=array('attach', 'img', 'flash'); //允许上传的文件类型
$config['img_allow_type']=array('jpg', 'jpeg', 'bmp', 'gif', 'png');	//图片允许上传的格式
$config['flash_allow_type']=array('swf', 'flv');	//flash允许上传的格式
$config['save_dir']=get_cfg('ly200.up_file_base_dir').'images/'.date('y_m_d/', $service_time);

$file_type=$_GET['file_type'];
!in_array($file_type, $config['file_type']) && $file_type=$config['file_type'][0];

$fn=(int)$_GET['CKEditorFuncNum'];
if(in_array($file_type, array('img', 'flash')) && !in_array(get_ext_name($_FILES['upload']['name']), $config[$file_type.'_allow_type'])){
	output_html($fn, '', get_lang('ckeditor.file_type_error'));
}
if($PicPath=up_file($_FILES['upload'], $config['save_dir'])){
	if(get_cfg('ly200.img_add_watermark')){
		include('../../inc/fun/img_add_watermark.php');
		img_add_watermark($PicPath);
	}
	output_html($fn, $PicPath, get_lang('ckeditor.file_upload_succ'));
}

function output_html($fn, $PicPath, $message){
	echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('$fn', '$PicPath', '$message');</script>";
	exit;
}
?>
</body>
</html>