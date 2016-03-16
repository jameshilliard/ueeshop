<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('member');

if($_POST['list_form_action']=='member_del'){
	check_permit('', 'member.del');
	if(count($_POST['select_MemberId'])){
		$MemberId=implode(',', $_POST['select_MemberId']);
		$db->delete('member', "MemberId in($MemberId)");
		$db->delete('member_address_book', "MemberId in($MemberId)");
		$db->delete('member_forgot', "MemberId in($MemberId)");
	}
	save_manage_log('批量删除会员');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['list_form_action']=='member_send_mail'){
	include('../../inc/manage/header.php');
	echo '<script language=javascript>';
	echo 'parent.openWindows("win_send_mail", "'.get_lang('send_mail.send_mail_system').'", "send_mail/index.php?MemberId='.implode(',', $_POST['select_MemberId']).'")';
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
$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$RegTimeS=$_GET['RegTimeS'];
$RegTimeE=$_GET['RegTimeE'];
$LastLoginTimeS=$_GET['LastLoginTimeS'];
$LastLoginTimeE=$_GET['LastLoginTimeE'];

$FullName && $where.=" and concat(FirstName, ' ', LastName) like '%$FullName%'";
$Email && $where.=" and Email like '%$Email%'";
if($RegTimeS && $RegTimeE){
	$ts=@strtotime($RegTimeS);
	$te=@strtotime($RegTimeE);
	$te && $te+=86400;
	($ts && $te) && $where.=" and RegTime between $ts and $te";
}
if($LastLoginTimeS && $LastLoginTimeE){
	$ts=@strtotime($LastLoginTimeS);
	$te=@strtotime($LastLoginTimeE);
	$te && $te+=86400;
	($ts && $te) && $where.=" and LastLoginTime between $ts and $te";
}

$order_ary=array(
	1=>'RegTime asc,',
	2=>'RegTime desc,',
	3=>'LastLoginTime asc,',
	4=>'LastLoginTime desc,',
	5=>'LoginTimes asc,',
	6=>'LoginTimes desc,'
);
$order=(int)$_GET['order'];

$row_count=$db->get_row_count('member', $where);
$total_pages=ceil($row_count/get_cfg('member.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('member.page_count');
$member_row=$db->get_limit('member', $where, '*', "{$order_ary[$order]}MemberId desc", $start_row, get_cfg('member.page_count'));

//获取页面跳转url参数
$query_string=query_string('page');
$query_string_no_order=query_string(array('page', 'order'));

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('member.member_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="manage_log_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.full_name');?>:<input name="FullName" class="form_input" type="text" size="10" maxlength='20'>
				<?=get_lang('ly200.email');?>:<input name="Email" class="form_input" type="text" size="20" maxlength='200'>
				<?=get_lang('member.reg_time');?>:<input name="RegTimeS" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />-<input name="RegTimeE" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />
				<?=get_lang('member.last_login_time');?>:<input name="LastLoginTimeS" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />-<input name="LastLoginTimeE" type="text" size="8" onclick="SelectDate(this)" contenteditable="false" value="" class="form_input" />
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
		<?php if(get_cfg('member.del') || $menu['send_mail']){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<td width="5%" nowrap><strong><?=get_lang('member.title');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.full_name');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.email');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('member.reg_time');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=1"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=2"><img src="../images/desc.png" /></a></td>
		<td width="10%" nowrap><strong><?=get_lang('member.reg_ip');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('member.last_login_time');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=3"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=4"><img src="../images/desc.png" /></a></td>
		<td width="10%" nowrap><strong><?=get_lang('member.last_login_ip');?></strong></td>
		<td width="5%" nowrap><strong><?=get_lang('member.login_times');?></strong><a href="index.php?<?=$query_string_no_order;?>&order=5"><img src="../images/asc.png" /></a><a href="index.php?<?=$query_string_no_order;?>&order=6"><img src="../images/desc.png" /></a></td>
		<td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	include('../../inc/fun/ip_to_area.php');
	for($i=0; $i<count($member_row); $i++){
		$reg_ip_area=ip_to_area($member_row[$i]['RegIp']);
		$last_login_ip_area=ip_to_area($member_row[$i]['LastLoginIp']);
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('member.del') || $menu['send_mail']){?><td><input type="checkbox" name="select_MemberId[]" value="<?=$member_row[$i]['MemberId'];?>"></td><?php }?>
		<td nowrap><?=htmlspecialchars($member_row[$i]['Title']);?></td>
		<td nowrap><?=htmlspecialchars($member_row[$i]['FirstName'].' '.$member_row[$i]['LastName']);?></td>
		<td nowrap><?php if($menu['send_mail']){?><a href="javascript:void(0);" onclick="this.blur(); parent.openWindows('win_send_mail', '<?=get_lang('send_mail.send_mail_system');?>', 'send_mail/index.php?email=<?=urlencode($member_row[$i]['Email'].'/'.$member_row[$i]['FirstName'].' '.$member_row[$i]['LastName']);?>');"><?=htmlspecialchars($member_row[$i]['Email']);?></a><?php }else{?><?=htmlspecialchars($member_row[$i]['Email']);?><?php }?></td>
		<td nowrap><?=date(get_lang('ly200.time_format_full'), $member_row[$i]['RegTime']);?></td>
		<td><?=htmlspecialchars($member_row[$i]['RegIp']);?> [<?=$reg_ip_area['country'].$reg_ip_area['area'];?>]</td>
		<td nowrap><?=date(get_lang('ly200.time_format_full'), $member_row[$i]['LastLoginTime']);?></td>
		<td><?=htmlspecialchars($member_row[$i]['LastLoginIp']);?> [<?=$last_login_ip_area['country'].$last_login_ip_area['area'];?>]</td>
		<td nowrap><?=$member_row[$i]['LoginTimes'];?></td>
		<td><a href="view.php?<?=$query_string;?>&MemberId=<?=$member_row[$i]['MemberId']?>"><img src="../images/view.gif" alt="<?=get_lang('ly200.view');?>" /></a></td>
	</tr>
	<?php }?>
	<?php if((get_cfg('member.del') || $menu['send_mail']) && count($member_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("select_MemberId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<?php if($menu['send_mail']){?><input name="member_send_mail" id="member_send_mail" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action');" value="<?=get_lang('send_mail.send');?>"><?php }?>
			<?php if(get_cfg('member.del')){?><input name="member_del" id="member_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>"><?php }?>
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