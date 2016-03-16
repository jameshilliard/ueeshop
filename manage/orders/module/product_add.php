<?php
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
$select_category=ouput_Category_to_Select('CateId', '', 'product_category', 'UId="0,"', 1, get_lang('ly200.select'));

//获取页面跳转url参数
$query_string=query_string('page');

//颜色与尺寸列表
if(get_cfg('product.color_ele')==1 && get_cfg('product.color_ele_mode')==1){
	$color_ary=$db->get_all('product_color');
	for($i=0; $i<count($color_ary); $i++){
		$color_array[$color_ary[$i]['CId']]=$color_ary[$i]['Color'];
	}
}
if(get_cfg('product.size_ele')==1 && get_cfg('product.size_ele_mode')==1){
	$size_ary=$db->get_all('product_size');
	for($i=0; $i<count($size_ary); $i++){
		$size_array[$size_ary[$i]['SId']]=$size_ary[$i]['Size'];
	}
}
?>
<div class="list_form">
	<table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr>
			<td height="22" class="flh_150">
				<form method="get" name="product_search_form" action="view.php" onsubmit="this.submit.disabled=true;">
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
					<input type="hidden" name="OrderId" value="<?=$OrderId;?>" />
					<input type="hidden" name="module" value="<?=$module;?>" />
				</form>
			</td>
		</tr>
	</table>
</div>
<form method="get" class="turn_page_form" action="view.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "view.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="Name" type="hidden" value="<?=$Name;?>">
	<input name="ItemNumber" type="hidden" value="<?=$ItemNumber;?>">
	<input name="Model" type="hidden" value="<?=$Model;?>">
	<input name="CateId" type="hidden" value="<?=$CateId;?>">
	<input name="ext_where" type="hidden" value="<?=$ext_where;?>">
	<input type="hidden" name="OrderId" value="<?=$OrderId;?>" />
	<input type="hidden" name="module" value="<?=$module;?>" />
</form>
<form name="list_form" id="list_form" class="list_form" method="post" action="view.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td nowrap colspan="2"><strong><?=get_lang('ly200.select');?></strong></td>
		<td width="20%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<?php if(get_cfg('product.item_number')){?><td width="10%" nowrap><strong><?=get_lang('product.item_number');?></strong></td><?php }?>
		<?php if(get_cfg('product.model')){?><td width="10%" nowrap><strong><?=get_lang('product.model');?></strong></td><?php }?>
		<?php if(get_cfg('product.price')){?><td width="10%" nowrap><strong><?=get_lang('product.price');?></strong></td><?php }?>
		<?php if(get_cfg('product.stock')){?><td width="5%" nowrap><strong><?=get_lang('product.stock');?></strong></td><?php }?>
		<?php if(get_cfg('product.pic_count')){?><td width="8%" nowrap><strong><?=get_lang('ly200.photo');?></strong></td><?php }?>
		<td width="10%" nowrap><strong><?=get_lang('ly200.time');?></strong></td>
	</tr>
	<?php
	for($i=0; $i<count($product_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=$start_row+$i+1;?></td>
		<td width="5%"><input type="checkbox" name="select_ProId[]" value="<?=$product_row[$i]['ProId'];?>"></td>
		<td width="8%" nowrap align="left">
			<?=get_lang('ly200.qty');?>: <input type="text" size="5" maxlength="5" value="1" name="Qty_<?=$product_row[$i]['ProId'];?>" class="form_input" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" /><br />
			<?php if(get_cfg('product.color_ele')==1 && get_cfg('product.color_ele_mode')==1 && $product_row[$i]['ColorId']!='||'){?>
				<?=get_lang('product.color');?>: <select name="Color_<?=$product_row[$i]['ProId'];?>"><?php
					$c=explode('|', $product_row[$i]['ColorId']);
					for($j=1; $j<count($c)-1; $j++){
						echo "<option value='{$color_array[$c[$j]]}'>{$color_array[$c[$j]]}</option>";
					}
				?></select><br />
			<?php }?>
			<?php if(get_cfg('product.size_ele')==1 && get_cfg('product.size_ele_mode')==1 && $product_row[$i]['SizeId']!='||'){?>
				<?=get_lang('product.size');?>: <select name="Size_<?=$product_row[$i]['ProId'];?>"><?php
					$c=explode('|', $product_row[$i]['SizeId']);
					for($j=1; $j<count($c)-1; $j++){
						echo "<option value='{$size_array[$c[$j]]}'>{$size_array[$c[$j]]}</option>";
					}
				?></select><br />
			<?php }?>
		</td>
		<td class="break_all"><a href="<?=get_url('product', $product_row[$i]);?>" target="_blank"><?=list_all_lang_data($product_row[$i], 'Name');?></a></td>
		<?php if(get_cfg('product.item_number')){?><td nowrap><?=$product_row[$i]['ItemNumber'];?></td><?php }?>
		<?php if(get_cfg('product.model')){?><td nowrap><?=$product_row[$i]['Model'];?></td><?php }?>
		<?php if(get_cfg('product.price')){?>
			<td nowrap class="flh_150" align="left">
				<?php
				$p_ary=get_cfg('product.price_list');
				for($j=0; $j<count($p_ary); $j++){
					echo get_lang('product.price_list.'.$p_ary[$j]).':'.get_lang('ly200.price_symbols').sprintf('%01.2f', $product_row[$i]['Price_'.$j]).'<br>';
				}
				if(get_cfg('product.special_offer')){
					echo get_lang('product.special_offer').':'.($product_row[$i]['IsSpecialOffer']?('<font class="fc_red">'.get_lang('ly200.price_symbols').sprintf('%01.2f', $product_row[$i]['SpecialOfferPrice']).'</font>'):get_lang('ly200.n_y_array.0'));
				}
				?>
			</td>
		<?php }?>
		<?php if(get_cfg('product.stock')){?><td nowrap><?=$product_row[$i]['Stock'];?></td><?php }?>
		<?php if(get_cfg('product.pic_count')){?><td><?=creat_imgLink_by_sImg($product_row[$i]['PicPath_0']);?></td><?php }?>
		<td nowrap><?=date(get_lang('ly200.time_format_ymd'), $product_row[$i]['AccTime']);?></td>
	</tr>
	<?php }?>
	<?php if((get_cfg('product.order') || get_cfg('product.del') || get_cfg('product.move')) && count($product_row)){?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="button" type="button" class="form_button" onClick='change_all("select_ProId[]");' value="<?=get_lang('ly200.anti_select');?>">
			<input name="add_product" id="add_product" type="button" class="form_button" onClick="click_button(this, 'list_form', 'act');" value="<?=get_lang('ly200.add');?>">
			<input type="checkbox" name="AutoUpdatePrice" value="1" checked="checked" /><?=get_lang('orders.auto_upd_price');?>
			<input name="act" id="act" type="hidden" value="">
			<input type="hidden" name="OrderId" value="<?=$OrderId;?>" />
			<input type="hidden" name="module" value="<?=$module;?>" />
		</td>
	</tr>
	<?php }?>
</table>
</form>
<form method="get" class="turn_page_form" action="view.php" onsubmit="javascript:turn_page(this);">
	<?=turn_page($page, $total_pages, "view.php?$query_string&page=", $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page'));?>
	<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
	<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
	<input name="Name" type="hidden" value="<?=$Name;?>">
	<input name="ItemNumber" type="hidden" value="<?=$ItemNumber;?>">
	<input name="Model" type="hidden" value="<?=$Model;?>">
	<input name="CateId" type="hidden" value="<?=$CateId;?>">
	<input name="ext_where" type="hidden" value="<?=$ext_where;?>">
	<input type="hidden" name="OrderId" value="<?=$OrderId;?>" />
	<input type="hidden" name="module" value="<?=$module;?>" />
</form>