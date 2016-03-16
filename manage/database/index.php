<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('database');

if($_POST['act_form_action']=='backup_del'){
	check_permit('database', 'database.del');
	for($i=0; $i<count($_POST['select_date_dir']); $i++){
		del_dir(get_cfg('database.save_dir').$_POST['select_date_dir'][$i].'/');
	}
	save_manage_log('批量删除数据库备份文件');
	
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string");
	exit;
}

if($_GET['query_string']){
	$page=(int)$_GET['page'];
	header("Location: index.php?{$_GET['query_string']}");
	exit;
}

$detail_card=(int)$_GET['detail_card'];

include('../../inc/manage/header.php');
?>
<iframe src="" name="act_iframe" style="display:none;"></iframe>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('database.database_manage');?></a></div>
<div class="act_form">
	<div class="card_list">
		<div class="<?=$detail_card==0?'cur':''?>"><a href="index.php?detail_card=0"><?=get_lang('database.database_backup');?></a></div>
		<div class="<?=$detail_card==1?'cur':''?>"><a href="index.php?detail_card=1"><?=get_lang('database.database_restore');?></a></div>
	</div>
</div>
<?php if($detail_card==0){?>
<form method="post" name="act_form" id="act_form" class="act_form" action="index.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
	<table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr> 
			<td align="center" height="200" id="backup_info" class="fz_14px"><a href="backup.php?tips_id=backup_info" target="act_iframe" class="fz_14px"><?=get_lang('database.backup_s');?></a></td>
		</tr>
	</table>
</form>
<?php }else{?>
<form method="post" name="act_form" id="act_form" class="act_form" action="index.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
	<table width="100%" border="0" cellpadding="0" cellspacing="1" style="display:none;" id="restore_info_table">
		<tr> 
			<td align="center" height="200" id="restore_info" class="fz_14px"></td>
		</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='act_form_title'>
		<tr align="center" class="act_form_title">
			<td width="10%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
			<?php if(get_cfg('database.del')){?><td width="10%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
			<td width="25%" nowrap><strong><?=get_lang('database.file_size');?></strong></td>
			<td width="25%" nowrap><strong><?=get_lang('database.file_count');?></strong></td>
			<td width="25%" nowrap><strong><?=get_lang('database.backup_time');?></strong></td>
			<td width="10%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
		</tr>
		<?php
		$i=1;
		$data_dir=@dir($site_root_path.get_cfg('database.save_dir'));
		if($data_dir){
			while($date_dir=$data_dir->read()){
				if($date_dir=='.' || $date_dir=='..'){
					continue;
				}
				
				$file_dir=@dir($site_root_path.get_cfg('database.save_dir').$date_dir.'/');
				$file_size=$file_count=0;
				
				while($sql_file=$file_dir->read()){
					if($sql_file!='.' && $sql_file!='..'){
						$file_count++;
						$file_size+=@filesize($site_root_path.get_cfg('database.save_dir').$date_dir.'/'.$sql_file);
					}
				}
		?>
			<tr align="center"> 
				<td nowrap><?=$i++;?></td>
				<?php if(get_cfg('database.del')){?><td><input type="checkbox" name="select_date_dir[]" value="<?=$date_dir;?>"></td><?php }?>
				<td nowrap><?=file_size_format($file_size);?></td>
				<td nowrap><?=$file_count;?></td>
				<td nowrap><?=@date(get_lang('ly200.time_format_full'), $date_dir);?></td>
				<td nowrap><a href="restore.php?tips_id=restore_info&dir=<?=$date_dir;?>&file_count=<?=$file_count;?>" target="act_iframe" onClick="if(!confirm('<?=get_lang('database.confirm_restore');?>')){return false;}else{$_('mouse_trBgcolor_table').style.display='none'; $_('restore_info_table').style.display=''; return true;};"><?=get_lang('database.restore');?></a></td>
			</tr>
			<?php }?>
			<?php if(get_cfg('database.del') && $i>1){?>
			<tr>
				<td colspan="20" class="bottom_act">
					<input name="button" type="button" class="form_button" onClick='change_all("select_date_dir[]");' value="<?=get_lang('ly200.anti_select');?>">
					<input name="backup_del" id="backup_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'act_form', 'act_form_action');};" value="<?=get_lang('ly200.del');?>">
					<input type="hidden" name="query_string" value="<?=urlencode('detail_card=1');?>">
					<input type="hidden" name="page" value="<?=$page;?>">
					<input name="act_form_action" id="act_form_action" type="hidden" value="">
				</td>
			</tr>
			<?php }?>
		<?php }?>
	</table>
</form>
<?php }?>
<?php include('../../inc/manage/footer.php');?>