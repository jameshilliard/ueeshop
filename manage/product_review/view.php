<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_review');

if($_POST){
	$page=(int)$_POST['page'];
	$RId=(int)$_POST['RId'];
	$query_string=$_POST['query_string'];
	
	$Display=(int)$_POST['Display'];
	$Reply=$_POST['Reply'];
	
	$db->update('product_review', "RId='$RId'", array(
			'Display'	=>	$Display,
			'Reply'		=>	$Reply,
			'ReplyTime'	=>	$service_time
		)
	);
	
	save_manage_log('产品评论回复');
	
	header("Location: index.php?$query_string");
	exit;
}

$RId=(int)$_GET['RId'];
$query_string=query_string('RId');

$product_review_row=$db->get_one('product_review', "RId='$RId'");
$product_row=$db->get_one('product', "ProId='{$product_review_row['ProId']}'");

include('../../inc/fun/ip_to_area.php');
$ip_area=ip_to_area($product_review_row['Ip']);

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('product_review.product_review_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.view');?></div>
<form method="post" name="act_form" id="act_form" class="act_form" action="view.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<tr> 
		<td width="5%" nowrap><?=get_lang('product_review.product');?>:</td>
		<td width="95%"><a href="<?=get_url('product', $product_row);?>" target="_blank"><?=htmlspecialchars($product_row['Name']);?></a></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_review.full_name');?>:</td>
		<td><?=htmlspecialchars($product_review_row['FullName']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.email');?>:</td>
		<td><?=htmlspecialchars($product_review_row['Email']);?><?php if($product_review_row['Email'] && $menu['send_mail']){?>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="this.blur(); parent.openWindows('win_send_mail', '<?=get_lang('send_mail.send_mail_system');?>', 'send_mail/index.php?email=<?=urlencode($product_review_row['Email'].'/'.$product_review_row['FullName']);?>');" class="red"><?=get_lang('send_mail.send');?></a><?php }?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('product_review.rating');?>:</td>
		<td><?=str_repeat('<img src="/images/lib/product/x0.jpg">', $product_review_row['Rating']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.contents');?>:</td>
		<td class="flh_150"><?=format_text($product_review_row['Contents']);?></td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.ip');?>:</td>
		<td><?=$product_review_row['Ip'];?> [<?=$ip_area['country'].$ip_area['area'];?>]</td>
	</tr>
	<tr>
		<td nowrap><?=get_lang('ly200.time');?>:</td>
		<td><?=date(get_lang('ly200.time_format_full'), $product_review_row['PostTime']);?></td>
	</tr>
	<?php if(get_cfg('product_review.display')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.display');?>:</td>
			<td><input type="checkbox" name="Display" value="1" <?= $product_review_row['Display']==1?'checked':'';?> /></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('product_review.reply')){?>
		<tr>
			<td nowrap><?=get_lang('product_review.reply');?>:</td>
			<td><textarea name="Reply" rows="7" cols="70" class="form_area"><?=htmlspecialchars($product_review_row['Reply'])?></textarea></td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><?php if(get_cfg('product_review.reply')){?><input type="submit" value="<?=get_lang('product_review.reply');?>" name="submit" class="form_button"><input type="hidden" name="page" value="<?=$page;?>"><input type="hidden" name="query_string" value="<?=$query_string;?>"><input type="hidden" name="RId" value="<?=$RId;?>"><?php }?><a href="index.php?<?=$query_string;?>" class="return<?=get_cfg('product_review.reply')?'':'_1';?>"><?=get_lang('ly200.return');?></a></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>