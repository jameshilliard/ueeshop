<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

function img_to_bmp($source_img, $dest_img){
	global $site_root_path;
	
	$img=$site_root_path.$source_img;
	if(!is_file($img)){
		return '';
	}
	
	$im=@imagecreatefromjpeg($img);
	$w=imagesx($im);
	$h=imagesy($im);
	$result='';
	
	if(!imageistruecolor($im)){
		$tmp=imagecreatetruecolor($w, $h);
		imagecopy($tmp, $im, 0, 0, 0, 0, $w, $h);
		imagedestroy($im);
		$im=&$tmp;
	}
	
	$biBPLine=$w*3;
	$biStride=($biBPLine+3)&~3;
	$biSizeImage=$biStride*$h;
	$bfOffBits=54;
	$bfSize=$bfOffBits+$biSizeImage;
	
	$result.=substr('BM', 0, 2);
	$result.=pack('VvvV', $bfSize, 0, 0, $bfOffBits);
	$result.=pack('VVVvvVVVVVV', 40, $w, $h, 1, 24, 0, $biSizeImage, 0, 0, 0, 0);
	
	$numpad=$biStride-$biBPLine;
	for($y=$h-1; $y>=0; --$y){
		for($x=0; $x<$w; ++$x){
			$col=imagecolorat($im, $x, $y);
			$result.=substr(pack('V', $col), 0, 3);
		}
		for($i=0; $i<$numpad; ++$i){
			$result.=pack('C', 0);
		}
	}
	
	$file=@fopen($dest_img, 'wb');
	@fwrite($file, $result);
	@fclose($file);
	return $dest_img;
}
?>