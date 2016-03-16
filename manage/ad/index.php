<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('ad');

if($_POST['action']=='del_ad'){
	check_permit('', 'ad.del');
	if(count($_POST['del_AId'])){
		$AId=implode(',', $_POST['del_AId']);
		$db->delete('ad', "AId in($AId)");
	}
	save_manage_log('删除广告图片');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('ad.ad_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('ad.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgad_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td width="20%" nowrap><strong><?=get_lang('ad.pagename');?></strong></td>
		<td width="75%" nowrap><strong><?=get_lang('ly200.list');?></strong></td>
	</tr>
	<?php
	$ad_row_count=$i=0;
	$rs=$db->query('select PageName from ad group by PageName order by AId asc');
	while($row=mysql_fetch_assoc($rs)){
	?>
	<tr align="center">
		<td nowrap><?=($i+++1);?></td>
		<td nowrap><?=$row['PageName'];?></td>
		<td nowrap align="left" class="sec_tb">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <?php
			  $ad_row=$db->get_all('ad', "PageName='{$row['PageName']}'", '*', 'AId asc');
			  $ad_row_count+=count($ad_row);
			  for($j=0; $j<count($ad_row); $j++){
			  ?>
			  <tr>
				<?php if(get_cfg('ad.del')){?><td width="10%" align="center"><input name="del_AId[]" type="checkbox" value="<?=$ad_row[$j]['AId'];?>" /></td><?php }?>
				<td width="80%"><?=$ad_row[$j]['AdPosition'];?></td>
				<?php if(get_cfg('ad.mod')){?><td width="10%" align="center"><a href="mod.php?AId=<?=$ad_row[$j]['AId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
			  </tr>
			  <?php }?>
			</table>
		</td>
	</tr>
	<?php }?>
	<?php if(get_cfg('ad.del') && $ad_row_count){?>
	<tr>
		<td colspan="5" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("del_AId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<input name="del_ad" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<input name="action" id="action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>