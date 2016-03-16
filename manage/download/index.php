<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('download');

if($_POST['list_form_action']=='download_order'){
	check_permit('', 'download.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$DId=(int)$_POST['DId'][$i];
		
		$db->update('download', "DId='$DId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('下载文件排序');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['list_form_action']=='download_move'){
	check_permit('', 'download.move');
	$CateId=(int)$_POST['CateId'];
	$DId=implode(',', $_POST['select_DId']);
	count($_POST['select_DId']) && move_to_other_category('download', $CateId, "DId in($DId)");
	
	save_manage_log('批量移动下载文件');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='download_move'){
	check_permit('', 'download.move');
	$SorceCateId=(int)$_POST['SorceCateId'];
	$DestCateId=(int)$_POST['DestCateId'];
	($SorceCateId && $DestCateId && $SorceCateId!=$DestCateId) && move_to_other_category('download', $DestCateId, get_search_where_by_CateId($SorceCateId, 'download_category'));
	
	save_manage_log('批量移动下载文件');
	
	header("Location: index.php");
	exit;
}

if($_POST['list_form_action']=='download_del'){
	check_permit('', 'download.del');
	if(count($_POST['select_DId'])){
		$DId=implode(',', $_POST['select_DId']);
		$where="DId in($DId)";
		
		$download_row=$db->get_all('download', $where, 'PicPath, FilePath, PageUrl');
		for($i=0; $i<count($download_row); $i++){
			del_file($download_row[$i]['PageUrl']);
			del_file($download_row[$i]['FilePath']);
			if(get_cfg('download.upload_pic')){
				del_file($download_row[$i]['PicPath']);
				del_file(str_replace('s_', '', $download_row[$i]['PicPath']));
			}
		}
		
		$db->delete('download_description', "DId in(select DId from download where $where)");
		$db->delete('download', $where);
	}
	save_manage_log('批量删除下载文件');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='download_del'){
	check_permit('', 'download.del');
	$CateId=(int)$_POST['CateId'];
	
	if($CateId){
		$where=get_search_where_by_CateId($CateId, 'download_category');
		
		$download_row=$db->get_all('download', $where, 'PicPath, FilePath, PageUrl');
		for($i=0; $i<count($download_row); $i++){
			del_file($download_row[$i]['PageUrl']);
			del_file($download_row[$i]['FilePath']);
			if(get_cfg('download.upload_pic')){
				del_file($download_row[$i]['PicPath']);
				del_file(str_replace('s_', '', $download_row[$i]['PicPath']));
			}
		}
		
		$db->delete('download_contents', "DId in(select DId from download where $where)");
		$db->delete('download', $where);
	}
	save_manage_log('批量删除下载文件');
	
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
$Name && $where.=" and Name like '%$Name%'";
if(isset($_GET['Language'])){
	$Language=(int)$_GET['Language'];
	$Language>=0 && $where.=" and Language='$Language'";
}
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'download_category');

$row_count=$db->get_row_count('download', $where);
$total_pages=ceil($row_count/get_cfg('download.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('download.page_count');
$download_row=$db->get_limit('download', $where, '*', 'MyOrder desc, DId desc', $start_row, get_cfg('download.page_count'));

//获取类别列表
$cate_ary=$db->get_all('download_category');
for($i=0; $i<count($cate_ary); $i++){
	$category[$cate_ary[$i]['CateId']]=$cate_ary[$i];
}
$select_category=ouput_Category_to_Select('CateId', '', 'download_category', 'UId="0,"', 1, get_lang('ly200.select'));

//获取页面跳转url参数
$query_string=query_string('page');

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('download.download_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('download.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<?php if(count($category)>1 && (get_cfg('download.move') || get_cfg('download.del'))){?>
	<tr>
		<td height="22" class="flh_150">
			<?php if(get_cfg('download.move')){?>
				<div>
					<form method="post" name="download_move_form" action="index.php" onsubmit="this.submit.disabled=true;">
						<?=get_lang('ly200.move');?>:<?=str_replace('CateId', 'SorceCateId', $select_category);?>-><?=str_replace('CateId', 'DestCateId', $select_category);?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.move');?>" class="form_button"><input type="hidden" name="bat_form_action" value="download_move" />
					</form>
				</div>
			<?php }?>
			<?php if(get_cfg('download.del')){?>
				<div>
					<form method="post" name="download_del_form" action="index.php" onsubmit="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{if(checkForm(this)){this.submit.disabled=true;}else{return false;}}">
						<?=get_lang('ly200.del');?>:<?=$select_category;?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.del');?>" class="form_button"><input type="hidden" name="bat_form_action" value="download_del" />
					</form>
				</div>
			<?php }?>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="download_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.name');?>:<input name="Name" class="form_input" type="text" size="25" maxlength='100'>
				<?php if(count(get_cfg('ly200.lang_array'))>1){?><?=get_lang('ly200.language');?>:<?=output_language_select(-1, 1, get_lang('ly200.select'));?><?php }?>
				<?php if(count($category)>1){?><?=get_lang('ly200.category');?>:<?=$select_category;?><?php }?>
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
		<?php if(get_cfg('download.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('download.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="20%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<td width="20%" nowrap><strong><?=get_lang('download.file');?></strong></td>
		<td width="5%" nowrap><strong><?=get_lang('ly200.download');?></strong></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?><td width="5%" nowrap><strong><?=get_lang('ly200.language');?></strong></td><?php }?>
		<?php if(count($category)>1){?><td width="15%" nowrap><strong><?=get_lang('ly200.category');?></strong></td><?php }?>
		<?php if(get_cfg('download.upload_pic')){?><td width="10%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('download.mod')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	for($i=0; $i<count($download_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('download.del')){?><td><input type="checkbox" name="select_DId[]" value="<?=$download_row[$i]['DId'];?>"></td><?php }?>
		<?php if(get_cfg('download.order')){?><td><input type="text" name="MyOrder[]" class="form_input" value="<?=$download_row[$i]['MyOrder'];?>" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" size="3" maxlength="10"><input name="DId[]" type="hidden" value="<?=$download_row[$i]['DId'];?>"></td><?php }?>
		<td class="break_all"><?=$download_row[$i]['Name'];?></td>
		<td class="break_all"><?=$download_row[$i]['FileName'];?></td>
		<td nowrap><?=creat_download_link($download_row[$i], get_lang('ly200.download'));?></td>
		<?php if(count(get_cfg('ly200.lang_array'))>1){?><td nowrap><?=get_lang('ly200.lang_array.lang_'.$download_row[$i]['Language']);?></td><?php }?>
		<?php if(count($category)>1){?>
			<td align="left" class="flh_150" nowrap><?php
				$lang_key=lang_name(array_search($download_row[$i]['Language'], get_cfg('ly200.lang_array')), 1);
				$UId=$category[$download_row[$i]['CateId']]['UId'];	//按CateId获取对应的UId
				$current_key_ary=@explode(',', $UId);
				for($m=1; $m<count($current_key_ary)-1; $m++){	//按CateId列表列出对应的类别名
					echo $category[$current_key_ary[$m]]['Category'.$lang_key].'<font class="fc_red">-></font>';
				}
				echo $category[$download_row[$i]['CateId']]['Category'.$lang_key];	//列表本身的类别名，因为UId不包含它本身
			?></td>
		<?php }?>
		<?php if(get_cfg('download.upload_pic')){?><td><?=creat_imgLink_by_sImg($download_row[$i]['PicPath']);?></td><?php }?>
		<?php if(get_cfg('download.mod')){?><td nowrap><a href="mod.php?<?=$query_string;?>&DId=<?=$download_row[$i]['DId']?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('download.order') || get_cfg('download.del') || get_cfg('download.move')) && count($download_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<?php if(get_cfg('download.order')){?><input name="download_order" id="download_order" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if((count($category)>1 && get_cfg('download.move')) || get_cfg('download.del')){?><input name="button" type="button" class="form_button" onClick='change_all("select_DId[]");' value="<?=get_lang('ly200.anti_select');?>"><?php }?>
			<?php if(count($category)>1 && get_cfg('download.move')){?>
				<?=get_lang('ly200.move_selected_to');?>:<?=$select_category;?>
				<input name="download_move" id="download_move" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.move');?>">
			<?php }?>
			<?php if(get_cfg('download.del')){?><input name="download_del" id="download_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>"><?php }?>
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