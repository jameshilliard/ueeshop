<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');

	$ProId=(int)$_GET['ProId'];
	$product_one=$db->get_one('product',"ProId='$ProId'");
	if(!$product_one)
	{
		echo '<script>window.location.href="/";</script>';	
	}
	$CateId=$product_one['CateId'];
	$UId=get_UId_by_CateId($CateId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=seo_meta($product_one['SeoTitle'],$product_one['SeoKeywords'],$product_one['SeoDescription']);?>
<link href="/css/global.css" rel="stylesheet" type="text/css" />
<link href="/css/lib.css" rel="stylesheet" type="text/css" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/lang/en.js"></script>
<script language="javascript" src="/js/global.js"></script>
<script language="javascript" src="/js/checkform.js"></script>
<script language="javascript" src="/js/swf_obj.js"></script>
<script language="javascript" src="/js/date.js"></script>
<link href="/css/magiczoom.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="/js/magiczoom.js" type="text/javascript"></script>
</head>

<body>
<div id="wrap">
	<?php include('inc/header.php'); ?>
	<div id="detail">
		<div class="iName"><a href="/">Home</a> &gt;&gt; <a href="/exchange.php">Git Exchange</a></div>
		<div style="display:none;">
			<?php
				for($i=0; $i<8; $i++){
				if(!is_file($site_root_path.$product_one['PicPath_'.$i])) continue;
			?>
			<div class="item"><img src="<?=$product_one['PicPath_'.$i]?>" <?=img_width_height(150,190,$product_one['PicPath_'.$i])?> /></div>
			<?php } ?>
		</div>
		<div class="iInfo">
			<div class="iLeft">
			<div id="show"></div>
				<div id="arrow_up"><img id="arrow_up_image" src="/images/TopMenu.jpg" onmouseover="moveDown()" onmouseout="stopMove()"/></div>
				<div id="prc_contain">
					<div id="piclist">
					<?php
						for($i=0; $i<8; $i++){
						if(!is_file($site_root_path.$product_one['PicPath_'.$i])) continue;
					?>
					<div class="item">
					<a href="javascript:void(0)"  onclick="showPreview('<?=str_replace('s_', '445X492_', $product_one['PicPath_'.$i]);?>');"><img src="<?=$product_one['PicPath_'.$i]?>" <?=img_width_height(150,190,$product_one['PicPath_'.$i])?> /></a>
					</div>
					<?php } ?>
					</div>
				</div>
				<div id="arrow_down"><img id="arrow_down_image" src="/images/BottomMenu.jpg" onmouseover="moveUp()" onmouseout="stopMove()" /></div>
			</div>
			<div class="iRight">
               <div class="pic">
               	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" valign="middle" headers="340"><img width="340" height="340" src="<?=str_replace('s_',$size_model, $product_one['PicPath_0']);?>" id="bigimgasdf"/></td>
                      </tr>
                    </table>
			</div>
				<div class="info">
					<form action="<?=$cart_url?>?module=add" method="post">
					<div class="item">
						
						<b><?=$product_one['Name'];?></b>
						<div class="price">Integral:<span><?=$product_one['Integral'];?></span></div>
						<div class="item_border"></div>
						<div class="cart"><input type="image" src="/images/exchange.jpg" /></div>
						<div class="quick">
							<p><?=$product_one['BriefDescription']?></p>
						</div>
						<input type="hidden" name="ProId" value="<?=$product_one['ProId']?>" />
						<input type="hidden" name="Qty" value="1" />
						<input type="hidden" name="Integral" value="<?=$product_one['Integral']?>" />
						<input type="hidden" name="IsGift" value="<?=$product_one['IsGift']?>" />
					</div>
					</form>
				</div>
			</div>
			<div class="clear"></div>
			<div class="iBD">
				<div class="BD_hd">
					<a href="javascript:void(0);" id="row_1" onclick="CL(1)" style="color:#f00;">PRODUCT DETAILS</a>
					<a href="javascript:void(0);" id="row_2" onclick="CL(2)">SIZES</a>
					<a href="javascript:void(0);" id="row_3" onclick="CL(3)">USER RATING</a>
				</div>
				<div class="BD_bd">
					<div class="desc_contents" id="des_1"><?=$db->get_value('product_description', "ProId='{$product_one['ProId']}'", 'Description');?></div>
					<div class="desc_contents" id="des_2" style="display:none;"><?=$db->get_value('product_description', "ProId='{$product_one['ProId']}'", 'Description_2');?></div>
					<div class="desc_contents" id="des_3" style="display:none;"><?=$db->get_value('product_description', "ProId='{$product_one['ProId']}'", 'Description_3');?></div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>

<script language="javascript">
function Size_value(num){
	var size_list=document.getElementById('size_list').getElementsByTagName('a');
	for(var i=0; i<size_list.length; i++){
		size_list[i].style.background='url(/images/pro_size.jpg) no-repeat';
		size_list[i].style.color='#D4A516';
	}
	document.getElementById('Size_'+num).style.background='url(/images/pro_size_hover.jpg) no-repeat';
	document.getElementById('Size_'+num).style.color='#fff';
	
	var SizeId=document.getElementById('SizeId');
	SizeId.value=num;
}
</script>

<script type="text/javascript">
function CL(num){
		for(i=1;i<4;i++)
		{
			document.getElementById('des_'+i).style.display='none';
			document.getElementById('row_'+i).style.color='white';
		}
		document.getElementById('des_'+num).style.display='block';
		document.getElementById('row_'+num).style.color='red';
}

var tt='';
var contain=document.getElementById('prc_contain');
var obj=document.getElementById('piclist');
function moveUp()
{
		tt=setInterval(function(){
			contain.scrollTop+=obj.offsetHeight*0.01;
			//show.innerHTML='scrollTop:'+contain.scrollTop+'<BR/>offsetHeight:'+obj.offsetHeight+'<BR/>ext:'+ext;
			if(contain.scrollTop==(obj.offsetHeight-contain.offsetHeight))
			{ 
				clearInterval(tt);
			}
		},30);


}
function stopMove()
{
	clearInterval(tt);	
}
function moveDown()
{
	tt=setInterval(function(){
		contain.scrollTop-=obj.offsetHeight*0.01;
		//show.innerHTML='scrollTop:'+contain.scrollTop+'<BR/>offsetHeight:'+obj.offsetHeight+'<BR/>ext:'+ext;
		if(contain.scrollTop==0)
		{ 
			clearInterval(tt);
		}
		},30);
	
}

function showPreview(s)
{
	document.getElementById('bigimgasdf').src=s;
	document.getElementById('zoom').href=s.replace('445X492_','');
	$j(document).a("domready",MagicZoom.refresh);
	$j(document).a("mousemove",MagicZoom.z1);
}
</script>
