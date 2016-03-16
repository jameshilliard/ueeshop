<?php
include($site_root_path.'/inc/lib/feedback/form_post.php');

ob_start();
?>
<div id="lib_feedback_form">
	<form action="<?=$_SERVER['PHP_SELF'].'?'.query_string();?>" method="post" name="feedback" OnSubmit="return checkForm(this);">
		<div class="rows">
			<label>Name: <font class='fc_red'>*</font></label>
			<span><input name="Name" type="text" class="form_input" check="Name is required!~*" size="20" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Company:&nbsp;&nbsp;</label>
			<span><input name="Company" type="text" class="form_input" size="30" maxlength="100"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Phone: <font class='fc_red'>*</font></label>
			<span><input name="Phone" type="text" class="form_input" check="Phone is required!~*" size="20" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Mobile:&nbsp;&nbsp;</label>
			<span><input name="Mobile" type="text" class="form_input" size="20" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Email:&nbsp;&nbsp;</label>
			<span><input name="Email" type="text" class="form_input" check="Email is required!~email|Email entered doesn't match with confirm Email value!" size="30" maxlength="100"></span>
			<div class="clear"></div>
		</div><!--
		<div class="rows">
			<label>QQ:&nbsp;&nbsp;</label>
			<span><input name="QQ" type="text" class="form_input" size="10" maxlength="10"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Face:&nbsp;&nbsp;</label>
			<span><select name="Face" onchange="$_('feedback_face').src='/images/lib/feedback_face/'+this.value+'.gif';">
			  <option value="0" selected>--Select--</option>
			  <option value="0">1</option>
			  <option value="1">1</option>
			  <option value="2">2</option>
			  <option value="3">3</option>
			  <option value="4">4</option>
			  <option value="5">5</option>
			  <option value="6">6</option>
			  <option value="7">7</option>
			  <option value="8">8</option>
			  <option value="9">9</option>
			</select><br /><img src="/images/lib/feedback_face/0.gif" id="feedback_face"></span>
			<div class="clear"></div>
		</div>-->
		<div class="rows">
			<label>Subject: <font class='fc_red'>*</font></label>
			<span><input name="Subject" type="text" class="form_input" check="Subject is required!~*" size="50" maxlength="30"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Message: <font class='fc_red'>*</font></label>
			<span><textarea name="Message" class="form_area contents" check="Message is required!~*"></textarea></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>Code Shown: <font class='fc_red'>*</font></label>
			<span><input name="VCode" type="text" class="form_input vcode" size="4" maxlength="4" check="Code Shown is required!~4m|*"><br /><?=verification_code('feedback');?> <a href='javascript:void(0);' onclick='this.blur(); obj=$_("<?=md5('feedback');?>"); obj.src=obj.src+Math.random(); return false' class="red">Load new image</a></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label></label>
			<span><input name="Submit" type="submit" class="form_button" value="Submit"></span>
			<div class="clear"></div>
		</div>
		<input type="hidden" name="jump_url" value="<?=$_SERVER['PHP_SELF'].'?'.query_string();?>" />
		<input type="hidden" name="Site" value="en" />
		<input type="hidden" name="data" value="feedback_en" />
	</form>
</div>
<?php
$feedback_form_en=ob_get_contents();
ob_clean();
?>