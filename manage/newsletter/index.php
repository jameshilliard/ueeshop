<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('newsletter');

if($_POST['list_form_action']=='newsletter_del'){
	check_permit('', 'newsletter.del');
	if(count($_POST['select_NId'])){
		$NId=implode(',', $_POST['select_NId']);
		$db->delete('newsletter', "NId in($NId)");
	}
	save_manage_log('批量删除邮件列表');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['list_form_action']=='member_send_mail'){
	include('../../inc/manage/header.php');
	echo '<script language=javascript>';
	echo 'parent.openWindows("win_send_mail", "'.get_lang('send_mail.send_mail_system').'", "send_mail/index.php?NId='.implode(',', $_POST['select_NId']).'")';
	echo '</script>';
	js_back();
}

if($_GET['query_string']){
	$page=(int)$_GET['page'];
	header("Location: index.php?{$_GET['query_string']}&page=$page");
	exit;
}

//分页查询
$where=1;
$row_count=$db->get_row_count('newsletter', $where);
$total_pages=ceil($row_count/get_cfg('newsletter.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('newsletter.page_count');
$newsletter_row=$db->get_limit('newsletter', $where, '*', 'NId desc', $start_row, get_cfg('newsletter.page_count'));

//获取页面跳转url参数
$query_string=query_string('page');

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('newsletter.newsletter_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="15%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('newsletter.del') || $menu['send_mail']){?><td width="15%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<td width="35%" nowrap><strong><?=get_lang('ly200.email');?></strong></td>
		<td width="35%" nowrap><strong><?=get_lang('ly200.time');?></strong></td>
	</tr>
	<?php
	for($i=0; $i<count($newsletter_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('newsletter.del') || $menu['send_mail']){?><td><input type="checkbox" name="select_NId[]" value="<?=$newsletter_row[$i]['NId'];?>"></td><?php }?>
		<td nowrap><?php if($menu['send_mail']){?><a href="javascript:void(0);" onclick="this.blur(); parent.openWindows('win_send_mail', '<?=get_lang('send_mail.send_mail_system');?>', 'send_mail/index.php?email=<?=urlencode($newsletter_row[$i]['Email']);?>');"><?=htmlspecialchars($newsletter_row[$i]['Email']);?></a><?php }else{?><?=htmlspecialchars($newsletter_row[$i]['Email']);?><?php }?></td>
		<td nowrap><?=date(get_lang('ly200.time_format_full'), $newsletter_row[$i]['PostTime']);?></td>
	</tr>
	<?php }?>
	<?php if((get_cfg('newsletter.del') || $menu['send_mail']) && count($newsletter_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("select_NId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<?php if($menu['send_mail']){?><input name="member_send_mail" id="member_send_mail" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action');" value="<?=get_lang('send_mail.send');?>"><?php }?>
			<?php if(get_cfg('newsletter.del')){?><input name="newsletter_del" id="newsletter_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>"><?php }?>
			<input type="hidden" name="query_string" value="<?=urlencode($query_string);?>">
			<input type="hidden" name="page" value="<?=$page;?>">
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<?php include('../../inc/manage/footer.php');?>