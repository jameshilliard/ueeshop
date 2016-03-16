<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('admin');

$Ly200Cfg=$tmp_Ly200Cfg;
$menu=$tmp_menu;
$manage_menu=$tmp_manage_menu;

if($_POST){
	$UserId=(int)$_POST['UserId'];
	if($_POST['Password']){
		$Password=password($_POST['Password']);
		$db->update('userinfo', "UserId='$UserId'", array(
				'Password'	=>	$Password
			)
		);
	}
	$Locked=(int)$_POST['Locked'];
	$GroupId=(int)$_POST['GroupId'];
	$db->update('userinfo', "UserId='$UserId'", array(
			'Locked'	=>	$Locked,
			'GroupId'	=>	$GroupId
		)
	);
	
	//------------------------------------------------------------------------权限(start here)------------------------------------------------------------------------
	if($GroupId!=1){
		$act_ary=array('add', 'mod', 'del', 'order', 'move', 'reset');
		$php_contents_menu='';
		$php_contents_config='';
		foreach($manage_menu as $group_key=>$group){
			if(implode($group)==''){
				continue;
			}
			foreach($group as $key=>$value){
				if($menu[$key]!=1 || $key=='admin'){
					continue;
				}
				$php_contents_menu.="\$permit_menu['$key']=".(int)$_POST['permit_'.$key].";\r\n";
				$find=0;
				$cfg=get_cfg($key);
				if(!is_array($cfg)){
					$find=1;
					$cfg=get_cfg(str_replace('_', '.', $key));
				}
				if($cfg['add']==1 || $cfg['mod']==1 || $cfg['del']==1 || $cfg['order']==1 || $cfg['move']==1 || $cfg['reset']==1){
					$k=$find==0?"['$key']":('[\''.str_replace('_', '\'][\'', $key).'\']');
					for($i=0; $i<count($act_ary); $i++){
						$cfg[$act_ary[$i]]==1 && $php_contents_config.="\$permit_Ly200Cfg{$k}['{$act_ary[$i]}']=".(int)$_POST["permit_{$key}_{$act_ary[$i]}"].";\r\n";
					}
				}
			}
		}
		$php_contents="<?php\r\n//管理员{$UserName}权限配置\r\n".$php_contents_menu."\r\n".$php_contents_config.'?>';
		write_file('/inc/manage/permit/', "$UserId.php", $php_contents);
	}else{
		del_file("/inc/manage/permit/$UserId.php");
	}
	//------------------------------------------------------------------------权限(end here)-------------------------------------------------------------------------
	
	save_manage_log('修改后台管理员');
	
	header('Location: index.php');
	exit;
}

$UserId=(int)$_GET['UserId'];
$userinfo_row=$db->get_one('userinfo', "UserId='$UserId'");

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('admin.admin_manage');?></a>&nbsp;-&gt;&nbsp;<a href="mod.php?UserId=<?=$UserId;?>"><?=$userinfo_row['UserName'];?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="mod.php" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr>
		<td width="5%" nowrap><?=get_lang('admin.password');?>:</td>
		<td width="95%" class="fc_yellow"><input name="Password" type="password" value="" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('admin.password');?>!~8m|<?=get_lang('admin.password_left_len');?>" size="25" maxlength="16">&nbsp;&nbsp;<?=get_lang('admin.keep_null_unmodpwd');?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('admin.re_password');?>:</td>
		<td><input name="RePassword" type="password" value="" class="form_input" check="<?=get_lang('ly200.filled_out').get_lang('admin.re_password');?>!~=Password|<?=get_lang('admin.repwd_dif_pwd');?>" size="25" maxlength="16"></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('admin.locked');?>:</td>
		<td><input type="checkbox" value="1" name="Locked" <?=$userinfo_row['Locked']?'checked':'';?> /></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.under_the');?>:</td>
		<td><select name="GroupId" onchange="change_admin_group(this.value);">
			<option value="1" <?=$userinfo_row['GroupId']==1?'selected':'';?>><?=get_lang('admin.group_1');?></option>
			<option value="2" <?=$userinfo_row['GroupId']==2?'selected':'';?>><?=get_lang('admin.group_2');?></option>
		</select></td>
	</tr>
	<tr id="permit_list" style="display:none;">
		<td nowrap><?=get_lang('admin.permit');?>:</td>
		<td class="permit">
			<?php
			@include("../../inc/manage/permit/$UserId.php");
			$act_ary=array('add', 'mod', 'del', 'order', 'move', 'reset');
			foreach($manage_menu as $group_key=>$group){
				if(implode($group)==''){
					continue;
				}
				echo '<div class="f"><strong>'.get_lang('menu.'.$group_key).'</strong></div>';
				foreach($group as $key=>$value){
					if($menu[$key]!=1 || $key=='admin'){
						continue;
					}
					$checked=$permit_menu[$key]==1?'checked':'';
					echo "<div class='s'><input type='checkbox' value='1' name='permit_$key' $checked onclick='change_admin_permit(this.name, this.checked);'>".get_lang($value[1]);
					$find=0;
					$cfg=get_cfg($key);
					if(!is_array($cfg)){
						$find=1;
						$cfg=get_cfg(str_replace('_', '.', $key));
					}
					if($cfg['add']==1 || $cfg['mod']==1 || $cfg['del']==1 || $cfg['order']==1 || $cfg['move']==1 || $cfg['reset']==1){
						$k=$find==0?"['$key']":('[\''.str_replace('_', '\'][\'', $key).'\']');
						echo '(';
						for($i=0; $i<count($act_ary); $i++){
							if($cfg[$act_ary[$i]]==1){
								$checked=(int)eval("return \$permit_Ly200Cfg{$k}[{$act_ary[$i]}];")==1?'checked':'';
								echo "<input type='checkbox' value='1' name='permit_{$key}_{$act_ary[$i]}' $checked>".get_lang('ly200.'.$act_ary[$i]);
							}
						}
						echo ')';
					}
					echo '</div>';
				}
			}
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input name="button" type="button" class="form_button" id="change_all_button" style="display:none;" onClick='change_all_admin_permit();' value="<?=get_lang('ly200.anti_select');?>">
			<input type="Submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='index.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="UserId" value="<?=$UserId;?>" />
		</td>
	</tr>
</table>
</form>
<script language="javascript">change_admin_group(<?=$userinfo_row['GroupId'];?>);</script>
<?php include('../../inc/manage/footer.php');?>