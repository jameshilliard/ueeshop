<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');


$list_row_count=4;	//每行显示的产品件数
$list_line_count=7;	//显示的行数
$query_string=query_string('page');
$turn_page_query_string=$website_url_type==0?"?$query_string&page=":'page-';
$page_count=$list_row_count*$list_line_count;

$CateId=isset($_GET['CateId'])?$_GET['CateId']:1;
$where='IsGift=0 and ColorCard=1';	//基本搜索条件，如果后台开启了上下架功能，这里请设置为：SoldOut=0

//-------------------------------------------------------------------------------------------------------------------------------------------------

if($CateId)
{

	$where.=' and '.get_search_where_by_CateId($CateId, 'product_category');
	$product_cat=$db->get_one('product_category',"CateId='$CateId'");
	$Name=$product_cat['Category'];
	$UId=$product_cat['UId'];
	$Cate=get_top_CateId_by_UId($UId);	 
}
$Keyword && $where.=" and (Name like '%$Keyword%' or ItemNumber like '%$Keyword%')";
(($P0 || $P1) && $P1>$P0) && $where.=" and Price_1 between $P0 and $P1";
$ItemNumber && $where.=" and ItemNumber='$ItemNumber'";
($ext && $ext_ary[$ext]) && $where.=" and {$ext_ary[$ext]}";

//-------------------------------------------------------------------------------------------------------------------------------------------------

$row_count=$db->get_row_count('product', $where);
$total_pages=ceil($row_count/$page_count);
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*$page_count;
$product_row=$db->get_limit('product', $where, '*', 'MyOrder desc, ProId desc', $start_row, $page_count);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=seo_meta($product_cat['SeoTitle'],$product_cat['SeoKeywords'],$product_cat['SeoDescription']);?>
<link href="/css/global.css" rel="stylesheet" type="text/css" />
<link href="/css/lib.css" rel="stylesheet" type="text/css" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/lang/en.js"></script>
<script language="javascript" src="/js/global.js"></script>
<script language="javascript" src="/js/checkform.js"></script>
<script language="javascript" src="/js/swf_obj.js"></script>
<script language="javascript" src="/js/date.js"></script>
</head>

<body>
<div id="wrap">
	<?php include('inc/header.php'); ?>
	<div id="inside">
		<div class="proR">
			<div class="pro_hd"><b><?=$Name?></b></div>
			<div id="itemlist">
				<?php
					$j=1;
					for($i=0;$i<count($product_row);$i++)
					{
						$url=get_url('product', $product_row[$i]);
				?>
				<div class="card_item">
					<div class="img"><a href="<?=$url;?>"><img src="<?=$product_row[$i]['PicPath_0'];?>"/></a></div>
					<div class="name"><a href="<?=$url;?>"><?=$product_row[$i]['Name'];?></a></div>
					<div class="price"><a href="<?=$url;?>">$<?=$product_row[$i]['Price_0'];?></a></div>
				</div>
				<?php 
						if($j++%$list_row_count==0) echo '<div class="clear"></div>';
				}?>
				<div class="blank6"></div>
<div id="turn_page"><?=turn_page($page, $total_pages, $turn_page_query_string, $row_count, $turn_page_ary['lang_0'][0], $turn_page_ary['lang_0'][1], $website_url_type);?></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>
