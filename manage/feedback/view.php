<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('feedback');

if($_POST){
	$FId=(int)$_POST['FId'];
	$query_string=$_POST['query_string'];
	
	$Display=(int)$_POST['Display'];
	$Reply=$_POST['Reply'];
	
	$db->update('feedback', "FId='$FId'", array(
			'Display'	=>	$Display,
			'Reply'		=>	$Reply,
			'ReplyTime'	=>	$service_time
		)
	);
	
	save_manage_log('在线留言回复');
	
	header("Location: index.php?$query_string");
	exit;
}

$FId=(int)$_GET['FId'];
$query_string=query_string('FId');

$feedback_row=$db->get_one('feedback', "FId='$FId'");
!$feedback_row['IsRead'] && $db->update('feedback', "FId='$FId'", array('IsRead'=>1));

include('../../inc/fun/ip_to_area.php');
$ip_area=ip_to_area($feedback_row['Ip']);

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('feedback.feedback_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.view');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="view.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.full_name');?>:</td>
		<td width="95%"><?=htmlspecialchars($feedback_row['Name']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('feedback.company');?>:</td>
		<td><?=htmlspecialchars($feedback_row['Company']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('feedback.phone');?>:</td>
		<td><?=htmlspecialchars($feedback_row['Phone']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('feedback.mobile');?>:</td>
		<td><?=htmlspecialchars($feedback_row['Mobile']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.email');?>:</td>
		<td><?=htmlspecialchars($feedback_row['Email']);?><?php if($feedback_row['Email'] && $menu['send_mail']){?>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="this.blur(); parent.openWindows('win_send_mail', '<?=get_lang('send_mail.send_mail_system');?>', 'send_mail/index.php?email=<?=urlencode($feedback_row['Email'].'/'.$feedback_row['Name']);?>');" class="red"><?=get_lang('send_mail.send');?></a><?php }?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('feedback.qq');?>:</td>
		<td><?=htmlspecialchars($feedback_row['QQ']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('feedback.subject');?>:</td>
		<td><?=htmlspecialchars($feedback_row['Subject']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('feedback.message');?>:</td>
		<td class="flh_150"><?=format_text($feedback_row['Message']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.ip');?>:</td>
		<td><?=$feedback_row['Ip'];?> [<?=$ip_area['country'].$ip_area['area'];?>]</td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.time');?>:</td>
		<td><?=date(get_lang('ly200.time_format_full'), $feedback_row['PostTime']);?></td>
	</tr>
	<?php if(get_cfg('feedback.display')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.display');?>:</td>
			<td><input type="checkbox" name="Display" value="1" <?= $feedback_row['Display']==1?'checked':'';?> /></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('feedback.reply')){?>
		<tr>
			<td nowrap><?=get_lang('feedback.reply');?>:</td>
			<td><textarea name="Reply" rows="7" cols="70" class="form_area"><?=htmlspecialchars($feedback_row['Reply'])?></textarea></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><?php if(get_cfg('feedback.reply')){?><input type="submit" value="<?=get_lang('feedback.reply');?>" name="submit" class="form_button"><input type="hidden" name="query_string" value="<?=$query_string;?>"><input type="hidden" name="FId" value="<?=$FId;?>"><?php }?><a href="index.php?<?=$query_string;?>" class="return<?=get_cfg('feedback.reply')?'':'_1';?>"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>