<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('database');

$tips_id=$_GET['tips_id'];
$step=(int)$_GET['step'];
$query_string=query_string('step');

include('../../inc/manage/header.php');

if($step==0){
?>
<script language="javascript">
	parent.$_('<?=$tips_id;?>').innerHTML='<?=get_lang('database.backup_ing');?>';
	setTimeout("window.location='backup.php?step=1&<?=$query_string;?>'", 1000);
</script>
<?php
	exit;
}elseif($step==1){
	@set_time_limit(0);
	$sql='';
	$file=1;
	$save_dir=get_cfg('database.save_dir').$service_time.'/';
	
	$tables=$db->query("show table status from $db_database");
	
	while($table=mysql_fetch_assoc($tables)){
		$sql.=make_header($table['Name']);
		$rs=$db->query("select * from {$table['Name']}");
		$num_fields=mysql_num_fields($rs);
		while($row=mysql_fetch_array($rs)){
			$sql.=make_record($table['Name'], $row, $num_fields);
			if(strlen($sql)>=get_cfg('database.file_size')){
				write_file($save_dir, 'f_'.sprintf('%03.0f', $file++).'.sql', $sql);
				$sql='';
			}
		}
	}
	
	$sql!='' && write_file($save_dir, 'f_'.sprintf('%03.0f', $file++).'.sql', $sql);
	
	save_manage_log('备份数据库');
	
	js_location("backup.php?step=2&$query_string");
}elseif($step==2){
	js_location("backup.php?step=3&$query_string");
}else{
?>
<script language="javascript">
	parent.$_('<?=$tips_id;?>').innerHTML='<?=get_lang('database.backup_ok');?><a href="index.php?detail_card=0" class="return_1 fz_14px"><?=get_lang('ly200.return');?></a>';
</script>
<?php
}

function make_header($table){
	global $db;
	$sql="DROP TABLE IF EXISTS $table;\n";
	$rs=$db->query("show create table $table");
	$row=mysql_fetch_assoc($rs);
	$sql.=preg_replace("/\n/", '', $row['Create Table']).";\n";
	return $sql;
}

function make_record($table, $row, $num_fields){
	global $db;
	$comma='';
	$sql="INSERT INTO $table VALUES(";
	for($i=0; $i<$num_fields; $i++){
		$sql.=($comma.'\''.mysql_escape_string($row[$i]).'\'');
		$comma=',';
	}
	$sql.=");\n";
	return $sql;
}

include('../../inc/manage/footer.php');
?>