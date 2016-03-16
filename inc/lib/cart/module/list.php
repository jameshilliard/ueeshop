<?php
$query_string=query_string('act');

if($_POST['data']=='cart_list'){
	for($i=0; $i<count($_POST['CId']); $i++){
		$_CId=(int)$_POST['CId'][$i];
		$_ProId=(int)$_POST['ProId'][$i];
		$_Qty=abs((int)$_POST['Qty'][$i]);
		$_Qty<=0 && $_Qty=1;
		$_S_Qty=abs((int)$_POST['S_Qty'][$i]);
		$_Remark=$_POST['Remark'][$i];
		$_S_Remark=$_POST['S_Remark'][$i];
		
		if($_Qty!=$_S_Qty || $_Remark!=$_S_Remark){
			$product_row=$db->get_one('product', "ProId='$_ProId'");
			$db->update('shopping_cart', "$where and CId='$_CId'", array(
					'Qty'	=>	$_Qty,
					'Remark'=>	$_Remark,
					'Price'	=>	pro_add_to_cart_price($product_row, $_Qty)
				)
			);
		}
	}
	js_location("$cart_url?$query_string");
}

if($_GET['act']=='remove' || $_GET['act']=='later'){
	$CId=(int)$_GET['CId'];
	$db->delete('shopping_cart', "$where and CId='$CId'");
	
	if($_GET['act']=='later' && (int)$_SESSION['member_MemberId']){
		$ProId=(int)$_GET['ProId'];
		if(!$db->get_row_count('wish_lists', "$where and ProId='$ProId'")){
			$db->insert('wish_lists', array(
					'MemberId'	=>	(int)$_SESSION['member_MemberId'],
					'ProId'		=>	$ProId
				)
			);
		}
	}
	js_location("$cart_url?$query_string");
}

$cart_row=$db->get_all('shopping_cart', $where, '*', 'ProId desc, CId desc');
?>
<div id="lib_cart_station"><a href="/">Home</a> &gt; Shopping Cart</div>
<div id="lib_cart_guid"><img src="/images/lib/cart/guid_1.gif" /></div>
<div id="lib_cart_list">
	<?php if(!$cart_row){?>
		<div class="empty_cart">
			<img src="/images/lib/cart/no_items.gif" align="left" /><strong>We are sorry, Your Shopping Cart is Empty</strong><br />
			<a href="/">Return to the homepage</a><br />
			If you have tried to add an item to your shopping cart and the shopping cart is still empty, you may not have "cookies" enabled on your browser, please adjusting your browser to allow cookies.<br /> 
			To continue shopping, Please <a href="/">Click Here</a>.
		</div>
	<?php
	}else{
		$total_price=$db->get_sum('shopping_cart', $where, 'Qty*Price');
	?>
		<div class="cart_info">
			<div class="fl">Your Shopping Cart: <span><?=$db->get_sum('shopping_cart', $where, 'Qty');?></span> Item(s), <?php if($product_weight==1){?>Weight: <span><?=$db->get_sum('shopping_cart', $where, 'Qty*Weight');?></span> KG, <?php }?>Subtotal: <span><?=iconv_price($total_price);?></span></div>
			<div class="fr"><a href="/">Continue Shopping</a><a href="<?=$cart_url;?>?module=checkout">Proceed to Checkout</a></div>
		</div>
		<form action="<?=$cart_url.'?'.$query_string;?>" method="post" name="cart_list_form" OnSubmit="return checkForm(this);">
			<div class="cart">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="item_list_table">
					<tr class="tb_title">
						<td width="12%">Picture</td>
						<td width="12%">Item No.</td>
						<td width="32%">Product Name</td>
						<td width="8%">Price</td>
						<td width="8%">Integral</td>
						<td width="8%">Quantity</td>
						<td width="12%">Total</td>
						<td width="12%" class="last">&nbsp;</td>
					</tr>
					<?php
					for($i=0; $i<count($cart_row); $i++){
					?>
					<tr class="item_list item_list_out" onmouseover="this.className='item_list item_list_over';" onmouseout="this.className='item_list item_list_out';">
						<td valign="top"><table width="92" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td height="92" align="center" class="item_img"><a href="<?=$cart_row[$i]['Url'];?>" target="_blank"><img src="<?=$cart_row[$i]['PicPath'];?>" /></a></td></tr></table></td>
						<td valign="top"><a href="<?=$cart_row[$i]['Url'];?>" target="_blank" class="proname"><?=$cart_row[$i]['ItemNumber'];?></a><?php if($product_weight==1){?><br /><?=$cart_row[$i]['Weight'];?> KG<?php }?></td>
						<td valign="top">
							<a href="<?=$cart_row[$i]['Url'];?>" target="_blank" class="proname"><?=$cart_row[$i]['Name'];?></a><?php if($cart_row[$i]['IsGift']==1) echo "(Gifts for commodities)"; ?><br />
							<?php if($cart_row[$i]['Size']){?>Size: <?=$cart_row[$i]['Size'];?><br /><?php }?>
                            <?php if($cart_row[$i]['CustomizeID']){?><?=$cart_row[$i]['Customize'];?>
								<div><a href="/cart_customize.php?CId=<?=$cart_row[$i]['CId']?>" style="color:#000; text-decoration:underline">Edit Customizations</a><?php if($_SESSION['member_IsFinish']){?>|<a href="/measurement.php?module=checkout" style="color:#000; text-decoration:underline">Edit Customizations</a><?php } ?></div>
							<?php }?>
                            
							<span class="remark">Purchasing Remark:</span><br /><input name="Remark[]" value="<?=addslashes($cart_row[$i]['Remark']);?>" type="text" size="70" maxlength="100" class="form_input" /><input type="hidden" name="S_Remark[]" value="<?=addslashes($cart_row[$i]['Remark']);?>" />
						</td>
						<td align="center" class="c_red">
							<?php
								if($cart_row[$i]['IsGift']==0){
									echo iconv_price($cart_row[$i]['Price']);
								}else{
									echo '';
								}
							?>
						</td>
						<td align="center" class="c_red"><?=$cart_row[$i]['Integral'];?></td>
						<td align="center"><input type="text" name="Qty[]" value="<?=$cart_row[$i]['Qty'];?>" class="form_input qty" onkeyup="set_number(this, 0);" onpaste="set_number(this, 0);" size="5" maxlength="5" /><input type="hidden" name="S_Qty[]" value="<?=$cart_row[$i]['Qty'];?>" /><input type="hidden" name="CId[]" value="<?=$cart_row[$i]['CId'];?>" /><input type="hidden" name="ProId[]" value="<?=$cart_row[$i]['ProId'];?>" /></td>
						<td align="center" class="c_red">
							<?php
								if($cart_row[$i]['IsGift']==0){
									echo iconv_price($cart_row[$i]['Price']*$cart_row[$i]['Qty']);
								}else{
									echo '';
								}
							?>
						</td>
						<td align="center"><a href="<?=$cart_url.'?'.$query_string;?>&act=remove&CId=<?=$cart_row[$i]['CId'];?>" title="Remove this item from shopping cart" class="opt">Remove</a><br/><?php if((int)$_SESSION['member_MemberId']){?><a href="<?=$cart_url.''.$query_string;?>&act=later&CId=<?=$cart_row[$i]['CId'];?>&ProId=<?=$cart_row[$i]['ProId'];?>" title="Remove this item from shopping cart and save this item to you wish list!" class="opt">For later</a><?php }?></td>
					</tr>
					<?php }?>
					<tr class="total">
						<td></td>
						<td colspan="5"></td>
						<td><?=iconv_price($total_price);?></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="checkout"><input type="image" name="imageField" src="/images/lib/cart/btn_update.png" /><a href="<?=$_SESSION['member_IsFinish']==1?"$cart_url":'/measurement.php' ?>?module=checkout"><img src="<?=$_SESSION['member_IsFinish']==1?"/images/lib/cart/btn_cheakout.png":'/images/btn_measurements.png' ?>" /></a></div>
			<input type="hidden" name="data" value="cart_list" />
		</form>
		<div class="ext_info">
		<strong>Useful tips for your Shopping Cart:</strong><br /> 
		1. You can add up to 10000 products in your Shopping Cart.<br />
		2. Products will remain in your Shopping Cart for the permanent.<br />
		3. Products can be added to your Shopping Cart whether you're logged in or not.
		</div>
	<?php }?>
</div>