<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

$id=$_GET['id'];
$product_default=explode('|',$_GET['CustomizeDefault']);
$Font=$_GET['Font'];
$Color=$_GET['Color'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
window.onload=function()
{
	parent.document.getElementById('Customized_box').innerHTML=document.getElementById('Customized_box').innerHTML;
}
</script>
</head>

<body>
		
		<div id="Customized_box" class="customized" >
				<?php
				$customize_list=$db->get_all('product_customize',"CateId='$id'");
				for($j=0;$j<count($customize_list);$j++)
				{
				?>
					<div class="t"><?=$customize_list[$j]['Name']?></div>
					<?php
					if($customize_list[$j]['NoProduct']!=1)
					{
						$customize_item=$db->get_all('product_customize_item',"CId='{$customize_list[$j]['CId']}'");
						for($a=0;$a<count($customize_item);$a++)
						{
					?>
					<input type="radio" <?=$customize_item[$a]['IId']==$product_default[$j]?'checked="checked"':''?> value="<?=$customize_item[$a]['IId']?>" name="CustomizeDefault[<?=$j?>]" /><?=$customize_item[$a]['Name']?>
					<?php }?>
				<?php }else{?>
					
					<div class="sub_t">Font</div>
					<?php
						$font=$db->get_all('product_font',"CId='{$customize_list[$j]['CId']}'");
						for($a=0;$a<count($font);$a++)
						{
					?>
					<input type="radio" value="<?=$font[$a]['Font']?>" name="Font" <?=$font[$a]['Font']==$Font?'checked="checked"':''?> /><?=$font[$a]['Font']?>
					<?php }?>
					<div class="sub_t">Color</div>
					<?php
						$color=$db->get_all('product_color',"CateId='{$customize_list[$j]['CId']}'");
						for($a=0;$a<count($color);$a++)
						{
					?>
						<div class="color_item" style="background:<?=$color[$a]['Color']?>"><input type="radio" value="<?=$color[$a]['Color']?>" name="Color" <?=$color[$a]['Color']==$Color?'checked="checked"':''?> /></div>
					<?php }?>
					<div class="clear"></div>
				<?php 
					}
				}?>
				</div>
</body>
</html>