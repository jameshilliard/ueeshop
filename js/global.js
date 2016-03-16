/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var doc=document.documentElement || document.body;
var isIe=(document.all)?true:false;
var ie_version;
if(isIe){
	var version=navigator.appVersion.split(';');
	var trim_version=version[1].replace(/[ ]/g, '');
	if(trim_version=='MSIE7.0'){
		ie_version=7;
	}else if(trim_version=='MSIE6.0'){
		ie_version=6;
	}
}

try{document.execCommand('BackgroundImageCache', false, true);}catch(e){};

function $_(obj){
	return document.getElementById(obj)?document.getElementById(obj):'';
}

function set_number(obj, float){
	p=float==1?/[^\d-.]/g:/[^\d]/g;
	obj.value=obj.value.replace(p, '');
}

function product_review_show_star(v){
	for(i=1; i<=5; i++){
		$_('rating_'+i).src='/images/lib/product/x1.jpg';
	}
	for(i=1; i<=v; i++){
		$_('rating_'+i).src='/images/lib/product/x0.jpg';
	}
}

//-----------------------------------------------------------------------------ajax---------------------------------------------------------------------------

var ajax_pools_array=new Array();

function request_data(){
	var axc=false;
	if(window.XMLHttpRequest){	//Mozilla浏览器
		axc=new XMLHttpRequest();
		(axc.overrideMimeType) && (axc.overrideMimeType('text/xml'));	//设置MiME类别
	}else if(window.ActiveXObject){	//IE浏览器
		try{
			axc=new ActiveXObject('Msxml3.XMLHTTP');
		}catch(e){ 
			try{ 
				axc=new ActiveXObject('Msxml2.XMLHTTP'); 
			}catch(e){
				try{
					axc=new ActiveXObject('Microsoft.XMLHTTP');
				}catch(e){}
			}
		}
	}
	return axc;
}

function get_ajax_obj(){
	ajax_pools_array[ajax_pools_array.length]=new request_data();
    return ajax_pools_array[ajax_pools_array.length-1];
}