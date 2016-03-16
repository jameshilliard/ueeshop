<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product_color', 'product.color.mod');

if($_GET['action']=='delimg'){
	$CId=(int)$_POST['CId'];
	$PicPath=$_GET['PicPath'];
	del_file($PicPath);
	del_file(str_replace('s_', '', $PicPath));
	
	$db->update('product_color', "CId='$CId'", array(
			'PicPath'	=>	''
		)
	);
	
	$str=js_contents_code(get_lang('ly200.del_success'));
	echo "<script language=javascript>parent.document.getElementById('img_list').innerHTML='$str'; parent.document.getElementById('img_list_a').innerHTML='';</script>";
	exit;
}

if($_POST){
	$CId=(int)$_POST['CId'];	
	$Color=$_POST['Color'];
	$CateId=$_POST['CateId'];
	
	if(get_cfg('product.color.upload_pic')){
		$save_dir=get_cfg('ly200.up_file_base_dir').'product/color/'.date('y_m_d/', $service_time);
		$S_PicPath=$_POST['S_PicPath'];
		
		if($BigPicPath=up_file($_FILES['PicPath'], $save_dir)){
			include('../../inc/fun/img_resize.php');
			$SmallPicPath=img_resize($BigPicPath, '', get_cfg('product.color.pic_width'), get_cfg('product.color.pic_height'));
			del_file($S_PicPath);
			del_file(str_replace('s_', '', $S_PicPath));
		}else{
			$SmallPicPath=$S_PicPath;
		}
	}
	
	$db->update('product_color', "CId='$CId'", array(
			'Color'		=>	$Color,
			'PicPath'	=>	$SmallPicPath
		)
	);
	
	//保存另外的语言版本的数据
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('product_color', 'Color');
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$ColorExt=$_POST['Color'.$field_ext];
			$db->update('product_color', "CId='$CId'", array(
					'Color'.$field_ext	=>	$ColorExt
				)
			);
		}
	}
	
	save_manage_log('更新产品颜色:'.$Color);
	
	header('Location: color.php?CateId='.$CateId);
	exit;
}

$CId=(int)$_GET['CId'];
$color_row=$db->get_one('product_color', "CId='$CId'");
$row=$db->get_one('product_customize',"CId='{$color_row['CateId']}'");
include('../../inc/manage/header.php');
?>
<script>
var ColorHex=new Array('00','33','66','99','CC','FF')
var SpColorHex=new Array('FF0000','00FF00','0000FF','FFFF00','00FFFF','FF00FF')
var current=null

function getEvent()
{
	if(document.all)
	{
	   return window.event;//如果是ie
	}
	func=getEvent.caller;
	while(func!=null)
	{
	   var arg0=func.arguments[0];
	   if(arg0)
	   {
		if((arg0.constructor==Event || arg0.constructor ==MouseEvent)||(typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation))
		{
		 return arg0;
		}
	   }
	   func=func.caller;
	}
	return null;
}


function intocolor(id)
{
var colorpanel=document.getElementById('colorpanel');
var colorTable=''
for (i=0;i<2;i++)
{
for (j=0;j<6;j++)
   {
    colorTable=colorTable+'<tr height=12>'
    colorTable=colorTable+'<td width=11 style="background-color:#000000">'
    
    if (i==0){
    colorTable=colorTable+'<td width=11 style="background-color:#'+ColorHex[j]+ColorHex[j]+ColorHex[j]+'">'} 
    else{
    colorTable=colorTable+'<td width=11 style="background-color:#'+SpColorHex[j]+'">'}

    
    colorTable=colorTable+'<td width=11 style="background-color:#000000">'
    for (k=0;k<3;k++)
     {
       for (l=0;l<6;l++)
       {
        colorTable=colorTable+'<td width=11 style="background-color:#'+ColorHex[k+i*3]+ColorHex[l]+ColorHex[j]+'">'
       }
     }
}
}
colorTable='<table width=253 border="0" cellspacing="0" cellpadding="0" style="border:1px #000000 solid;border-bottom:none;border-collapse: collapse" bordercolor="000000">'
           +'<tr height=30><td colspan=21 bgcolor=#cccccc>'
           +'<table cellpadding="0" cellspacing="1" border="0" style="border-collapse: collapse">'
           +'<tr><td width="3"><td><input type="text" name="DisColor" id="DisColor" size="6" disabled style="border:solid 1px #000000;background-color:#ffff00"></td>'
           +'<td width="3"><td><input type="text" name="HexColor" id="HexColor" size="7" style="border:inset 1px;font-family:Arial;" value="#000000"></td></tr></table></td></table>'
           +'<table border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="000000" onmouseover="doOver()" onmouseout="doOut()" onclick="doclick(\''+id+'\')" style="cursor:hand;">'
           +colorTable+'</table>';          
colorpanel.innerHTML=colorTable;
colorpanel.style.display='';
}

function doOver() {
var evt=getEvent();
var element=evt.srcElement || evt.target;
var DisColor=document.getElementById("DisColor");
var HexColor=document.getElementById("HexColor");
if ((element.tagName=="TD") && (current!=element)) {
        if (current!=null){current.style.backgroundColor = current._background}     
        element._background = element.style.backgroundColor
        DisColor.style.backgroundColor = rgbToHex(element.style.backgroundColor)
        HexColor.value = rgbToHex(element.style.backgroundColor)
        element.style.backgroundColor = "white"
        current = element
    }
}

/** 
* firefox 的颜色是以(RGB())出现，进行转换
*/
function rgbToHex(aa)
{
if(aa.indexOf("rgb") != -1)
{
    aa=aa.replace("rgb(","")
    aa=aa.replace(")","")
    aa=aa.split(",")
    r=parseInt(aa[0]);
    g=parseInt(aa[1]);
    b=parseInt(aa[2]);
    r = r.toString(16);
    if (r.length == 1) { r = '0' + r; }
    g = g.toString(16);
    if (g.length == 1) { g = '0' + g; }
    b = b.toString(16);
    if (b.length == 1) { b = '0' + b; }
    return ("#" + r + g + b).toUpperCase();
}
else
{
    return aa;
}
}

function doOut() {

    if (current!=null) current.style.backgroundColor = current._background;
}

function doclick(id){

var evt=getEvent();
var element=evt.srcElement || evt.target;
if (element.tagName=="TD"){
   var bg=rgbToHex(element._background);
   document.getElementById(id).value=bg;
   document.getElementById('colorpanel').style.display='none';
}
}
</script>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$customize_aty[$row['CateId']]?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$row['CateId']?>"><?=$row['Name']?></a>&nbsp;-&gt;&nbsp;<a href="color.php?CateId=<?=$row['CId']?>"><?=get_lang('product.color');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.mod');?></div>
</div>
<form method="post" name="act_form" id="act_form" class="act_form" action="color_mod.php" enctype="multipart/form-data" onsubmit="return checkForm(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table">
	<?php for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){?>
		<tr>
			<td width="5%" nowrap><?=get_lang('product.color').lang_name($i, 0);?>:</td>
			<td width="95%"><input name="Color<?=lang_name($i, 1);?>" value="<?=htmlspecialchars($color_row['Color'.lang_name($i, 1)]);?>" class="form_input" type="text" size="30" maxlength="40" check="<?=get_lang('ly200.filled_out').get_lang('product.color');?>!~*" id="color" onClick="intocolor('color')">
            <div id="colorpanel" style="position: absolute;"></div><div style=" width:25px; height:25px; background:<?=$color_row['Color'.lang_name($i, 1)]?>"></div></td>
		</tr>
	<?php }?>
	<?php if(get_cfg('product.color.upload_pic')){?>
		<tr>
			<td nowrap><?=get_lang('ly200.photo');?>:</td>
			<td>
				<input name="PicPath" type="file" size="50" class="form_input" contenteditable="false"><br>
				<?php if(is_file($site_root_path.$color_row['PicPath'])){?>
				<iframe src="about:blank" name="del_img_iframe" style="display:none;"></iframe>
				<table border="0" cellspacing="0" cellpadding="0" style="margin-top:8px;">
					<tr>
						<td width="70" height="70" style="border:1px solid #ddd; background:#fff;" align="center" id="img_list"><a href="<?=str_replace('s_', '', $color_row['PicPath']);?>" target="_blank"><img src="<?=$color_row['PicPath'];?>" <?=img_width_height(70, 70, $color_row['PicPath']);?> /></a><input type='hidden' name='S_PicPath' value='<?=$color_row['PicPath'];?>'></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:4px;"><?=get_lang('ly200.photo');?><span id="img_list_a">&nbsp;<a href="color_mod.php?action=delimg&CId=<?=$CId;?>&PicPath=<?=$color_row['PicPath'];?>" target="del_img_iframe" class="blue">(<?=get_lang('ly200.del');?>)</a></span></td>
					</tr>
				</table>
				<?php }?>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="<?=get_lang('ly200.mod');?>" class="form_button"><a href='color.php' class="return"><?=get_lang('ly200.return');?></a><input type="hidden" name="CId" value="<?=$CId;?>"><input type="hidden" name="CateId" value="<?=$color_row['CateId']?>"></td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>