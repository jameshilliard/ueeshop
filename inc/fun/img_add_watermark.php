<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

//********************************************************图片添加水印配置（开始）*******************************************************************
$watermark=array(
	'allowed_width'		=>	400,	//源图片宽度小于此值不添加水印直接返回
	'allowed_height'	=>	300,	//源图片高度小于此值不添加水印直接返回
	'padding_border'	=>	10,		//水印离图片边缘的像素数
	'string'			=>	'广州联雅网络科技有限公司',	//文字水印内容，支持中文
	'string_ttf' 		=>	'/inc/file/simfang.ttf',	//文字水印使用的字体
	'string_color'		=>	'000000',	//文字水印的字体颜色，用完整十六进制写法
	'string_font_size'	=>	16,	//文字水印文字的大小
	'img'				=>	'',	//图片水印路径
	'img_alpha'			=>	45,	//图片水印透明度
	'position'			=>	5,	//水印的位置
	'type'				=>	1	//水印类型，0为添加图片水印，1为添加文字水印
);
//********************************************************图片添加水印配置（结束）*******************************************************************

function img_add_watermark($source_img){
	global $site_root_path, $watermark;
	
	if(!is_file($site_root_path.$source_img) || (!is_file($site_root_path.$watermark['img']) && $watermark['type']==0)){	//源文件不存在则直接返回
		return $source_img;	//返回源文件路径
	}
	
	$source_img_info=getimagesize($site_root_path.$source_img);
	$source_img_mime=$source_img_info['mime'];	//图片MIME类型，PHP version>=4.3
	
	if($source_img_mime=='image/jpeg'){
		$source_im=imagecreatefromjpeg($site_root_path.$source_img);
	}elseif($source_img_mime=='image/x-png'){
		$source_im=imagecreatefrompng($site_root_path.$source_img);
	}elseif($source_img_mime=='image/gif'){
		$source_im=imagecreatefromgif($site_root_path.$source_img);
	}else{
		return $source_img;	//返回源文件路径
	}
	
	$source_width=imagesx($source_im);	//源图片宽
	$source_height=imagesy($source_im);	//源图片高
	
    if($source_width<$watermark['allowed_width'] || $source_height<$watermark['allowed_height']){
		return $source_img;	//返回源文件路径
	}
	
	if($watermark['type']==0){	//图片水印
		$watermark_img_info=getimagesize($site_root_path.$watermark['img']);
		$watermark_img_mime=$watermark_img_info['mime'];	//图片MIME类型，PHP version>=4.3
		
		if($watermark_img_mime=='image/jpeg'){
			$watermark_im=imagecreatefromjpeg($site_root_path.$watermark['img']);
		}elseif($watermark_img_mime=='image/x-png'){
			$watermark_im=imagecreatefrompng($site_root_path.$watermark['img']);
		}elseif($watermark_img_mime=='image/gif'){
			$watermark_im=imagecreatefromgif($site_root_path.$watermark['img']);
		}else{
			return $source_img;	//返回源文件路径
		}
		
		$watermark_width=imagesx($watermark_im);	//水印图片宽
		$watermark_height=imagesy($watermark_im);	//水印图片高
	}else{	//文字水印
		$tmp=imagettfbbox($watermark['string_font_size'], 0, $site_root_path.$watermark['string_ttf'], $watermark['string']);	//取得使用指定字体的文本的范围大小
        $watermark_width=$tmp[2]-$tmp[0];
        $watermark_height=$tmp[3]-$tmp[5];
        unset($tmp);
    }
	
    switch($watermark['position']){	//水印位置
        case 1:	//1为顶端居左
            $posX=$watermark['padding_border'];
            $posY=$watermark['padding_border'];
            break;
        case 2:	//2为顶端居中
            $posX=($source_width-$watermark_width)/2;
            $posY=$watermark['padding_border'];
            break;
        case 3:	//3为顶端居右
            $posX=$source_width-$watermark_width-$watermark['padding_border'];
            $posY=$watermark['padding_border'];
            break;
        case 4:	//4为中部居左
            $posX=$watermark['padding_border'];
            $posY=($source_height-$watermark_height)/2;
            break;
        case 5:	//5为中部居中
            $posX=($source_width-$watermark_width)/2;
            $posY=($source_height-$watermark_height)/2;
            break;
        case 6:	//6为中部居右
            $posX=$source_width-$watermark_width-$watermark['padding_border'];
            $posY=($source_height-$watermark_height)/2;
            break;
        case 7:	//7为底端居左
            $posX=$watermark['padding_border'];
            $posY=$source_height-$watermark_height-$watermark['padding_border'];
            break;
        case 8:	//8为底端居中
            $posX=($source_width-$watermark_width)/2;
            $posY=$source_height-$watermark_height-$watermark['padding_border'];
            break;
        case 9:	//9为底端居右
            $posX=$source_width-$watermark_width-$watermark['padding_border'];
            $posY=$source_height-$watermark_height-$watermark['padding_border'];
            break;
        default:	//随机
            $posX=mt_rand($watermark['padding_border'], $source_width-$watermark_width-$watermark['padding_border']);
            $posY=mt_rand($watermark['padding_border'], $source_height-$watermark_height-$watermark['padding_border']);
    }
	
	imagealphablending($source_im, true);     //设定图像的混色模式
	
    if($watermark['type']==0){	//图片水印
        imagecopymerge($source_im, $watermark_im, $posX, $posY, 0, 0, $watermark_width, $watermark_height, $watermark['img_alpha']);	//拷贝水印到目标文件
		imagedestroy($watermark_im);
	}else{	//文字水印
		$R=hexdec(substr($watermark['string_color'], 1, 2));
		$G=hexdec(substr($watermark['string_color'], 3, 2));
		$B=hexdec(substr($watermark['string_color'], 5, 2));
		imagettftext($source_im, $watermark['string_font_size'], 0, $posX, $posY + $watermark_height, imagecolorallocate($source_im, $R, $G, $B), $site_root_path.$watermark['string_ttf'], $watermark['string']);
    }
	
	if($source_img_mime=='image/jpeg'){
		imagejpeg($source_im, $site_root_path.$source_img, 100);
	}elseif($source_img_mime=='image/x-png'){
		imagepng($source_im, $site_root_path.$source_img);
	}elseif($source_img_mime=='image/gif'){
		imagegif($source_im, $site_root_path.$source_img);
	}
	
    imagedestroy($source_im);
	
	return $source_img;	//返回源文件路径
}
?>