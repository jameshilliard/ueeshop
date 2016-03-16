<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_font', 'product.font.mod');

if($_GET['action']=='delimg'){
	$FId=(int)$_POST['FId'];
	$PicPath=$_GET['PicPath'];
	del_file($PicPath);
	del_file(str_replace('s_', '', $PicPath));
	
	$db->update('product_font', "FId='$FId'", array(
			'PicPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$FId=(int)$_POST['FId'];	
	$Font=$_POST['Font'];
	$CId=$_POST['CId'];
	
	if(get_cfg('product.font.upload_pic')){
		$save_dir=get_cfg('ly200.up_file_base_dir').'product/font/'.date('y_m_d/', $service_time);
		$S_PicPath=$_POST['S_PicPath'];
		
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('product.font.pic_width'), get_cfg('product.font.pic_height'));
			del_file($S_PicPath);
			del_file(str_replace('s_', '', $S_PicPath));
		}else{
			$SmallPicPath=$S_PicPath;
		}
	}
	
	$db->update('product_font', "FId='$FId'", array(
			'Font'		=>	$Font,
			'PicPath'	=>	$SmallPicPath
		)
	);

	save_manage_log('更新字体:'.$Font);
	
	header('Location: font.php?CId='.$CId);
	exit;
}

$FId=(int)$_GET['FId'];
$font_row=$db->get_one('product_font', "FId='$FId'");
$row=$db->get_one('product_customize',"CId='{$font_row['CId']}'");
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CId=<?=$row['CId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<a href="font.php?CId=<?=$row['CId']?>"><?=get_lang('product.font');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
</div>
<form method="post" name="act_form" id="act_form" class="act_form" action="font_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgfont_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('product.font').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Font<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($font_row['Font'.lang_name($i, 1)]);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('product.font');?>!~*" id="font" onClick="intofont('font')">
            <div id="fontpanel" style="position: absolute;"></div><div style=" width:25px; height:25px; background:<?=$font_row['Font'.lang_name($i, 1)]?>"></div></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('product.font.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$font_row['PicPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $font_row['PicPath']);?>" target="_blank"><img src="<?=$font_row['PicPath'];?>" <?=img_width_height(70, 70, $font_row['PicPath']);?> /></a><input type='hidden' name='S_PicPath' value='<?=$font_row['PicPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="font_mod.php?action=delimg&FId=<?=$FId;?>&PicPath=<?=$font_row['PicPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='font.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="FId" value="<?=$FId;?>"><input type="hidden" name="CId" value="<?=$font_row['CId']?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>