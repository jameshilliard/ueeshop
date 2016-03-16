<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('info');

if($_POST['list_form_action']=='info_order'){
	check_permit('', 'info.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$InfoId=(int)$_POST['InfoId'][$i];
		
		$db->update('info', "InfoId='$InfoId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('文章排序');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['list_form_action']=='info_move'){
	check_permit('', 'info.move');
	$CateId=(int)$_POST['CateId'];
	$InfoId=implode(',', $_POST['select_InfoId']);
	count($_POST['select_InfoId']) && move_to_other_category('info', $CateId, "InfoId in($InfoId)");
	
	save_manage_log('批量移动文章');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='info_move'){
	check_permit('', 'info.move');
	$SorceCateId=(int)$_POST['SorceCateId'];
	$DestCateId=(int)$_POST['DestCateId'];
	($SorceCateId && $DestCateId && $SorceCateId!=$DestCateId) && move_to_other_category('info', $DestCateId, get_search_where_by_CateId($SorceCateId, 'info_category'));
	
	save_manage_log('批量移动文章');
	
	header("Location: index.php");
	exit;
}

if($_POST['list_form_action']=='info_del'){
	check_permit('', 'info.del');
	if(count($_POST['select_InfoId'])){
		$InfoId=implode(',', $_POST['select_InfoId']);
		$where="InfoId in($InfoId)";
		
		$info_row=$db->get_all('info', $where, 'PicPath, PageUrl');
		for($i=0; $i<count($info_row); $i++){
			del_file($info_row[$i]['PageUrl']);
			if(get_cfg('info.upload_pic')){
				del_file($info_row[$i]['PicPath']);
				del_file(str_replace('s_', '', $info_row[$i]['PicPath']));
			}
		}
		
		$db->delete('info_contents', "InfoId in(select InfoId from info where $where)");
		$db->delete('info', $where);
	}
	save_manage_log('批量删除文章');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='info_del'){
	check_permit('', 'info.del');
	$CateId=(int)$_POST['CateId'];
	
	if($CateId){
		$where=get_search_where_by_CateId($CateId, 'info_category');
		
		$info_row=$db->get_all('info', $where, 'PicPath, PageUrl');
		for($i=0; $i<count($info_row); $i++){
			del_file($info_row[$i]['PageUrl']);
			if(get_cfg('info.upload_pic')){
				del_file($info_row[$i]['PicPath']);
				del_file(str_replace('s_', '', $info_row[$i]['PicPath']));
			}
		}
		
		$db->delete('info_contents', "InfoId in(select InfoId from info where $where)");
		$db->delete('info', $where);
	}
	save_manage_log('批量删除文章');
	
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
$Title=$_GET['Title'];
$CateId=(int)$_GET['CateId'];
$ext_where=$_GET['ext_where'];
$Title && $where.=" and Title like '%$Title%'";
if($_GET['Language']!=''){
	$Language=(int)$_GET['Language'];
	$Language>=0 && $where.=" and Language='$Language'";
}
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'info_category');
$ext_where && $where.=" and $ext_where";

$row_count=$db->get_row_count('info', $where);
$total_pages=ceil($row_count/get_cfg('info.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('info.page_count');
$info_row=$db->get_limit('info', $where, '*', 'MyOrder desc, InfoId desc', $start_row, get_cfg('info.page_count'));

//获取类别列表
$cate_ary=$db->get_all('info_category');
for($i=0; $i<count($cate_ary); $i++){
	$category[$cate_ary[$i]['CateId']]=$cate_ary[$i];
}
$select_category=ouput_Category_to_Select('CateId', '', 'info_category', 'UId="0,"', 1, get_lang('ly200.select'));

//获取页面跳转url参数
$query_string=query_string('page');

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('info.info_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('info.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<?php if(count($category)>1 && (get_cfg('info.move') || get_cfg('info.del'))){?>
	<tr>
		<td height="22" class="flh_150">
			<?php if(get_cfg('info.move')){?>
				<div>
					<form method="post" name="info_move_form" action="index.php" onsubmit="this.submit.disabled=true;">
						<?=get_lang('ly200.move');?>:<?=str_replace('CateId', 'SorceCateId', $select_category);?>-><?=str_replace('CateId', 'DestCateId', $select_category);?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.move');?>" class="form_button"><input type="hidden" name="bat_form_action" value="info_move" />
					</form>
				</div>
			<?php }?>
			<?php if(get_cfg('info.del')){?>
				<div>
					<form method="post" name="info_del_form" action="index.php" onsubmit="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{if(checkForm(this)){this.submit.disabled=true;}else{return false;}}">
						<?=get_lang('ly200.del');?>:<?=$select_category;?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.del');?>" class="form_button"><input type="hidden" name="bat_form_action" value="info_del" />
					</form>
				</div>
			<?php }?>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="info_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.title');?>:<input name="Title" class="form_input" type="text" size="25" maxlength='100'>
				<?php if(count(get_cfg('ly200.lang_array'))>1){?><?=get_lang('ly200.language');?>:<?=output_language_select(-1, 1, get_lang('ly200.select'));?><?php }?>
				<?php if(count($category)>1){?><?=get_lang('ly200.category');?>:<?=$select_category;?><?php }?>
				<?php if(get_cfg('info.is_in_index') || get_cfg('info.is_hot')){?>
					<?=get_lang('ly200.other_property');?>:<select name="ext_where">
						<option value=''>--<?=get_lang('ly200.select');?>--</option>
						<?php if(get_cfg('info.is_in_index')){?><option value='IsInIndex=1'><?=get_lang('ly200.is_in_index');?></option><?php }?>
						<?php if(get_cfg('info.is_hot')){?><option value='IsHot=1'><?=get_lang('info.is_hot');?></option><?php }?>
					</select>
				<?php }?>
				<input type="submit" name="submit" class="form_button" value="<?=get_lang('ly200.search');?>">
			</form>
		</td>
	</tr>
</table>
<form method="get" class="turn_page_form" action="index.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "index.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" class="form_input" type="text" size="2" maxlength="5">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="query_string" type="hidden" value="<?=$query_string;?>">
</form>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('info.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('info.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="25%" nowrap><strong><?=get_lang('ly200.title');?></strong></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?><td width="5%" nowrap><strong><?=get_lang('ly200.language');?></strong></td><?php }?>
		<?php if(count($category)>1){?><td width="15%" nowrap><strong><?=get_lang('ly200.category');?></strong></td><?php }?>
		<?php if(get_cfg('info.upload_pic')){?><td width="10%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('info.author')){?><td width="10%" nowrap><strong><?=get_lang('info.author');?></strong></td><?php }?>
		<?php if(get_cfg('info.provenance')){?><td width="10%" nowrap><strong><?=get_lang('info.provenance');?></strong></td><?php }?>
		<?php if(get_cfg('info.burden')){?><td width="10%" nowrap><strong><?=get_lang('info.burden');?></strong></td><?php }?>
		<?php if(get_cfg('info.is_in_index') || get_cfg('info.is_hot')){?><td width="10%" nowrap><strong><?=get_lang('ly200.other_property');?></strong></td><?php }?>
		<?php if(get_cfg('info.acc_time')){?><td width="10%" nowrap><strong><?=get_lang('ly200.time');?></strong></td><?php }?>
		<?php if(get_cfg('info.mod')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	for($i=0; $i<count($info_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('info.del')){?><td><input type="checkbox" name="select_InfoId[]" value="<?=$info_row[$i]['InfoId'];?>"></td><?php }?>
		<?php if(get_cfg('info.order')){?><td><input type="text" name="MyOrder[]" value="<?=$info_row[$i]['MyOrder'];?>" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" class="form_input" size="3" maxlength="10"><input name="InfoId[]" type="hidden" value="<?=$info_row[$i]['InfoId'];?>"></td><?php }?>
		<td class="break_all"><a href="<?=get_url('info', $info_row[$i]);?>" target="_blank"><?=$info_row[$i]['Title'];?></a></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?><td nowrap><?=get_lang('ly200.lang_array.lang_'.$info_row[$i]['Language']);?></td><?php }?>
		<?php if(count($category)>1){?>
			<td align="left" class="flh_150" nowrap><?php
				$lang_key=lang_name(array_search($info_row[$i]['Language'], get_cfg('ly200.lang_array')), 1);
				$UId=$category[$info_row[$i]['CateId']]['UId'];	//按CateId获取对应的UId
				$current_key_ary=@explode(',', $UId);
				for($m=1; $m<count($current_key_ary)-1; $m++){	//按CateId列表列出对应的类别名
					echo $category[$current_key_ary[$m]]['Category'.$lang_key].'<font class="fc_red">-></font>';
				}
				echo $category[$info_row[$i]['CateId']]['Category'.$lang_key];	//列表本身的类别名，因为UId不包含它本身
			?></td>
		<?php }?>
		<?php if(get_cfg('info.upload_pic')){?><td><?=creat_imgLink_by_sImg($info_row[$i]['PicPath']);?></td><?php }?>
		<?php if(get_cfg('info.author')){?><td><?=$info_row[$i]['Author'];?></td><?php }?>
		<?php if(get_cfg('info.provenance')){?><td><?=$info_row[$i]['Provenance'];?></td><?php }?>
		<?php if(get_cfg('info.burden')){?><td><?=$info_row[$i]['Burden'];?></td><?php }?>
		<?php if(get_cfg('info.is_in_index') || get_cfg('info.is_hot')){?>
			<td class="flh_150">
				<?=(get_cfg('info.is_in_index') && $info_row[$i]['IsInIndex'])?get_lang('ly200.is_in_index').'<br>':'';?>
				<?=(get_cfg('info.is_hot') && $info_row[$i]['IsHot'])?get_lang('info.is_hot').'<br>':'';?>
			</td>
		<?php }?>
		<?php if(get_cfg('info.acc_time')){?><td nowrap><?=date(get_lang('ly200.time_format_ymd'), $info_row[$i]['AccTime']);?></td><?php }?>
		<?php if(get_cfg('info.mod')){?><td nowrap><a href="mod.php?<?=$query_string;?>&InfoId=<?=$info_row[$i]['InfoId']?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('info.order') || get_cfg('info.del') || get_cfg('info.move')) && count($info_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<?php if(get_cfg('info.order')){?><input name="info_order" id="info_order" class="form_button" type="button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if((count($category)>1 && get_cfg('info.move')) || get_cfg('info.del')){?><input name="button" type="button" class="form_button" onClick='change_all("select_InfoId[]");' value="<?=get_lang('ly200.anti_select');?>"><?php }?>
			<?php if(count($category)>1 && get_cfg('info.move')){?>
				<?=get_lang('ly200.move_selected_to');?>:<?=$select_category;?>
				<input name="info_move" id="info_move" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.move');?>">
			<?php }?>
			<?php if(get_cfg('info.del')){?><input name="info_del" id="info_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>"><?php }?>
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