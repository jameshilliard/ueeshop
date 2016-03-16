<?php
include('../../inc/site_config.php');
include('../../inc/set/ext_var.php');
include('../../inc/fun/mysql.php');
include('../../inc/function.php');
include('../../inc/manage/config.php');
include('../../inc/manage/do_check.php');

check_permit('product', 'product.add');

$ProId=(int)$_GET['ProId'];
$query_string=query_string('ProId');

$product_row=$db->get_one('product', "ProId='$ProId'");
$product_ext_row=$db->get_one('product_ext', "ProId='$ProId'");
$product_description_row=$db->get_one('product_description', "ProId='$ProId'");
$product_wholesale_price_row=$db->get_all('product_wholesale_price', "ProId='$ProId'", '*', 'Price desc, PId desc');

$save_dir=mk_dir(get_cfg('ly200.up_file_base_dir').'product/'.date('y_m_d/', $service_time));
$SmallPicPath=array();
if(get_cfg('product.pic_count')){
	for($i=0; $i<get_cfg('product.pic_count'); $i++){
		if(is_file($site_root_path.$product_row['PicPath_'.$i])){
			$dir=dirname($product_row['PicPath_'.$i]).'/';
			$file=str_replace('s_', '', basename($product_row['PicPath_'.$i]));
			$new_name=rand_code().'.'.get_ext_name($file);
			foreach(get_cfg('product.pic_size') as $key=>$value){
				@copy($site_root_path.$dir.$value.'_'.$file, $site_root_path.$save_dir.$value.'_'.$new_name);
			}
			@copy($site_root_path.$dir.$file, $site_root_path.$save_dir.$new_name);
			@copy($site_root_path.$product_row['PicPath_'.$i], $site_root_path.$save_dir.'s_'.$new_name);
			$SmallPicPath[]=$save_dir.'s_'.$new_name;
		}
	}
}

$db->insert('product', array(
		'CateId'			=>	(int)$product_row['CateId'],
		'Name'				=>	addslashes($product_row['Name']),
		'ItemNumber'		=>	addslashes($product_row['ItemNumber']),
		'Model'				=>	addslashes($product_row['Model']),
		'IsInIndex'			=>	(int)$product_row['IsInIndex'],
		'IsHot'				=>	(int)$product_row['IsHot'],
		'IsRecommend'		=>	(int)$product_row['IsRecommend'],
		'IsNew'				=>	(int)$product_row['IsNew'],
		'SoldOut'			=>	(int)$product_row['SoldOut'],
		'ColorId'			=>	addslashes($product_row['ColorId']),
		'SizeId'			=>	addslashes($product_row['SizeId']),
		'BrandId'			=>	(int)$product_row['BrandId'],
		'Stock'				=>	(int)$product_row['Stock'],
		'Weight'			=>	(float)$product_row['Weight'],
		'PicPath_0'			=>	$SmallPicPath[0],
		'PicPath_1'			=>	$SmallPicPath[1],
		'PicPath_2'			=>	$SmallPicPath[2],
		'PicPath_3'			=>	$SmallPicPath[3],
		'PicPath_4'			=>	$SmallPicPath[4],
		'PicPath_5'			=>	$SmallPicPath[5],
		'PicPath_6'			=>	$SmallPicPath[6],
		'PicPath_7'			=>	$SmallPicPath[7],
		'Alt_0'				=>	addslashes($product_row['Alt_0']),
		'Alt_1'				=>	addslashes($product_row['Alt_1']),
		'Alt_2'				=>	addslashes($product_row['Alt_2']),
		'Alt_3'				=>	addslashes($product_row['Alt_3']),
		'Alt_4'				=>	addslashes($product_row['Alt_4']),
		'Alt_5'				=>	addslashes($product_row['Alt_5']),
		'Alt_6'				=>	addslashes($product_row['Alt_6']),
		'Alt_7'				=>	addslashes($product_row['Alt_7']),
		'Price_0'			=>	(float)$product_row['Price_0'],
		'Price_1'			=>	(float)$product_row['Price_1'],
		'Price_2'			=>	(float)$product_row['Price_2'],
		'Price_3'			=>	(float)$product_row['Price_3'],
		'IsSpecialOffer'	=>	(int)$product_row['IsSpecialOffer'],
		'SpecialOfferPrice'	=>	(float)$product_row['SpecialOfferPrice'],
		'BriefDescription'	=>	addslashes($product_row['BriefDescription']),
		'SeoTitle'			=>	addslashes($product_row['SeoTitle']),
		'SeoKeywords'		=>	addslashes($product_row['SeoKeywords']),
		'SeoDescription'	=>	addslashes($product_row['SeoDescription']),
		'AccTime'			=>	(int)$product_row['AccTime'],
		'Customized'		=>	(int)$product_row['Customized'],
		'ColorCard'			=>	$product_row['ColorCard']
	)
);

$ProId=$db->get_insert_id();
$db->insert('product_description', array(
		'ProId'			=>	$ProId,
		'Description'	=>	addslashes($product_description_row['Description'])
	)
);

//保存批发价
if(get_cfg('product.price') && get_cfg('product.wholesale_price')){
	for($i=0; $i<count($product_wholesale_price_row); $i++){
		$qty=(int)$product_wholesale_price_row[$i]['Qty'];
		$price=(float)$product_wholesale_price_row[$i]['Price'];
		if($qty && $price){
			$db->insert('product_wholesale_price', array(
					'ProId'	=>	$ProId,
					'Qty'	=>	$qty,
					'Price'	=>	$price
				)
			);
		}
	}
}

//保存另外的语言版本的数据
if(count(get_cfg('ly200.lang_array'))>1){
	add_lang_field('product', array('Name', 'BriefDescription', 'SeoTitle', 'SeoKeywords', 'SeoDescription'));
	add_lang_field('product_description', 'Description');
	
	for($i=1; $i<count(get_cfg('ly200.lang_array')); $i++){
		$field_ext='_'.get_cfg('ly200.lang_array.'.$i);
		$NameExt=$product_row['Name'.$field_ext];
		$BriefDescriptionExt=$product_row['BriefDescription'.$field_ext];
		$SeoTitleExt=$product_row['SeoTitle'.$field_ext];
		$SeoKeywordsExt=$product_row['SeoKeywords'.$field_ext];
		$SeoDescriptionExt=$product_row['SeoDescription'.$field_ext];
		$db->update('product', "ProId='$ProId'", array(
				'Name'.$field_ext				=>	addslashes($NameExt),
				'BriefDescription'.$field_ext	=>	addslashes($BriefDescriptionExt),
				'SeoTitle'.$field_ext			=>	addslashes($SeoTitleExt),
				'SeoKeywords'.$field_ext		=>	addslashes($SeoKeywordsExt),
				'SeoDescription'.$field_ext		=>	addslashes($SeoDescriptionExt)
			)
		);
		
		if(get_cfg('product.description')){
			$DescriptionExt=$product_description_row['Description'.$field_ext];
			$db->update('product_description', "ProId='$ProId'", array(
					'Description'.$field_ext	=>	addslashes($DescriptionExt)
				)
			);
		}
	}
}

//保存扩展数据
$field=$field_type=array();
$data['ProId']=$ProId;
$columns=$db->show_columns('product_ext', 1);	//获取数据表的所有字段名称
foreach(get_cfg('product.ext') as $form_type=>$field_list){	//扩展参数，form_type=>表单类型，field_list=>表单各项配置值
	foreach($field_list as $field_name=>$field_cfg){	//field_name=>表单名称，field_cfg=>表单配置
		if(in_array($form_type, array('input_text', 'textarea', 'ckeditor'))){
			if($field_cfg[0]){	//多语言输入
				for($i=0; $i<count(get_cfg('ly200.lang_array')); $i++){
					$data[$field_name.lang_name($i, 1)]=addslashes($product_ext_row[$field_name.lang_name($i, 1)]);
					$field[]=$field_name.lang_name($i, 1);
					$field_type[]=$form_type;
				}
			}else{	//不区分语言输入
				$data[$field_name]=addslashes($product_ext_row[$field_name]);
				$field[]=$field_name;
				$field_type[]=$form_type;
			}
		}elseif(in_array($form_type, array('input_radio', 'input_checkbox', 'select'))){
			$data[$field_name]=addslashes($product_ext_row[$field_name]);
			$field[]=$field_name;
			$field_type[]=$form_type;
		}elseif($form_type=='input_file'){
			$data[$field_cfg]=addslashes($product_ext_row[$field_cfg]);
			$field[]=$field_cfg;
			$field_type[]=$form_type;
		}
	}
}
for($i=0; $i<count($field); $i++){	//添加字段
	if(!in_array($field[$i], $columns)){
		if($field_type[$i]=='textarea'){
			$f='varchar(255)';
		}elseif($field_type[$i]=='ckeditor'){
			$f='text';
		}else{
			$f='varchar(100)';
		}
		$db->query("alter table product_ext add $field[$i] $f");
	}
}
$db->insert('product_ext', $data);

set_page_url('product', "ProId='$ProId'", get_cfg('product.page_url'), 1);

save_manage_log('复制产品:'.addslashes($product_row['Name']));

js_location("mod.php?ProId=$ProId", get_lang('product.copy_success'));
exit;
?>