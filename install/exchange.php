<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');
include('inc/lib/product/list_lang_1.php');
	$CateId=isset($_GET['CateId'])?$_GET['CateId']:1;
	$product_cat=$db->get_one('product_category',"CateId='$CateId'");
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
			<div class="pro_hd"><b>Exchange</b></div>
			<?php
				$list_row_count=4;	//每行显示的产品件数
				$list_line_count=5;	//显示的行数
				$query_string=query_string('page');
				$turn_page_query_string=$website_url_type==0?"?$query_string&page=":'page-';
				$page_count=$list_row_count*$list_line_count;
				$Exchange_row=$db->get_limit('product','IsGift=1','*','MyOrder DESC,ProId ASC');
			?>
			<div id="lib_product_list">
				<ul class="pro_list">
					<?php
					$j=1;
					for($i=0; $i<count($Exchange_row); $i++){
					?>
					<li>
						<div class="iPic" style="width:200px;"><a href="/exchange_detail.php?ProId=<?=$Exchange_row[$i]['ProId'];?>"><img width="200" height="200" src="<?=$Exchange_row[$i]['PicPath_0'];?>"/></a></div>
						<div class="iTxt" style="width:200px;">
							<p><a href="/exchange_detail.php?ProId=<?=$Exchange_row[$i]['ProId'];?>"><?=$Exchange_row[$i]['Name'];?></a></p>
							<p class="price">Integral:<font color="red"><?=$Exchange_row[$i]['Integral'];?></font></p>
						</div>
					</li>
					<?php if($j++%$list_row_count==0){echo '<div class="blank12"></div>';};?>
				<?php }?>
				</ul>
				<div class="clear"></div>
			</div>
			<div class="blank6"></div>
			<div id="turn_page"><?=turn_page($page, $total_pages, $turn_page_query_string, $row_count, $turn_page_ary['lang_0'][0], $turn_page_ary['lang_0'][1], $website_url_type);?></div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>

