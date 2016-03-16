<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('database');

$step=(int)$_GET['step'];
$tips_id=$_GET['tips_id'];
$dir=$_GET['dir'];
$file_count=(int)$_GET['file_count'];
$cur_file=(int)$_GET['cur_file'];
!$cur_file && $cur_file=1;
$query_string=query_string(array('step', 'cur_file'));

include('../../inc/manage/header.php');

if($step==0){
?>
<script language="javascript">
	parent.$_('<?=$tips_id;?>').innerHTML='<?=get_lang('database.restore_ing');?>';
	setTimeout("window.location='restore.php?step=1&<?=$query_string;?>'", 1000);
</script>
<?php
	exit;
}elseif($step==1){
	@set_time_limit(0);
	
	if($cur_file<=$file_count){
		restore_data(get_cfg('database.save_dir').$dir.'/', 'f_'.sprintf('%03.0f', $cur_file).'.sql');
?>
		<script language="javascript">
			parent.$_('<?=$tips_id;?>').innerHTML='<?=sprintf(get_lang('database.restore_ing_limit'), $cur_file++, $file_count);?>';
			setTimeout("window.location='restore.php?step=1&cur_file=<?=$cur_file;?>&<?=$query_string;?>'", 500);
		</script>
<?php
		exit;
	}

	save_manage_log('恢复数据库');
	
	js_location("restore.php?step=2&$query_string");
}elseif($step==2){
	js_location("restore.php?step=3&$query_string");
}else{
?>
<script language="javascript">
	parent.$_('<?=$tips_id;?>').innerHTML='<?=get_lang('database.restore_ok');?><a href="index.php?detail_card=1" class="return_1 fz_14px"><?=get_lang('ly200.return');?></a>';
</script>
<?php
}

//----------------------------------------------------------------------------------------------------------------------------------------------------

function restore_data($dir, $file){
	global $site_root_path, $db;
	
	$sql=@file($site_root_path.$dir.$file);
	$query='';
	
	foreach($sql as $value){
		$value=trim($value);
		if(!$value || $value[0]=='#'){	//忽略记录为空或者以#开头的行
			continue;
		}
		
		if(eregi(';$', $value)){	//以;为结尾的行
			$query.=$value;
			$db->query($query);
			$query='';
		}else{
			$query.=$value;
		}
	}
}

include('../../inc/manage/footer.php');
?>