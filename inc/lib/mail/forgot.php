<?php
ob_start();
?>
<font style="font-size:14px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;">Recover your account password</font><br /><br />
Dear <?=htmlspecialchars($fullname);?>:<br /><br />
This is an automated email from <a href="<?=$domain;?>" target="_blank" style="color:#1E5494; text-decoration:underline; font-family:Arial, Helvetica, sans-serif;"><?=$domain_un_protocol;?></a> in response to your request to reset your password. Please do not reply to this email.<br /><br />
To reset your password and access your <a href="<?=$domain;?>" target="_blank" style="color:#1E5494; text-decoration:underline; font-family:Arial, Helvetica, sans-serif;"><?=$domain_un_protocol;?></a> account, follow these steps:<br /><br />
<div style="font-family:Arial, Helvetica, sans-serif; line-height:180%; padding-left:20px;">1)&nbsp;&nbsp;Click on the link below. If nothing happens when you click on the link, please copy and paste the link into the address bar of your web browser.<br /><a href="<?=$domain.$member_url;?>?module=forgot&email=<?=urlencode($EmailEncode);?>&expiry=<?=urlencode($Expiry);?>" target="_blank" style="color:#1E5494; text-decoration:underline; font-family:Arial, Helvetica, sans-serif;"><?=$domain.$member_url;?>?module=forgot&amp;email=<?=urlencode($EmailEncode);?>&amp;expiry=<?=urlencode($Expiry);?></a></div><br />
<div style="font-family:Arial, Helvetica, sans-serif; line-height:180%; padding-left:20px;">2)&nbsp;&nbsp;The above link will take you to our "Reset password" page. Fill in the appropriate fields and click "Submit".<br />You will then be able to access your account.</div><br />
If you have any queries, please email our Customer Care Team.<br /><br />
Yours sincerely,<br /><br />
<?=$domain_un_protocol;?> Customer Care Team
<?php
$mail_contents=ob_get_contents();
ob_end_clean();
?>