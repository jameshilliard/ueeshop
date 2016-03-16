<?php
include($site_root_path.'/inc/lib/feedback/form_post.php');

ob_start();
?>
<div id="lib_feedback_form">
	<form action="<?=$_SERVER['PHP_SELF'].'?'.query_string();?>" method="post" name="feedback" OnSubmit="return checkForm(this);">
		<div class="rows">
			<label>姓名: <font class='fc_red'>*</font></label>
			<span><input name="Name" type="text" class="form_input" check="请正确填写姓名！~*" size="20" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>公司:&nbsp;&nbsp;&nbsp;</label>
			<span><input name="Company" type="text" class="form_input" size="30" maxlength="100"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>电话: <font class='fc_red'>*</font></label>
			<span><input name="Phone" type="text" class="form_input" check="请正确填写电话号码！~tel|“{value}”不是一个有效的电话号码！*" size="20" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>手机:&nbsp;&nbsp;&nbsp;</label>
			<span><input name="Mobile" type="text" class="form_input" check="请正确填写手机号码！~mobile|“{value}”不是一个有效的手机号码！" size="20" maxlength="20"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>邮箱:&nbsp;&nbsp;&nbsp;</label>
			<span><input name="Email" type="text" class="form_input" check="请正确填写邮箱地址！~email|“{value}”不是一个有效的邮箱地址！" size="30" maxlength="100"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>QQ:&nbsp;&nbsp;&nbsp;</label>
			<span><input name="QQ" type="text" class="form_input" size="10" maxlength="10"></span>
			<div class="clear"></div>
		</div><!--
		<div class="rows">
			<label>头像:&nbsp;&nbsp;&nbsp;</label>
			<span><select name="Face" onchange="$_('feedback_face').src='/images/lib/feedback_face/'+this.value+'.gif';">
			  <option value="0" selected>--请选择头像--</option>
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
			<label>主题: <font class='fc_red'>*</font></label>
			<span><input name="Subject" type="text" class="form_input" check="请正确填写主题！~*" size="50" maxlength="30"></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>内容: <font class='fc_red'>*</font></label>
			<span><textarea name="Message" class="form_area contents" check="请正确填写内容！~*"></textarea></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>验证码: <font class='fc_red'>*</font></label>
			<span><input name="VCode" type="text" class="form_input vcode" size="4" maxlength="4" check="请正确填写验证码！~4m|*"><br /><?=verification_code('feedback');?> <a href='javascript:void(0);' onclick='this.blur(); obj=$_("<?=md5('feedback');?>"); obj.src=obj.src+Math.random(); return false' class="red">看不清楚？ 换一个</a></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label></label>
			<span><input name="Submit" type="submit" class="form_gory_button" value="提 交"></span>
			<div class="clear"></div>
		</div>
		<input type="hidden" name="jump_url" value="<?=$_SERVER['PHP_SELF'].'?'.query_string();?>" />
		<input type="hidden" name="Site" value="cn" />
		<input type="hidden" name="data" value="feedback_cn" />
	</form>
</div>
<?php
$feedback_form_cn=ob_get_contents();
ob_clean();
?>