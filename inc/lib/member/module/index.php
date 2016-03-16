<?php
$shipping_address=$db->get_one('member_address_book', "$where and AddressType=0 and IsDefault=1");
$billing_address=$db->get_one('member_address_book', "$where and AddressType=1");
$member_list=$db->get_one('member',"$where");
?>
<div id="lib_member_index">
	<div class="lib_member_title">Welcome <?=htmlspecialchars($_SESSION['member_FirstName'].' '.$_SESSION['member_LastName']);?></div>
	<div class="lib_member_info">Welcome to your account dashboard. Select below to update your personal details, communication preferences and view your<br />order history.</div>
	<div>
		<div class="item_card">
			<div class="title">Your Personal Details</div>
			<div class="info lib_member_item_card">
				<strong>Email: </strong><?=htmlspecialchars($_SESSION['member_Email']);?><br />
				<strong>Integral: </strong><?=$member_list['Integral'];?><br />
				<!--<strong>Title: </strong><?=htmlspecialchars($_SESSION['member_Title']);?><br />
				<strong>First Name: </strong><?=htmlspecialchars($_SESSION['member_FirstName']);?><br />
				<strong>Last Name: </strong><?=htmlspecialchars($_SESSION['member_LastName']);?>
				<div class="blank9"></div>
				<div><input type="button" value="Edit >>" class="form_button" onclick="window.location='<?=$member_url;?>?module=profile';" /></div>-->
			</div>
		</div>
		<div class="item_card">
			<div class="title">Order History</div>
			<div class="info lib_member_item_card">
				<a href="<?=$member_url;?>?module=orders&act=list">All Orders</a> <span>(<?=$db->get_row_count('orders', "$where");?>)</span><br />
				<?php foreach($order_status_ary as $key=>$value){?>
					<a href="<?=$member_url;?>?module=orders&status=<?=$key;?>&act=list"><?=$value;?></a> <span>(<?=$db->get_row_count('orders', "$where and OrderStatus='$key'");?>)</span><br />
				<?php }?>
			</div>
		</div>
		<div class="blank12"></div>
	</div>
</div>