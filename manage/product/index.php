<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product');

if($_POST['list_form_action']=='product_order'){
	check_permit('', 'product.order');
	for($i=0; $i<count($_POST['MyOrder']); $i++){
		$order=abs((int)$_POST['MyOrder'][$i]);
		$ProId=(int)$_POST['ProId'][$i];
		
		$db->update('product', "ProId='$ProId'", array(
				'MyOrder'	=>	$order
			)
		);
	}
	save_manage_log('产品排序');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['list_form_action']=='product_move'){
	check_permit('', 'product.move');
	$CateId=(int)$_POST['CateId'];
	$ProId=implode(',', $_POST['select_ProId']);
	count($_POST['select_ProId']) && move_to_other_category('product', $CateId, "ProId in($ProId)");
	
	save_manage_log('批量移动产品');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='product_move'){
	check_permit('', 'product.move');
	$SorceCateId=(int)$_POST['SorceCateId'];
	$DestCateId=(int)$_POST['DestCateId'];
	($SorceCateId && $DestCateId && $SorceCateId!=$DestCateId) && move_to_other_category('product', $DestCateId, get_search_where_by_CateId($SorceCateId, 'product_category'));
	
	save_manage_log('批量移动产品');
	
	header('Location: index.php');
	exit;
}

if($_POST['list_form_action']=='product_del'){
	check_permit('', 'product.del');
	if(count($_POST['select_ProId'])){
		$ProId=implode(',', $_POST['select_ProId']);
		$where="ProId in($ProId)";
		
		$product_row=$db->get_all('product', $where);
		for($i=0; $i<count($product_row); $i++){
			del_file($product_row[$i]['PageUrl']);
			if(get_cfg('product.pic_count')){
				for($j=0; $j<get_cfg('product.pic_count'); $j++){
					del_file($product_row[$i]['PicPath_'.$j]);
					del_file(str_replace('s_', '', $product_row[$i]['PicPath_'.$j]));
					foreach(get_cfg('product.pic_size') as $key=>$value){
						del_file(str_replace('s_', $value.'_', $product_row[$i]['PicPath_'.$j]));
					}
				}
			}
		}
		
		$db->delete('product_ext', "ProId in(select ProId from product where $where)");
		$db->delete('product_description', "ProId in(select ProId from product where $where)");
		$db->delete('product_wholesale_price', "ProId in(select ProId from product where $where)");
		$db->delete('product', $where);
	}
	save_manage_log('批量删除产品');
	
	$page=(int)$_POST['page'];
	$query_string=urldecode($_POST['query_string']);
	header("Location: index.php?$query_string&page=$page");
	exit;
}

if($_POST['bat_form_action']=='product_del'){
	check_permit('', 'product.del');
	$CateId=(int)$_POST['CateId'];
	
	if($CateId){
		$where=get_search_where_by_CateId($CateId, 'product_category');
		
		$product_row=$db->get_all('product', $where);
		for($i=0; $i<count($product_row); $i++){
			del_file($product_row[$i]['PageUrl']);
			if(get_cfg('product.pic_count')){
				for($j=0; $j<get_cfg('product.pic_count'); $j++){
					del_file($product_row[$i]['PicPath_'.$j]);
					del_file(str_replace('s_', '', $product_row[$i]['PicPath_'.$j]));
					foreach(get_cfg('product.pic_size') as $key=>$value){
						del_file(str_replace('s_', $value.'_', $product_row[$i]['PicPath_'.$j]));
					}
				}
			}
		}
		
		$db->delete('product_ext', "ProId in(select ProId from product where $where)");
		$db->delete('product_description', "ProId in(select ProId from product where $where)");
		$db->delete('product_wholesale_price', "ProId in(select ProId from product where $where)");
		$db->delete('product', $where);
	}
	save_manage_log('批量删除产品');
	
	header('Location: index.php');
	exit;
}

if($_GET['query_string']){
	$page=(int)$_GET['page'];
	header("Location: index.php?{$_GET['query_string']}&page=$page");
	exit;
}

$order_by_ary=array(
	0=>'',
	1=>'Stock asc,',
	2=>'Stock desc,',
	3=>'AccTime asc,',
	4=>'AccTime desc,'
);

//分页查询
$where=1;
$Name=$_GET['Name'];
$ItemNumber=$_GET['ItemNumber'];
$Model=$_GET['Model'];
$CateId=(int)$_GET['CateId'];
$ext_where=$_GET['ext_where'];
$OrderBy=(int)$_GET['OrderBy'];
($OrderBy<0 || count($order_by_ary)<=$OrderBy) && $OrderBy=0;

$Name && $where.=" and Name like '%$Name%'";
$ItemNumber && $where.=" and ItemNumber='$ItemNumber'";
$Model && $where.=" and Model='$Model'";
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'product_category');
$ext_where && $where.=" and $ext_where";

$row_count=$db->get_row_count('product', $where);
$total_pages=ceil($row_count/get_cfg('product.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('product.page_count');
$product_row=$db->get_limit('product', $where, '*', $order_by_ary[$OrderBy].'MyOrder desc, ProId desc', $start_row, get_cfg('product.page_count'));

//获取类别列表
$cate_ary=$db->get_all('product_category');
for($i=0; $i<count($cate_ary); $i++){
	$category[$cate_ary[$i]['CateId']]=$cate_ary[$i];
}
$category_count=$db->get_row_count('product_category');
$select_category=ouput_Category_to_Select('CateId', '', 'product_category', 'UId="0,"', 1, get_lang('ly200.select'));

//获取页面跳转url参数
$query_string=query_string('page');

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('product.product_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('product.add')){?><div class="float_right"><a href="add.php"><?=get_lang('ly200.add');?></a></div><?php }?>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<?php if($category_count>1 && (get_cfg('product.move') || get_cfg('product.del'))){?>
	<tr>
		<td height="22" class="flh_150">
			<?php if(get_cfg('product.move')){?>
				<div>
					<form method="post" name="product_move_form" action="index.php" onsubmit="this.submit.disabled=true;">
						<?=get_lang('ly200.move');?>:<?=str_replace('CateId', 'SorceCateId', $select_category);?>-><?=str_replace('CateId', 'DestCateId', $select_category);?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.move');?>" class="form_button"><input type="hidden" name="bat_form_action" value="product_move" />
					</form>
				</div>
			<?php }?>
			<?php if(get_cfg('product.del')){?>
				<div>
					<form method="post" name="product_del_form" action="index.php" onsubmit="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{if(checkForm(this)){this.submit.disabled=true;}else{return false;}}">
						<?=get_lang('ly200.del');?>:<?=$select_category;?>
						<input type="submit" name="submit" value="<?=get_lang('ly200.del');?>" class="form_button"><input type="hidden" name="bat_form_action" value="product_del" />
					</form>
				</div>
			<?php }?>
		</td>
	</tr>
	<?php }?>
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="product_search_form" action="index.php" onsubmit="this.submit.disabled=true;">
				<?=get_lang('ly200.name');?>:<input name="Name" class="form_input" type="text" size="25" maxlength='100'>
				<?php if(get_cfg('product.item_number')){?><?=get_lang('product.item_number');?>:<input name="ItemNumber" class="form_input" type="text" size="15" maxlength='50'><?php }?>
				<?php if(get_cfg('product.model')){?><?=get_lang('product.model');?>:<input name="Model" class="form_input" type="text" size="15" maxlength='50'><?php }?>
				<?php if($category_count>1){?><?=get_lang('ly200.category');?>:<?=$select_category;?><?php }?>
				<?php if(get_cfg('product.is_in_index') || get_cfg('product.is_hot') || get_cfg('product.is_recommend') || get_cfg('product.is_new') || get_cfg('product.sold_out') || (get_cfg('product.price') && get_cfg('product.special_offer'))){?>
					<?=get_lang('ly200.other_property');?>:<select name="ext_where">
						<option value=''>--<?=get_lang('ly200.select');?>--</option>
						<?php if(get_cfg('product.is_in_index')){?><option value='IsInIndex=1'><?=get_lang('ly200.is_in_index');?></option><?php }?>
						<?php if(get_cfg('product.is_hot')){?><option value='IsHot=1'><?=get_lang('product.is_hot');?></option><?php }?>
						<?php if(get_cfg('product.is_recommend')){?><option value='IsRecommend=1'><?=get_lang('product.is_recommend');?></option><?php }?>
						<?php if(get_cfg('product.is_new')){?><option value='IsNew=1'><?=get_lang('product.is_new');?></option><?php }?>
						<?php if(get_cfg('product.is_gift')){?><option value='IsGift=1'><?=get_lang('product.is_gift');?></option><?php }?>
						<?php if(get_cfg('product.sold_out')){?><option value='SoldOut=1'><?=get_lang('product.sold_out');?></option><?php }?>
						<?php if(get_cfg('product.price') && get_cfg('product.special_offer')){?><option value='IsSpecialOffer=1'><?=get_lang('product.special_offer');?></option><?php }?>
					</select>
				<?php }?>
				<?=get_lang('ly200.order');?>:<select name="OrderBy">
					<option value='0'>--<?=get_lang('ly200.select');?>--</option>
					<?php if(get_cfg('product.stock')){?>
						<option value="1"><?=get_lang('product.stock_asc');?></option>
						<option value="2"><?=get_lang('product.stock_desc');?></option>
					<?php }?>
					<option value="3"><?=get_lang('product.time_asc');?></option>
					<option value="4"><?=get_lang('product.time_desc');?></option>
				</select>
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
		<?php if(get_cfg('product.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<?php if(get_cfg('product.order')){?><td width="5%" nowrap><strong><?=get_lang('ly200.order');?></strong></td><?php }?>
		<td width="20%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<?php if(get_cfg('product.item_number')){?><td width="10%" nowrap><strong><?=get_lang('product.item_number');?></strong></td><?php }?>
		<?php if(get_cfg('product.model')){?><td width="10%" nowrap><strong><?=get_lang('product.model');?></strong></td><?php }?>
		<?php if(get_cfg('product.price')){?><td width="10%" nowrap><strong><?=get_lang('product.price');?></strong></td><?php }?>
		<?php if(get_cfg('product.stock')){?><td width="5%" nowrap><strong><?=get_lang('product.stock');?></strong></td><?php }?>
		<?php if($category_count>1){?><td width="15%" nowrap><strong><?=get_lang('ly200.category');?></strong></td><?php }?>
		<?php if(get_cfg('product.pic_count')){?><td width="8%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<?php if(get_cfg('product.is_in_index') || get_cfg('product.is_hot') || get_cfg('product.is_recommend') || get_cfg('product.is_new') || get_cfg('product.sold_out')){?><td width="8%" nowrap><strong><?=get_lang('ly200.other_property');?></strong></td><?php }?>
		<td width="10%" nowrap><strong><?=get_lang('ly200.time');?></strong></td>
		<?php if(get_cfg('product.mod') || get_cfg('product.copy')){?><td width="5%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	for($i=0; $i<count($product_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<?php if(get_cfg('product.del')){?><td><input type="checkbox" name="select_ProId[]" value="<?=$product_row[$i]['ProId'];?>"></td><?php }?>
		<?php if(get_cfg('product.order')){?><td><input type="text" name="MyOrder[]" class="form_input" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" value="<?=$product_row[$i]['MyOrder'];?>" size="3" maxlength="10"><input name="ProId[]" type="hidden" value="<?=$product_row[$i]['ProId'];?>"></td><?php }?>
		<td class="break_all"><a href="<?=get_url('product', $product_row[$i]);?>" target="_blank"><?=list_all_lang_data($product_row[$i], 'Name');?></a></td>
		<?php if(get_cfg('product.item_number')){?><td nowrap><?=$product_row[$i]['ItemNumber'];?></td><?php }?>
		<?php if(get_cfg('product.model')){?><td nowrap><?=$product_row[$i]['Model'];?></td><?php }?>
		<?php if(get_cfg('product.price')){?>
			<td nowrap class="flh_150" align="left">
				<?php
				if($product_row[$i]['IsGift']==0){
					$p_ary=get_cfg('product.price_list');
					for($j=0; $j<count($p_ary); $j++){
						echo get_lang('product.price_list.'.$p_ary[$j]).':'.get_lang('ly200.price_symbols').sprintf('%01.2f', $product_row[$i]['Price_'.$j]).'<br>';
					}
					if(get_cfg('product.special_offer')){
						echo get_lang('product.special_offer').':'.($product_row[$i]['IsSpecialOffer']?('<font class="fc_red">'.get_lang('ly200.price_symbols').sprintf('%01.2f', $product_row[$i]['SpecialOfferPrice']).'</font>'):get_lang('ly200.n_y_array.0')).'<br>';
					}
					if(get_cfg('product.list_price')){
						echo get_lang('product.list_price').':'.($product_row[$i]['IsListPrice']?('<font class="fc_red">'.get_lang('ly200.price_symbols').sprintf('%01.2f', $product_row[$i]['ListPrice']).'</font>'):get_lang('ly200.n_y_array.0')).'<br>';
					}
				}
				if(get_cfg('product.Integral')){
					echo get_lang('product.Integral').':'.($product_row[$i]['Integral']?('<font class="fc_red">'.get_lang('ly200.Integral').$product_row[$i]['Integral'].'</font>'):get_lang('ly200.Integral'));
				}
				?>
			</td>
		<?php }?>
		<?php if(get_cfg('product.stock')){?><td nowrap><?=$product_row[$i]['Stock'];?></td><?php }?>
		<?php if($category_count>1){?>
			<td align="left" class="flh_150" nowrap><?php
				$lang_key=lang_name(array_search($product_row[$i]['Language'], get_cfg('ly200.lang_array')), 1);
				$UId=$category[$product_row[$i]['CateId']]['UId'];	//按CateId获取对应的UId
				$current_key_ary=@explode(',', $UId);
				for($m=1; $m<count($current_key_ary)-1; $m++){	//按CateId列表列出对应的类别名
					echo $category[$current_key_ary[$m]]['Category'.$lang_key].'<font class="fc_red">-></font>';
				}
				echo $category[$product_row[$i]['CateId']]['Category'.$lang_key];	//列表本身的类别名，因为UId不包含它本身
			?></td>
		<?php }?>
		<?php if(get_cfg('product.pic_count')){?><td><?=creat_imgLink_by_sImg($product_row[$i]['PicPath_0']);?></td><?php }?>
		<?php if(get_cfg('product.is_in_index') || get_cfg('product.is_hot') || get_cfg('product.is_recommend') || get_cfg('product.is_new') || get_cfg('product.sold_out')){?>
			<td class="flh_150">
				<?=(get_cfg('product.is_in_index') && $product_row[$i]['IsInIndex'])?get_lang('ly200.is_in_index').'<br>':'';?>
				<?=(get_cfg('product.is_hot') && $product_row[$i]['IsHot'])?get_lang('product.is_hot').'<br>':'';?>
				<?=(get_cfg('product.is_recommend') && $product_row[$i]['IsRecommend'])?get_lang('product.is_recommend').'<br>':'';?>
				<?=(get_cfg('product.is_new') && $product_row[$i]['IsNew'])?get_lang('product.is_new').'<br>':'';?>
				<?=(get_cfg('product.is_gift') && $product_row[$i]['IsGift'])?get_lang('product.is_gift').'<br>':'';?>
				<?=get_cfg('product.sold_out')?get_lang('product.sold_out').':'.get_lang('ly200.n_y_array.'.$product_row[$i]['SoldOut']).'<br>':'';?>
			</td>
		<?php }?>
		<td nowrap><?=date(get_lang('ly200.time_format_ymd'), $product_row[$i]['AccTime']);?></td>
		<?php if(get_cfg('product.mod') || get_cfg('product.copy')){?><td nowrap><?php if(get_cfg('product.mod')){?><a href="mod.php?<?=$query_string;?>&ProId=<?=$product_row[$i]['ProId']?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a><?php }?><?php if(get_cfg('product.copy')){?>&nbsp;&nbsp;<a href="copy.php?<?=$query_string;?>&ProId=<?=$product_row[$i]['ProId']?>"><img src="../images/copy.gif" alt="<?=get_lang('ly200.copy');?>"></a><?php }?></td><?php }?>
	</tr>
	<?php }?>
	<?php if((get_cfg('product.order') || get_cfg('product.del') || get_cfg('product.move')) && count($product_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<?php if(get_cfg('product.order')){?><input name="product_order" id="product_order" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.order');?>"><?php }?>
			<?php if(($category_count>1 && get_cfg('product.move')) || get_cfg('product.del')){?><input name="button" type="button" class="form_button" onClick='change_all("select_ProId[]");' value="<?=get_lang('ly200.anti_select');?>"><?php }?>
			<?php if($category_count>1 && get_cfg('product.move')){?>
				<?=get_lang('ly200.move_selected_to');?>:<?=$select_category;?>
				<input name="product_move" id="product_move" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.move');?>">
			<?php }?>
			<?php if(get_cfg('product.del')){?><input name="product_del" id="product_del" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'list_form_action');};" value="<?=get_lang('ly200.del');?>"><?php }?>
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