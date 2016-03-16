<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('html');

include('../../inc/manage/header.php');
?>
<script language="javascript">
parent.$_('html_process_table').style.display='';
stop_scroll=0;
timer=setInterval('scroll_win();', 20);

function scroll_win(){
	window.scroll(0, 10000);
}

function over(){
	if(stop_scroll==0){
		clearInterval(timer);
	}
}

function out(){
	if(stop_scroll==0){
		timer=setInterval('scroll_win();', 20);
	}
}
</script>
<div class="flh_150" style="padding:8px;" onmouseover="over();" onmouseout="out();">
<?php
include('include.php');	//加载公共文件

$filename=$_GET['filename'];
echo get_lang('html.processing').'<br>';
include("php/$filename.php");
echo get_lang('html.process_success');
?>
</div>
<script language="javascript">
setTimeout('clearInterval('+timer+')', 200);
stop_scroll=1;
</script>
<?php include('../../inc/manage/footer.php');?>