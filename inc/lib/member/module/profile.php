<?php
$query_string=query_string(array('forgot_success', 'forgot_fail'));

if($_POST['data']=='member_profile'){
	$Title=$_POST['Title'];
	$FirstName=$_POST['FirstName'];
	$LastName=$_POST['LastName'];
	
	$db->update('member', $where, array(
			'Title'		=>	$Title,
			'FirstName'	=>	$FirstName,
			'LastName'	=>	$LastName
		)
	);
	
	$_SESSION['member_Title']=stripslashes($Title);
	$_SESSION['member_FirstName']=stripslashes($FirstName);
	$_SESSION['member_LastName']=stripslashes($LastName);
	
	js_location("$member_url?profile_success=1&$query_string");
}
?>
<div id="lib_member_profile">
	<div class="lib_member_title">Edit Personal Details</div>
	<?php if($_GET['profile_success']==1){?>
		<div class="blank15"></div>
		<div class="change_success lib_member_item_card">
			<br />You have successfully change your profile.<br /><br /><br />
			<a href="<?=$member_url;?>?module=index"><strong>My Account</strong></a><br /><br />
		</div>
	<?php }else{?>
		<div class="lib_member_info">If you wish to make any changes to your personal details please complete the fields below and click the "Save Changes" button when you have finished.</div>
		<div class="form lib_member_item_card">
			<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_profile_form" OnSubmit="return checkForm(this);">
				<div class="lib_member_sub_title">Change your personal details</div>
				<div class="rows">
					<label>Title: <font class="fc_red">*</font></label>
					<span><select name="Title">
						<option value="Miss" <?=$_SESSION['member_Title']=='Miss'?'selected':'';?>>Miss</option>
						<option value="Mrs" <?=$_SESSION['member_Title']=='Mrs'?'selected':'';?>>Mrs</option>
						<option value="Ms" <?=$_SESSION['member_Title']=='Ms'?'selected':'';?>>Ms</option>
						<option value="Mr" <?=$_SESSION['member_Title']=='Mr'?'selected':'';?>>Mr</option>
					</select></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="rows">
					<label>First Name: <font class="fc_red">*</font></label>
					<span><input name="FirstName" value="<?=htmlspecialchars($_SESSION['member_FirstName']);?>" type="text" class="form_input" check="First Name is required!~*" size="40" maxlength="20"></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="rows">
					<label>Last Name: <font class="fc_red">*</font></label>
					<span><input name="LastName" value="<?=htmlspecialchars($_SESSION['member_LastName']);?>" type="text" class="form_input" check="Last Name is required!~*" size="40" maxlength="20"></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="rows">
					<label></label>
					<span><input name="Submit" type="submit" class="form_button form_button_130" value="Save Changes"></span>
					<div class="clear"></div>
				</div>
				<input type="hidden" name="data" value="member_profile" />
			</form>
		</div>
	<?php }?>
</div>