<?php
include('inc/site_config.php');
include('inc/set/ext_var.php');
include('inc/fun/mysql.php');
include('inc/function.php');

$module=$_GET['module'];
if(!(int)$_SESSION['member_MemberId'])
{
	$url=$member_url.'?module=login&jump_url='.urlencode('/measurement.php');
	if($module=='checkout')
	{
		$url=$member_url.'?module=login&jump_url='.urlencode('/measurement.php?module=checkout');
	}
	js_location($url);
}
if($module=='checkout')
{
	$where="MemberId='{$_SESSION['member_MemberId']}'";
	$cart_row=$db->get_all('shopping_cart', $where, '*', 'ProId desc, CId desc');
	!$cart_row && js_location("$cart_url?module=list");
}
if($_POST['module']=='add')
{
	$HEIGHTFT=(int)$_POST['HEIGHTFT'];
	$HEIGHTIN=(int)$_POST['HEIGHTIN'];
	$WEIGHT=(float)$_POST['WEIGHT'];
	$AGE=(float)$_POST['AGE'];
	$shoulders=(float)$_POST['shoulders'];
	$chest=(float)$_POST['chest'];
	$stomach=(float)$_POST['stomach'];
	$posture=(float)$_POST['posture'];
	$ShirtNeck=(float)$_POST['ShirtNeck'];
	$JacketShirtLength=(float)$_POST['JacketShirtLength'];
	$ChestSize=(float)$_POST['ChestSize'];
	$StomachSize=(float)$_POST['StomachSize'];
	$JacketHips=(float)$_POST['JacketHips'];
	$ShoulderSize=(float)$_POST['ShoulderSize'];
	$SleeveLength=(float)$_POST['SleeveLength'];
	$BicepSize=(float)$_POST['BicepSize'];
	$WristSize=(float)$_POST['WristSize'];
	$PantsLength=(float)$_POST['PantsLength'];
	$Waist=(float)$_POST['Waist'];
	$Crotch=(float)$_POST['Crotch'];
	$ThighSize=(float)$_POST['ThighSize'];
	$KneeSize=(float)$_POST['KneeSize'];
	$target=$_POST['target'];
	$_SESSION['member_IsFinish']=1;
	
	$db->update('member',"MemberId='{$_SESSION['member_MemberId']}'",
				array(
					  'HEIGHTFT'			=>$HEIGHTFT,
					  'HEIGHTIN'			=>$HEIGHTIN,
					  'WEIGHT'				=>$WEIGHT,
					  'AGE'					=>$AGE,
					  'shoulders'			=>$shoulders,
					  'chest'				=>$chest,
					  'stomach'				=>$stomach,
					  'posture'				=>$posture,
					  'ShirtNeck'			=>$ShirtNeck,
					  'JacketShirtLength'	=>$JacketShirtLength,
					  'ChestSize'			=>$ChestSize,
					  'StomachSize'			=>$StomachSize,
					  'JacketHips'			=>$JacketHips,
					  'ShoulderSize'		=>$ShoulderSize,
					  'SleeveLength'		=>$SleeveLength,
					  'BicepSize'			=>$BicepSize,
					  'WristSize'			=>$WristSize,
					  'PantsLength'			=>$PantsLength,
					  'Waist'				=>$Waist,
					  'Crotch'				=>$Crotch,
					  'ThighSize'			=>$ThighSize,
					  'KneeSize'			=>$KneeSize,
					  'IsFinish'			=>1
					  ));
	
	
	if($target=='checkout')
	{
		$url=$cart_url.'?module=list';
	}
	else
	{
		$url=$member_url;	
	}
	js_location($url);
}

$measurement=$db->get_one('member',"MemberId='{$_SESSION['member_MemberId']}'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=seo_meta();?>
<link href="/css/global.css" rel="stylesheet" type="text/css" />
<link href="/css/lib.css" rel="stylesheet" type="text/css" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/lang/en.js"></script>
<script language="javascript" src="/js/global.js"></script>
<script language="javascript" src="/js/checkform.js"></script>
<script language="javascript" src="/js/swf_obj.js"></script>
<script language="javascript" src="/js/date.js"></script>
<script type="text/javascript" src="/js/m.js"></script>
<script type="text/javascript" src="/js/flowplayer-3.2.6.min.js"></script>
<script type="text/javascript">
/////////////////////////////////////////////////////////// 封装部分	
function drag(obj,$txt)
{
		var aLi = obj.getElementsByTagName("li");
		obj.style.width = (80 * aLi.length) + "px";		
		obj.onmousedown=function (ev)
		{
			var oEvent=ev||event;
			disX=oEvent.clientX-this.offsetLeft;
			document.onmousemove=function (ev)
			{
				var oEvent=ev||event;			
				var l=oEvent.clientX-disX;
				var $first = -20;
				var $end   = parseInt((aLi.length-2)*aLi[0].offsetWidth-60);
				if(l > $first)
				{
					l = $first;
				} else if(l < -$end) {
					l = -$end;
				}
				obj.style.left = l + "px";
				return false;
			};		
			document.onmouseup=function ()
			{
				document.onmousemove=null;
				document.onmouseup=null;
				$mod = setNum(obj.style.left)%20; //求余
				if(Math.abs($mod) > 10)//求个数
				{
					ruler_num = Math.floor(setNum(obj.style.left)/20);
				} else {
					ruler_num = Math.ceil(setNum(obj.style.left)/20);
				}
				startMove(obj,{left:ruler_num*20});
				$txt.value = Math.abs(ruler_num)*0.25;
				obj.releaseCapture && obj.releaseCapture();
			};
			this.setCapture && this.setCapture();		
			return false;
		};	
		
		$txt.onkeyup = function(){
		var $max = 78 //标尺上限	
		$val = this.value;
		$val = parseFloat($val);		
		if($val > ($max-1))
		{
			this.value = ($max-1);
		}  else if($val <= 0){
			$val = 0;
			this.value = $val;
		}
		$cha = $val-($val%0.25).toFixed(2);
		$total = (($cha+0.25)/0.25)*20-20; 
		if($val && $val < $max)
		{
			startMove(obj,{left:-$total});
		}
	}
}
////////////////////////////////////////////通用函数
function $$(oParent,elem)//获取对象标签名
{
		return oParent.getElementsByTagName(elem);	
}
function $$$(oParent,className)//获取对象类名
{
		var aClass = [];
		i = 0;
		reClass = new RegExp("(\\s|^)" + className + "($|\\s)");
		aElement = this.$$(oParent,"*");
		for (i = 0; i < aElement.length; i++)reClass.test(aElement[i].className) && aClass.push(aElement[i]);
		return aClass;
}
function setNum($n)
{
	return (parseInt($n))
}
</script>
<script language="javascript">
	 var shoulders_ary=Array();
	<?php
   	for($i=0;$i<count($shoulders_img);$i++)
	{
		echo "shoulders_ary[$i]='{$shoulders_img[$i]}';";
	}
   ?>
   
    var chest_ary=Array();
	<?php
   	for($i=0;$i<count($chest_img);$i++)
	{
		echo "chest_ary[$i]='{$chest_img[$i]}';";
	}
   ?>
   
   var stomach_ary=Array();
	<?php
   	for($i=0;$i<count($stomach_img);$i++)
	{
		echo "stomach_ary[$i]='{$stomach_img[$i]}';";
	}
   ?>
   
   var posture_ary=Array();
	<?php
   	for($i=0;$i<count($posture_img);$i++)
	{
		echo "posture_ary[$i]='{$posture_img[$i]}';";
	}
   ?>
 	var vidoe_list=Array();
	vidoe_list[2]='/media/shirt_neck.flv';
	vidoe_list[3]='/media/jacket_shirt_length.flv';
	vidoe_list[4]='/media/chest_size.flv';
	vidoe_list[5]='/media/stomach_size.flv';
	vidoe_list[6]='/media/jacket_hips.flv';
	vidoe_list[7]='/media/shoulder_size.flv';
	vidoe_list[8]='/media/sleeve_length.flv';
	vidoe_list[9]='/media/bicep_size.flv';
	vidoe_list[10]='/media/wrist_size.flv';
	vidoe_list[11]='/media/pants_length.flv';
	vidoe_list[12]='/media/waist.flv';
	vidoe_list[13]='/media/crotch.flv';
	vidoe_list[14]='/media/thigh_size.flv';
	vidoe_list[15]='/media/knee_size.flv';
	var player='';
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
	for(i=2;i<16;i++)
	{
		if($_('player'+i))
		{
			$_('player'+i).style.display='none';
		}
	}
	if(c==1)
	{
		cur++;
		if(cur>=num) cur=num-1;
		if($_('HEIGHTIN').value=='0' || $_('WEIGHT').value=='' || $_('AGE').value=='' || isNaN($_('WEIGHT').value) || isNaN($_('AGE').value))
		{
			return true;	
		}
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

	if(cur>1)
	{
		drag(oUlArr[cur-2],txtArr[cur-2]);
		player='player'+cur;
		if(typeof($_(player))=='object')
		{
			$_(player).style.display='block';
		}
		else
		{
			var obj_child=document.createElement('a');
			obj_child.setAttribute('id',player);
			obj_child.setAttribute('class','player');
			obj_child.setAttribute('display','block');
			
			obj_child.setAttribute('href',vidoe_list[cur]);
			$_('player_box_'+cur).appendChild(obj_child);	
		}
		
		flowplayer(player, "/player/flowplayer-3.2.7.swf");
	}
}
function changItem(obj,name,id)
{
	var v='';
	var items=document.getElementsByName(name);
	for(i=0;i<items.length;i++)
	{
		if(items[i].checked)
		{		
			$_(id).src=obj[items[i].value];
		}
	}
}
</script>
</head>

<body>
<div id="wrap">
	<?php include('inc/header.php'); ?>
	<div id="detail">
		<div id="customize">
			<div class="title">Measurement Profile</div>
            <form action="measurement.php" method="post">
            <?php
			if($measurement['IsFinish']){
			?>
            <div id="save_but" style="margin-bottom:10px"><input type="submit" name="sub" value="Save" /></div>
            <?php }?>
            <div class="contents">
            	<div id="tablist">
               	<div class="MeasurementBox">
                	<div class="box_l">
                    	<h3 class="box_column">setup your measurement profile</h3>
                        <div class="box_remark">Get started by filling in your height, weight, and age.</div>
                    </div>
                	<div class="box_r">
                    	<div class="panel_a_item" style="margin-top:90px;">
                        	<div class="l">HEIGHT</div>
                            <div class="r"><span><select name="HEIGHTFT" id="HEIGHTFT"><?php for($i=4;$i<8;$i++){?><option value="<?=$i?>"  <?=$measurement['HEIGHTFT']==$i?'selected="selected"':''?> ><?=$i?></option><?php } ?></select></span><span>ft</span><span><select name="HEIGHTIN" id="HEIGHTIN"><?php for($i=0;$i<12;$i++){?><option value="<?=$i?>" <?=$measurement['HEIGHTIN']==$i?'selected="selected"':''?> ><?=$i?></option><?php } ?></select></span><span>in</span></div>
                            <div class="clear"></div>
                        </div>
                        <div class="panel_a_item">
                        	<div class="l">WEIGHT</div>
                            <div class="r"><span><input type="text" name="WEIGHT" id="WEIGHT" value="<?=$measurement['WEIGHT']?>" class="w40" /></span><span>lbs</span></div>
                            <div class="clear"></div>
                        </div>
                        <div class="panel_a_item">
                        	<div class="l">AGE</div>
                            <div class="r"><span><input type="text" name="AGE" id="AGE" value="<?=$measurement['AGE']?>" class="w30" /></span><span>years old</span></div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<h3 class="box_column">body shape</h3>
                        <div class="box_remark">Please tell us a little bit about your body shape.</div>
                    </div>
                	<div class="box_r">
                        <div class="panel_b_item">
                        	<div class="img"><img src="/images/shoulders_normal.png" id="shoulders" /></div>
                            <div class="item_cloumn">SHOULDERS</div>
                            <div class="list">
                            	<ul>
                                <?php
									for($i=0;$i<count($shoulders);$i++)
									{
								?>
                                	<li><input type="radio" name="shoulders" value="<?=$i?>" <?=$measurement['shoulders']==$i || ($i==1 && $measurement['IsFinish']==0)?'checked="checked"':''?> onclick="changItem(shoulders_ary,'shoulders','shoulders')" /><?=$shoulders[$i]?></li>
                                <?php }?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="panel_b_item">
                        	<div class="img"><img src="/images/chest_normal.png" id="chest" /></div>
                            <div class="item_cloumn">CHEST</div>
                            <div class="list">
                            	<ul>
                                <?php
									for($i=0;$i<count($chest);$i++)
									{
								?>
                                	<li><input type="radio" name="chest" value="<?=$i?>" onclick="changItem(chest_ary,'chest','chest')" <?=$measurement['chest']==$i || ($i==1 && $measurement['IsFinish']==0)?'checked="checked"':''?> /><?=$chest[$i]?></li>
                                <?php }?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="panel_b_item">
                        	<div class="img"><img src="/images/stomach_average.png" id="stomach" /></div>
                            <div class="item_cloumn">STOMACH</div>
                            <div class="list">
                            	<ul>
                                 <?php
									for($i=0;$i<count($stomach);$i++)
									{
								?>
                                	<li><input type="radio" name="stomach" value="<?=$i?>" onclick="changItem(stomach_ary,'stomach','stomach')" <?=$measurement['stomach']==$i || ($i==1 && $measurement['IsFinish']==0)?'checked="checked"':''?> /><?=$stomach[$i]?></li>
                                <?php }?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="panel_b_item">
                        	<div class="img"><img src="/images/posture_normal.png" id="posture" /></div>
                            <div class="item_cloumn">POSTURE</div>
                            <div class="list">
                            	<ul>
                               <?php
									for($i=0;$i<count($posture);$i++)
									{
								?>
                                	<li><input type="radio" name="posture" value="<?=$i?>"  onclick="changItem(posture_ary,'posture','posture')"  <?=$measurement['posture']==$i || ($i==1 && $measurement['IsFinish']==0)?'checked="checked"':''?> /><?=$posture[$i]?></li>
                                <?php }?>
                                </ul>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Shirt Neck</h3>
                        <div class="box_remark">1.Wrap the tape around your neck where your shirt collar would be.</div>
                        <div class="box_remark">2.Imagine this is your actual shirt collar and adjust to your desired size.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="ShirtNeck" value="<?=$measurement['ShirtNeck']==''?'13.75':$measurement['ShirtNeck']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_2"></div>
                     	<div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                        <div class="remark_list">
                        <h3 class="box_column">Jacket/Shirt Length</h3>
                        <div class="box_remark"><span>1.</span>Pop Your Collar. Place the tape where the shoulder and neck seams meet.</div>
                        <div class="box_remark"><span>2.</span>Measure straight down to the desired length, usually around the thumb joint.</div></div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="JacketShirtLength" value="<?=$measurement['JacketShirtLength']==''?'2':$measurement['JacketShirtLength']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_3"></div>
                    	<div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Chest Size</h3>
                        <div class="box_remark"><span>1.</span>Measure around the widest part of your chest, usually around the nipples.</div><div class="box_remark"><span>2.</span>Leave room for 1 finger.</div>
                        </div>
                        
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="ChestSize" value="<?=$measurement['ChestSize']==''?'2':$measurement['ChestSize']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_4"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Stomach Size</h3>
                        <div class="box_remark"><span>1.</span>Measure around the widest part of your stomach, usually around the belly button.</div></div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="StomachSize" value="<?=$measurement['StomachSize']==''?'2':$measurement['StomachSize']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_5"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Jacket Hips</h3>
                        <div class="box_remark"><span>1.</span>Measure the widest part of your hips, usually where your bum peaks.</div>
                        <div class="box_remark"><span>2.</span>Measure snug with room for one finger.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="JacketHips" value="<?=$measurement['JacketHips']==''?'2':$measurement['JacketHips']?>" class="inputTxt" /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_6"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Shoulder Size</h3>
                        <div class="box_remark"><span>1.</span>Place the tape where the shoulder and armhole seam meet on a well-fitting shirt.</div>
                        <div class="box_remark"><span>2.</span>Measure straight across.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="ShoulderSize" value="<?=$measurement['ShoulderSize']==''?'2':$measurement['ShoulderSize']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_7"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">sleeve Length</h3>
                        <div class="box_remark"><span>1.</span>Place the tape where the shoulder and armhole seam meet on a well-fitting shirt..</div>
                        <div class="box_remark"><span>2.</span>Measure straight down your arm to your desired length.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="SleeveLength" value="<?=$measurement['SleeveLength']==''?'2':$measurement['SleeveLength']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_8"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Bicep Size</h3>
                        <div class="box_remark"><span>1.</span>At the top of the armpit, measure the width of your bicep.</div>
                        <div class="box_remark"><span>2.</span>Measure snug with room for one finger.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="BicepSize" value="<?=$measurement['BicepSize']==''?'2':$measurement['BicepSize']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_9"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Wrist Size</h3>
                        <div class="box_remark"><span>1.</span>Measure around the wrist bone.</div>
                        <div class="box_remark"><span>2.</span>Leave room for one finger.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="WristSize" value="<?=$measurement['WristSize']==''?'2':$measurement['WristSize']?>" class="inputTxt" /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_10"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Pants Length</h3>
                        <div class="box_remark"><span>1.</span>Start from the top of the pants' waistband.</div>
                        <div class="box_remark"><span>2.</span>Measure along the side pants seam to the bottom of your pants or roughly 1 to 1.5 inches from the ground.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="PantsLength" value="<?=$measurement['PantsLength']==''?'2':$measurement['PantsLength']?>" class="inputTxt" /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_11"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Waist</h3>
                        <div class="box_remark"><span>1.</span>Have the tape follow the top of your pants the whole way around.</div>
                        <div class="box_remark"><span>2.</span>Imagine the tape is your actual pants' waist and adjust to your desired snugness.</div>
                        <div class="box_remark"><span>3.</span>It is not uncommon for your measured size to be a few inches different from the label size in your pants.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="Waist" value="<?=$measurement['Waist']==''?'2':$measurement['Waist']?>" class="inputTxt" /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_12"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Crotch</h3>
                        <div class="box_remark"><span>1.</span>Place the tape at the middle of your waist.</div>
                        <div class="box_remark"><span>2.</span>Follow the crotch seam through your legs, up to the front of the pants.</div>
                        <div class="box_remark"><span>3.</span>Measure snug.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="Crotch" value="<?=$measurement['Crotch']==''?'2':$measurement['Crotch']?>" class="inputTxt" /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_13"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Thigh Size</h3>
                        <div class="box_remark"><span>1.</span>Starting at the top of your inseam, measure around your thigh.</div>
                        <div class="box_remark"><span>2.</span>Measure snug with room for one finger.</div>
                        </div>
                        <div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="ThighSize" value="<?=$measurement['ThighSize']==''?'2':$measurement['ThighSize']?>" class="inputTxt"/><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_14"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="MeasurementBox">
                	<div class="box_l">
                    	<div class="remark_list">
                        <h3 class="box_column">Knee Size</h3>
                        <div class="box_remark"><span>1.</span>Measure around your knee cap, snug, with room for one finger.</div>
                        </div>
                     	<div class="Rulerbox">
                        	<div class="ruler"><ul class="numbers"><li class="nobg"></li><?php for($i=0;$i<80;$i++){?> <li><?=$i?></li><?php }?></ul></div>
                            <div class="marker"><img src="images/tm_marker.gif" width="19" height="10" /></div>
                            <div class="sizebox"><input type="text" name="KneeSize" value="<?=$measurement['KneeSize']==''?'2':$measurement['KneeSize']?>" class="inputTxt"  /><label class="input_label" >"</label></div>
                        </div>
                    </div>
                	<div class="box_r">
                    	<div id="player_box_15"></div> 
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                </div>
            </div>
            <div class="button">
            	<button type="button" class="pre_but" id="pre_but" onclick="btnChange(16,0)">PREVIOUS</button>
                <button type="button" class="next_but" id="next_but" onclick="btnChange(16,1)">NEXT</button>
                <button type="submit" class="next_but" id="addcart_but">FINISH</button>
       		</div>
            <input type="hidden" name="module" value="add" />
            <input type="hidden" name="target" value="<?=$module?>" />
            </form>
		</div>
		<div class="clear"></div>
	</div>
    <script language="javascript">
    var aBox= $$$(document,"Rulerbox");
	var ruler_num= aBox.length;
	var aTxt = $$$(document,"inputTxt");
	var aUl = $$$(document,"numbers");
	var oUlArr = [];
	var txtArr = [];
	
	for(var $j=0;$j<aTxt.length;$j++)
	{
		var $value = aTxt[$j].value; // 标尺默认值
		var $result = parseInt($value/0.25);
		aUl[$j].style.left =  -$result*20 + "px";
		//CA.setCss(aUl[$i],{left:$result*20});
	}
	
	for(var $i=0;$i<ruler_num;$i++)
	{
		oUlArr.push($$$(document,"numbers")[$i]);
		txtArr.push($$$(document,"inputTxt")[$i]);
	}
    </script>
	<?php include('inc/footer.php'); ?>
</div>
</body>
</html>