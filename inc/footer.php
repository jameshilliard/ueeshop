<div id="footer_bg">
	<ul class="bottomNav">
		<li><a href="/">Home</a></li>
		<li><a href="/product.php?CateId=2">Clothes</a></li>
		<li><a href="/product.php?CateId=4">Blazers &amp; Coats</a></li>
		<li><a href="/article.php?AId=6">Privacy Notice</a></li>
		<li><a href="/article.php?AId=5">FAQ</a></li>
		<li class="last"><a href="/article.php?AId=2">Contact us</a></li>
	</ul>
</div>
<div id="footer">
	<div class="guide">
		<ul class="guide_list">
			<span><a href="/article.php?AId=1">ABOUT US</a></span>
			<?php
				$info_list=$db->get_limit('info',"CateId=4");
				for($i=0; $i<count($info_list); $i++){
					$url=get_url('info',$info_list[$i]);
			?>
			<li><a href="<?=$url?>"><?=$info_list[$i]['Title']?></a></li>
			<?php } ?>
		</ul>
		<ul class="guide_list">
			<span><a href="/article.php?AId=2">CONTACT US</a></span>
		</ul>
		<ul class="guide_list">
			<span><a href="/article.php?AId=8">HOW TO MEASURE</a></span>
		</ul>
		<ul class="guide_list">
			<span><a href="/article.php?AId=9">PAYMENT</a></span>
		</ul>
		<ul class="guide_list last">
			<span><a href="/article.php?AId=10">RETURNS</a></span>
		</ul>
	</div>
    <div class="thanks">
	<div><?=$db->get_value('article',"AId=7",'Contents')?></div>
	Copyright © 2012 macquinsuit.com. All rights reserved &nbsp;&nbsp;&nbsp;&nbsp;Powered by <a href="http://www.ly200.com/" target="_blank">LY Network</a>
    </div>
    <div id="postal">
        <?php
            $links=$db->get_all('links');
            for($i=0; $i<count($links); $i++){
                $url=get_url('links',$links);
        ?>
        <a href="<?=$url?>"><img src="<?=$links[$i]['LogoPath']?>" /></a>
        <?php } ?>
    </div>
</div>

<?=fs_53kf_code()?>
