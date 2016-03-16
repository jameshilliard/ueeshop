<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('order_count_trend');

$FullName=$_GET['FullName'];
$Email=$_GET['Email'];
$CountType=(int)$_GET['CountType'];
$OrderTime_S=$_GET['OrderTime_S'];
$OrderTime_E=$_GET['OrderTime_E'];
$view_order_status_ary=count($_GET['OrderStatus'])?$_GET['OrderStatus']:array(5, 6);

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="order_count_trend.php"><?=get_lang('count.order_count_trend');?></a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="order_view_form" action="order_count_trend.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.full_name');?>:<input name="FullName" value="<?=htmlspecialchars($FullName);?>" class="form_input" type="text" size="10" maxlength='50'>
				<?=get_lang('ly200.email');?>:<input name="Email" value="<?=htmlspecialchars($Email);?>" class="form_input" type="text" size="20" maxlength='200'>
				<?=get_lang('ly200.time');?>:<input name="OrderTime_S" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=$OrderTime_S;?>" class="form_input" />-<input name="OrderTime_E" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=$OrderTime_E;?>" class="form_input" />
				<?=get_lang('count.count_type');?>:<select name="CountType">
					<option value="0" <?=$CountType==0?'selected':'';?>><?=get_lang('count.count_type_0');?></option>
					<option value="1" <?=$CountType==1?'selected':'';?>><?=get_lang('count.count_type_1');?></option>
					<option value="2" <?=$CountType==2?'selected':'';?>><?=get_lang('count.count_type_2');?></option>
				</select>
				<br />
				<?=get_lang('orders.order_status');?>:
				<?php foreach($order_status_ary as $key=>$value){?>
					<input type="checkbox" name="OrderStatus[]" value="<?=$key;?>" <?=in_array($key, $view_order_status_ary)?'checked':'';?>><?=$value;?>&nbsp;&nbsp;
				<?php }?>
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
			<div id="order_count_trend_data"></div>
			<script type="text/javascript">
			var soxxzst = new SWFObject('swf/amline.swf', 'amline', '800', '400', '7', '#FFFFFF');
			soxxzst.addVariable('path', 'swf/');
			soxxzst.addVariable('chart_id', 'amline');
			soxxzst.addVariable('settings_file', escape('settings/order_count_trend.php?<?=$service_time;?>'));
			soxxzst.addVariable('data_file', escape('data/order_count_trend.php?<?=query_string().'&t='.$service_time;?>'));
			soxxzst.addVariable('preloader_color', '#000000');
			soxxzst.write('order_count_trend_data');
			</script>
		</td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>