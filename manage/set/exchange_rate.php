<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('exchange_rate');

//币种大全：http://www.ly200.com/help/currency.php
$currency_ary=array(
	'USD'	=>	'$',
	'EUR'	=>	'&#8364;',
	'GBP'	=>	'&#163;',
	'AUD'	=>	'AU$',
	'CAD'	=>	'CA$',
	'SGD'	=>	'S$',
	'JPY'	=>	'&#165;',
	'HKD'	=>	'HK$',
	'THB'	=>	'&#3647;',
	'TWD'	=>	'NT$',
);

if($_POST['list_form_action']=='exchange_rate_set'){
	$Default=$_POST['Default'];
	(int)$_POST['IsInvocation_'.$Default]!=1 && $_POST['IsInvocation_'.$Default]=1;
	if($Default==''){
		foreach($currency_ary as $key=>$value){
			if((int)$_POST['IsInvocation_'.$key]==1){
				$Default=$key;
				break;
			}
		}
	}
	if($Default==''){
		foreach($currency_ary as $key=>$value){
			$Default=$key;
			break;
		}
		$_POST['IsInvocation_'.$Default]=1;
	}
	$_POST['ExchangeRate_'.$Default]=1;
	
	$php_contents="<?php\r\n//汇率设置\r\n\r\n";
	$php_contents.="\$mCfg['ExchangeRate']['Default']='$Default';\r\n\r\n";
	
	foreach($currency_ary as $key=>$value){
		$IsInvocation=(int)$_POST['IsInvocation_'.$key];
		$ExchangeRate=abs(sprintf('%01.4f', $_POST['ExchangeRate_'.$key]));
		$ExchangeRate<=0 && $ExchangeRate=1;
		$php_contents.="\$mCfg['ExchangeRate']['$key']['Name']='$key';\r\n";
		$php_contents.="\$mCfg['ExchangeRate']['$key']['Symbols']='$value';\r\n";
		$php_contents.="\$mCfg['ExchangeRate']['$key']['Invocation']=$IsInvocation;\r\n";
		$php_contents.="\$mCfg['ExchangeRate']['$key']['Rate']=$ExchangeRate;\r\n\r\n";
	}
	$php_contents.='?>';
	write_file('/inc/set/', 'exchange_rate.php', $php_contents);
	
	save_manage_log('汇率设置');
	
	header('Location: exchange_rate.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header"><?=get_lang('ly200.current_location');?>:<a href="exchange_rate.php"><?=get_lang('set.exchange_rate.set');?></a></div>
<form name="list_form" id="list_form" class="list_form" method="post" action="exchange_rate.php"> 
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcolor_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="10%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<td width="18%" nowrap><strong><?=get_lang('ly200.name');?></strong></td>
		<td width="18%" nowrap><strong><?=get_lang('ly200.symbols');?></strong></td>
		<td width="18%" nowrap><strong><?=get_lang('ly200.invocation');?></strong></td>
		<td width="18%" nowrap><strong><?=get_lang('set.exchange_rate.rate');?> (<?=$mCfg['ExchangeRate'][$mCfg['ExchangeRate']['Default']]['Symbols'];?>1.00)</strong></td>
		<td width="18%" nowrap><strong><?=get_lang('ly200.default');?></strong></td>
	</tr>
	<?php
	$i=0;
	foreach($currency_ary as $key=>$value){
	?>
	<tr align="center">
		<td nowrap><?=$i+++1;?></td>
		<td nowrap><img src="/images/lib/currency/<?=$key;?>.jpg" align="absmiddle" /> <?=$key;?></td>
		<td nowrap><?=$value;?></td>
		<td><input type="checkbox" name="IsInvocation_<?=$key;?>" value="1" <?=$mCfg['ExchangeRate'][$key]['Invocation']==1?'checked':'';?> /></td>
		<td><input type="text" name="ExchangeRate_<?=$key;?>" size="5" maxlength="10" onkeyup="set_number(this, 1);" onpaste="set_number(this, 1);" value="<?=$mCfg['ExchangeRate'][$key]['Rate'];?>" class="form_input" <?=$key==$mCfg['ExchangeRate']['Default']?'disabled':'';?> /></td>
		<td><input type="radio" name="Default" value="<?=$key;?>" <?=$mCfg['ExchangeRate']['Default']==$key?'checked':'';?> onclick="click_button($_('exchange_rate_set'), 'list_form', 'list_form_action')" /></td>
	</tr>
	<?php }?>
	<tr>
		<td colspan="20" class="bottom_act">
			<input name="exchange_rate_set" id="exchange_rate_set" type="button" class="form_button" onClick="click_button(this, 'list_form', 'list_form_action')" value="<?=get_lang('ly200.submit');?>">
			<input name="list_form_action" id="list_form_action" type="hidden" value="">
		</td>
	</tr>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>