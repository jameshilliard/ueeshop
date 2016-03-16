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

if($_POST['list_form_action']=='article_order'){
	!in_array($Group, get_cfg('article.amdo')) && no_permit();
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$AId=(int)$_POST['AId'][$i];
		
		$db->update('article', "AId='$AId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('信息页排序');
	
	header("Location: index.php?GroupId=$GroupId");
	exit;
}

if($_POST['list_form_action']=='article_del'){
	!in_array($Group, get_cfg('article.amdo')) && no_permit();
	if(count($_POST['select_AId'])){
		$AId=implode(',', $_POST['select_AId']);
		
		$art_row=$db->get_all('article', "AId in($AId)", 'PageUrl');
		for($i=0; $i<count($art_row); $i++){
			del_file($art_row[$i]['PageUrl']);
		}
		
		$db->delete('article', "AId in($AId)");
	}
	save_manage_log('批量删除信息页');
	
	header("Location: index.php?GroupId=$GroupId");
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php?GroupId=<?=$GroupId;?>"><?=get_lang('article.group_'.$GroupId);?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(in_array($Group, get_cfg('article.amdo'))){?><div class="float_right"><a href="add.php?GroupId=<?=$GroupId;?>"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(in_array($Group, get_cfg('article.amdo'))){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(in_array($Group, get_cfg('article.amdo'))){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="25%" nowrap><strong><?=get_lang('ly200.title');?></strong></td>
		<td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	$article_row=$db->get_all('article', "GroupId='$GroupId'", '*', 'MyOrder desc, AId asc');
	for($i=0; $i<count($article_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(in_array($Group, get_cfg('article.amdo'))){?><td><input type="checkbox" name="select_AId[]" value="<?=$article_row[$i]['AId'];?>"></td><?php }?>
		<?php if(in_array($Group, get_cfg('article.amdo'))){?><td><input type="text" name="MyOrder[]" class="form_input" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=$article_row[$i]['MyOrder'];?>" size="3" maxlength="10"><input name="AId[]" type="hidden" value="<?=$article_row[$i]['AId'];?>"></td><?php }?>
		<td class="break_all"><a href="<?=get_url('article', $article_row[$i]);?>" target="_blank"><?=list_all_lang_data($article_row[$i], 'Title');?></a></td>
		<td nowrap><a href="mod.php?AId=<?=$article_row[$i]['AId'];?>&GroupId=<?=$GroupId;?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td>
	</tr>
	<?php }?>
	<?php if(in_array($Group, get_cfg('article.amdo')) && count($article_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="article_order" id="article_order" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>">
			<input name="button" type="button" class="form_button" onClick='change_all("select_AId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<input name="article_del" id="article_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>">
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
			<input type="hidden" name="GroupId" value="<?=$GroupId;?>" />
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>