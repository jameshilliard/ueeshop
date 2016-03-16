<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');


$CateId=(int)$_GET['CateId'];
$list_row=$db->get_all('product_customize',"CateId='$CateId'",'*');

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<a href="customize_list.php?CateId=<?=$CateId?>"><?=$customize_aty[$CateId]?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
    <div class="float_right" style="display:none"><a href="customize_add.php?CateId=<?=$CateId?>"><?=get_lang('ly200.add');?></a></div>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="customize.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgsize_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td width="16%" nowrap><strong>名称</strong></td>
		<td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	for($i=0; $i<count($list_row); $i++){
	?>
	<tr align="center">
    <td nowrap><?=($i+1)?></td>
    <td nowrap><?=$list_row[$i]['Name'];?></td>
    <td nowrap>
    <?php
		if($list_row[$i]['NoProduct']!=1)
		{
	?>
     <a href="customize_mod.php?CId=<?=$list_row[$i]['CId']?>">编辑</a>&nbsp;&nbsp;<a href="customize_item_list.php?CId=<?=$list_row[$i]['CId']?>">列表</a>
    <?php
		}else
		{
	?>
    	 <a href="font.php?CId=<?=$list_row[$i]['CId']?>">字体</a>&nbsp;&nbsp;<a href="color.php?CateId=<?=$list_row[$i]['CId']?>">颜色</a>
    <?php		
		}
	?>
   </td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>