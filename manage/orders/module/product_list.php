<form name="list_form" id="list_form" class="list_form" method="post" action="view.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="8%"><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('orders.mod')){?><td width="8%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<td width="14%"><strong><?=get_lang('ly200.photo');?></strong></td>
		<td width="40%"><strong><?=get_lang('orders.product');?></strong></td>
		<td width="10%"><strong><?=get_lang('product.price');?></strong></td>
		<td width="10%"><strong><?=get_lang('ly200.qty');?></strong></td>
		<td width="10%"><strong><?=get_lang('orders.sub_total');?></strong></td>
		<td width="10%"><strong><?=get_lang('orders.Integral');?></strong></td>
	</tr>
	<?php
	$pro_count=$total_price=0;
	$item_row=$db->get_all('orders_product_list', $where, '*', 'ProId desc, LId desc');
	for($i=0; $i<count($item_row); $i++){
		$pro_count+=$item_row[$i]['Qty'];
		$total_price+=$item_row[$i]['Qty']*$item_row[$i]['Price'];
	?>
	<tr align="center">
		<td><?=($i+1);?></td>
		<?php if(get_cfg('orders.mod')){?><td><input type="checkbox" name="select_LId[]" value="<?=$item_row[$i]['LId'];?>"></td><?php }?>
		<td><a href="<?=$item_row[$i]['Url'];?>" target="_blank"><img src="<?=$item_row[$i]['PicPath'];?>" /></a></td>
		<td align="left" class="flh_150">
			<a href="<?=$item_row[$i]['Url'];?>" target="_blank"><?=$item_row[$i]['Name'];?></a><br />
			<?=get_lang('product.item_number');?>: <?=$item_row[$i]['ItemNumber'];?><br />
			
			<?php if($item_row[$i]['Size']){?><?=get_lang('product.size');?>: <?=$item_row[$i]['Size'];?><br /><?php }?>
			<?php if($product_weight==1){?><?=get_lang('product.weight');?>: <?=$item_row[$i]['Weight'];?> KG<br /><?php }?>
            <?php if($item_row[$i]['Customize']){?><?=$item_row[$i]['Customize'];?><?php }?>
			<?=get_lang('ly200.remark');?>: <?php if(get_cfg('orders.mod')){?><input type="text" name="Remark[]" size="45" maxlength="100" class="form_input" value="<?=htmlspecialchars($item_row[$i]['Remark']);?>" /><?php }else{?><?=htmlspecialchars($item_row[$i]['Remark']);?><?php }?>
		</td>
		<td><?=get_lang('ly200.price_symbols');?><?php if(get_cfg('orders.mod')){?><input type="text" name="Price[]" size="5" maxlength="10" class="form_input" value="<?=sprintf('%01.2f', $item_row[$i]['Price']);?>" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);"><?php }else{?><?=sprintf('%01.2f', $item_row[$i]['Price']);?><?php }?></td>
		<td><?php if(get_cfg('orders.mod')){?><input type="text" name="Qty[]" size="5" maxlength="5" class="form_input" value="<?=$item_row[$i]['Qty'];?>" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);"><?php }else{?><?=$item_row[$i]['Qty'];?><?php }?></td>
		<td><?=get_lang('ly200.price_symbols').sprintf('%01.2f', $item_row[$i]['Price']*$item_row[$i]['Qty']);?></td>
		<td><?=$item_row[$i]['Integral'];?></td>
	</tr>
	<?php }?>
	<tr align="center">
		<td colspan="<?=get_cfg('orders.mod')?6:5;?>" nowrap align="left">
			<?php if(get_cfg('orders.mod')){?>
				<input name="mod_product" id="mod_product" type="button" class="form_button" onClick="click_button(this, 'list_form', 'act')" value="<?=get_lang('ly200.mod');?>">
				<input name="button" type="button" class="form_button" onClick='change_all("select_LId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_product" id="del_product" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'act');};" value="<?=get_lang('ly200.del');?>">
				<input type="checkbox" name="AutoUpdatePrice" value="1" checked="checked" /><?=get_lang('orders.auto_upd_price');?>
				<input name="act" id="act" type="hidden" value="">
				<input type="hidden" name="OrderId" value="<?=$OrderId;?>" />
				<input type="hidden" name="module" value="<?=$module;?>" />
			<?php }?>
		</td>
		<td><strong><?=$pro_count;?></strong></td>
		<td><strong><?=get_lang('ly200.price_symbols').sprintf('%01.2f', $total_price);?></strong></td>
	</tr>
</table>
</form>