<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('info', 'info.mod');

if($_GET['action']=='delimg'){
	$InfoId=(int)$_POST['InfoId'];
	$PicPath=$_GET['PicPath'];
	
	del_file($PicPath);
	del_file(str_replace('s_', '', $PicPath));
	
	$db->update('info', "InfoId='$InfoId'", array(
			'PicPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'info/'.date('y_m_d/', $service_time);
	$InfoId=(int)$_POST['InfoId'];
	$query_string=$_POST['query_string'];
	
	$Title=$_POST['Title'];
	$CateId=$db->get_row_count('info_category')>1?(int)$_POST['CateId']:$db->get_value('info_category', 1, 'CateId');
	$Language=(int)$_POST['Language'];
	$ExtUrl=$_POST['ExtUrl'];
	$Author=$_POST['Author'];
	$Provenance=$_POST['Provenance'];
	$Burden=$_POST['Burden'];
	$BriefDescription=$_POST['BriefDescription'];
	$IsInIndex=(int)$_POST['IsInIndex'];
	$IsHot=(int)$_POST['IsHot'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	$AccTime=@strtotime($_POST['AccTime']);
	$Contents=save_remote_img($_POST['Contents'], $save_dir);
	
	if(get_cfg('info.upload_pic')){
		$S_PicPath=$_POST['S_PicPath'];
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('info.pic_width'), get_cfg('info.pic_height'));
			if(get_cfg('ly200.img_add_watermark')){
				include('../../inc/fun/img_add_watermark.php');
				img_add_watermark($BigPicPath);
			}
			
			del_file($S_PicPath);
			del_file(str_replace('s_', '', $S_PicPath));
		}else{
			$SmallPicPath=$S_PicPath;
		}
	}
	
	$db->update('info', "InfoId='$InfoId'", array(
			'CateId'			=>	$CateId,
			'Title'				=>	$Title,
			'PicPath'			=>	$SmallPicPath,
			'ExtUrl'			=>	$ExtUrl,
			'Author'			=>	$Author,
			'Provenance'		=>	$Provenance,
			'Burden'			=>	$Burden,
			'BriefDescription'	=>	$BriefDescription,
			'IsInIndex'			=>	$IsInIndex,
			'IsHot'				=>	$IsHot,
			'SeoTitle'			=>	$SeoTitle,
			'SeoKeywords'		=>	$SeoKeywords,
			'SeoDescription'	=>	$SeoDescription,
			'AccTime'			=>	$AccTime,
			'Language'			=>	$Language
		)
	);
	$db->update('info_contents', "InfoId='$InfoId'", array(
			'Contents'	=>	$Contents
		)
	);
	
	set_page_url('info', "InfoId='$InfoId'", get_cfg('info.page_url'), 1);
	
	save_manage_log('编辑文章:'.$Title);
	
	header("Location: index.php?$query_string");
	exit;
}

$InfoId=(int)$_GET['InfoId'];
$query_string=query_string('InfoId');

$info_row=$db->get_one('info', "InfoId='$InfoId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('info.info_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.title');?>:</td>
		<td width="95%"><input name="Title" type="text" value="<?=htmlspecialchars($info_row['Title'])?>" class="form_input" size="50" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('ly200.title');?>!~*"></td>
	</tr>
	<?php if($db->get_row_count('info_category')>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.category');?>:</td>
			<td><?=ouput_Category_to_Select('CateId', $info_row['CateId'], 'info_category', 'UId="0,"', 1, get_lang('ly200.select'));?></td>
		</tr>
	<?php }?>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select($info_row['Language']);?></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$info_row['PicPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $info_row['PicPath']);?>" target="_blank"><img src="<?=$info_row['PicPath'];?>" <?=img_width_height(70, 70, $info_row['PicPath']);?> /></a><input type='hidden' name='S_PicPath' value='<?=$info_row['PicPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="mod.php?action=delimg&InfoId=<?=$InfoId;?>&PicPath=<?=$info_row['PicPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.ext_url')){?>
		<tr>
			<td nowrap><?=get_lang('info.ext_url');?>:</td>
			<td><input name="ExtUrl" type="text" value="<?=htmlspecialchars($info_row['ExtUrl'])?>" class="form_input" size='60' maxlength="100"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.author')){?>
		<tr>
			<td nowrap><?=get_lang('info.author');?>:</td>
			<td><input name="Author" type="text" value="<?=htmlspecialchars($info_row['Author'])?>" class="form_input" size="25" maxlength="50"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.provenance')){?>
		<tr>
			<td nowrap><?=get_lang('info.provenance');?>:</td>
			<td><input name="Provenance" type="text" value="<?=htmlspecialchars($info_row['Provenance'])?>" class="form_input" size="25" maxlength="50"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.burden')){?>
		<tr>
			<td nowrap><?=get_lang('info.burden');?>:</td>
			<td><input name="Burden" type="text" value="<?=htmlspecialchars($info_row['Burden'])?>" class="form_input" size="25" maxlength="50"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.brief_description')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.brief_description');?>:</td>
			<td><textarea name="BriefDescription" rows="5" cols="60" class="form_area"><?=htmlspecialchars($info_row['BriefDescription'])?></textarea></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.seo_tkd')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.seo.seo');?>:</td>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
					<td width="95%"><input name="SeoTitle" type="text" value="<?=htmlspecialchars($info_row['SeoTitle'])?>" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
					<td><input name="SeoKeywords" type="text" value="<?=htmlspecialchars($info_row['SeoKeywords'])?>" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
					<td><input name="SeoDescription" type="text" value="<?=htmlspecialchars($info_row['SeoDescription'])?>" class="form_input" size="70" maxlength="200"></td>
				  </tr>
				</table>
			</td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.acc_time')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.time');?>:</td>
			<td><input name="AccTime" type="text" size="8" onClick="SelectDate(this);" contenteditable="false" value="<?=date('Y-m-d', $info_row['AccTime']);?>" class="form_input" /></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('info.is_in_index') || get_cfg('info.is_hot')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.other_property');?>:</td>
			<td>
				<?php if(get_cfg('info.is_in_index')){?><input name="IsInIndex" type="checkbox" value="1" <?=$info_row['IsInIndex']=='1'?'checked':'';?>><?=get_lang('ly200.is_in_index');?><?php }?>
				<?php if(get_cfg('info.is_hot')){?><input name="IsHot" type="checkbox" value="1" <?=$info_row['IsHot']=='1'?'checked':'';?>><?=get_lang('info.is_hot');?><?php }?>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td nowrap><?=get_lang('ly200.contents');?>:</td>
		<td class="ck_editor"><textarea class="ckeditor" name="Contents"><?=htmlspecialchars($db->get_value('info_contents', "InfoId='$InfoId'", 'Contents'));?></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"><a href="index.php?<?=$query_string;?>" class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="query_string" value="<?=$query_string;?>"><input type="hidden" name="InfoId" value="<?=$InfoId;?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>