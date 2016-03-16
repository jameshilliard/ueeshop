<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('survey', 'survey.add');

if($_POST){
	$Subject=$_POST['Subject'];
	$Language=(int)$_POST['Language'];
	
	$db->insert('survey', array(
			'Subject'	=>	$Subject,
			'Language'	=>	$Language
		)
	);
	$SId=$db->get_insert_id();
	
	for($i=0; $i<count($_POST['ItemTitle']); $i++){
		$_ItemTitle=$_POST['ItemTitle'][$i];
		$_VotesCount=(int)$_POST['VotesCount'][$i];
		if($_ItemTitle){
			$db->insert('survey_item', array(
					'SId'		=>	$SId,
					'ItemTitle'	=>	$_ItemTitle,
					'VotesCount'=>	$_VotesCount
				)
			);
		}
	}
	
	save_manage_log('添加在线调查:'.$Subject);
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('survey.survey_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.add');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="add.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgsurvey_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('survey.subject');?>:</td>
		<td width="95%"><input name="Subject" type="text" value="" class="form_input" size="40" maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('survey.subject');?>!~*"></td>
	</tr>
	<?php if(count(get_cfg('ly200.lang_array'))>1){?>
		<tr>
			<td nowrap><?=get_lang('ly200.language');?>:</td>
			<td><?=output_language_select();?></td>
		</tr>
	<?php }?>
	<tr>
		<td><?=get_lang('survey.item');?>:</td>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" id="survey_item_list" class="item_data_table">
				<tr>
					<td><a href="javascript:void(0);" onClick="this.blur(); add_survey_item('survey_item_list');" class="red"><?=get_lang('ly200.add_item');?></a></td>
				</tr>
				<?php for($i=0; $i<get_cfg('survey.survey_item_n'); $i++){?>
					<tr>
						<td><input name="ItemTitle[]" type="text" value="" class="form_input" size="45" maxlength="100"><?=get_lang('survey.votes_count');?>:<input name="VotesCount[]" type="text" value="" class="form_input" size="5" maxlength="10" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);"><a href="javascript:void(0)" onClick="$_('survey_item_list').deleteRow(this.parentNode.parentNode.rowIndex);"><img src="../images/del.gif" hspace="5" /></a></td>
					</tr>
				<?php }?>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="Submit" name="submit" value="<?=get_lang('ly200.add');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>