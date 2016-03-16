<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('ad', 'ad.mod');

if($_GET['action']=='delimg'){
	$AId=(int)$_POST['AId'];
	$Field=$_GET['Field'];
	$PicPath=$_GET['PicPath'];
	$ImgId=$_GET['ImgId'];
	
	del_file($PicPath);
	
	$db->update('ad', "AId='$AId'", array(
			$Field	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('$ImgId').innerHTML='$str'; parent.document.getElementById('{$ImgId}_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'ad/'.date('y_m_d/', $service_time);
	$AId=(int)$_POST['AId'];
	$AdType=(int)$_POST['AdType'];
	
	if($AdType==0){
		$PicCount=(int)$_POST['PicCount'];
		$PicPath=$Name=$Url=array();
		
		for($i=0; $i<$PicCount; $i++){
			$S_PicPath=$_POST['S_PicPath_'.$i];
			$Name[]=$_POST['Name_'.$i];
			$Url[]=$_POST['Url_'.$i];
			
			if($tmp_path=up_file($_FILES['PicPath_'.$i], $save_dir)){
				$PicPath[$i]=$tmp_path;
				del_file($S_PicPath);
			}else{
				$PicPath[$i]=$S_PicPath;
			}
		}
		$db->update('ad', "AId='$AId'", array(
				'Name_0'		=>	$Name[0],
				'Name_1'		=>	$Name[1],
				'Name_2'		=>	$Name[2],
				'Name_3'		=>	$Name[3],
				'Name_4'		=>	$Name[4],
				'Url_0'			=>	$Url[0],
				'Url_1'			=>	$Url[1],
				'Url_2'			=>	$Url[2],
				'Url_3'			=>	$Url[3],
				'Url_4'			=>	$Url[4],
				'PicPath_0'		=>	$PicPath[0],
				'PicPath_1'		=>	$PicPath[1],
				'PicPath_2'		=>	$PicPath[2],
				'PicPath_3'		=>	$PicPath[3],
				'PicPath_4'		=>	$PicPath[4]
			)
		);
	}elseif($AdType==1){
		$Name=$_POST['Name'];
		$S_FlashPath=$_POST['S_FlashPath'];
		
		if($tmp_path=up_file($_FILES['FlashPath'], $save_dir)){
			$FlashPath=$tmp_path;
			del_file($S_FlashPath);
		}else{
			$FlashPath=$S_FlashPath;
		}
		
		$db->update('ad', "AId='$AId'", array(
				'Name'		=>	$Name,
				'FlashPath'	=>	$FlashPath
			)
		);
	}else{
		$Name=$_POST['Name'];
		$Contents=save_remote_img($_POST['Contents'], $save_dir);
		
		$db->update('ad', "AId='$AId'", array(
				'Name'		=>	$Name,
				'Contents'	=>	$Contents
			)
		);
	}
	
	save_manage_log('更新广告图片');
	
	header('Location: index.php');
	exit;
}
					
$AId=(int)$_GET['AId'];
$ad_row=$db->get_one('ad', "AId='$AId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('ad.ad_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('ad.pagename');?>:</td>
		<td width="95%"><?=$ad_row['PageName'];?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.ad_position');?>:</td>
		<td><?=$ad_row['AdPosition'];?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.width');?>:</td>
		<td><?=$ad_row['Width']?$ad_row['Width'].'px':'auto';?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ad.height');?>:</td>
		<td><?=$ad_row['Height']?$ad_row['Height'].'px':'auto';?></td>
	</tr>
	<?php if($ad_row['AdType']==0){?>
		<tr>
			<td nowrap><?=get_lang('ad.photo');?>:</td>
			<td>
				<?php for($i=0; $i<$ad_row['PicCount']; $i++){?>
					<?=$ad_row['PicCount']>1?($i+1).'. ':'';?><input name="PicPath_<?=$i?>" type="file" size="40" class="form_input" contenteditable="false">&nbsp;&nbsp;<?=get_lang('ad.name');?>:<input name="Name_<?=$i;?>" type="text" value="<?=htmlspecialchars($ad_row['Name_'.$i]);?>" class="form_input" size="15" maxlength="50">&nbsp;&nbsp;<?=get_lang('ad.url');?>:<input name="Url_<?=$i;?>" type="text" value="<?=htmlspecialchars($ad_row['Url_'.$i]);?>" class="form_input" size="35" maxlength="200" /><input type="hidden" name="S_PicPath_<?=$i;?>" value="<?=$ad_row['PicPath_'.$i];?>" /><br />
				<?php }?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px; margin-left:<?=$ad_row['PicCount']>1?'12':'0';?>px;">
				  <tr>
					<?php
					for($i=0; $i<$ad_row['PicCount']; $i++){
						if(!is_file($site_root_path.$ad_row['PicPath_'.$i])){
							continue;
						}
					?>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list_<?=$i;?>"><a href="<?=str_replace('s_', '', $ad_row['PicPath_'.$i]);?>" target="_blank"><img src="<?=$ad_row['PicPath_'.$i];?>" <?=img_width_height(70, 70, $ad_row['PicPath_'.$i]);?> /></a><input type='hidden' name='S_PicPath_<?=$i;?>' value='<?=$ad_row['PicPath_'.$i];?>'></td>
								</tr>
								<tr>
									<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo').($ad_row['PicCount']>1?($i+1):'');?><span id="img_list_<?=$i;?>_a">&nbsp;<a href="mod.php?action=delimg&AId=<?=$AId;?>&Field=PicPath_<?=$i;?>&PicPath=<?=$ad_row['PicPath_'.$i];?>&ImgId=img_list_<?=$i;?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
								</tr>
							</table>
						</td>
						<td width="5">&nbsp;&nbsp;</td>
					<?php }?>
				  </tr>
				</table>
			</td>
		</tr>
	<?php }elseif($ad_row['AdType']==1){?>
		<tr>
			<td nowrap><?=get_lang('ad.name');?>:</td>
			<td><input name="Name" type="text" value="<?=htmlspecialchars($ad_row['Name']);?>" class="form_input" size="25" maxlength="50"></td>
		</tr>
		<tr>
			<td nowrap><?=get_lang('ad.flash');?>:</td>
			<td><input name="FlashPath" type="file" size="40" class="form_input" contenteditable="false"><input type="hidden" name="S_FlashPath" value="<?=$ad_row['FlashPath'];?>" /></td>
		</tr>
	<?php }else{?>
		<tr>
			<td nowrap><?=get_lang('ad.name');?>:</td>
			<td><input name="Name" type="text" value="<?=htmlspecialchars($ad_row['Name']);?>" class="form_input" size="25" maxlength="50"></td>
		</tr>
		<tr>
			<td nowrap><?=get_lang('ad.contents');?>:</td>
			<td class="ck_editor"><textarea class="ckeditor" name="Contents"><?=htmlspecialchars($ad_row['Contents']);?></textarea></td>
		</tr>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('ly200.preview');?>:</td>
		<td><?=ad($AId);?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="AId" value="<?=$AId;?>"><input type="hidden" name="AdType" value="<?=$ad_row['AdType'];?>" /><input type="hidden" name="PicCount" value="<?=$ad_row['PicCount'];?>" /></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>