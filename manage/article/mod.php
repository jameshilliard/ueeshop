<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

$GroupId=(int)$_GET['GroupId']?(int)$_GET['GroupId']:(int)$_POST['GroupId'];
check_permit('article_group_'.$GroupId);
$Group='group_'.$GroupId;

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'article/'.date('y_m_d/', $service_time);
	$AId=(int)$_POST['AId'];
	$Title=$_POST['Title'];
	$SeoTitle=$_POST['SeoTitle'];
	$SeoKeywords=$_POST['SeoKeywords'];
	$SeoDescription=$_POST['SeoDescription'];
	$Contents=save_remote_img($_POST['Contents'], $save_dir);
	
	$db->update('article', "AId='$AId'", array(
			'GroupId'		=>	$GroupId,
			'Title'			=>	$Title,
			'SeoTitle'		=>	$SeoTitle,
			'SeoKeywords'	=>	$SeoKeywords,
			'SeoDescription'=>	$SeoDescription,
			'Contents'		=>	$Contents
		)
	);
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('article', array('Title', 'Contents', 'SeoTitle', 'SeoKeywords', 'SeoDescription'));
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$TitleExt=in_array($Group, get_cfg('article.amdo'))?$_POST['Title'.$field_ext]:addslashes($db->get_value('article', "AId='$AId'", 'Title'.$field_ext));
			$SeoTitleExt=$_POST['SeoTitle'.$field_ext];
			$SeoKeywordsExt=$_POST['SeoKeywords'.$field_ext];
			$SeoDescriptionExt=$_POST['SeoDescription'.$field_ext];
			$ContentsExt=save_remote_img($_POST['Contents'.$field_ext], $save_dir);
			$db->update('article', "AId='$AId'", array(
					'Title'.$field_ext			=>	$TitleExt,
					'SeoTitle'.$field_ext		=>	$SeoTitleExt,
					'SeoKeywords'.$field_ext	=>	$SeoKeywordsExt,
					'SeoDescription'.$field_ext	=>	$SeoDescriptionExt,
					'Contents'.$field_ext		=>	$ContentsExt
				)
			);
		}
	}
	
	set_page_url('article', "AId='$AId'", get_cfg('article.page_url'), 1, 0);
	
	save_manage_log( '编辑信息页：'.$Title);
	
	header('Location: index.php?GroupId='.$GroupId);
	exit;
}

$AId=(int)$_GET['AId'];
$article_row=$db->get_one('article', "AId='$AId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php?GroupId=<?=$GroupId;?>"><?=get_lang('article.group_'.$GroupId);?></a>&nbsp;-&gt;&nbsp;<a href="mod.php?AId=<?=$AId;?>&GroupId=<?=$GroupId;?>"><?=$article_row['Title'];?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr> 
			<td width="5%" nowrap><?=get_lang('ly200.title').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Title<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($article_row['Title'.lang_name($i, 1)]);?>" class="form_input" size="30" maxlength="100"></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('article.seo_tkd')){?>
		<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
			<tr>
				<td nowrap><?=get_lang('ly200.seo.seo').lang_name($i, 0);?>:</td>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="5%" nowrap="nowrap"><?=get_lang('ly200.seo.title');?>:</td>
						<td width="95%"><input name="SeoTitle<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($article_row['SeoTitle'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					  <tr>
						<td nowrap="nowrap"><?=get_lang('ly200.seo.keywords');?>:</td>
						<td><input name="SeoKeywords<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($article_row['SeoKeywords'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					  <tr>
						<td nowrap="nowrap"><?=get_lang('ly200.seo.description');?>:</td>
						<td><input name="SeoDescription<?=lang_name($i, 1);?>" type="text" value="<?=htmlspecialchars($article_row['SeoDescription'.lang_name($i, 1)]);?>" class="form_input" size="70" maxlength="200"></td>
					  </tr>
					</table>
				</td>
			</tr>
		<?php }?>
	<?php }?>
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('ly200.contents').lang_name($i, 0);?>:</td>
			<td width="95%" class="ck_editor"><textarea class="ckeditor" name="Contents<?=lang_name($i, 1);?>"><?=htmlspecialchars($article_row['Contents'.lang_name($i, 1)]);?></textarea></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=get_lang('ly200.mod');?>" name="submit" class="form_button"><a href='index.php?GroupId=<?=$GroupId;?>' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="AId" value="<?=$AId;?>" /><input type="hidden" name="GroupId" value="<?=$GroupId;?>" /></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>