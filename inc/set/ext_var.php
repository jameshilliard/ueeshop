<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

$website_url_type=0;	//链接地址类型，0：动态，1：静态，当值为0时请设置$website_url_pchar，$website_url_pchar_ary和$website_url_mode变量
$website_url_pchar_key='none';	//链接地址前辍数组的下标，默认为none，如多语言，判断当前语言设置前辍，如中文版为：cn，英文版为：en，则在生成链接地址时，自动在最前面加上/cn或/en
$website_url_pchar_ary=array(	//链接地址前辍
	'none'	=>	'',
	'cn'	=>	'/cn',
	'en'	=>	'/en',
);
$website_url_mode_ary=array(
	'article_group_0'	=>	'return sprintf("/article.php?AId=%s", $row["AId"]);',	//信息页分组一
	'article_group_1'	=>	'return sprintf("/article.php?AId=%s", $row["AId"]);',	//信息页分组二
	'article_group_2'	=>	'return sprintf("/article.php?AId=%s", $row["AId"]);',	//信息页分组三
	'article_group_3'	=>	'return sprintf("/article.php?AId=%s", $row["AId"]);',	//信息页分组四
	'article_group_4'	=>	'return sprintf("/article.php?AId=%s", $row["AId"]);',	//信息页分组五
	
	'info_category'		=>	'return sprintf("/info.php?CateId=%s", $row["CateId"]);',	//文章分类列表页
	'info'				=>	'return sprintf("/info_detail.php?InfoId=%s", $row["InfoId"]);',	//文章详细页
	
	'instance_category'	=>	'return sprintf("/instance.php?CateId=%s", $row["CateId"]);',	//成功案例分类列表页
	'instance'			=>	'return sprintf("/instance_detail.php?CaseId=%s", $row["CaseId"]);',	//成功案例详细页
	
	'product_category'	=>	'return sprintf("/product.php?CateId=%s", $row["CateId"]);',	//产品分类列表页
	'product_brand'		=>	'return sprintf("/brand.php?BId=%s", $row["BId"]);',	//产品品牌列表页
	'product'			=>	'return sprintf("/product_detail.php?ProId=%s", $row["ProId"]);',	//产品详细页
);

$turn_page_ary=array(
	'lang_0'	=>	array('Prev', 'Next'),
	'lang_1'	=>	array('<<', '>>')
);


$shoulders=array('Square Shoulders','Normal Shoulders','Sloping Shoulders');
$shoulders_img=array('/images/shoulders_square.png','/images/shoulders_normal.png','/images/shoulders_sloping.png');
$chest=array('Muscular Chest','Regular Chest','Husky/Hefty Chest');
$chest_img=array('/images/chest_muscular.png','/images/chest_normal.png','/images/chest_husky_hefty.png');
$stomach=array('Flat Stomach','Average Stomach','Rounded Stomach');
$stomach_img=array('/images/stomach_flat.png','/images/stomach_average.png','/images/stomach_rounded.png');
$posture=array('Flat Posture','Normal Posture','Hunched Posture');
$posture_img=array('/images/posture_flat.png','/images/posture_normal.png','/images/posture_hunched.png');
//-------------------------------------------------------------------购物网站配置，非购物网站可全部注释掉(start here)---------------------------------------------------------------
$product_weight=1;	//产品是否有重量参数（此参数影响购物车，会员中心订单查询，后台订单管理）

$_SESSION['Currency']=='' && $_SESSION['Currency']=$mCfg['ExchangeRate']['Default'];	//设置默认的币种

$member_url='/account.php';	//会员中心的链接地址
$cart_url='/cart.php';	//购物车的链接地址
(int)$_SESSION['member_MemberId']==0 && $cart_SessionId=substr(md5(md5(session_id())), 0, 10);

$order_default_status=1;	//订单默认状态
$order_status_ary=array(	//订单状态，加下标，为了可随时根据需要增加或删除项目
	1=>'Awaiting Payment',
	2=>'Awaiting Confirm Payment',
	3=>'Payment Wrong',
	4=>'Awaiting Shipping',
	5=>'Shipment Shipped',
	6=>'Received',
	7=>'Cancelled'
);


$customize_aty=array(0=>'Suits',1=>'Blazers&Coats',2=>'Shirts',3=>'Pants');
//-------------------------------------------------------------------购物网站配置，非购物网站可全部注释掉(end here)-----------------------------------------------------------------
?>