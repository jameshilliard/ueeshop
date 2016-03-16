<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');
	
if($_POST)
{
		$CId=$_POST['CId'];
		$Color=$_POST['Color'];
		$CustomizeID=@implode('|',$_POST['customize']);
		$Font=$_POST['Font'];
		$Txta=$_POST['Txta'];
		$Txtb=$_POST['Txtb'];
		$Customized=$_POST['Customized'];
		$Location=$_POST['Location'];
		$customize_list=$db->get_all("product_customize","CateId='$Customized'");
		for($i=0;$i<count($customize_list);$i++)
		{
			
			$name=$db->get_value('product_customize_item',"IId='{$_POST['customize'][$i]}'",'Name');
				if($customize_list[$i]['NoProduct'])
				{
					$Customize.='<span style="font-weight:bold">'.$customize_list[$i]['Name'].'</span><br/>';
					if($Customized==2)
					{
						$Txta && $Customize.='Text:'.$Txta.'<br/>';
						$Location && $Customize.='Location:'.$Location.'<br/>';
					}
					else
					{
						if($Txta && $Txtb) $Customize.='Text:<br/>'.$Txta.'<br/>'.$Txtb.'<br/>';
					}
					$Font && $Customize.='Font:'.$Font;
					$Color && $Customize.='<br/>Color:<div style="width:20px; height:20px; background:'.$Color.'"></div>';
				}
				else
				{
					$Customize.='<span style="font-weight:bold">'.$customize_list[$i]['Name'].':</span>'.$name.'<br/>';	
				}
			
		}
		
	$db->update('shopping_cart',"CId='$CId'",
				array(
					'Customize' =>	$Customize,
					'Font'		=>	$Font,
					'CustomizeID'=>	$CustomizeID,
					'Txta'		=>	$Txta,
					'Txtb'		=>	$Txtb,
					'Customized'=>	$Customized,
					'Location'	=>	$Location,
					'Color'		=>	$Color
					));	
	js_location("$cart_url?module=list");
}
	$CId=(int)$_GET['CId'];
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

<script language="javascript">
	var cur=0;
	function $_(o){
		var o=document.getElementById(o) ? document.getElementById(o) : '';
		return o;
	}
	
	
	function moveElement(elementID, final_x, final_y, interval){
	var elem=$_(elementID);
	if(elem.movement){
		clearTimeout(elem.movement);
	}
	if(!elem.style.left){
		elem.style.left="0px";
	}
	if(!elem.style.top){
		elem.style.top="0px";
	}
	var xpos=parseInt(elem.style.left);
	var ypos=parseInt(elem.style.top);
	if(xpos == final_x  &&  ypos == final_y){
		return true;
	}
	if(xpos < final_x){
		var dist=Math.ceil((final_x - xpos)/10);
		xpos=xpos + dist;
	}
	if(xpos > final_x){
		var dist=Math.ceil((xpos - final_x)/10);
		xpos=xpos - dist;
	}
	if(ypos < final_y){
		var dist=Math.ceil((final_y - ypos)/10);
		ypos=ypos + dist;
	}
	if(ypos > final_y){
		var dist=Math.ceil((ypos - final_y)/10);
		ypos=ypos - dist;
	}
	elem.style.left=xpos + "px";
	elem.style.top=ypos + "px";
	var repeat="moveElement('"+elementID+"',"+final_x+","+final_y+","+interval+")";
	elem.movement=setTimeout(repeat,interval);
}


function btnChange(num,c)
{
	$_('tablist').style.width=num*970+'px';
	if(c==1)
	{
		cur++;
		if(cur>=num) cur=num-1;
	}
	else
	{
		cur--;
		if(cur<0) cur=0;	
	}
	
	if(cur==0)
	{
		$_('pre_but').style.visibility='hidden';
	}
	else
	{
		$_('pre_but').style.visibility='visible';
		if(cur>=(num-1))
		{
			$_('next_but').style.display='none';	
			$_('addcart_but').style.display='inline';
		}
		else
		{
			$_('addcart_but').style.display='none';		
			$_('next_but').style.display='inline';	
		}
	}
	var l=parseInt(cur*-970);
	moveElement('tablist',l,0,6);
} 

function getElement(id,v,cur,num)
{
	$_('customize_'+id).value=v;
	
	for(i=0;i<num;i++)
	{
		$_('item_'+id+'_'+i).className='item_box';		
	}
	$_('item_'+id+'_'+cur).className='item_box on';
}
function getFont(id,num,v)
{
	for(i=0;i<num;i++)
	{
		$_('font_'+i).style.borderColor='#ffffff';		
	}
	$_('font_'+id).style.borderColor='#F2CC67';
	$_('Font').value=v;
}

function getColor(id,num,v)
{
	for(i=0;i<num;i++)
	{
		$_('color_'+i).style.borderColor=color_aty[i];		
	}
	$_('color_'+id).style.borderColor='#F2CC67';
	$_('Color').value=v;
}
</script>
</head>

<body>
<div id="wrap">
	<?php include('inc/header.php'); ?>
	<div id="detail">
		<div id="customize">
			<div class="title">Customize</div>
            <form action="cart_customize.php" method="post">
            <div id="save_but" style="margin-bottom:10px"><input type="submit" name="sub" value="Save" /></div>
            <div class="contents">
            	<div id="tablist">
                <?php
				$cart_row=$db->get_one('shopping_cart',"CId='$CId'");
				$customize_list=$db->get_all("product_customize","CateId='{$cart_row['Customized']}'");
				$product_default=@explode('|',$cart_row['CustomizeID']);
                for($i=0;$i<count($customize_list);$i++)
                {
				
                ?>
                    <div class="tabbox">
                        <div class="t"><?=$customize_list[$i]['Name']?></div>
                       
                        <div class="tab_l">
						<?php
						if($customize_list[$i]['NoProduct']!=1)
						{
                            $customize_item_list=$db->get_all("product_customize_item","CId='{$customize_list[$i]['CId']}'");
                            $customize_item_count=count($customize_item_list);
                            for($j=0;$j<$customize_item_count;$j++){
                            ?>	
                                <div class="item_box<?=$product_default[$i]==$customize_item_list[$j]['IId']?' on':''?>" id="item_<?=$i?>_<?=$j?>" onclick="getElement(<?=$i?>,'<?=$customize_item_list[$j]['IId']?>',<?=$j?>,<?=$customize_item_count?>)">
                                    <div class="img"><img src="<?=$customize_item_list[$j]['PicPath']?>" /></div>
                                    <div class="name"><?=$customize_item_list[$j]['Name']?></div>
                                </div>
                                
                           <?php 
                                    if(($j+1)%4==0) echo '<div class="clear"></div>';
                            }
						}else
						{
						?>
                        <div class="monogram">
                        	<div class="tabtxt">
                            	<h4>TEXT</h4>
                                <?php
									if($cart_row['Customized']==2)
									{
								?>
                                <div class="tabtxt_a"><input type="text" name="Txta" value="<?=$cart_row['Txta']?>" style="width:60px"/></div>
                                <div class="msg">(max 3 characters)</div>
                                <h4>LOCATION</h4>
                                <div class="location"><input type="radio" name="Location" value="Cuff" <?=$cart_row['Location']=='Cuff'?'checked="checked"':''?>  />Cuff&nbsp;&nbsp;<input type="radio" name="Location" value="Chest " <?=$cart_row['Location']=='Chest'?'checked="checked"':''?> />Chest </div>
                                <?php		
									}
									else
									{
								?>
                            	<div class="tabtxt_a"><input type="text" name="Txta" value="<?=$cart_row['Txta']?>" /></div>
                                <div class="tabtxt_b"><input type="text" name="Txtb" value="<?=$cart_row['Txtb']?>" /></div>
                                <div class="msg">(max 20characters per line)</div>
                                <?php }?>
                            </div>
                            <div class="tabfont">
                            	<h4>FONT</h4>
                                <div>
                                <?php
									$font=$db->get_all('product_font',"CId='{$customize_list[$i]['CId']}'");
									$font_count=count($font);
									for($a=0;$a<$font_count;$a++)
									{
										
								?>
                                	<div class="sub_item" id="font_<?=$a?>" onclick="getFont(<?=$a?>,<?=$font_count?>,'<?=$font[$a]['Font']?>')" style="<?=$font[$a]['Font']==$cart_row['Font']?'border-color:#F2CC67':''?>"><img src="<?=$font[$a]['PicPath']?>" /></div>
                                <?php }?>
                              
                                <div class="clear"></div>
                                </div>
                            </div>
                            <div class="tabcolor">
                            	<h4>COLOR</h4>
                                <div>
                                <?php
									$color=$db->get_all('product_color',"CateId='{$customize_list[$i]['CId']}'");
									$color_count=count($color);
									$color_list=array();
									for($a=0;$a<$color_count;$a++)
									{
										$color_list[$a]=$color[$a]['Color'];
								?>
                                	<div class="sub_item" id="color_<?=$a?>" onclick="getColor(<?=$a?>,<?=$color_count?>,'<?=$color[$a]['Color']?>')" style="background:<?=$color[$a]['Color']?>; border:5px solid <?=$cart_row['Color']==$color[$a]['Color']?'#F2CC67':$color[$a]['Color']?> "></div>
                                <?php }?>
                                <div class="clear"></div>
                                <script language="javascript">
									var color_aty=Array()
									<?php
										for($a=0;$a<$color_count;$a++)
										{
									?>
									color_aty[<?=$a?>]='<?=$color_list[$a]?>';
									<?php 
										}
									?>
                                </script>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php	
						}
						?>
                            <div class="clear"></div>
                        </div>
                       
                        <div class="tab_r"><?=$customize_list[$i]['Contents']?></div>
                        <div class="clear"></div>
                    </div>
                 	<?php if($customize_list[$i]['NoProduct']!=1){ ?>
					<input type="hidden" name="customize[<?=$i?>]" id="customize_<?=$i?>" value="<?=$product_default[$i]?>" /><?php }?>
            	<?php }?>
                </div>
            </div>
            <div class="button">
            	<button type="button" class="pre_but" id="pre_but" onclick="btnChange(<?=$i?>,0)">PREVIOUS</button>
                <button type="button" class="next_but" id="next_but" onclick="btnChange(<?=$i?>,1)">NEXT</button>
                <button type="submit" class="next_but" id="addcart_but">Save</button>
       			</div>
            <input type="hidden" name="CId" value="<?=$CId?>" />
            <input type="hidden" id="Font" name="Font" value="<?=$cart_row['Font']?>" />
            <input type="hidden" id="Color" name="Color" value="<?=$cart_row['Color']?>" />
            <input type="hidden" name="Customized" value="<?=$cart_row['Customized']?>" />
            </form>
		</div>
		<div class="clear"></div>
	</div>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>