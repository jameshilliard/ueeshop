<?php
	$CateId=isset($_GET['CateId'])?$_GET['CateId']:1;
	$cate_one=$db->get_one('product_category',"CateId='$CateId'");
	$cateName=$cate_one['Category'];
?>
<div class="newsL">
	<div class="i_hd">News</div>
	<ul class="i_bd">
	<?php
		$news_list=$db->get_limit('info',"CateId=1",'*','MyOrder DESC,InfoId ASC',0,8);
		for($i=0; $i<count($news_list); $i++){
		$url=get_url('info',$news_list[$i]);
	?>
	<li><a href="<?=$url?>"><?=cut_str($news_list[$i]['Title'],35)?></a></li>
	<?php } ?>
	</ul>
</div>
<div class="proL">
	<div class="i_hd"><?=$cateName?></div>
	<div class="i_bd">
		<ul class="pro_cat">
			<?php
				$pro_cat=$db->get_limit('product_category',"UId='0,".$CateId.",'");
				for($i=0; $i<count($pro_cat); $i++){
					$url=get_url('product_category',$pro_cat[$i]);
			?>
			<li><a href="<?=$url?>"><?=$pro_cat[$i]['Category']?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
