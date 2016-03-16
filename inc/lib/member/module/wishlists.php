<?php
$query_string=query_string('page');

if($_GET['act']=='remove'){
	$ProId=(int)$_GET['ProId'];
	$db->delete('wish_lists', "$where and ProId='$ProId'");
	js_location("$member_url?".query_string(array('act', 'ProId')));
}

if($_GET['act']=='add' || $_POST['act']=='add'){
	$ProId=(int)$_GET['ProId'];
	!$ProId && $ProId=(int)$_POST['ProId'];
	if($db->get_row_count('product', "ProId='$ProId'") && !$db->get_row_count('wish_lists', "$where and ProId='$ProId'")){
		$db->insert('wish_lists', array(
				'MemberId'	=>	(int)$_SESSION['member_MemberId'],
				'ProId'		=>	$ProId,
				'WishTime'	=>	$service_time
			)
		);
	}
	js_location("$member_url?module=wishlists");
}

$where="ProId in(select ProId from wish_lists where $where)";
$page_count=20;
$row_count=$db->get_row_count('product', $where);
$total_pages=ceil($row_count/$page_count);
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*$page_count;
$list_row=$db->get_limit('product', $where, '*', 'ProId desc', $start_row, $page_count);
?>
<div id="lib_member_wishlists">
	<div class="lib_member_title">Wish Lists</div>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="item_list">
		<tr class="tb_title">
			<td width="15%">Pictures</td>
			<td width="55%">Product</td>
			<td width="15%">Detail</td>
			<td width="15%" class="last">Remove</td>
		</tr>
		<?php
		for($i=0; $i<count($list_row); $i++){
			$url=get_url('product', $list_row[$i]);
		?>
		<tr class="item_list item_list_out" onmouseover="this.className='item_list item_list_over';" onmouseout="this.className='item_list item_list_out';" align="center">
			<td valign="top"><table width="94" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td height="94" align="center" class="item_img"><a href="<?=$url;?>" target="_blank"><img src="<?=str_replace('s_', '90X90_', $list_row[$i]['PicPath_0']);?>" /></a></td></tr></table></td>
			<td align="left">
				<a href="<?=$url;?>" target="_blank" class="proname"><?=$list_row[$i]['Name'];?></a><br /><br />
				Item No.: <a href="<?=$url;?>" target="_blank" class="proname"><?=$list_row[$i]['ItemNumber'];?></a>
			</td>
			<td><a href="<?=$url;?>" target="_blank" class="proname">Detail</a></td>
			<td><a href="<?=$member_url.'?'.query_string();?>&act=remove&ProId=<?=$list_row[$i]['ProId'];?>" class="proname">Remove List</a></td>
		</tr>
		<?php }?>
		<?php if(!count($list_row)){?>
		<tr class="item_list">
			<td align="center" height="150" colspan="4" bgcolor="#ffffff">not found!</td>
		</tr>
		<?php }?>
	</table>
	<div id="turn_page"><div class="en"><?=turn_page($page, $total_pages, "?$query_string&page=", $row_count, '<<', '>>');?></div></div>
</div>