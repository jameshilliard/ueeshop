<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('send_mail');

if($_POST){
	$save_dir=get_cfg('ly200.up_file_base_dir').'send_mail/'.date('y_m_d/', $service_time);
	$Email=$_POST['Email'];
	$AllMember=(int)$_POST['AllMember'];
	$AllNewsletter=(int)$_POST['AllNewsletter'];
	$Subject=$_POST['Subject'];
	$Contents=save_remote_img($_POST['Contents'], $save_dir);
	$same=(substr_count($Subject, '{Email}') || substr_count($Subject, '{FullName}') || substr_count($Contents, '{Email}') || substr_count($Contents, '{FullName}'))?0:1;
	
	if($AllMember==1){	//包括网站所有注册用户
		$row=$db->get_all('member', '1', 'Email, FirstName, LastName');
		for($i=0; $i<count($row); $i++){
			$Email.="\r\n".$row[$i]['Email'].'/'.$row[$i]['FirstName'].' '.$row[$i]['LastName'];
		}
	}
	
	if($AllNewsletter==1){	//包括邮件列表里的邮箱
		$row=$db->get_all('newsletter');
		for($i=0; $i<count($row); $i++){
			$Email.="\r\n".$row[$i]['Email'].'/ ';
		}
	}
	
	$to_ary=$to_name_ary=$send_list=array();
	$Email_ary=explode("\r\n", $Email);
	
	for($i=0; $i<count($Email_ary); $i++){
		if($Email_ary[$i]!=''){
			$list_ary=explode('/', str_replace(';', '', $Email_ary[$i]));
			if(in_array(trim($list_ary[0]), $send_list)){
				continue;
			}else{
				$send_list[]=trim($list_ary[0]);
			}
			
			if($same==0){	//邮件内容不相同
				$to=trim($list_ary[0]);
				$to_name=trim($list_ary[1]);
				$mail_subject=str_replace(array('{Email}', '{FullName}'), array($to, $to_name), $Subject);
				$mail_contents=str_replace(array('{Email}', '{FullName}'), array($to, $to_name), $Contents);
				include('../../inc/lib/mail/template.php');
				sendmail($to, $to_name, $mail_subject, $mail_contents);
			}else{	//邮件内容全部相同的
				$to_ary[]=trim($list_ary[0]);
				$to_name_ary[]=trim($list_ary[1]);
			}
		}
	}
	
	if($same && count($to_ary)){
		$to=implode('; ', $to_ary);
		$to_name=implode('; ', $to_name_ary);
		$mail_contents=$Contents;
		include('../../inc/lib/mail/template.php');
		sendmail($to, $to_name, $Subject, $mail_contents);
	}
	
	save_manage_log('邮件系统发送邮件');
	
	js_location('index.php', get_lang('send_mail.send_success'));
}

$mail_default_list='';
if($_GET['MemberId']!=''){
	$member_row=$db->get_all('member', "MemberId in({$_GET['MemberId']})", 'Email, FirstName, LastName', 'MemberId desc');
	for($i=0; $i<count($member_row); $i++){
		$mail_default_list.="{$member_row[$i]['Email']}/{$member_row[$i]['FirstName']} {$member_row[$i]['LastName']}\r\n";
	}
}elseif($_GET['NId']!=''){
	$newsletter_row=$db->get_all('newsletter', "NId in({$_GET['NId']})", 'Email', 'NId desc');
	for($i=0; $i<count($newsletter_row); $i++){
		$mail_default_list.="{$newsletter_row[$i]['Email']}\r\n";
	}
}else{
	$mail_default_list=$_GET['email'];
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('send_mail.send_mail_system');?></a></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70%" valign="top">
		<form method="post" name="act_form" id="act_form" class="act_form" action="index.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
		<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
			<tr> 
				<td width="5%" nowrap><?=get_lang('send_mail.to');?>:</td>
				<td width="95%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td valign="top">
							<textarea name="Email" id="Email" style="width:400px; height:200px;" class="form_area"><?=$mail_default_list;?></textarea>
						</td>
						<td valign="top" class="flh_150" style="padding-left:10px;">
							<?=get_lang('send_mail.tips');?><br /><br />
							<input type="checkbox" name="AllMember" value="1"><?=get_lang('send_mail.send_to_all_member');?><br />
							<input type="checkbox" name="AllNewsletter" value="1"><?=get_lang('send_mail.send_to_all_newsletter');?>
						</td>
					  </tr>
					</table>
				</td>
			</tr>
			<tr>
				<td nowrap><?=get_lang('send_mail.subject');?>:</td>
				<td><input name="Subject" type="text" value="" class="form_input" size='60' maxlength="100" check="<?=get_lang('ly200.filled_out').get_lang('send_mail.subject');?>!~*"></td>
			</tr>
			<tr>
				<td nowrap><?=get_lang('ly200.contents');?>:</td>
				<td class="ck_editor"><textarea class="ckeditor" name="Contents"></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="<?=get_lang('send_mail.send');?>" name="submit" class="form_button"></td>
			</tr>
		</table>
		</form>
	</td>
    <td width="30%" valign="top">
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
		  <tr>
			<td height="24" style="border-bottom:1px solid #ccc;">&nbsp;&nbsp;<strong><?=get_lang('send_mail.member_list');?>:</strong></td>
		  </tr>
		  <tr>
			<td id="mail_list"></td>
		  </tr>
		  <tr>
			<td height="30">
				<input name="button" type="button" class="form_button" onClick='change_all("MailList[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="button" type="button" class="form_button" onClick='bat_add_email_address();' value="<?=get_lang('ly200.add');?>">
			</td>
		  </tr>
		</table>
		<iframe src="mail_list.php" name="mail_list_iframe" style="display:none;"></iframe>
	</td>
  </tr>
</table>
<script language="javascript">
function add_email_address(v){
	if($_('Email').value.indexOf(v)==-1){
		$_('Email').value=v+"\r\n"+$_('Email').value;
	}
}

function bat_add_email_address(){
	var objs=document.getElementsByTagName('input');
	for(i=objs.length-1; i>0; i--){
		if(objs[i].type.toLowerCase()=='checkbox' && objs[i].name=='MailList[]' && objs[i].checked==true && $_('Email').value.indexOf(objs[i].value)==-1){
			$_('Email').value=objs[i].value+"\r\n"+$_('Email').value;
		}
	}
}
</script>
<?php include('../../inc/manage/footer.php');?>