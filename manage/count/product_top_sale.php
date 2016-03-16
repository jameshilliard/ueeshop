<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_top_sale');

$where='OrderStatus in(5,6)';
$OrderTime_S=$_GET['OrderTime_S'];
$OrderTime_E=$_GET['OrderTime_E'];
$CateId=(int)$_GET['CateId'];

if($OrderTime_S!='' && $OrderTime_E!=''){
	$time_start=@strtotime($OrderTime_S);
	$time_end=@strtotime($OrderTime_E);
	$where="OrderId in(select OrderId from orders where OrderTime between $time_start and $time_end and $where)";
}else{
	$where="OrderId in(select OrderId from orders where $where)";
}
$CateId && $where.=' and '.get_search_where_by_CateId($CateId, 'product_category');

$rs=$db->query("select sum(Qty) as qty, sum(Price*Qty) as price, ProId, Name, Url from orders_product_list where $where group by ProId order by qty desc limit 200");

$count_rs=$db->query("select sum(Qty) as qty from orders_product_list where $where");
$count_row=mysql_fetch_assoc($count_rs);

$price_rs=$db->query("select sum(Price*Qty) as price from orders_product_list where $where");
$price_row=mysql_fetch_assoc($price_rs);

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="product_top_sale.php"><?=get_lang('count.product_top_sale');?></a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bat_form">
	<tr>
		<td height="22" class="flh_150">
			<form method="get" name="order_view_form" action="product_top_sale.php" onsubmit="this.submit.disabled=true;">
				<?php if($db->get_row_count('product_category')>1){?><?=get_lang('ly200.category');?>:<?=ouput_Category_to_Select('CateId', '', 'product_category', 'UId="0,"', 1, get_lang('ly200.select'));?><?php }?>
				<?=get_lang('ly200.time');?>:<input name="OrderTime_S" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=$OrderTime_S;?>" class="form_input" />-<input name="OrderTime_E" type="text" size="8" onclick="SelectDate(this);" contenteditable="false" value="<?=$OrderTime_E;?>" class="form_input" />
				<input type="submit" name="submit" value="<?=get_lang('ly200.view');?>" class="form_button" />
			</form>
		</td>
	</tr>
</table>
<form name="list_form" id="list_form" class="list_form" method="post" action="color.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="10%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td width="40%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<td width="25%" nowrap><strong><?=get_lang('count.qty');?></strong></td>
		<td width="25%" nowrap><strong><?=get_lang('count.price');?></strong></td>
	</tr>
	<?php
	$i=1;
	while($row=mysql_fetch_assoc($rs)){
		$p0=$count_row['qty']?@sprintf('%01.2f', $row['qty']/$count_row['qty']*100).'%':'1px';
		$p1=$price_row['price']?@sprintf('%01.2f', $row['price']/$price_row['price']*100).'%':'1px';
	?>
	<tr align="center">
		<td nowrap><?=($i++)?></td>
		<td><a href="<?=$row['Url'];?>" target="_blank"><?=$row['Name'];?></a></td>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="left">
					<td nowrap width="30"><?=$row['qty'];?></td>
					<td nowrap width="150"><div style="width:150px; height:10px; border:1px solid #ddd;"><div style="background:red; width:<?=$p0;?>; height:10px; overflow:hidden;"></div></div></td>
					<td nowrap width="60"><?=@sprintf('%01.2f', $row['qty']/$count_row['qty']*100);?>%</td>
				</tr>
			</table>
		</td>
		<td nowrap>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="left">
					<td nowrap width="70"><?=get_lang('ly200.price_symbols').sprintf('%01.2f', $row['price']);?></td>
					<td nowrap width="150"><div style="width:150px; height:10px; border:1px solid #ddd;"><div style="background:red; width:<?=$p1;?>; height:10px; overflow:hidden;"></div></div></td>
					<td nowrap width="60"><?=@sprintf('%01.2f', $row['price']/$price_row['price']*100);?>%</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>