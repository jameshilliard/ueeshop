<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('order_status_count');

$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$OrderTime_S=$_GET['OrderTime_S'];
$OrderTime_E=$_GET['OrderTime_E'];

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="order_status_count.php"><?=get_lang('count.order_status_count');?></a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="order_view_form" action="order_status_count.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.full_name');?>:<input name="FullName" value="<?=htmlspecialchars($FullName);?>" class="form_input" type="text" size="10" maxlength='50'>
				<?=get_lang('ly200.email');?>:<input name="Email" value="<?=htmlspecialchars($Email);?>" class="form_input" type="text" size="20" maxlength='200'>
				<?=get_lang('ly200.time');?>:<input name="OrderTime_S" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=$OrderTime_S;?>" class="form_input" />-<input name="OrderTime_E" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=$OrderTime_E;?>" class="form_input" />
				<input type="submit" name="submit" value="<?=get_lang('ly200.view');?>" class="form_button" />
			</form>
		</td>
	</tr>
</table>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1">
	<tr>
		<td>
			<script type="text/javascript" src="js/swfobject.js"></script>
			<div id="order_status_count_data"></div>
			<script type="text/javascript">
			var sopieall=new SWFObject('swf/ampie.swf', 'ampie', '800', '400', '7', '#FFFFFF');
			sopieall.addVariable('path', 'swf/');
			sopieall.addVariable('chart_id', 'ampie');
			sopieall.addVariable('settings_file', escape('settings/order_status_count.php?<?=$service_time;?>'));
			sopieall.addVariable('data_file', escape('data/order_status_count.php?<?=query_string().'&t='.$service_time;?>'));
			sopieall.addVariable('preloader_color', '#999999');
			sopieall.write('order_status_count_data');
			</script>
		</td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>