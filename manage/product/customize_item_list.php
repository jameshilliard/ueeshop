<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');


if($_POST['action']=='order_item'){
	$CId=$_POST['CId'];
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$IId=(int)$_POST['IId'][$i];
		$order=abs((int)$_POST['MyOrder'][$i]);
		
		$db->update('product_customize_item', "IId='$IId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('定制项目排序');
	header('Location: customize_item_list.php?CId='.$CId);
	exit;
}

if($_POST['action']=='del_item'){
	$CId=$_POST['CId'];
	if(count($_POST['select_IId'])){
		$IId=implode(',', $_POST['select_IId']);
		$db->delete('product_customize_item', "IId in($IId)");
	}
	save_manage_log('删除定制项目');
	header('Location: customize_item_list.php?CId='.$CId);
	exit;
}
$CId=$_GET['CId'];
$row=$db->get_one('product_customize',"CId='$CId'");
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_item_list.php?CId=<?=$row['CId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<div class="float_right"><a href="customize_item_add.php?CId=<?=$row['CId']?>"><?=get_lang('ly200.add');?></a></div>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="customize_item_list.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgsize_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('product.size.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('product.size.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="16%" nowrap><strong>名称</strong></td>
        <td width="16%" nowrap><strong>图片</strong></td>
		<td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	$item_row=$db->get_all('product_customize_item',"CId='$CId'", '*', 'MyOrder desc, IId asc');
	for($i=0; $i<count($item_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<td><input name="select_IId[]" type="checkbox" value="<?=$item_row[$i]['IId'];?>" /></td>
		<td><input name="MyOrder[]" class="form_input" type="text" size="3" maxlength="10" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=htmlspecialchars($item_row[$i]['MyOrder']);?>" /><input type="hidden" name="IId[]" value="<?=$item_row[$i]['IId'];?>" /></td>
		<td nowrap><?=$item_row[$i]['Name'];?></td>
        <td nowrap><img src="<?=$item_row[$i]['PicPath'];?>" /></td>
		<td nowrap><a href="customize_item_mod.php?IId=<?=$item_row[$i]['IId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td>
	</tr>
	<?php }?>

	<tr>
		<td colspan="6" class="bottom_act">
		<input name="order_item" type="button" class="form_button" onClick="click_button(this, 'list_form', 'action');" value="<?=get_lang('ly200.order');?>">
		<input name="button" type="button" class="form_button" onClick='change_all("select_IId[]");' value="<?=get_lang('ly200.anti_select');?>">
		<input name="del_item" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
		<input name="action" id="action" type="hidden" value="">
        <input name="CId"  type="hidden" value="<?=$CId?>" />
		</td>
	</tr>

</table>
</form>
<?php include('../../inc/manage/footer.php');?>