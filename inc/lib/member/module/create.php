<?php
$query_string=query_string('create_fail');

if($_POST['data']=='member_create'){
	$Title=$_POST['Title'];
	$FirstName=$_POST['FirstName'];
	$LastName=$_POST['LastName'];
	$Email=$_POST['Email'];
	$Password=password($_POST['Password']);
	$AddressLine1=$_POST['AddressLine1'];
	$AddressLine2=$_POST['AddressLine2'];
	$City=$_POST['City'];
	$State=$_POST['State'];
	$Country=$_POST['Country'];
	$PostalCode=$_POST['PostalCode'];
	$Phone=$_POST['Phone'];
	$jump_url=substr($_GET['jump_url'], 0, 1)!='/'?"$member_url?module=index":$_GET['jump_url'];
	
	($Email=='' || $Password=='' || preg_match('/^[a-z0-9][a-z\.0-9-_]+@[a-z0-9_-]+(?:\.[a-z]{0,3}\.[a-z]{0,2}|\.[a-z]{0,3}|\.[a-z]{0,2})$/i', $Email)==false) && js_location($jump_url);	//关键信息空的话不允许提交
	
	if(!$db->get_row_count('member', "Email='$Email'")){
		$db->insert('member', array(
				'Title'			=>	$Title,
				'FirstName'		=>	$FirstName,
				'LastName'		=>	$LastName,
				'Email'			=>	$Email,
				'Password'		=>	$Password,
				'RegTime'		=>	$service_time,
				'RegIp'			=>	get_ip(),
				'LastLoginTime'	=>	$service_time,
				'LastLoginIp'	=>	get_ip(),
				'LoginTimes'	=>	1
			)
		);
		
		$MemberId=$db->get_insert_id();
		
		//AddressType: 0(shipping address) 1(billing address)
		$db->insert('member_address_book', array(
				'MemberId'		=>	$MemberId,
				'Title'			=>	$Title,
				'FirstName'		=>	$FirstName,
				'LastName'		=>	$LastName,
				'AddressLine1'	=>	$AddressLine1,
				'AddressLine2'	=>	$AddressLine2,
				'City'			=>	$City,
				'State'			=>	$State,
				'Country'		=>	$Country,
				'PostalCode'	=>	$PostalCode,
				'Phone'			=>	$Phone,
				'AddressType'	=>	0,
				'IsDefault'		=>	1
			)
		);
		
		$db->insert('member_address_book', array(
				'MemberId'		=>	$MemberId,
				'Title'			=>	$Title,
				'FirstName'		=>	$FirstName,
				'LastName'		=>	$LastName,
				'AddressLine1'	=>	$AddressLine1,
				'AddressLine2'	=>	$AddressLine2,
				'City'			=>	$City,
				'State'			=>	$State,
				'Country'		=>	$Country,
				'PostalCode'	=>	$PostalCode,
				'Phone'			=>	$Phone,
				'AddressType'	=>	1
			)
		);
		
		$_SESSION['member_MemberId']=$MemberId;
		$_SESSION['member_Email']=$Email;
		$_SESSION['member_Title']=$Title;
		$_SESSION['member_FirstName']=$FirstName;
		$_SESSION['member_LastName']=$LastName;
		$_SESSION['member_Password']=$Password;
		
		$db->insert('member_login_log', array(
				'MemberId'	=>	(int)$_SESSION['member_MemberId'],
				'LoginTime'	=>	$service_time,
				'LoginIp'	=>	get_ip()
			)
		);
		
		include($site_root_path.'/inc/lib/cart/init.php');
		
		$_SESSION['create_post']='';
		unset($_SESSION['create_post']);
		
		js_location($jump_url);
	}else{
		$_SESSION['create_post']=$_POST;
		js_location("$member_url?create_fail=1&$query_string");
	}
}
?>
<div id="lib_member_create">
	<?php if($_GET['create_fail']==1){?>
		<div class="lib_member_msgerror"><img src="/images/lib/member/msg_error.png" align="absmiddle" />&nbsp;&nbsp;Sorry, this email address has already been used!</div>
	<?php }?>
	<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_create_form" OnSubmit="return checkForm(this);">
		<div class="lib_member_title">CREATE ACCOUNT</div>
		<div class="blank6"></div>
		<div class="required_info">Asterisk (<font class="fc_red">*</font>) indicates required fields.</div>
		<!--<div class="f_item">your personal details</div>
		<div class="rows">
			<label>Title: <font class="fc_red">*</font></label>
			<span><select name="Title">
				<option value="Miss" <?=$_SESSION['create_post']['Title']=='Miss'?'selected':'';?>>Miss</option>
				<option value="Mrs" <?=$_SESSION['create_post']['Title']=='Mrs'?'selected':'';?>>Mrs</option>
				<option value="Ms" <?=$_SESSION['create_post']['Title']=='Ms'?'selected':'';?>>Ms</option>
				<option value="Mr" <?=$_SESSION['create_post']['Title']=='Mr'?'selected':'';?>>Mr</option>
			</select></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>First Name: <font class="fc_red">*</font></label>
			<span><input name="FirstName" value="<?=htmlspecialchars($_SESSION['create_post']['Email']);?>" type="text" class="form_input" check="First Name is required!~*" size="40" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Last Name: <font class="fc_red">*</font></label>
			<span><input name="LastName" value="<?=htmlspecialchars($_SESSION['create_post']['Email']);?>" type="text" class="form_input" check="Last Name is required!~*" size="40" maxlength="20"></span>
			<div class="clear"></div>
		</div>-->
		<div class="f_item">your login details</div>
		<div class="rows">
			<label>Email: <font class="fc_red">*</font></label>
			<span><input name="Email" value="<?=htmlspecialchars($_SESSION['create_post']['Email']);?>" type="text" class="form_input" check="Email is required!~email|Email entered doesn't match with confirm Email value!*" size="50" maxlength="100"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Confirm Email: <font class="fc_red">*</font></label>
			<span><input name="ConfirmEmail" value="<?=htmlspecialchars($_SESSION['create_post']['ConfirmEmail']);?>" type="text" class="form_input" check="Confirm Email  is required!~=Email|Confirm Email entered doesn't match with Email value!*" size="50" maxlength="100"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Password: <font class="fc_red">*</font></label>
			<span><input name="Password" value="<?=htmlspecialchars($_SESSION['create_post']['Password']);?>" type="password" class="form_input" check="Password is required!~*" size="50" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Confirm Password: <font class="fc_red">*</font></label>
			<span><input name="ConfirmPassword" value="<?=htmlspecialchars($_SESSION['create_post']['ConfirmPassword']);?>" type="password" class="form_input" check="Confirm Password is required!~=Password|Confirm Password entered doesn't match with Password value!*" size="50" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<!--
		<div class="f_item">your contact details</div>
		<div class="rows">
			<label>Address Line 1: <font class="fc_red">*</font></label>
			<span><input name="AddressLine1" value="<?=htmlspecialchars($_SESSION['create_post']['AddressLine1']);?>" type="text" class="form_input" check="Address Line 1 is required!~*" size="70" maxlength="200"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Address Line 2:</label>
			<span><input name="AddressLine2" value="<?=htmlspecialchars($_SESSION['create_post']['AddressLine2']);?>" type="text" class="form_input" size="70" maxlength="200"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>City: <font class="fc_red">*</font></label>
			<span><input name="City" value="<?=htmlspecialchars($_SESSION['create_post']['City']);?>" type="text" class="form_input" check="City is required!~*" size="40" maxlength="50"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>State: <font class="fc_red">*</font></label>
			<span><input name="State" value="<?=htmlspecialchars($_SESSION['create_post']['State']);?>" type="text" class="form_input" check="State is required!~*" size="40" maxlength="50"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Country: <font class="fc_red">*</font></label>
			<span><?=ouput_table_to_select('country', 'Country', 'Country', 'Country', 'Country asc, CId asc', 0, 1, $_SESSION['create_post']['Country'], '', 'Please select Country', 'Please select Country!~*');?></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Postal Code: <font class="fc_red">*</font></label>
			<span><input name="PostalCode" value="<?=htmlspecialchars($_SESSION['create_post']['PostalCode']);?>" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" type="text" class="form_input" check="Postal Code is required!~*" size="10" maxlength="10"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Phone: <font class="fc_red">*</font></label>
			<span><input name="Phone" value="<?=htmlspecialchars($_SESSION['create_post']['Phone']);?>" type="text" class="form_input" check="Phone is required!~*" size="40" maxlength="20" /></span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label></label>
			<span><input name="Accept" <?=$_SESSION['create_post']['Accept']==1?'checked':'';?> value="1" type="checkbox" onclick="if(this.checked==true){$_('AcceptHidden').value=1;}else{$_('AcceptHidden').value='';};" />I accept the Terms &amp; Conditions and have read and understood the Privacy &amp; policy<input name="AcceptHidden" id="AcceptHidden" type="hidden" value="<?=htmlspecialchars($_SESSION['create_post']['AcceptHidden']);?>" check="Please accept the Terms and Conditions!~*"></span>
			<div class="clear"></div>
		</div>
		-->
		<div class="dline"></div>
		<div class="rows">
			<label></label>
			<span><input name="Submit" type="submit" class="form_button form_button_130" value="Create New Account"></span>
			<div class="clear"></div>
		</div>
		<input type="hidden" name="data" value="member_create" />
	</form>
</div>
<?php
$_SESSION['create_post']='';
unset($_SESSION['create_post']);
?>