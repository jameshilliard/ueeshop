<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('country');

if($_POST['action']=='del_country'){
	check_permit('', 'country.del');
	if(count($_POST['del_CId'])){
		$CId=implode(',', $_POST['del_CId']);
		$db->delete('country', "CId in($CId)");
	}
	save_manage_log('删除国家地区');
	
	header('Location: index.php');
	exit;
}

if($_GET['action']=='reset'){
	check_permit('', 'country.reset');
	$db->query('truncate table country');
	$country_str='Andorra|United Arab Emirates|Afghanistan|Antigua and Barbuda|Anguilla|Albania|Armenia|Netherlands Antilles|Angola|Antarctica|Argentina|American Samoa|Austria|Australia|Aruba|Azerbaijan|Bosnia and Herzegovina|Barbados|Bangladesh|Belgium|Burkina Faso|Bulgaria|Bahrain|Burundi|Benin|Bermuda|Brunei|Bolivia|Brazil|Bahamas|Bhutan|Bouvet Island|Botswana|Belarus|Belize|Canada|Cocos Islands|Congo|Central African Republic|Congo|Switzerland|Cote D\'ivoire|Cook Islands|Chile|Cameroon|China|Colombia|Costa Rica|Cuba|Cape Verde|Christmas Island|Cyprus|Czech Republic|Germany|Djibouti|Denmark|Dominica|Dominican Republic|Algeria|Ecuador|Estonia|Egypt|Western Sahara|Eritrea|Spain|Ethiopia|Finland|Fiji|Falkland Islands|Micronesia|Faroe Islands|France|Gabon|United Kingdom|Grenada|Georgia|French Guiana|Ghana|Gibraltar|Greenland|Gambia|Guinea|Guadeloupe|Equatorial Guinea|Greece|South Georgia and The South Sandwich Islands|Guatemala|Guam|Guinea-Bissau|Guyana|Hong Kong|Heard Island and Mcdonald Islands|Honduras|Croatia|Haiti|Hungary|Indonesia|Ireland|Israel|India|British Indian Ocean Territory|Iraq|Iran|Iceland|Italy|Jamaica|Jordan|Japan|Kenya|Kyrgyzstan|Cambodia|Kiribati|Comoros|Saint Kitts and Nevis|Korea, Democratic People\'s Republic Of|Korea|Kuwait|Cayman African Republic|Kazakhstan|Laos|Lebanon|Saint Lucia|Liechtenstein|Sri Lanka|Liberia|Lesotho|Lithuania|Luxembourg|Latvia|Libyan Arab Jamahiriya|Morocco|Monaco|Moldova|Madagascar|Marshall Islands|Macedonia|Mali|Myanmar|Mongolia|Macao|Northern Mariana Islands|Martinique|Mauritania|Montserrat|Malta|Mauritius|Maldives|Malawi|Mexico|Malaysia|Mozambique|Namibia|New Caledonia|Niger|Norfolk Island|Nigeria|Nicaragua|Netherlands|Norway|Nepal|Nauru|Niue|New Zealand|Oman|Panama|Peru|French Polynesia|Papua New Guinea|Philippines|Pakistan|Poland|Saint Pierre and Miquelon|Pitcairn|Puerto Rico|Palestinian Territory, Occupied|Portugal|Palau|Paraguay|Qatar|Reunion|Romania|Serbia|Russia|Rwanda|Saudi Arabia|Solomon Islands|Seychelles|Sudan|Sweden|Singapore|Saint Helena|Slovenia|Svalbard and Jan Mayen|Slovakia|Sierra Leone|San Marino|Senegal|Somalia|Suriname|Sao Tome and Principe|El Salvador|Syrian Arab Republic|Swaziland|Turks and Caicos Islands|Chad|French Southern Territories|Togo|Thailand|Tajikistan|Tokelau|Turkmenistan|Tunisia|Tonga|East Timor|Turkey|Trinidad and Tobago|Tuvalu|TaiWan|Tanzania|Ukraine|Uganda|United States Minor Outlying Islands|United States Of America|Uruguay|Uzbekistan|Holy See (Vatican City State)|Saint Lucia|Venezuela|US Virgin Islands|US Virgin Islands|Viet Nam|Vanuatu|Wallis & Futuna Is|Western Samoa|Yemen|Mayotte|Yugoslavia|South Africa|Zambia|Zimbabwe';
	$country_ary=@explode('|', $country_str);
	for($i=0; $i<count($country_ary); $i++){
		$db->insert('country', 
				array('Country'=>addslashes($country_ary[$i])
			)
		);
	}
	
	if(count(get_cfg('ly200.lang_array'))>1){
		add_lang_field('country', 'Country');
		
		for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
			$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
			$db->query("update country set Country$field_ext=Country");
		}
	}
	
	save_manage_log('重置国家地区');
	
	header('Location: index.php');
	exit;
}

include('../../inc/manage/header.php');
?>
<div class="header">
	<div class="float_left"><?=get_lang('ly200.current_location');?>:<a href="index.php"><?=get_lang('country.country_manage');?></a>&nbsp;-&gt;&nbsp;<?=get_lang('ly200.list');?></div>
	<?php if(get_cfg('country.add') || get_cfg('country.reset')){?>
		<div class="float_right">
			<?php if(get_cfg('country.add')){?><a href="add.php"><?=get_lang('ly200.add');?></a><?php }?>
			<?php if(get_cfg('country.reset')){?>&nbsp;&nbsp;<a href="index.php?action=reset" class="red" onClick="if(!confirm('<?=get_lang('ly200.confirm_reset');?>')){return false;}else{return true}"><?=get_lang('ly200.reset');?></a><?php }?>
		</div>
	<?php }?>
</div>
<form name="list_form" id="list_form" class="list_form" method="post" action="index.php">
<table width="100%" border="0" cellpadding="0" cellspacing="1" id="mouse_trBgcolor_table" not_mouse_trBgcountry_tr='list_form_title'>
	<tr align="center" class="list_form_title" id="list_form_title">
		<td width="5%" nowrap><strong><?=get_lang('ly200.number');?></strong></td>
		<?php if(get_cfg('country.del')){?><td width="5%" nowrap><strong><?=get_lang('ly200.select');?></strong></td><?php }?>
		<td width="16%" nowrap><strong><?=get_lang('country.country');?></strong></td>
		<?php if(get_cfg('country.mod')){?><td width="8%" nowrap><strong><?=get_lang('ly200.operation');?></strong></td><?php }?>
	</tr>
	<?php
	$country_row=$db->get_all('country', 1, '*', 'Country asc, CId asc');
	for($i=0; $i<count($country_row); $i++){
	?>
	<tr align="center">
		<td nowrap><?=($i+1)?></td>
		<?php if(get_cfg('country.del')){?><td><input name="del_CId[]" type="checkbox" value="<?=$country_row[$i]['CId'];?>" /></td><?php }?>
		<td nowrap><?=list_all_lang_data($country_row[$i], 'Country');?></td>
		<?php if(get_cfg('country.mod')){?><td nowrap><a href="mod.php?CId=<?=$country_row[$i]['CId'];?>"><img src="../images/mod.gif" alt="<?=get_lang('ly200.mod');?>"></a></td><?php }?>
	</tr>
	<?php }?>
	<?php if(get_cfg('country.del') && count($country_row)){?>
	<tr>
		<td colspan="5" class="bottom_act">
			<?php if(get_cfg('country.del')){?>
				<input name="button" type="button" class="form_button" onClick='change_all("del_CId[]");' value="<?=get_lang('ly200.anti_select');?>">
				<input name="del_country" type="button" class="form_button" onClick="if(!confirm('<?=get_lang('ly200.confirm_del');?>')){return false;}else{click_button(this, 'list_form', 'action');};" value="<?=get_lang('ly200.del');?>">
			<?php }?>
			<input name="action" id="action" type="hidden" value="">
		</td>
	</tr>
	<?php }?>
</table>
</form>
<?php include('../../inc/manage/footer.php');?>