<?php
$query_string=query_string(array('forgot_success', 'forgot_fail'));

if($_POST['data']=='member_password'){
	if(password($_POST['OldPassword'])!=$_SESSION['member_Password']){
		$_SESSION['password_post']=$_POST;
		js_location("$member_url?password_fail=1&$query_string");
	}
	
	$_SESSION['member_Password']=password($_POST['Password']);
	
	$db->update('member', $where, array(
			'Password'	=>	$_SESSION['member_Password']
		)
	);
	
	js_location("$member_url?password_success=1&$query_string");
}
?>
<div id="lib_member_password">
	<?php if($_GET['password_fail']==1){?>
		<div class="lib_member_msgerror"><img src="/images/lib/member/msg_error.png" align="absmiddle" />&nbsp;&nbsp;Sorry, Current Password is not correct.</div>
	<?php }?>
	<div class="lib_member_title">Change Password</div>
	<?php if($_GET['password_success']==1){?>
		<div class="blank15"></div>
		<div class="change_success lib_member_item_card">
			<br />You have successfully change your password.<br /><br /><br />
			<a href="<?=$member_url;?>?module=index"><strong>My Account</strong></a><br /><br />
		</div>
	<?php }else{?>
		<div class="lib_member_info">If you wish to change the password for your account, please complete the fields below and click the "Change Password" button when you have finished.</div>
		<div class="form lib_member_item_card">
			<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_password_form" OnSubmit="return checkForm(this);">
				<div class="lib_member_sub_title">Change your Password</div>
				<div class="rows">
					<label>Old Password: <font class="fc_red">*</font></label>
					<span><input name="OldPassword" value="<?=htmlspecialchars($_SESSION['password_post']['OldPassword']);?>" type="password" class="form_input" check="Old Password is required!~*" size="50" maxlength="20"></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="rows">
					<label>New Password: <font class="fc_red">*</font></label>
					<span><input name="Password" value="<?=htmlspecialchars($_SESSION['password_post']['Password']);?>" type="password" class="form_input" check="New Password is required!~*" size="50" maxlength="20"></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="rows">
					<label>Re-type New Password: <font class="fc_red">*</font></label>
					<span><input name="ConfirmPassword" value="<?=htmlspecialchars($_SESSION['password_post']['ConfirmPassword']);?>" type="password" class="form_input" check="Re-type New Password is required!~=Password|Re-type New Password entered doesn't match with New Password value!*" size="50" maxlength="20"></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="rows">
					<label></label>
					<span><input name="Submit" type="submit" class="form_button form_button_130" value="Change Password"></span>
					<div class="clear"></div>
				</div>
				<input type="hidden" name="data" value="member_password" />
			</form>
		</div>
	<?php }?>
</div>
<?php
$_SESSION['password_post']='';
unset($_SESSION['password_post']);
?>