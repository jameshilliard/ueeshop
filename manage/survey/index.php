<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('survey');

if($_POST['action']=='order_survey'){
	check_permit('', 'survey.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$SId=(int)$_POST['SId'][$i];
		$order=abs((int)$_POST['MyOrder'][$i]);
		
		$db->update('survey', "SId='$SId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	
	save_manage_log('在线调查排序');
	
	header('Location: index.php');
	exit;
}

if($_POST['action']=='del_survey'){
	check_permit('', 'survey.del');
	if(count($_POST['select_SId'])){
		$SId=implode(',', $_POST['select_SId']);
		$db->delete('survey', "SId in($SId)");
		$db->delete('survey_item', "SId in($SId)");
	}
	save_manage_log('删除在线调查');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('survey.survey_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('survey.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgsurvey_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('survey.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('survey.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="25%" nowrap><strong><?=get_lang('survey.subject');?></strong></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?><td width="5%" nowrap><strong><?=get_lang('ly200.language');?></strong></td><?php }?>
		<td width="50%" nowrap><strong><?=get_lang('survey.item');?></strong></td>
		<?php if(get_cfg('survey.mod')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$survey_row=$db->get_all('survey', 1, '*', 'MyOrder desc, SId asc');
	for($i=0; $i<count($survey_row); $i++){
		$votes_count=$db->get_sum('survey_item', "SId='{$survey_row[$i]['SId']}'", 'VotesCount');
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<?php if(get_cfg('survey.del')){?><td><input name="select_SId[]" type="checkbox" value="<?=$survey_row[$i]['SId'];?>" /></td><?php }?>
		<?php if(get_cfg('survey.order')){?><td><input name="MyOrder[]" class="form_input" type="text" size="3" maxlength="10" value="<?=htmlspecialchars($survey_row[$i]['MyOrder']);?>" /><input type="hidden" name="SId[]" value="<?=$survey_row[$i]['SId'];?>" /></td><?php }?>
		<td nowrap class="flh_150"><?=$survey_row[$i]['Subject'];?><br /><?=get_lang('survey.votes_count');?>: <font class="fc_red"><?=$votes_count;?></font></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?><td nowrap><?=get_lang('ly200.lang_array.lang_'.$survey_row[$i]['Language']);?></td><?php }?>
		<td class="sec_tb">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php
				$survey_item_row=$db->get_all('survey_item', "SId='{$survey_row[$i]['SId']}'", '*', 'IId asc');
				for($j=0; $j<count($survey_item_row); $j++){
					$p=$votes_count?@sprintf('%01.2f', $survey_item_row[$j]['VotesCount']/$votes_count*100).'%':'1px';
				?>
					<tr align="left">
						<td nowrap width="30"><?=$j+1;?>.</td>
						<td width="150"><?=$survey_item_row[$j]['ItemTitle'];?></td>
						<td nowrap width="60"><?=get_lang('survey.votes_count');?>:<?=$survey_item_row[$j]['VotesCount'];?></td>
						<td nowrap width="150"><div style="width:120px; height:10px; border:1px solid #ddd;"><div style="background:red; width:<?=$p;?>; height:10px; overflow:hidden;"></div></div></td>
						<td nowrap width="60"><?=@sprintf('%01.2f', $survey_item_row[$j]['VotesCount']/$votes_count*100);?>%</td>
					</tr>
				<?php }?>
			</table>
		</td>
		<?php if(get_cfg('survey.mod')){?><td nowrap><a href="mod.php?SId=<?=$survey_row[$i]['SId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('survey.order') || get_cfg('survey.del')) && count($survey_row)){?>
	<tr>
		<td colspan="7" class="bottom_act">
			<?php if(get_cfg('survey.order')){?><input name="order_survey" type="button" class="form_button" onClick="click_button(this, 'list_form', 'action');" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(get_cfg('survey.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("select_SId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_survey" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="action" id="action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>