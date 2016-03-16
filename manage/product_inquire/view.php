<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_inquire');

$IId=(int)$_GET['IId'];
$query_string=query_string('IId');

$product_inquire_row=$db->get_one('product_inquire', "IId='$IId'");
!$product_inquire_row['IsRead'] && $db->update('product_inquire', "IId='$IId'", array('IsRead'=>1));

include('../../inc/fun/ip_to_area.php');
$ip_area=ip_to_area($product_inquire_row['Ip']);

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('product_inquire.product_inquire_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.view');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="view.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('ly200.full_name');?>:</td>
		<td width="95%"><?=htmlspecialchars($product_inquire_row['FirstName'].' '.$product_inquire_row['LastName']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.email');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['Email'] && $menu['send_mail']);?>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="this.blur(); parent.openWindows('win_send_mail', '<?=get_lang('send_mail.send_mail_system');?>', 'send_mail/index.php?email=<?=urlencode($product_inquire_row['Email'].'/'.$product_inquire_row['FirstName'].' '.$product_inquire_row['LastName']);?>');" class="red"><?=get_lang('send_mail.send');?></a></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.address');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['Address']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.city');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['City']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.country');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['Country']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.postal_code');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['PostalCode']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.phone');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['Phone']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.fax');?>:</td>
		<td><?=format_text($product_inquire_row['Fax']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.inquire_product');?>:</td>
		<td class="flh_150"><?php
		$product_row=$db->get_all('product', "ProId in({$product_inquire_row['ProId']})", '*', 'MyOrder desc, ProId desc');
		for($i=0; $i<count($product_row); $i++){
			$url=get_url('product', $product_row[$i]);
			echo "<a href='$url' target='_blank' class='blue'>{$product_row[$i]['Name']}</a><br>";
		}
		?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.subject');?>:</td>
		<td><?=htmlspecialchars($product_inquire_row['Subject']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_inquire.message');?>:</td>
		<td class="flh_150"><?=format_text($product_inquire_row['Message']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.ip');?>:</td>
		<td><?=$product_inquire_row['Ip'];?> [<?=$ip_area['country'].$ip_area['area'];?>]</td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.time');?>:</td>
		<td><?=date(get_lang('ly200.time_format_full'), $product_inquire_row['PostTime']);?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><a href="index.php?<?=$query_string;?>" class="return_1"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>