<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_brand');

if($_POST['list_form_action']=='brand_order'){
	check_permit('', 'product.brand.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$BId=(int)$_POST['BId'][$i];
		
		$db->update('product_brand', "BId='$BId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('产品品牌排序');
	
	header('Location: brand.php');
	exit;
}

if($_POST['list_form_action']=='brand_del'){
	check_permit('', 'product.brand.del');
	if(count($_POST['select_BId'])){
		$BId=implode(',', $_POST['select_BId']);
		$where="BId in($BId)";
		
		$brand_row=$db->get_all('product_brand', $where, 'LogoPath, PageUrl');
		for($i=0; $i<count($brand_row); $i++){
			del_dir($brand_row[$i]['PageUrl']);
			if(get_cfg('product.brand.upload_logo')){
				del_file($brand_row[$i]['LogoPath']);
				del_file(str_replace('s_', '', $brand_row[$i]['LogoPath']));
			}
		}
		$db->delete('product_brand', $where);
		$db->delete('product_brand_description', $where);
	}
	
	save_manage_log('删除产品品牌');
	
	header('Location: brand.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="brand.php"><?=get_lang('product.brand_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('product.brand.add')){?><div class="float_right"><a href="brand_add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="brand.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('product.brand.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('product.brand.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="15%" nowrap><strong><?=get_lang('product.brand');?></strong></td>
		<?php if(get_cfg('product.brand.upload_logo')){?><td width="15%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('product.brand.mod')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$brand_row=$db->get_all('product_brand', 1, '*', 'MyOrder desc, BId asc');
	for($i=0; $i<count($brand_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('product.brand.del')){?><td><input type="checkbox" name="select_BId[]" value="<?=$brand_row[$i]['BId'];?>"></td><?php }?>
		<?php if(get_cfg('product.brand.order')){?><td><input type="text" name="MyOrder[]" class="form_input" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=$brand_row[$i]['MyOrder'];?>" size="3" maxlength="10"><input name="BId[]" type="hidden" value="<?=$brand_row[$i]['BId'];?>"></td><?php }?>
		<td class="break_all"><a href="<?=get_url('product_brand', $brand_row[$i]);?>" target="_blank"><?=list_all_lang_data($brand_row[$i], 'Brand');?></a></td>
		<?php if(get_cfg('product.brand.upload_logo')){?><td><?=creat_imgLink_by_sImg($brand_row[$i]['LogoPath']);?></td><?php }?>
		<?php if(get_cfg('product.brand.mod')){?><td nowrap><a href="brand_mod.php?BId=<?=$brand_row[$i]['BId']?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('product.brand.order') || get_cfg('product.brand.del')) && count($brand_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<?php if(get_cfg('product.brand.order')){?><input name="brand_order" id="brand_order" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(get_cfg('product.brand.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("select_BId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="brand_del" id="brand_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>