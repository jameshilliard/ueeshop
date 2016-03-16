<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('instance');

if($_POST['list_form_action']=='instance_order'){
	check_permit('', 'instance.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$CaseId=(int)$_POST['CaseId'][$i];
		
		$db->update('instance', "CaseId='$CaseId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('成功案例排序');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['list_form_action']=='instance_move'){
	check_permit('', 'instance.move');
	$CateId=(int)$_POST['CateId'];
	$CaseId=implode(',', $_POST['select_CaseId']);
	count($_POST['select_CaseId']) && move_to_other_category('instance', $CateId, "CaseId in($CaseId)");
	
	save_manage_log('批量移动成功案例');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='instance_move'){
	check_permit('', 'instance.move');
	$SorceCateId=(int)$_POST['SorceCateId'];
	$DestCateId=(int)$_POST['DestCateId'];
	($SorceCateId && $DestCateId && $SorceCateId!=$DestCateId) && move_to_other_category('instance', $DestCateId, get_search_where_by_CateId($SorceCateId, 'instance_category'));
	
	save_manage_log('批量移动成功案例');
	
	header("Location: index.php");
	exit;
}

if($_POST['list_form_action']=='instance_del'){
	check_permit('', 'instance.del');
	if(count($_POST['select_CaseId'])){
		$CaseId=implode(',', $_POST['select_CaseId']);
		$where="CaseId in($CaseId)";
		
		$instance_row=$db->get_all('instance', $where);
		for($i=0; $i<count($instance_row); $i++){
			del_file($instance_row[$i]['PageUrl']);
			if(get_cfg('instance.pic_count')){
				for($j=0; $j<get_cfg('instance.pic_count'); $j++){
					del_file($instance_row[$i]['PicPath_'.$j]);
					del_file(str_replace('s_', '', $instance_row[$i]['PicPath_'.$j]));
					foreach(get_cfg('instance.pic_size') as $key=>$value){
						del_file(str_replace('s_', $value.'_', $instance_row[$i]['PicPath_'.$j]));
					}
				}
			}
		}
		
		$db->delete('instance_description', "CaseId in(select CaseId from instance where $where)");
		$db->delete('instance', $where);
	}
	save_manage_log('批量删除成功案例');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='instance_del'){
	check_permit('', 'instance.del');
	$CateId=(int)$_POST['CateId'];
	if($CateId){
		$where=get_search_where_by_CateId($CateId, 'instance_category');
		
		$instance_row=$db->get_all('instance', $where);
		for($i=0; $i<count($instance_row); $i++){
			del_file($instance_row[$i]['PageUrl']);
			if(get_cfg('instance.pic_count')){
				for($j=0; $j<get_cfg('instance.pic_count'); $j++){
					del_file($instance_row[$i]['PicPath_'.$j]);
					del_file(str_replace('s_', '', $instance_row[$i]['PicPath_'.$j]));
					foreach(get_cfg('instance.pic_size') as $key=>$value){
						del_file(str_replace('s_', $value.'_', $instance_row[$i]['PicPath_'.$j]));
					}
				}
			}
		}
		
		$db->delete('instance_description', "CaseId in(select CaseId from instance where $where)");
		$db->delete('instance', $where);
	}
	save_manage_log('批量删除成功案例');
	
	header("Location: index.php");
	exit;
}

if($_GET['query_string']){
	$page=(int)$_GET['page'];
	header("Location: index.php?{$_GET['query_string']}&page=$page");
	exit;
}

//分页查询
$where=1;
$Name=$_GET['Name'];
$CateId=(int)$_GET['CateId'];
$ext_where=$_GET['ext_where'];
$Name && $where.=" and Name like '%$Name%'";
if($_GET['Language']!=''){
	$Language=(int)$_GET['Language'];
	$Language>=0 && $where.=" and Language='$Language'";
}
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'instance_category');
$ext_where && $where.=" and $ext_where";
$row_count=$db->get_row_count('instance', $where);
$total_pages=ceil($row_count/get_cfg('instance.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('instance.page_count');
$instance_row=$db->get_limit('instance', $where, '*', 'MyOrder desc, CaseId desc', $start_row, get_cfg('instance.page_count'));

//获取类别列表
$cate_ary=$db->get_all('instance_category');
for($i=0; $i<count($cate_ary); $i++){
	$category[$cate_ary[$i]['CateId']]=$cate_ary[$i];
}
$select_category=ouput_Category_to_Select('CateId', '', 'instance_category', 'UId="0,"', 1, get_lang('ly200.select'));

//获取页面跳转url参数
$query_string=query_string('page');

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('instance.instance_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('instance.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<?php if(count($category)>1 && (get_cfg('instance.move') || get_cfg('instance.del'))){?>
	<tr>
		<td height="22" class="flh_150">
			<?php if(get_cfg('instance.move')){?>
				<div>
					<form method="post" name="instance_move_form" action="index.php" onsubmit="this.submit.disabled=true;">
						<?=get_lang('ly200.move');?>:<?=str_replace('CateId', 'SorceCateId', $select_category);?>-><?=str_replace('CateId', 'DestCateId', $select_category);?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.move');?>" class="form_button"><input type="hidden" name="bat_form_action" value="instance_move" />
					</form>
				</div>
			<?php }?>
			<?php if(get_cfg('instance.del')){?>
				<div>
					<form method="post" name="instance_del_form" action="index.php" onsubmit="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{if(checkForm(this)){this.submit.disabled=true;}else{return false;}}">
						<?=get_lang('ly200.del');?>:<?=$select_category;?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.del');?>" class="form_button"><input type="hidden" name="bat_form_action" value="instance_del" />
					</form>
				</div>
			<?php }?>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="instance_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.name');?>:<input name="Name" type="text" size="25" maxlength='100' class="form_input">
				<?php if(count(get_cfg('ly200.lang_array'))>1 && !get_cfg('instance.add_mode')){?><?=get_lang('ly200.language');?>:<?=output_language_select(-1, 1, get_lang('ly200.select'));?><?php }?>
				<?php if(count($category)>1){?><?=get_lang('ly200.category');?>:<?=$select_category;?><?php }?>
				<?php if(get_cfg('instance.is_in_index') || get_cfg('instance.is_classic')){?>
					<?=get_lang('ly200.other_property');?>:<select name="ext_where">
						<option value=''>--<?=get_lang('ly200.select');?>--</option>
						<?php if(get_cfg('instance.is_in_index')){?><option value='IsInIndex=1'><?=get_lang('ly200.is_in_index');?></option><?php }?>
						<?php if(get_cfg('instance.is_classic')){?><option value='IsClassic=1'><?=get_lang('instance.is_classic');?></option><?php }?>
					</select>
				<?php }?>
				<input type="submit" name="submit" value="<?=get_lang('ly200.search');?>" class="form_button">
			</form>
		</td>
	</tr>
</table>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('instance.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('instance.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="25%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1 && !get_cfg('instance.add_mode')){?><td width="5%" nowrap><strong><?=get_lang('ly200.language');?></strong></td><?php }?>
		<?php if(count($category)>1){?><td width="15%" nowrap><strong><?=get_lang('ly200.category');?></strong></td><?php }?>
		<?php if(get_cfg('instance.pic_count')){?><td width="10%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('instance.is_in_index') || get_cfg('instance.is_classic')){?><td width="10%" nowrap><strong><?=get_lang('ly200.other_property');?></strong></td><?php }?>
		<td width="7%" nowrap><strong><?=get_lang('ly200.time');?></strong></td>
		<?php if(get_cfg('instance.mod')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	for($i=0; $i<count($instance_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('instance.del')){?><td><input type="checkbox" name="select_CaseId[]" value="<?=$instance_row[$i]['CaseId'];?>"></td><?php }?>
		<?php if(get_cfg('instance.order')){?><td><input type="text" name="MyOrder[]" class="form_input" value="<?=$instance_row[$i]['MyOrder'];?>" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" size="3" maxlength="10"><input name="CaseId[]" type="hidden" value="<?=$instance_row[$i]['CaseId'];?>"></td><?php }?>
		<td class="break_all"><a href="<?=get_url('instance', $instance_row[$i]);?>" target="_blank"><?=get_cfg('instance.add_mode')?list_all_lang_data($instance_row[$i], 'Name'):$instance_row[$i]['Name'];?></a></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1 && !get_cfg('instance.add_mode')){?><td nowrap><?=get_lang('ly200.lang_array.lang_'.$instance_row[$i]['Language']);?></td><?php }?>
		<?php if(count($category)>1){?>
			<td align="left" class="flh_150" nowrap><?php
				$lang_key=lang_name(array_search($instance_row[$i]['Language'], get_cfg('ly200.lang_array')), 1);
				$UId=$category[$instance_row[$i]['CateId']]['UId'];	//按CateId获取对应的UId
				$current_key_ary=@explode(',', $UId);
				for($m=1; $m<count($current_key_ary)-1; $m++){	//按CateId列表列出对应的类别名
					echo $category[$current_key_ary[$m]]['Category'.$lang_key].'<font class="fc_red">-></font>';
				}
				echo $category[$instance_row[$i]['CateId']]['Category'.$lang_key];	//列表本身的类别名，因为UId不包含它本身
			?></td>
		<?php }?>
		<?php if(get_cfg('instance.pic_count')){?><td><?=creat_imgLink_by_sImg($instance_row[$i]['PicPath_0']);?></td><?php }?>
		<?php if(get_cfg('instance.is_in_index') || get_cfg('instance.is_classic')){?>
			<td class="flh_150">
				<?=(get_cfg('instance.is_in_index') && $instance_row[$i]['IsInIndex'])?get_lang('ly200.is_in_index').'<br>':'';?>
				<?=(get_cfg('instance.is_classic') && $instance_row[$i]['IsClassic'])?get_lang('instance.is_classic').'<br>':'';?>
			</td>
		<?php }?>
		<td nowrap><?=date(get_lang('ly200.time_format_ymd'), $instance_row[$i]['UpdateTime']);?></td>
		<?php if(get_cfg('instance.mod')){?><td nowrap><a href="mod.php?<?=$query_string;?>&CaseId=<?=$instance_row[$i]['CaseId']?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('instance.order') || get_cfg('instance.del') || get_cfg('instance.move')) && count($instance_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<?php if(get_cfg('instance.order')){?><input name="instance_order" class="form_button" id="instance_order" type="button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if((count($category)>1 && get_cfg('instance.move')) || get_cfg('instance.del')){?><input name="button" class="form_button" type="button" onClick='change_all("select_CaseId[]");' value="<?=get_lang('ly200.anti_select');?>"><?php }?>
			<?php if(count($category)>1 && get_cfg('instance.move')){?>
				<?=get_lang('ly200.move_selected_to');?>:<?=$select_category;?>
				<input name="instance_move" id="instance_move" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.move');?>">
			<?php }?>
			<?php if(get_cfg('instance.del')){?><input name="instance_del" id="instance_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>"><?php }?>
			<input type="hidden" name="query_string" value="<?=urlencode($query_string);?>">
			<input type="hidden" name="page" value="<?=$page;?>">
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<?php include('../../inc/manage/footer.php');?>