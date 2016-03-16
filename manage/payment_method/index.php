<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('payment_method');

if($_POST['list_form_action']=='payment_method_order'){
	check_permit('', 'payment_method.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$PId=(int)$_POST['PId'][$i];
		
		$db->update('payment_method', "PId='$PId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('付款方式排序');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('payment_method.payment_method_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="8%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('payment_method.order')){?><td width="10%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="25%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<td width="25%" nowrap><strong><?=get_lang('ly200.logo');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('ly200.invocation');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('payment_method.additional_fee');?></strong></td>
		<?php if(get_cfg('payment_method.mod')){?><td width="10%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$payment_method_row=$db->get_all('payment_method', '1', '*', 'MyOrder desc, PId asc');
	for($i=0; $i<count($payment_method_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$i+1 ;?></td>
		<?php if(get_cfg('payment_method.order')){?><td><input type="text" name="MyOrder[]" class="form_input" value="<?=$payment_method_row[$i]['MyOrder'];?>" size="3" maxlength="10"><input name="PId[]" type="hidden" value="<?=$payment_method_row[$i]['PId'];?>"></td><?php }?>
		<td class="break_all"><?=$payment_method_row[$i]['Name'];?></td>
		<td><?=creat_imgLink_by_sImg($payment_method_row[$i]['LogoPath']);?></td>
		<td><?=get_lang('ly200.n_y_array.'.$payment_method_row[$i]['IsInvocation']);?></td>
		<td nowrap><?=$payment_method_row[$i]['AdditionalFee'];?>%</td>
		<?php if(get_cfg('payment_method.mod')){?><td nowrap><a href="mod.php?PId=<?=$payment_method_row[$i]['PId']?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if(get_cfg('payment_method.order') && count($payment_method_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<?php if(get_cfg('payment_method.order')){?><input name="payment_method_order" id="payment_method_order" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>"><?php }?>
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
<?php include('../../inc/manage/footer.php');?>