<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_color');

if($_POST['action']=='order_color'){
	check_permit('', 'product.color.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$CId=(int)$_POST['CId'][$i];
		$order=abs((int)$_POST['MyOrder'][$i]);
		
		$db->update('product_color', "CId='$CId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	
	save_manage_log('产品颜色排序');
	
	header('Location: color.php');
	exit;
}

if($_POST['action']=='del_color'){
	check_permit('', 'product.color.del');
	$CateId=$_POST['CateId'];
	if(count($_POST['select_CId'])){
		$CId=implode(',', $_POST['select_CId']);
		$where="CId in($CId)";
		
		if(get_cfg('product.color.upload_pic')){
			$color_row=$db->get_all('product_color', $where, 'PicPath');
			for($i=0; $i<count($color_row); $i++){
				del_file($color_row[$i]['PicPath']);
				del_file(str_replace('s_', '', $color_row[$i]['PicPath']));
			}
		}
		$db->delete('product_color', $where);
	}
	save_manage_log('删除产品颜色');
	
	header('Location: color.php?CateId='.$CateId);
	exit;
}
$CateId=(int)$_GET['CateId'];
$row=$db->get_one('product_customize',"CId='$CateId'");
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<a href="color.php?CateId=<?=$row['CId']?>"><?=get_lang('product.color');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('product.color.add')){?><div class="float_right"><a href="color_add.php?CateId=<?=$CateId?>"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="color.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('product.color.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('product.color.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="16%" nowrap><strong><?=get_lang('product.color');?></strong></td>
		<?php if(get_cfg('product.color.upload_pic')){?><td width="10%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('product.color.mod')){?><td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$color_row=$db->get_all('product_color',"CateId='$CateId'", '*', 'MyOrder desc, CId asc');
	for($i=0; $i<count($color_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<?php if(get_cfg('product.color.del')){?><td><input name="select_CId[]" type="checkbox" value="<?=$color_row[$i]['CId'];?>" /></td><?php }?>
		<?php if(get_cfg('product.color.order')){?><td><input name="MyOrder[]" class="form_input" type="text" size="3" maxlength="10" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=htmlspecialchars($color_row[$i]['MyOrder']);?>" /><input type="hidden" name="CId[]" value="<?=$color_row[$i]['CId'];?>" /></td><?php }?>
		<td nowrap><div style=" width:25px; height:25px; background:<?=list_all_lang_data($color_row[$i], 'Color');?>"></div><?=list_all_lang_data($color_row[$i], 'Color');?></td>
		<?php if(get_cfg('product.color.upload_pic')){?><td><?=creat_imgLink_by_sImg($color_row[$i]['PicPath']);?></td><?php }?>
		<?php if(get_cfg('product.color.mod')){?><td nowrap><a href="color_mod.php?CId=<?=$color_row[$i]['CId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('product.color.order') || get_cfg('product.color.del')) && count($color_row)){?>
	<tr>
		<td colspan="6" class="bottom_act">
			<?php if(get_cfg('product.color.order')){?><input name="order_color" type="button" class="form_button" onClick="click_button(this, 'list_form', 'action');" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(get_cfg('product.color.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("select_CId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_color" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="action" id="action" type="hidden" value="">
            <input name="CateId"  type="hidden" value="<?=$CateId?>">
            
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>