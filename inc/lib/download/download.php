<?php
include('../../site_config.php');
include('../../set/ext_var.php');
include('../../function.php');
include('../../fun/mysql.php');

$DId=(int)$_GET['DId'];
$download_row=$db->get_one('download', "DId='$DId'", 'FilePath, FileName');
down_file($download_row['FilePath'], $download_row['FileName']);
?>