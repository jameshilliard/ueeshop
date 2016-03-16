<?php
$query_string=query_string(array('forgot_success', 'forgot_fail'));

if($_POST['data']=='member_forgot'){
	$Email=$_POST['Email'];
	$user_row=$db->get_one('member', "Email='$Email'");
	
	if($user_row){
		$EmailEncode=base64_encode($user_row['Email']);
		$Expiry=base64_encode(rand_code(15));
		$fullname=$user_row['Title'].' '.$user_row['FirstName'].' '.$user_row['LastName'];
		$domain=get_domain();
		$domain_un_protocol=get_domain(0);
		
		if(!$db->get_row_count('member_forgot', "MemberId='{$user_row['MemberId']}' and IsReset=0")){
			$db->insert('member_forgot', array(
					'MemberId'		=>	$user_row['MemberId'],
					'EmailEncode'	=>	$EmailEncode,
					'Expiry'		=>	$Expiry,
					'ResetTime'		=>	$service_time
				)
			);
		}else{
			$db->update('member_forgot', "MemberId='{$user_row['MemberId']}' and IsReset=0", array(
					'EmailEncode'	=>	$EmailEncode,
					'Expiry'		=>	$Expiry,
					'ResetTime'		=>	$service_time
				)
			);
		}
		
		include($site_root_path.'/inc/lib/mail/forgot.php');
		include($site_root_path.'/inc/lib/mail/template.php');
		sendmail($user_row['Email'], $fullname, "$domain_un_protocol Password Recovery", $mail_contents);
		
		$_SESSION['forgot_post']='';
		unset($_SESSION['forgot_post']);
		
		js_location("$member_url?forgot_success=1&$query_string");
	}else{
		$_SESSION['forgot_post']=$_POST;
		js_location("$member_url?forgot_fail=1&$query_string");
	}
}

if($_POST['data']=='member_reset_password'){
	$Password=password($_POST['Password']);
	$email=$_POST['email'];
	$expiry=$_POST['expiry'];
	
	$user_row=$db->get_one('member_forgot', "EmailEncode='$email' and Expiry='$expiry' and IsReset=0");
	!$user_row && js_location('/');
	
	$db->update('member', "MemberId='{$user_row['MemberId']}'", array(
			'Password'	=>	$Password
		)
	);
	$db->update('member_forgot', "FId='{$user_row['FId']}'", array(
			'IsReset'	=>	1
		)
	);
	
	js_location("$member_url?reset_success=1&$query_string");
}

$email=$_GET['email'];
$expiry=$_GET['expiry'];
?>
<div id="lib_member_forgot">
	<?php if($_GET['forgot_fail']==1){?>
		<div class="lib_member_msgerror"><img src="/images/lib/member/msg_error.png" align="absmiddle" />&nbsp;&nbsp;Sorry, this Email Address is not registered with our online store.</div>
	<?php }?>
	<div class="lib_member_title">Reset Your Password</div>
	<?php if($email=='' || $expiry==''){?>
		<?php if($_GET['forgot_success']==1){?>
			<div class="blank15"></div>
			<div class="send_tips lib_member_item_card">
				<div>
					We have sent an email to the address you have on file with us. Please follow the instructions in this email to reset your password.
					<div class="no_email">Haven't received the email?</div>
					Check your bulk and junk email folders. If you still can't find the password reset email, please call our Customer Care Team. Thank you!
				</div>
				<div class="continue_shopping"><input type="button" name="continue_shopping" value="Continue Shopping" class="form_button form_button_130" onClick="window.location='/';"></div>
			</div>
		<?php }else{?>
			<div class="lib_member_info">Before we can reset your password, we require that you enter your email address below. You will then receive an email with instructions to reset your password.</div>
			<div class="form lib_member_item_card">
				<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_forgot_form" OnSubmit="return checkForm(this);">
					<div class="lib_member_sub_title">Enter your email address</div>
					<div class="rows">
						<label>Email address:</label>
						<span><input name="Email" value="<?=htmlspecialchars($_SESSION['forgot_post']['Email']);?>" type="text" class="form_input" size="50" maxlength="20" check="Email is required!~email|Email entered doesn't match with confirm Email value!*"></span>
						<div class="clear"></div>
					</div>
					<div class="blank6"></div>
					<div class="rows">
						<label></label>
						<span><input name="Submit" type="submit" class="form_button" value="Submit"></span>
						<div class="clear"></div>
					</div>
					<div class="blank15"></div>
					<div class="dline"></div>
					<div class="lib_member_info">If you can't remember which email address you registered with or still have problems signing in to your account please contact our Customer Services.</div>
					<input type="hidden" name="data" value="member_forgot" />
				</form>
			</div>
		<?php }?>
	<?php
	}else{
	?>
		<?php if($_GET['reset_success']==1){?>
			<div class="blank15"></div>
			<div class="reset_success lib_member_item_card">
				<br />You have successfully reset your password.<br /><br /><br />
				<a href="<?=$member_url;?>?module=login"><strong>Sign In My Account</strong></a><br /><br />
			</div>
		<?php
		}else{
			!$db->get_row_count('member_forgot', "EmailEncode='$email' and Expiry='$expiry' and IsReset=0") && js_location('/');
		?>
			<div class="lib_member_info">To reset your password, please enter your new password below.</div>
			<div class="form reset_form lib_member_item_card">
				<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_reset_password_form" OnSubmit="return checkForm(this);">
					<div class="rows">
						<label>New Password: <font class="fc_red">*</font></label>
						<span><input name="Password" value="<?=htmlspecialchars($_SESSION['create_post']['Password']);?>" type="password" class="form_input" check="New Password is required!~*" size="50" maxlength="20"></span>
						<div class="clear"></div>
					</div>
					<div class="blank6"></div>
					<div class="rows">
						<label>Re-type New Password: <font class="fc_red">*</font></label>
						<span><input name="ConfirmPassword" value="<?=htmlspecialchars($_SESSION['create_post']['ConfirmPassword']);?>" type="password" class="form_input" check="Re-type New Password is required!~=Password|Re-type New Password entered doesn't match with New Password value!*" size="50" maxlength="20"></span>
						<div class="clear"></div>
					</div>
					<div class="blank6"></div>
					<div class="rows">
						<label></label>
						<span><input name="Submit" type="submit" class="form_button" value="Submit"></span>
						<div class="clear"></div>
					</div>
					<input type="hidden" name="email" value="<?=htmlspecialchars($email);?>" />
					<input type="hidden" name="expiry" value="<?=htmlspecialchars($expiry);?>" />
					<input type="hidden" name="data" value="member_reset_password" />
				</form>
			</div>
		<?php }?>
	<?php }?>
</div>
<?php
$_SESSION['forgot_post']='';
unset($_SESSION['forgot_post']);
?>