<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

function img_resize($source_img, $dest_img='', $dest_width=200, $dest_height=150){	//源图片必须相对于网站根目录
	global $site_root_path;
	
    if(!is_file($site_root_path.$source_img)){
		return '';
	}
	
	$dest_img=='' && $dest_img=dirname($source_img).'/s_'.basename($source_img);
	$source_img_info=getimagesize($site_root_path.$source_img);
	$source_img_mime=$source_img_info['mime'];	//图片MIME类型，PHP version>=4.3
	
	if($source_img_mime=='image/jpeg'){
		$im=imagecreatefromjpeg($site_root_path.$source_img);
	}elseif($source_img_mime=='image/png'){
		$im=imagecreatefrompng($site_root_path.$source_img);
	}elseif($source_img_mime=='image/gif'){
		$im=imagecreatefromgif($site_root_path.$source_img);
	}else{
		@copy($site_root_path.$source_img, $site_root_path.$dest_img);
		@chmod($site_root_path.$dest_img, 0777);
		return $return_img;	//返回调整后的文件
	}
	
	$source_width=imagesx($im);	//源图片宽
	$source_height=imagesy($im);	//源图片高
	
	if($source_width>$dest_width || $source_height>$dest_height){
		if($source_width>=$dest_width){
			$width_ratio=$dest_width/$source_width;
			$resize_width=true;
		}
		
		if($source_height>=$dest_height){
			$height_ratio=$dest_height/$source_height;
			$resize_height=true;
		}
		
		if($resize_width && $resize_height){
			$ratio=$width_ratio < $height_ratio ? $width_ratio : $height_ratio;
		}elseif($resize_width){
			$ratio=$width_ratio;
		}elseif($resize_height){
			$ratio=$height_ratio;
		}
		
		$new_width=$source_width * $ratio;
		$new_height=$source_height * $ratio;
		if(function_exists('imagecopyresampled')){
			$new_im=imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($new_im, $im, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);
		}else{
			$new_im=imagecreate($new_width, $new_height);
			imagecopyresized($new_im, $im, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);
		}
		imagejpeg($new_im, $site_root_path.$dest_img, 100);
		imagedestroy($new_im);
	}else{
		imagejpeg($im, $site_root_path.$dest_img, 100);
	}
	
	imagedestroy($im);
	@chmod($site_root_path.$dest_img, 0777);
	
	return $dest_img;	//返回调整后的文件名
}
?>