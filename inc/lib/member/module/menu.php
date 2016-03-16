<div id="lib_member_menu" class="lib_member_item_card">
	<dl>
		<dt>account details</dt>
			<dd><a href="<?=$member_url;?>?module=index">Account Dashboard</a></dd>
			<dd><a href="<?=$member_url;?>?module=profile">Edit Personal Details</a></dd>
			<dd><a href="<?=$member_url;?>?module=password">Change Password</a></dd>
			<dd><a href="/measurement.php">Measurement Profile</a></dd>
			
			<dd class="clear_line"></dd>
		<dt>delivery details</dt>
			<dd><a href="<?=$member_url;?>?module=addressbook">Manage Address Book</a></dd>
			<dd class="clear_line"></dd>
		<dt>order history</dt>
			<dd><a href="<?=$member_url;?>?module=orders&act=list">All Orders</a></dd>
			<?php foreach($order_status_ary as $key=>$value){?>
				<dd><a href="<?=$member_url;?>?module=orders&status=<?=$key;?>&act=list"><?=$value;?></a></dd>
			<?php }?>
			<dd class="clear_line"></dd>
		<dt>shopping</dt>
			<dd><a href="<?=$cart_url;?>">Shopping Cart</a></dd>
			<dd><a href="<?=$member_url;?>?module=wishlists">Wish Lists</a></dd>
			<dd class="clear_line"></dd>
		<dt>sign out</dt>
			<dd><a href="<?=$member_url;?>?module=logout">Sign Out</a></dd>
	</dl>
</div>