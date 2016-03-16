<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('instance_category');

if($_POST['action']=='order_category'){
	check_permit('', 'instance.category.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$CateId=(int)$_POST['CateId'][$i];
		$order=abs((int)$_POST['MyOrder'][$i]);
		
		$db->update('instance_category', "CateId='$CateId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	
	save_manage_log('成功案例类别排序');
	
	header('Location: category.php');
	exit;
}

if($_POST['action']=='del_category'){
	check_permit('', 'instance.category.del');
	for($i=0; $i<count($_POST['del_CateId']); $i++){
		$CateId=(int)$_POST['del_CateId'][$i];
		$where=get_search_where_by_CateId($CateId, 'instance_category');
		
		$category_row=$db->get_all('instance_category', $where, 'PicPath, PageUrl');
		for($j=0; $j<count($category_row); $j++){
			del_dir($category_row[$j]['PageUrl']);
			if(get_cfg('instance.category.upload_pic')){
				del_file($category_row[$j]['PicPath']);
				del_file(str_replace('s_', '', $category_row[$j]['PicPath']));
			}
		}
		
		$db->delete('instance_category_description', $where);
		$db->delete('instance_category', $where);
	}
	
	category_subcate_statistic('instance_category');
	
	save_manage_log('删除成功案例类别');
	
	header('Location: category.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="category.php"><?=get_lang('instance.category_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('instance.category.add')){?><div class="float_right"><a href="category_add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<form name="category_list_form" id="category_list_form" class="category_list_form" method="post" action="category.php"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='category_list_form_title'>
	<tr align="center" class="category_list_form_title" id="category_list_form_title">
		<?php if(get_cfg('instance.category.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('instance.category.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="85%" align="left" nowrap style="padding-left:7px;"><strong><?=get_lang('ly200.category_name');?></strong></td>
		<?php if(get_cfg('instance.category.mod')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$category_row=ouput_Category_to_Array('instance_category');
	for($i=0; $i<count($category_row); $i++){
	?>
	<tr align="center" class="category">
		<?php if(get_cfg('instance.category.del')){?><td><input name="del_CateId[]" type="checkbox" value="<?=$category_row[$i]['CateId'];?>" /></td><?php }?>
		<?php if(get_cfg('instance.category.order')){?><td><input name="MyOrder[]" class="form_input" type="text" size="3" maxlength="10" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=htmlspecialchars($category_row[$i]['MyOrder']);?>" /><input type="hidden" name="CateId[]" value="<?=$category_row[$i]['CateId'];?>" /></td><?php }?>
		<td nowrap align="left" class="category_list_form_catename">
			<div class="fz_18px category_list_form_catename_prechars"><?=$category_row[$i]['PreChars'];?></div><div><a href="<?=get_url('instance_category', $category_row[$i]);?>" target="_blank"><?=list_all_lang_data($category_row[$i], 'Category');?></a></div>
			<?php if(get_cfg('instance.category.upload_pic')){?><div><?=creat_imgLink_by_sImg($category_row[$i]['PicPath'], 20, 20);?></div><?php }?>
		</td>
		<?php if(get_cfg('instance.category.mod')){?><td><a href="category_mod.php?CateId=<?=$category_row[$i]['CateId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>" hspace="5" /></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('instance.category.order') || get_cfg('instance.category.del')) && count($category_row)){?>
	<tr>
		<td colspan="4" class="bottom_act">
			<?php if(get_cfg('instance.category.order')){?><input name="order_category" class="form_button" type="button" onClick="click_button(this, 'category_list_form', 'action');" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(get_cfg('instance.category.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("del_CateId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_category" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'category_list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="action" id="action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>