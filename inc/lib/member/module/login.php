<?php
$query_string=query_string('login_fail');

if($_POST['data']=='member_login'){
	$Email=$_POST['Email'];
	$Password=password($_POST['Password']);
	$remember=$_POST['remember'];
	$jump_url=$_GET['jump_url'];
	$jump_url=(substr($jump_url, 0, 1)!='/' || substr_count($jump_url, 'logout'))?"$member_url?module=index":$jump_url;
	
	if($remember == 1){
		setcookie('Email',$Email,time()+3600);
		setcookie('Password',$Password,time()+3600);
		setcookie('remember',$remember,time()+3600);
	}else{
		setcookie('Email',$Email,time()-3600);
		setcookie('Password',$Password,time()-3600);
		setcookie('remember',$remember,time()-3600);
	}
	
	if($Email=='' || $_POST['Password']==''){
		$_SESSION['login_post']=$_POST;
		js_location("$member_url?fail=1&$query_string");
	}else{
		$user_row=$db->get_one('member', "Email='$Email' and Password='$Password'");
		if($user_row){
			$_SESSION['member_MemberId']=$user_row['MemberId'];
			$_SESSION['member_Email']=$user_row['Email'];
			$_SESSION['member_Title']=$user_row['Title'];
			$_SESSION['member_FirstName']=$user_row['FirstName'];
			$_SESSION['member_LastName']=$user_row['LastName'];
			$_SESSION['member_Password']=$user_row['Password'];
			$_SESSION['member_IsFinish']=$user_row['IsFinish'];
			
			$db->update('member', "MemberId='{$user_row['MemberId']}'", array(
					'LastLoginIp'	=>	get_ip(),
					'LastLoginTime'	=>	$service_time,
					'LoginTimes'	=>	$user_row['LoginTimes']+1
				)
			);
			
			$db->insert('member_login_log', array(
					'MemberId'	=>	(int)$_SESSION['member_MemberId'],
					'LoginTime'	=>	$service_time,
					'LoginIp'	=>	get_ip()
				)
			);
			
			include($site_root_path.'/inc/lib/cart/init.php');
			
			$_SESSION['login_post']='';
			unset($_SESSION['login_post']);
			
			js_location($jump_url);
		}else{
			$_SESSION['login_post']=$_POST;
			js_location("$member_url?login_fail=1&$query_string");
		}
	}
}

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
<div id="lib_member_login">
	<?php if($_GET['login_fail']==1){?>
		<div class="lib_member_msgerror"><img src="/images/lib/member/msg_error.png" align="absmiddle" />&nbsp;&nbsp;Your login attempt was not successful. Please try again.</div>
	<?php }?>
	<div class="lib_member_title">Sign In and Create Account</div>
	<div class="lib_member_info">By creating an account with our online store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</div>
	<div>
		<div class="login_form">
			<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_login_form" OnSubmit="return checkForm(this);">
				<div class="t">SIGN IN</div>
				<div class="f_card">
					<div class="rows">
						<label>Email:</label>
						<span><input name="Email" value="<?=htmlspecialchars($_SESSION['login_post']['Email']);?>" type="text" class="form_input" size="41" maxlength="100" check="Email is required!~email|Email entered doesn't match with confirm Email value!*"></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>Password:</label>
						<span><input name="Password" value="<?=htmlspecialchars($_SESSION['login_post']['Password']);?>" type="password" class="form_input" size="41" maxlength="20" check="Password is required!~*"></span>
						<div class="clear"></div>
					</div>
					<div class="rem"><?php if($_COOKIE['remember']==1){?><input type="checkbox" name="remember" value="1" checked><?php }else{($_COOKIE['remember'] == "")?><input type="checkbox" name="remember" value="1"><?php }?> Remember Me</div>
					<div class="forgot"><a href="<?=$member_url;?>?module=forgot">Forgot your password?</a></div>
				</div>
				<div class="btn"><?php if((int)$_GET['checkout']==1){?><input type="button" name="proceed_to_checkout" value="Proceed to Checkout" class="form_button form_button_130" onClick="window.location='<?=$_GET['jump_url'];?>&proceed_to_checkout=1';">&nbsp;&nbsp;&nbsp;&nbsp;<?php }?><input type="submit" name="Submit" value="SIGN IN" class="form_button"></div>
				<input type="hidden" name="data" value="member_login" />
                <input type="hidden" name="jump_url" value="<?=$_GET['jump_url'];?>" />
			</form>
		</div>
		<div class="new_customer">
			<?php if($_GET['create_fail']==1){?>
				<div class="lib_member_msgerror"><img src="/images/lib/member/msg_error.png" align="absmiddle" />&nbsp;&nbsp;Sorry, this email address has already been used!</div>
			<?php }?>
			<form action="<?=$member_url.'?'.$query_string;?>" method="post" name="member_create_form" OnSubmit="return checkForm(this);">
			<div class="t">CREATE ACCOUNT</div>
			<div class="f_card">
				<div class="rows">
					<label>First Name: <font class="fc_red">*</font></label>
					<span><input name="FirstName" value="<?=htmlspecialchars($_SESSION['create_post']['Email']);?>" type="text" class="form_input" check="First Name is required!~*" size="40" maxlength="20"></span>
					<div class="clear"></div>
				</div>
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
			</div>
			<div class="rows">
				<label></label>
				<span style="margin-left:72px;"><input name="Submit" type="submit" class="form_button form_button_130" value="CREATE"></span>
				<div class="clear"></div>
			</div>
			<input type="hidden" name="data" value="member_create" />
			</form>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php
$_SESSION['login_post']='';
unset($_SESSION['login_post']);
?>