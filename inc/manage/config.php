<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

//*********************************网站后台综合相关参数设置（start here）************************************************************
$Ly200Cfg=array(
	'ly200'			=>	array(
							'up_file_base_dir'	=>	'/u_file/',   //网站所有上传的文件保存的基本目录
							'price_symbols'		=>	$mCfg['ExchangeRate'][$mCfg['ExchangeRate']['Default']]['Symbols'],	//后台所有用到价格的符号
							'img_add_watermark'	=>	0,	//后台对上传的图片是否加水印
							'un_allow_up_ext'	=>	array('php', 'php3', 'php4', 'bat', 'com', 'exe', 'dll', 'asp', 'asa', 'cgi', 'jsp'),	//不允许上传的文件格式
							'lang_array'		=>	array('lang_0'),	//网站语言版本，第一项值为默认语言版本，语言版本名称于：$Ly200Lang['ly200']['array_lang']
						),
	
	//文章管理系统
	'info'			=>	array(
							'add'				=>	1,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'order'				=>	1,	//排序
							'move'				=>	1,	//移动
							'is_in_index'		=>	0,	//首页显示
							'is_hot'			=>	0,	//热点文章
							'ext_url'			=>	0,	//外部链接
							'author'			=>	0,	//作者
							'provenance'		=>	0,	//来源
							'burden'			=>	0,	//责任编辑
							'brief_description'	=>	0,	//简短介绍
							'acc_time'			=>	1,	//时间
							'upload_pic'		=>	0,	//上传图片
							'pic_width'			=>	160,//缩略图宽度
							'pic_height'		=>	120,//缩略图高度
							'seo_tkd'			=>	1,	//SEO Title,Keywords,Description
							'page_url'			=>	'Title',	//PageUrl值计算字段
							'page_count'		=>	20,	//列表页每页显示数量
							'category'			=>	array(
														'dept'			=>	1,	//级数
														'add'			=>	0,	//添加
														'mod'			=>	1,	//修改
														'mod_name'		=>	0,	//是否允许修改类别名称
														'del'			=>	0,	//删除
														'order'			=>	0,	//排序
														'description'	=>	0,	//类别说明
														'upload_pic'	=>	1,	//上传图片
														'pic_width'		=>	789,//缩略图宽度
														'pic_height'	=>	246,//缩略图高度
														'seo_tkd'		=>	1,	//SEO Title,Keywords,Description
														'page_url'		=>	'Category',	//PageUrl值计算字段
													),
						),
	
	//成功案例管理系统
	'instance'		=>	array(
							'add'				=>	1,	//添加
							'add_mode'			=>	0,	//添加模式，0：按语言版本分别发布，1：同时发布多语言
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'order'				=>	1,	//排序
							'move'				=>	1,	//移动
							'is_in_index'		=>	0,	//首页显示
							'is_classic'		=>	0,	//经典案例
							'pic_count'			=>	1,	//图片数量，0-5张，设置为0则不需上传图片
							'pic_alt'			=>	0,	//图片Alt
							'pic_size'			=>	array('400X300', 'default'=>'160X120'),	//缩略图尺寸
							'brief_description'	=>	0,	//简短介绍
							'seo_tkd'			=>	1,	//SEO Title,Keywords,Description
							'description'		=>	1,	//详细介绍
							'page_url'			=>	'Name',	//PageUrl值计算字段
							'page_count'		=>	20,	//列表页每页显示数量
							'category'			=>	array(
														'dept'			=>	1,	//级数
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'mod_name'		=>	1,	//是否允许修改类别名称
														'del'			=>	1,	//删除
														'order'			=>	1,	//排序
														'description'	=>	0,	//类别说明
														'upload_pic'	=>	0,	//上传图片
														'pic_width'		=>	160,//缩略图宽度
														'pic_height'	=>	120,//缩略图高度
														'seo_tkd'		=>	1,	//SEO Title,Keywords,Description
														'page_url'		=>	'Category',	//PageUrl值计算字段
													),
						),
	
	//下载管理系统
	'download'		=>	array(
							'add'				=>	1,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'order'				=>	1,	//排序
							'move'				=>	1,	//移动
							'upload_pic'		=>	0,	//上传图片
							'pic_width'			=>	160,//缩略图宽度
							'pic_height'		=>	120,//缩略图高度
							'brief_description'	=>	0,	//简短介绍
							'seo_tkd'			=>	0,	//SEO Title,Keywords,Description
							'description'		=>	0,	//详细介绍
							'page_url'			=>	'Name',	//PageUrl值计算字段
							'page_count'		=>	20,	//列表页每页显示数量
							'category'			=>	array(
														'dept'			=>	1,	//级数
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'mod_name'		=>	1,	//是否允许修改类别名称
														'del'			=>	1,	//删除
														'order'			=>	1,	//排序
														'description'	=>	0,	//类别说明
														'upload_pic'	=>	0,	//上传图片
														'pic_width'		=>	160,//缩略图宽度
														'pic_height'	=>	120,//缩略图高度
														'seo_tkd'		=>	1,	//SEO Title,Keywords,Description
														'page_url'		=>	'Category',	//PageUrl值计算字段
													),
						),
	
	//文章管理系统
	'article'		=>	array(
							'amdo'				=>	array(),	//允许添加，修改标题，删除，排序的分组
							'seo_tkd'			=>	1,	//SEO Title,Keywords,Description
							'page_url'			=>	'Title',	//PageUrl值计算字段
						),
	
	//产品管理系统
	'product'		=>	array(
							'add'				=>	1,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'copy'				=>	1,	//复制
							'order'				=>	1,	//排序
							'move'				=>	1,	//移动
							'item_number'		=>	1,	//编号
							'model'				=>	0,	//型号
							'is_in_index'		=>	1,	//首页显示
							'is_hot'			=>	0,	//热卖
							'is_recommend'		=>	0,	//推荐
							'is_new'			=>	0,	//新品
							'is_gift'			=>	1,	//赠品
							'sold_out'			=>	0,	//下架
							'color_ele'			=>	0,	//颜色，从product_color表选择
							'color_ele_mode'	=>	0,	//颜色选择模式，0：单选，1：多选
							'size_ele'			=>	0,	//尺寸，从product_size表选择
							'size_ele_mode'		=>	0,	//尺寸选择模式，0：单选，1：多选
							'brand_ele'			=>	0,	//品牌，从product_brand表单选
							'stock'				=>	0,	//库存
							'weight'			=>	1,	//重量
							'pic_count'			=>	5,	//图片数量，0-8张，设置为0则不需上传图片
							'pic_alt'			=>	0,	//图片Alt
							'pic_size'			=>	array('340X450', 'default'=>'220X330', '90X90'),	//缩略图尺寸（购物网站请一定要开启90*90的缩略图）
							'price'				=>	1,	//价格，以下3项为本项的扩展，关闭本项，即以下3项设置均失效
							'price_list'		=>	array('price_0'),	//价格名称下标列表，对应Price_0、Price_1、Price_2、Price_3字段，最多4个，价格名称由语言文件设置
							'Integral'			=>	1,	//积分
							'special_offer'		=>	0,	//特价
							'list_price'		=>	1,	//原价
							'wholesale_price'	=>	0,	//批发价，即多件优惠
							'wholesale_price_n'	=>	3,	//批发价默认选项数量
							'brief_description'	=>	1,	//简短介绍
							'seo_tkd'			=>	1,	//SEO Title,Keywords,Description
							'description'		=>	1,	//详细介绍
							'page_url'			=>	'Name',	//PageUrl值计算字段
							'page_count'		=>	20,	//列表页每页显示数量
							'customize'				=>	array(
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'del'			=>	1,	//删除
														'order'			=>	1,	//排序
														'upload_pic'	=>	1,	//上传图片
														'pic_width'		=>	130,//缩略图宽度
														'pic_height'	=>	130,//缩略图高度
													),
							'color'				=>	array(
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'del'			=>	1,	//删除
														'order'			=>	0,	//排序
														'upload_pic'	=>	0,	//上传图片
														'pic_width'		=>	55,//缩略图宽度
														'pic_height'	=>	55,//缩略图高度
													),
							'font'				=>	array(
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'del'			=>	1,	//删除
														'order'			=>	0,	//排序
														'upload_pic'	=>	1,	//上传图片
														'pic_width'		=>	55,//缩略图宽度
														'pic_height'	=>	55,//缩略图高度
													),
							'size'				=>	array(
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'del'			=>	1,	//删除
														'order'			=>	1,	//排序
													),
							'brand'				=>	array(
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'del'			=>	1,	//删除
														'order'			=>	1,	//排序
														'description'	=>	0,	//类别说明
														'upload_logo'	=>	0,	//上传图片
														'logo_width'	=>	160,//缩略图宽度
														'logo_height'	=>	120,//缩略图高度
														'seo_tkd'		=>	1,	//SEO Title,Keywords,Description
														'page_url'		=>	'Brand',	//PageUrl值计算字段
													),
							'category'			=>	array(
														'dept'			=>	2,	//级数
														'add'			=>	1,	//添加
														'mod'			=>	1,	//修改
														'mod_name'		=>	1,	//是否允许修改类别名称
														'del'			=>	1,	//删除
														'order'			=>	1,	//排序
														'description'	=>	0,	//类别说明
														'upload_pic'	=>	1,	//上传图片
														'pic_width'		=>	789,//缩略图宽度
														'pic_height'	=>	246,//缩略图高度
														'seo_tkd'		=>	1,	//SEO Title,Keywords,Description
														'page_url'		=>	'Category',	//PageUrl值计算字段
													),
							'ext'				=>	array(
														/*
														产品资料扩展参数定义，二维数组下标对应数据库的字段名称和表单名称，字段长度：input=char 100，select=char 100，textarea=char 255，ckeditor=text
														如项示例：
														文本框：		'Volume'	=>	array(0, 25, 50, array('')),
														单选框：		'Applicable'=>	array('电脑硬件|路由设备|打印机', '电脑硬件'),
														多选框：		'SalesArea'	=>	array('广东|广西|海南', '广东|海南'),
														文件框：		'Manual',
														下拉框：		'Materials'	=>	array('钢|铁|塑料|皮革', '塑料'),
														文本段：		'Feature'	=>	array(1, 5, 50, array('')),
														编辑器：		'Warranty'	=>	array(1, array('')),
														*/
														'input_text'	=>	array(	//input[type='text']，参数：是否多语言，表单长度，允许最多输入字符数，默认值
																			),
														'input_radio'	=>	array(	//input[type='radio']，参数：选项列表，默认值
																			),
														'input_checkbox'=>	array(	//input[type='checkbox']，参数：选项列表，默认值；表单名称将自动加上[]，所以如果只有一个选项的，如选择是与否，不要使用此功能
																			),
														'input_file'	=>	array(	//input[type='file']
																			),
														'select'		=>	array(	//下拉表单，参数：选项列表，默认值；本功能用单选功能同样可以实现，区别在于本项可以不选任何值，单选则不一定
																			),
														'textarea'		=>	array(	//textarea，参数：是否多语言，行数，列数，默认值
																			),
														'ckeditor'		=>	array(	//编辑器，参数：是否多语言，默认值
																			),
													),
						),
	
	//会员管理系统
	'member'		=>	array(
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//运费管理系统
	'shipping'		=>	array(
							'add'				=>	1,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'order'				=>	1,	//排序
							's_price_by_weight'	=>	1,	//是否按重量计运费
						),
	
	//付款方式管理系统
	'payment_method'=>	array(
							'mod'				=>	1,	//修改
							'order'				=>	1,	//排序
							'logo_width'		=>	135,//Logo宽度
							'logo_height'		=>	35,	//Logo高度
						),
	
	//订单管理系统
	'orders'		=>	array(
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//在线留言管理系统
	'feedback'		=>	array(
							'del'				=>	1,	//删除
							'reply'				=>	1,	//是否需要回复
							'display'			=>	0,	//前台显示控制
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//在线询盘管理系统
	'product_inquire'=>	array(
							'del'				=>	1,	//删除
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//邮件列表管理系统
	'newsletter'	=>	array(
							'del'				=>	1,	//删除
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//产品评论管理系统
	'product_review'=>	array(
							'del'				=>	1,	//删除
							'reply'				=>	1,	//是否需要回复
							'display'			=>	1,	//前台显示控制
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//国家地区管理系统
	'country'		=>	array(
							'add'				=>	1,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'reset'				=>	1,	//重置
						),
	
	//广告图片管理系统
	'ad'			=>	array(
							'add'				=>	0,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
						),
	
	//在线调查管理系统
	'survey'		=>	array(
							'add'				=>	0,	//添加，通常添加、删除、排序都不需要用到，因为只有一个调查主题
							'mod'				=>	1,	//修改
							'del'				=>	0,	//删除
							'order'				=>	0,	//排序
							'survey_item_n'		=>	5,	//添加时的默认选项数量
						),
	
	//友情链接管理系统
	'links'			=>	array(
							'add'				=>	1,	//添加
							'mod'				=>	1,	//修改
							'del'				=>	1,	//删除
							'order'				=>	1,	//排序
							'upload_logo'		=>	1,	//上传图片
							'logo_width'		=>	120,//缩略图宽度
							'logo_height'		=>	60,//缩略图高度
							'page_count'		=>	20,	//列表页每页显示数量
						),
	
	//日志管理系统
	'manage_log'	=>	array(
							'del'				=>	1,	//删除
							'page_count'		=>	40,	//列表页每页显示数量
						),
	
	//数据库管理系统
	'database'		=>	array(
							'del'				=>	1,	//删除备份文件
							'file_size'			=>	5*1024*1024,	//备份文件每卷的大小
							'save_dir'			=>	'/_mysql_backup/',	//文件保存基本目录
						),
);
//*********************************网站后台综合相关参数设置（end here）**************************************************************

//*********************************后台左菜单显示相关参数（start here）**************************************************************
$menu=array(
	'global_set'		=>	1,	//全局设置
	'orders_print_set'	=>	0,	//订单打印设置
	'send_mail_set'		=>	0,	//发送邮件设置
	'exchange_rate_set'	=>	0,	//汇率设置
	
	'info_category'		=>	1,	//文章类别
	'info'				=>	1,	//文章管理
	
	'instance_category'	=>	0,	//成功案例类别
	'instance'			=>	0,	//成功案例管理
	
	'download_category'	=>	0,	//下载中心类别
	'download'			=>	0,	//下载中心管理
	
	'article_group_0'	=>	1,	//信息页
	'article_group_1'	=>	0,	//信息页
	'article_group_2'	=>	0,	//信息页
	'article_group_3'	=>	0,	//信息页
	'article_group_4'	=>	0,	//信息页
	
	'product_color'		=>	0,	//产品颜色
	'product_size'		=>	0,	//产品尺寸
	'product_brand'		=>	0,	//产品品牌
	'product_category'	=>	1,	//产品类别
	'product_customize'	=>	1,	//产品定制参数
	'product'			=>	1,	//产品管理
	
	'member'			=>	1,	//会员管理
	'shipping'			=>	0,	//运费管理
	'payment_method'	=>	1,	//付款方式管理
	'orders'			=>	1,	//订单管理
	'send_mail'			=>	1,	//发送邮件
	
	'order_price_count'	=>	0,	//订单金额统计
	'order_price_trend'	=>	0,	//订单金额走势图
	'order_count'		=>	0,	//订单量统计
	'order_count_trend'	=>	0,	//订单量走势图
	'order_status_count'=>	0,	//订单状态统计
	'product_top_sale'	=>	0,	//产品销售排行
	
	'feedback'			=>	0,	//在线留言管理
	'product_inquire'	=>	0,	//在线询盘管理
	'newsletter'		=>	0,	//邮件列表管理
	'product_review'	=>	0,	//产品评论管理
	
	'admin'				=>	1,	//后台用户管理
	'admin_update_pwd'	=>	1,	//修改密码
	
	'country'			=>	0,	//国家地区管理
	'ad'				=>	1,	//广告管理
	'survey'			=>	0,	//在线调查管理
	'links'				=>	1,	//友情链接管理
	'html'				=>	0,	//静态化
	
	'manage_log'		=>	1,	//日志管理
	'database'			=>	1,	//数据库管理
	'phpmyadmin'		=>	1,	//phpmyadmin
);

$manage_menu=array(
	'set'		=>	array(
						'global_set'		=>	array('set/global.php', 'set.global'),
						'orders_print_set'	=>	array('set/orders_print.php', 'set.orders_print.set'),
						'send_mail_set'		=>	array('set/send_mail.php', 'set.send_mail.set'),
						'exchange_rate_set'	=>	array('set/exchange_rate.php', 'set.exchange_rate.set'),
					),
	'info'		=>	array(
						'info_category'		=>	array('info/category.php', 'info.category_manage'),
						'info'				=>	array('info/index.php', 'info.info_manage'),
					),
	'instance'	=>	array(
						'instance_category'	=>	array('instance/category.php', 'instance.category_manage'),
						'instance'			=>	array('instance/index.php', 'instance.instance_manage'),
					),
	'download'	=>	array(
						'download_category'	=>	array('download/category.php', 'download.category_manage'),
						'download'			=>	array('download/index.php', 'download.download_manage'),
					),
	'article'	=>	array(
						'article_group_0'	=>	array('article/index.php?GroupId=0', 'article.group_0'),
						'article_group_1'	=>	array('article/index.php?GroupId=1', 'article.group_1'),
						'article_group_2'	=>	array('article/index.php?GroupId=2', 'article.group_2'),
						'article_group_3'	=>	array('article/index.php?GroupId=3', 'article.group_3'),
						'article_group_4'	=>	array('article/index.php?GroupId=4', 'article.group_4'),
					),
	'product'	=>	array(
						'product_color'		=>	array('product/color.php', 'product.color_manage'),
						'product_size'		=>	array('product/size.php', 'product.size_manage'),
						'product_brand'		=>	array('product/brand.php', 'product.brand_manage'),
						'product_category'	=>	array('product/category.php', 'product.category_manage'),
						'product_customize' =>	array('product/customize.php', 'product.customize_manage'),
						'product'			=>	array('product/index.php', 'product.product_manage'),
					),
	'sale'		=>	array(
						'member'			=>	array('member/index.php', 'member.member_manage'),
						'shipping'			=>	array('shipping/index.php', 'shipping.shipping_manage'),
						'payment_method'	=>	array('payment_method/index.php', 'payment_method.payment_method_manage'),
						'orders'			=>	array('orders/index.php', 'orders.orders_manage'),
						'send_mail'			=>	array('send_mail/index.php', 'send_mail.send_mail_system'),
					),
	'count'		=>	array(
						'order_price_count'	=>	array('count/order_price_count.php', 'count.order_price_count'),
						'order_price_trend'	=>	array('count/order_price_trend.php', 'count.order_price_trend'),
						'order_count'		=>	array('count/order_count.php', 'count.order_count'),
						'order_count_trend'	=>	array('count/order_count_trend.php', 'count.order_count_trend'),
						'order_status_count'=>	array('count/order_status_count.php', 'count.order_status_count'),
						'product_top_sale'	=>	array('count/product_top_sale.php', 'count.product_top_sale'),
					),
	'feedback'	=>	array(
						'feedback'			=>	array('feedback/index.php', 'feedback.feedback_manage'),
						'product_inquire'	=>	array('product_inquire/index.php', 'product_inquire.product_inquire_manage'),
						'newsletter'		=>	array('newsletter/index.php', 'newsletter.newsletter_manage'),
						'product_review'	=>	array('product_review/index.php', 'product_review.product_review_manage'),
					),
	'admin'		=>	array(
						'admin'				=>	array('admin/index.php', 'admin.admin_manage'),
						'admin_update_pwd'	=>	array('admin/password.php', 'admin.update_password'),
					),
	'other'		=>	array(
						'country'			=>	array('country/index.php', 'country.country_manage'),
						'ad'				=>	array('ad/index.php', 'ad.ad_manage'),
						'survey'			=>	array('survey/index.php', 'survey.survey_manage'),
						'links'				=>	array('links/index.php', 'links.links_manage'),
						'html'				=>	array('html/index.php', 'html.html_manage'),
					),
	'ext'		=>	array(
						'manage_log'		=>	array('manage_log/index.php', 'manage_log.log_manage'),
						'database'			=>	array('database/index.php', 'database.database_manage'),
						'phpmyadmin'		=>	array('phpmyadmin/index.php', 'phpmyadmin.phpmyadmin', 1),
					)
);
//*********************************后台左菜单显示相关参数（start here）**************************************************************

//*********************************加载语言文件（start here）**********************************************************************
$_SESSION['ly_AdminLanguage']	=	'cn';
if(is_file($site_root_path."/inc/lang/{$_SESSION['ly_AdminLanguage']}.php")){
	include($site_root_path."/inc/lang/{$_SESSION['ly_AdminLanguage']}.php");
}else{
	include($site_root_path.'/inc/lang/cn.php');
}
//*********************************加载语言文件（end here）************************************************************************

//*********************************加载权限文件（start here）**********************************************************************
if((int)$_SESSION['ly_AdminUserId']){
	foreach($manage_menu as $group_key=>$group){
		foreach($group as $key=>$value){
			$menu[$key]==0 && $manage_menu[$group_key][$key]='';
		}
	}
	
	$tmp_Ly200Cfg=$Ly200Cfg;
	$tmp_menu=$menu;
	$tmp_manage_menu=$manage_menu;
	
}
//*********************************加载权限文件（end here）************************************************************************
?>