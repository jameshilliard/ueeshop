<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('html');

include('../../inc/manage/header.php');

$info_category=ouput_Category_to_Select('CateId', '', 'info_category', 'UId="0,"', 1, get_lang('ly200.select'));
$instance_category=ouput_Category_to_Select('CateId', '', 'instance_category', 'UId="0,"', 1, get_lang('ly200.select'));
$download_category=ouput_Category_to_Select('CateId', '', 'download_category', 'UId="0,"', 1, get_lang('ly200.select'));
$product_category=ouput_Category_to_Select('CateId', '', 'product_category', 'UId="0,"', 1, get_lang('ly200.select'));
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('html.html_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
<div class="list_form">
	<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
		<tr align="center" class="list_form_title" id="list_form_title">
			<td width="20%"><strong><?=get_lang('html.page_name');?></strong></td>
			<td width="60%"><strong><?=get_lang('html.option');?></strong></td>
			<td width="20%"><strong><?=get_lang('ly200.operation');?></strong></td>
		</tr>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.index');?></td>
			<td align="left"></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="index" name="filename"></td>
		</tr>
		</form>
		
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.article');?></td>
			<td align="left">
				<?=get_lang('ly200.select');?>: <select name='AId'>
					<option value="">--<?=get_lang('ly200.select');?>--</option>
					<?php
					$row=$db->get_all('article', '1', 'AId, Title', 'MyOrder desc,AId asc');
					for($i=0; $i<count($row); $i++){
					?>
					<option value='<?=$row[$i]['AId']?>'><?=$row[$i]['Title']?></option>
					<?php }?>
				</select>
			</td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="article" name="filename"></td>
		</tr>
		</form>
		
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.info_category');?></td>
			<td align="left"><?=get_lang('ly200.select').': '.$info_category;?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="info_category" name="filename"></td>
		</tr>
		</form>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.info_detail');?></td>
			<td align="left"><?=get_lang('ly200.select').': '.$info_category;?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="info_detail" name="filename"></td>
		</tr>
		</form>
		
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.instance_list');?></td>
			<td align="left"></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="instance_list" name="filename"></td>
		</tr>
		</form>
		<?php if($db->get_row_count('instance_category')){?>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.instance_category');?></td>
			<td align="left"><?=get_lang('ly200.select').': '.$instance_category;?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="instance_category" name="filename"></td>
		</tr>
		</form>
		<?php }?>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.instance_detail');?></td>
			<td align="left"><?=$db->get_row_count('instance_category')?(get_lang('ly200.select').': '.$instance_category):'';?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="instance_detail" name="filename"></td>
		</tr>
		</form>
		
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.download_list');?></td>
			<td align="left"></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="download_list" name="filename"></td>
		</tr>
		</form>
		<?php if($db->get_row_count('download_category')){?>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.download_category');?></td>
			<td align="left"><?=get_lang('ly200.select').': '.$download_category;?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="download_category" name="filename"></td>
		</tr>
		</form>
		<?php }?>
		
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.product_list');?></td>
			<td align="left">
				<?=get_lang('ly200.select');?>: <select name="PageType">
					<option value="">--<?=get_lang('ly200.select');?>--</option>
					<option value="All"><?=get_lang('html.all_list');?></option>
					<?php if(get_cfg('product.is_hot')){?><option value="IsHot"><?=get_lang('product.is_hot');?></option><?php }?>
					<?php if(get_cfg('product.is_recommend')){?><option value="IsRecommend"><?=get_lang('product.is_recommend');?></option><?php }?>
					<?php if(get_cfg('product.is_new')){?><option value="IsNew"><?=get_lang('product.is_new');?></option><?php }?>
					<?php if(get_cfg('product.price') && get_cfg('product.special_offer')){?><option value="IsSpecialOffer"><?=get_lang('product.special_offer');?></option><?php }?>
				</select>
			</td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="product_list" name="filename"></td>
		</tr>
		</form>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.product_category');?></td>
			<td align="left"><?=get_lang('ly200.select').': '.$product_category;?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="product_category" name="filename"></td>
		</tr>
		</form>
		<form action="html.php" method="get" target="html_iframe">
		<tr onMouseover="this.className='mouseover';" onMouseout="this.className='mouseout';" align="center">
			<td><?=get_lang('html.page.product_detail');?></td>
			<td align="left"><?=get_lang('ly200.select').': '.$product_category;?></td>
			<td><input type="submit" class="form_button" value="<?=get_lang('ly200.refer');?>"><input type="hidden" value="product_detail" name="filename"></td>
		</tr>
		</form>
	</table>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="html_process_table" style="display:none;">
	<tr>
		<td height="22" style="border:1px solid #ccc;"><iframe name="html_iframe" src="" frameborder="0" width="100%" height="200"></iframe></td>
	</tr>
</table>
<?php include('../../inc/manage/footer.php');?>