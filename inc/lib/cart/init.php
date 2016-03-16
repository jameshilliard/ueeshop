<?php
$left_time=$service_time-3600*24*7;	//7天

$db->update('shopping_cart', "SessionId='$cart_SessionId'", array(
		'SessionId'	=>	'',
		'MemberId'	=>	$_SESSION['member_MemberId']
	)
);

$CId='0';
$rs=$db->query("select CId from (select * from shopping_cart where MemberId='{$_SESSION['member_MemberId']}' order by CId desc) temp group by concat(ProId, Color, Size)");
while($row=mysql_fetch_assoc($rs)){
	$CId.=','.$row['CId'];
}
$CId.=',0';
$db->delete('shopping_cart', "((MemberId='{$_SESSION['member_MemberId']}' and CId not in($CId)) or (MemberId=0 and AddTime<$left_time))");	//删除没登录的用户加入购物车超过7天的物品
?>