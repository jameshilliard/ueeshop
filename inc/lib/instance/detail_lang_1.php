<?php
$instance_img_width=400;	///成功案例图片宽度
$instance_img_height=300;	//成功案例图片高度
$contents_width=720;	//显示内容的div的宽度

!$instance_row && include($site_root_path.'/inc/lib/instance/get_detail_row.php');

ob_start();
?>
<div id="lib_instance_detail" style="width:<?=$contents_width;?>px;">
	<div class="info">
		<div class="img" style="width:<?=$instance_img_width;?>px; height:<?=$instance_img_height;?>px">
			<div><img src="<?=str_replace('s_', '400X300_', $instance_row['PicPath_0']);?>"/></div>
		</div>
		<div class="pro_info" style="width:<?=$contents_width-$instance_img_width-15;?>px;">
			<div class="proname"><?=$instance_row['Name'];?></div>
			<!---->
				<div class="item flh_180"><?=format_text($instance_row['BriefDescription']);?></div>
			<!---->
			<div class="item"></div>
		</div>
	</div>
	<div class="description">
		<div class="desc_nav">
			<div>Description</div>
		</div>
		<div class="desc_contents"><?=$db->get_value('instance_description', "CaseId='{$instance_row['CaseId']}'", 'Description');?></div>
	</div>
</div>
<?php
$instance_detail_lang_1=ob_get_contents();
ob_end_clean();
?>