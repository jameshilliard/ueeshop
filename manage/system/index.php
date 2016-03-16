<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

include('../../inc/fun/ip_to_area.php');
$last_ip_area=ip_to_area($_SESSION['ly_AdminLastLoginIp']);
$ip_area_now=ip_to_area(get_ip());

$db_size=0;
$rs=$db->query('show table status');
while($row=mysql_fetch_assoc($rs)){
	$db_size+=$row['Data_length']+$row['Index_length'];
}

$rs=$db->query('select version() as dbversion');
$row=mysql_fetch_assoc($rs);
$dbversion=$row['dbversion'];

include('../../inc/manage/header.php');
?>
<div id="home">
	<div class="toper"><script language="javascript" src="http://www.ly200.com/system/js/toper.php"></script></div>
	<div class="info">
		<div>
			<div class="lefter">
				<div class="cr">
					<div class="txt"><?=sprintf(get_lang('home.welcome'), $_SESSION['ly_AdminUserName']);?></div>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="15%" nowrap><?=get_lang('home.last_login');?>:</td>
						<td width="85%"><span><?=@date('Y-m-d H:i:s', $_SESSION['ly_AdminLastLoginTime']);?> [<?=$_SESSION['ly_AdminLastLoginIp'].', '.$last_ip_area['country'].$last_ip_area['area'];?>]</span></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.current_login');?>:</td>
						<td><span><?=date('Y-m-d H:i:s', $_SESSION['ly_AdminNowLoginTime']);?> [<?=get_ip().', '.$ip_area_now['country'].$ip_area_now['area'];?>]</span></td>
					  </tr>
					</table>
					<div class="system_info"><strong><?=get_lang('home.system_info');?>:</strong></div>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="15%" nowrap><?=get_lang('home.php_version');?>:</td>
						<td width="85%"><?=PHP_VERSION;?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.mysql_version');?>:</td>
						<td><?=$dbversion;?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.mysql_size');?>:</td>
						<td><?=file_size_format($db_size);?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.system_os');?>:</td>
						<td><?=$_SERVER['SERVER_SOFTWARE'];?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.upload_max_size');?>:</td>
						<td><?=ini_get('file_uploads')?ini_get('upload_max_filesize'):'Disabled';?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.max_execution_time');?>:</td>
						<td><?=ini_get('max_execution_time').' seconds';?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.mail_support');?>:</td>
						<td><?=ini_get('sendmail_path')?'Unix Sendmail (Path:'.ini_get('sendmail_path').')':(ini_get('SMTP')?'SMTP(Server:'.ini_get('SMTP').')':'Disabled');?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.cookie_test');?>:</td>
						<td><?=isset($_COOKIE)?'SUCCESS':'FAIL';?></td>
					  </tr>
					  <tr>
						<td nowrap><?=get_lang('home.service_time');?>:</td>
						<td><?=date(get_lang('ly200.time_format_full'), $service_time);?></td>
					  </tr>
					</table>
				</div>
			</div>
			<div class="righter">
				<div class="news"><script language="javascript" src="http://www.ly200.com/system/js/info.php"></script></div>
				<div class="qlink"><script language="javascript" src="http://www.ly200.com/system/js/qlink.php"></script></div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php include('../../inc/manage/footer.php');?>