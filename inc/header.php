<?php
	$str=$_SERVER['REQUEST_URI'];
	$Astr=$_SERVER['PHP_SELF'];
	$Proid=isset($_GET['ProId'])?$_GET['ProId']:0;
	$pro_row=$db->get_one('product',"ProId='$Proid'");
	$isgift=$pro_row['IsGift'];
	$CateIds=$pro_row['CateId'];
	$UId=get_UId_by_CateId($CateIds);
	$cate=get_top_CateId_by_UId($UId);
	
	$Cateid=isset($_GET['CateId'])?$_GET['CateId']:0;
	$pro_cat=$db->get_one('product_category',"CateId='$Cateid'");
	$uid=$pro_cat['UId'];
	$cates=get_top_CateId_by_UId($uid);
	
	$Infoid=isset($_GET['InfoId'])?$_GET['InfoId']:0;
	$info_one=$db->get_one('info',"InfoId='$Infoid'");
	$info=$info_one['CateId'];
?>
<div id="hd_bg">
	<div class="header">
		<div class="logo"><a href="/"><img src="/images/logo.jpg" /></a></div>
		<div class="hd_R">
			<div class="topNav">
				<a href="/account.php?module=login">
				<?php
				if((int)$_SESSION['member_MemberId']){
					echo htmlspecialchars($_SESSION['member_Email']);
				}else{
					echo 'Log In or New Guest';
				}
				?>
				</a>| 
				<a href="/account.php">My Account</a>| 
				<a href="/article.php?AId=4">how to customize</a> | 
				<a href="/article.php?AId=1">About us</a>
			</div>
			<div class="Search">
				<form action="/product.php" method="get">
					<input id="Search_txt" type="text" name="Keyword" value="Please enter keywords to search" onclick="Click()" onblur="Blur()" />
					<input id="Search_sub" type="submit" name="submit" value="" />
				</form>
			</div>
		</div>
	</div>
</div>
<div id="nav_bg">
	<div class="nav">
		<ul class="navMenu" id="nav_menu">
			<li><a href="/">Home</a>
			</li>
			<?php
				$top_category=$db->get_all('product_category','UId="0," and OnNav=1','*','MyOrder desc,CateId asc');
				for($i=0;$i<count($top_category);$i++)
				{
					$top_url=get_url('product_category',$top_category[$i]); 
			?>
			<li onmouseover="menu(this,1)" onmouseout="menu(this,0)"><a  href="<?=$top_url?>"><?=$top_category[$i]['Category']?></a>
				<dl style="<?=$top_category[$i]['SubCate']>0?'':'display:none'?>">
				<?php 
					$sub_category=$db->get_all('product_category', "UId='{$top_category[$i]['UId']}{$top_category[$i]['CateId']},'",'*','MyOrder DESC,CateId ASC');
					for($j=0; $j<count($sub_category); $j++){
					$url=$sub_category[$j]['ColorCard']==1?'/color_card.php?CateId='.$sub_category[$j]['CateId']:get_url('product_category',$sub_category[$j]); 
				?>
				<dt><a href="<?=$url;?>"><?=$sub_category[$j]['Category'];?></a></dt>
				<?php }?>
				</dl>
			</li>
			<?php }?>
			<li><a  class="long" href="/exchange.php">Gift Exchange</a></li>
		</ul>
		<div class="shopping"><img src="/images/shopping.jpg" /><a href="/cart.php">Shopping cart <b id="total_item">0</b> items</a></div>
		<iframe class="iFR" src="/inc/lib/shop/info.php"></iframe>
	</div>
</div>
<script language="javascript">
function menu(obj,c)
{
	if(c)
	{
		obj.style.position='relative';
		obj.style.background='#EFB85C';
		var items=obj.getElementsByTagName('dl');
		items[0].style.top=37+'px';
		var items_a=obj.getElementsByTagName('a');
		items_a[0].style.color='#000';
	}
	else
	{
		obj.style.position='static';
		obj.style.background='none';
		var items=obj.getElementsByTagName('dl');
		items[0].style.top=-1000000+'px';
		var items_a=obj.getElementsByTagName('a');
		items_a[0].style.color='#EFB85C';
	}
	
	
}
function Click(){
	var txt=document.getElementById('Search_txt');
	if(txt.value=='Please enter keywords to search'){
		txt.value='';
	}
}
function Blur(){
	var txt=document.getElementById('Search_txt');
	if(txt.value==''){
		txt.value='Please enter keywords to search';
	}
}
</script>
