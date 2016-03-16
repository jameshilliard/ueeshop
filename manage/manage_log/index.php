<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('manage_log');

if($_POST['list_form_action']=='manage_log_del'){
	check_permit('', 'manage_log.del');
	if(count($_POST['select_LId'])){
		$LId=implode(',', $_POST['select_LId']);
		$where="LId in($LId)";
		$db->delete('manage_log', $where);
	}
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_GET['query_string']){
	$page=(int)$_GET['page'];
	header("Location: index.php?{$_GET['query_string']}&page=$page");
	exit;
}

//分页查询
$where=1;
$AdminUserName=$_GET['AdminUserName'];
$PageUrl=$_GET['PageUrl'];
$Ip=$_GET['Ip'];
$LogContents=$_GET['LogContents'];
$OpTimeS=$_GET['OpTimeS'];
$OpTimeE=$_GET['OpTimeE'];
$AdminUserName && $where.=" and AdminUserName='$AdminUserName'";
$PageUrl && $where.=" and PageUrl like '%$PageUrl%'";
$Ip && $where.=" and Ip='$Ip'";
$LogContents && $where.=" and LogContents like '%$LogContents%'";
if($OpTimeS && $OpTimeE){
	$ts=@strtotime($OpTimeS);
	$te=@strtotime($OpTimeE);
	$te && $te+=86400;
	($ts && $te) && $where.=" and OpTime between $ts and $te";
}

$row_count=$db->get_row_count('manage_log', $where);
$total_pages=ceil($row_count/get_cfg('manage_log.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('manage_log.page_count');
$manage_log_row=$db->get_limit('manage_log', $where, '*', 'LId desc', $start_row, get_cfg('manage_log.page_count'));

//获取页面跳转url参数
$query_string=query_string('page');

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('manage_log.log_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="manage_log_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('manage_log.admin_user_name');?>:<input name="AdminUserName" class="form_input" type="text" size="10" maxlength='20'>
				<?=get_lang('manage_log.page_url');?>:<input name="PageUrl" class="form_input" type="text" size="25" maxlength='200'>
				<?=get_lang('manage_log.ip');?>:<input name="Ip" class="form_input" type="text" size="15" maxlength='15'>
				<?=get_lang('manage_log.log_contents');?>:<input name="LogContents" class="form_input" type="text" size="20" maxlength='100'>
				<?=get_lang('ly200.time');?>:<input name="OpTimeS" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />-<input name="OpTimeE" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />
				<input type="submit" name="submit" value="<?=get_lang('ly200.search');?>" class="form_button" />
			</form>
		</td>
	</tr>
</table>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('manage_log.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<td width="10%" nowrap><strong><?=get_lang('manage_log.admin_user_name');?></strong></td>
		<td width="20%"><strong><?=get_lang('manage_log.page_url');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('manage_log.ip');?></strong></td>
		<td width="20%" nowrap><strong><?=get_lang('manage_log.ip_to_address');?></strong></td>
		<td width="20%"><strong><?=get_lang('manage_log.log_contents');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.time');?></strong></td>
	</tr>
	<?php
	include('../../inc/fun/ip_to_area.php');
	for($i=0; $i<count($manage_log_row); $i++){
		$ip_area=ip_to_area($manage_log_row[$i]['Ip']);
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('manage_log.order')){?><td><input type="text" name="MyOrder[]" value="<?=$manage_log_row[$i]['MyOrder'];?>" style="width:25px" maxlength="8"><input name="LId[]" type="hidden" value="<?=$manage_log_row[$i]['LId'];?>"></td><?php }?>
		<?php if(get_cfg('manage_log.del')){?><td><input type="checkbox" name="select_LId[]" value="<?=$manage_log_row[$i]['LId'];?>"></td><?php }?>
		<td nowrap><?=$manage_log_row[$i]['AdminUserName'];?></td>
		<td class="break_all"><?=$manage_log_row[$i]['PageUrl'];?></td>
		<td nowrap><?=$manage_log_row[$i]['Ip'];?></td>
		<td nowrap><?=$ip_area['country'].$ip_area['area']?></td>
		<td class="break_all"><?=$manage_log_row[$i]['LogContents'];?></td>
		<td nowrap><?=date(get_lang('ly200.time_format_full'), $manage_log_row[$i]['OpTime']);?></td>
	</tr>
	<?php }?>
	<?php if(get_cfg('manage_log.del') && count($manage_log_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("select_LId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<input name="manage_log_del" id="manage_log_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>">
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