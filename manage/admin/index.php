<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('admin');

if($_POST['action']=='del_user'){
	if(count($_POST['select_UserId'])){
		for($i=0; $i<count($_POST['select_UserId']); $i++){
			del_file("/inc/manage/permit/{$_POST['select_UserId'][$i]}.php");
		}
		
		$UserId=implode(',', $_POST['select_UserId']);
		$db->delete('userinfo', "UserId in($UserId) and UserId!='{$_SESSION['ly200_AdminUserId']}'");
	}
	save_manage_log('删除后台管理员');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('admin.admin_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="6%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td>
		<td width="16%" nowrap><strong><?=get_lang('admin.username');?></strong></td>
		<td width="14%" nowrap><strong><?=get_lang('admin.locked');?></strong></td>
		<td width="14%" nowrap><strong><?=get_lang('ly200.under_the');?></strong></td>
		<td width="19%" nowrap><strong><?=get_lang('admin.last_login_time');?></strong></td>
		<td width="30%" nowrap><strong><?=get_lang('admin.last_login_ip');?></strong></td>
		<td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	include('../../inc/fun/ip_to_area.php');
	$userinfo_row=$db->get_all('userinfo', 1, '*', 'UserId desc');
	for($i=0; $i<count($userinfo_row); $i++){
		$ip_area=ip_to_area($userinfo_row[$i]['LastLoginIp']);
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<td><input name="select_UserId[]" type="checkbox" <?=$_SESSION['ly200_AdminUserId']!=$userinfo_row[$i]['UserId']?'':'disabled';?> value="<?=$userinfo_row[$i]['UserId'];?>" /></td>
		<td nowrap><?=$userinfo_row[$i]['UserName'];?></td>
		<td nowrap><?=get_lang('ly200.n_y_array.'.$userinfo_row[$i]['Locked']);?></td>
		<td nowrap><?=get_lang('admin.group_'.$userinfo_row[$i]['GroupId']);?></td>
		<td nowrap><?=$userinfo_row[$i]['LastLoginTime']?date(get_lang('ly200.time_format_full'), $userinfo_row[$i]['LastLoginTime']):'';?></td>
		<td nowrap><?=$userinfo_row[$i]['LastLoginIp']?($userinfo_row[$i]['LastLoginIp'].' ['.$ip_area['country'].$ip_area['area'].']'):'';?></td>
		<td nowrap><?php if($_SESSION['ly200_AdminUserId']!=$userinfo_row[$i]['UserId']){?><a href="mod.php?UserId=<?=$userinfo_row[$i]['UserId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a><?php }?></td>
	</tr>
	<?php }?>
	<?php if(count($userinfo_row)){?>
	<tr>
		<td colspan="8" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("select_UserId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<input name="del_user" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<input name="action" id="action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>