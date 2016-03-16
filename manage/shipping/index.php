<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('shipping');

if($_POST['action']=='order_shipping'){
	check_permit('', 'shipping.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$SId=(int)$_POST['SId'][$i];
		$order=abs((int)$_POST['MyOrder'][$i]);
		
		$db->update('shipping', "SId='$SId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	
	save_manage_log('快递方式排序');
	
	header('Location: index.php');
	exit;
}

if($_POST['action']=='del_shipping'){
	check_permit('', 'shipping.del');
	if(count($_POST['select_SId'])){
		$SId=implode(',', $_POST['select_SId']);
		$db->delete('shipping', "SId in($SId)");
		$db->delete('shipping_price', "SId in($SId)");
	}
	save_manage_log('删除快递方式');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('shipping.shipping_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('shipping.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="8%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('shipping.del')){?><td width="10%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('shipping.order')){?><td width="10%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="26%" nowrap><strong><?=get_lang('shipping.express');?></strong></td>
		<td width="26%" nowrap><strong><?=get_lang('shipping.free_shipping_price');?></strong></td>
		<td width="10%" nowrap><strong><?=get_lang('shipping.set_shipping_price');?></strong></td>
		<?php if(get_cfg('shipping.mod')){?><td width="10%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$shipping_row=$db->get_all('shipping', 1, '*', 'MyOrder desc, SId asc');
	for($i=0; $i<count($shipping_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<?php if(get_cfg('shipping.del')){?><td><input name="select_SId[]" type="checkbox" value="<?=$shipping_row[$i]['SId'];?>" /></td><?php }?>
		<?php if(get_cfg('shipping.order')){?><td><input name="MyOrder[]" class="form_input" type="text" size="3" maxlength="10" value="<?=htmlspecialchars($shipping_row[$i]['MyOrder']);?>" /><input type="hidden" name="SId[]" value="<?=$shipping_row[$i]['SId'];?>" /></td><?php }?>
		<td nowrap><?=list_all_lang_data($shipping_row[$i], 'Express');?></td>
		<td nowrap><?=$shipping_row[$i]['FreeShippingInvocation']==0?get_lang('ly200.not_invocation'):get_lang('ly200.price_symbols').$shipping_row[$i]['FreeShippingPrice'];?></td>
		<td nowrap><a href="shipping_price.php?SId=<?=$shipping_row[$i]['SId'];?>"><?=get_lang('ly200.set');?></a></td>
		<?php if(get_cfg('shipping.mod')){?><td nowrap><a href="mod.php?SId=<?=$shipping_row[$i]['SId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('shipping.order') || get_cfg('shipping.del')) && count($shipping_row)){?>
	<tr>
		<td colspan="7" class="bottom_act">
			<?php if(get_cfg('shipping.order')){?><input name="order_shipping" type="button" class="form_button" onClick="click_button(this, 'list_form', 'action');" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(get_cfg('shipping.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("select_SId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_shipping" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="action" id="action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>