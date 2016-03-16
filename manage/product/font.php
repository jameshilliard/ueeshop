<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_font');

if($_POST['action']=='order_font'){
	check_permit('', 'product.font.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$FId=(int)$_POST['FId'][$i];
		$order=abs((int)$_POST['MyOrder'][$i]);
		
		$db->update('product_font', "FId='$FId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	
	save_manage_log('字体排序');
	
	header('Location: font.php');
	exit;
}

if($_POST['action']=='del_font'){
	check_permit('', 'product.font.del');
	$CId=$_POST['CId'];
	if(count($_POST['select_FId'])){
		$FId=implode(',', $_POST['select_FId']);
		$where="FId in($FId)";
		
		if(get_cfg('product.font.upload_pic')){
			$font_row=$db->get_all('product_font', $where, 'PicPath');
			for($i=0; $i<count($font_row); $i++){
				del_file($font_row[$i]['PicPath']);
				del_file(str_replace('s_', '', $font_row[$i]['PicPath']));
			}
		}
		$db->delete('product_font', $where);
	}
	save_manage_log('删除字体');
	
	header('Location: font.php?CId='.$CId);
	exit;
}
$CId=(int)$_GET['CId'];
$row=$db->get_one('product_customize',"CId='$CId'");
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CId=<?=$row['CId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<a href="font.php?CId=<?=$row['CId']?>"><?=get_lang('product.font');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('product.font.add')){?><div class="float_right"><a href="font_add.php?CId=<?=$CId?>"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="font.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgfont_table" not_mouse_trBgfont_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('product.font.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('product.font.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="16%" nowrap><strong><?=get_lang('product.font');?></strong></td>
		<?php if(get_cfg('product.font.upload_pic')){?><td width="10%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('product.font.mod')){?><td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$font_row=$db->get_all('product_font',"CId='$CId'", '*', 'MyOrder desc, FId asc');
	for($i=0; $i<count($font_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<?php if(get_cfg('product.font.del')){?><td><input name="select_FId[]" type="checkbox" value="<?=$font_row[$i]['FId'];?>" /></td><?php }?>
		<?php if(get_cfg('product.font.order')){?><td><input name="MyOrder[]" class="form_input" type="text" size="3" maxlength="10" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=htmlspecialchars($font_row[$i]['MyOrder']);?>" /><input type="hidden" name="FId[]" value="<?=$font_row[$i]['FId'];?>" /></td><?php }?>
		<td nowrap><?=list_all_lang_data($font_row[$i], 'Font');?></td>
		<?php if(get_cfg('product.font.upload_pic')){?><td><img src="<?=$font_row[$i]['PicPath'];?>" /></td><?php }?>
		<?php if(get_cfg('product.font.mod')){?><td nowrap><a href="font_mod.php?FId=<?=$font_row[$i]['FId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('product.font.order') || get_cfg('product.font.del')) && count($font_row)){?>
	<tr>
		<td colspan="6" class="bottom_act">
			<?php if(get_cfg('product.font.order')){?><input name="order_font" type="button" class="form_button" onClick="click_button(this, 'list_form', 'action');" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(get_cfg('product.font.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("select_FId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_font" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="action" id="action" type="hidden" value="">
            <input name="CId"  type="hidden" value="<?=$CId?>">
            
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>