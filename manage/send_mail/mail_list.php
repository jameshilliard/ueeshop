<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('send_mail');

$row_count=$db->get_row_count('member', '1');
$total_pages=ceil($row_count/get_cfg('member.page_count'));
$page=(int)$_GET['page'];
$page<1 && $page=1;
$page>$total_pages && $page=1;
$start_row=($page-1)*get_cfg('member.page_count');
$member_row=$db->get_limit('member', '1', 'Email, FirstName, LastName', 'MemberId desc', $start_row, get_cfg('member.page_count'));

include('../../inc/manage/header.php');
?>
<div id="page_contents">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <?php for($i=0; $i<count($member_row); $i++){?>
	  <tr>
		<td style="border-bottom:1px solid #ccc;" height="30"><input type="checkbox" name="MailList[]" value="<?=htmlspecialchars($member_row[$i]['Email'].'/'.$member_row[$i]['FirstName'].' '.$member_row[$i]['LastName']);?>" /><a href="javascript:void(0);" onclick="this.blur(); add_email_address(this.innerHTML);"><?=htmlspecialchars($member_row[$i]['Email'].'/'.$member_row[$i]['FirstName'].' '.$member_row[$i]['LastName']);?></a></td>
	  </tr>
	  <?php }?>
	</table>
	<form method="get" class="turn_page_form" action="mail_list.php" onsubmit="javascript:turn_page(this);" target="mail_list_iframe">
		<?=str_replace('<a', '<a target="mail_list_iframe"', turn_page($page, $total_pages, 'mail_list.php?page=', $row_count, get_lang('ly200.pre_page'), get_lang('ly200.next_page')));?>
		<?=get_lang('ly200.turn');?>:<input name="page" id="page" type="text" size="2" maxlength="5" class="form_input">&nbsp;<input name="submit" type="submit" class="form_button" value="<?=get_lang('ly200.turn');?>">
		<input name="total_pages" id="total_pages" type="hidden" value="<?=$total_pages;?>">
		<input name="query_string" type="hidden" value="<?=$query_string;?>">
	</form>
</div>
<script language="javascript">
parent.$_('mail_list').innerHTML=$_('page_contents').innerHTML;
</script>
<?php include('../../inc/manage/footer.php');?>