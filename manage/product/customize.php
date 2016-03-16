<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');
include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="customize.php"><?=get_lang('product.customize_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgsize_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td width="16%" nowrap><strong>名称</strong></td>
		<td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td>
	</tr>
	<?php
	for($i=0; $i<count($customize_aty); $i++){
	?>
	<tr align="center">
		<td nowrap><?=($i)?></td>
		<td nowrap><?=$customize_aty[$i];?></td>
		<td nowrap><a href="customize_list.php?CateId=<?=$i?>">列表</a></td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>