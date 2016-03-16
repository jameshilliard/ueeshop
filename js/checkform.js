/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791

定义格式：提示语~格式|格式提示语*

其中*为可选，如果不定义，则允许为空
格式同样为可选项，格式列表如下：
1、数字f		数字后加f，如：3f，表示长度必须为3位
2、数字m		数字后加:，如：3m，表示长度必须为3位或3位以上
3、date		日期检测
4、=表单名称	检测当前表单的值是否为“表单名称”的值，通常用于确认密码检测
5、email		Email检测
6、mobile	大陆手机号码检测，不包含国际区码86，检测范围：130-139，150-153，155-159，180，185-189
7、tel		大陆电话号码检测，不包含国际区码86，格式如：020-83226791或02083226791

注：格式提示语可使用表单值，用{value}表示，如：请正确填写邮件地址！~email|您输入的值：{value}，这不是一个合法的邮件地址*
*/

var first_error_input=null;

function checkForm(fm){
	if($_('ly200DivMask') || $_('ly200PopInfoTips')){
		return false;
	}
	
	var error_info=new Array;
	var submit_button=null;
	first_error_input=null;
	
	for(var i=0; i<fm.length; i++){
		fm[i].type.toLowerCase()=='submit' && (submit_button=fm[i]);
		fm[i].className=fm[i].className.replace(' form_focus', '');
		
		var check=isIe?fm[i].check:fm[i].getAttribute('check');
		if(check==null || check=='undefined'){
			continue;	//忽略未定义check的元素
		}
		
		var format_pos=check.lastIndexOf('~');
		if(format_pos<0){
			continue;
		}
		
		var tips=check.substring(0, format_pos);	//提示信息
		var format_str=check.substring(format_pos+1, check.length);	//格式要求
		
		if(format_str.charAt(format_str.length-1)=='*'){	//不允许为空
			var notNull=true;	//不允许为空
			var format=format_str.substring(0, format_str.length-1);	//格式
		}else{
			var notNull=false;	//允许为空
			var format=format_str.substring(0, format_str.length);	//格式
		}
		
		if(notNull==false && format==''){	//允许为空并且不需要格式检查
			continue;
		}
		
		var value=fm[i].value=trim(fm[i].value);	//内容去除空格
		if(value=='' && notNull){
			error_info[error_info.length]=tips;
			fm[i].className=fm[i].className+' form_focus';
			first_error_input==null && fm[i].type.toLowerCase()!='hidden' && (first_error_input=fm[i]);
			continue;
		}else if(format=='' || value==''){
			continue;
		}
		
		var format_need=format.substring(0, format.lastIndexOf('|'));
		var format_need_first_char=format_need.charAt(0);
		var format_need_last_char=format_need.charAt(format_need.length-1);
		var format_tips=format.substring(format.lastIndexOf('|')+1, format.length).replace('{value}', '<font class="fc_red">'+value+'</font>');
		(format_tips=='') && (format_tips=tips);
		
		if(format_need_last_char=='f' || format_need_last_char=='m'){	//以f或m结尾，可能是需要进行长度检查的
			var fromat_need_length=format_need.substring(0, format_need.length-1);
			if(!isNaN(fromat_need_length) && (format_need_last_char=='f' && value.length!=fromat_need_length) || (format_need_last_char=='m' && value.length<fromat_need_length)){
				error_info[error_info.length]=format_tips;
				fm[i].className=fm[i].className+' form_focus';
				first_error_input==null && fm[i].type.toLowerCase()!='hidden' && (first_error_input=fm[i]);
			}
		}else if(format_need=='date'){	//日期格式检查
			var found=value.match(/(\d{1,5})-(\d{1,2})-(\d{1,2})/);
			if(found!=null){
				var year=trim_0(found[1]);
				var month=trim_0(found[2])-1;
				var date=trim_0(found[3]);
				var d=new Date(year, month, date);
			}
			if(found==null || found[0]!=value || found[2]>12 || found[3]>31 || d.getFullYear()!=year || d.getMonth()!=month || d.getDate()!=date){
				error_info[error_info.length]=format_tips;
				fm[i].className=fm[i].className+' form_focus';
				first_error_input==null && fm[i].type.toLowerCase()!='hidden' && (first_error_input=fm[i]);
			}
		}else if((format_need_first_char=='=' && trim(fm[format_need.substring(1, format_need.length)].value)!=value) || (format_need=='email' && !/([\w][\w-\.]*@[\w][\w-_]*\.[\w][\w\.]+)/g.test(value)) || (format_need=='mobile' && !(/^13\d{9}$/g.test(value) || /^15[0-35-9]\d{8}$/g.test(value) || /^18[05-9]\d{8}$/g.test(value))) || (format_need=='tel' && !(/^\d{3,4}-{0,1}\d{7,8}$/g.test(value)))){	//检测是否与某字段的值相等，邮件格式检查，手机号码检测，电话号码检测
			error_info[error_info.length]=format_tips;
			fm[i].className=fm[i].className+' form_focus';
			first_error_input==null && fm[i].type.toLowerCase()!='hidden' && (first_error_input=fm[i]);
		}
	}
	
	if(error_info.length){
		alert_tips(error_info);
		return false;
	}else{
		submit_button!=null && (submit_button.disabled=true);
		return true;
	}
}

function trim(str){	//除去字符串变量两端的空格
	return str.replace(/^ */, '').replace(/ *$/, '');
}

function trim_0(str){	//除去字符串表示的数值变量开头的所有的0
	if(str.length==0){
		return str;
	}
	str=str.replace(/^0*/, '');
	str.length==0 && (str='0');
	return str;
}

function alert_tips(error_info){	//弹出信息
	var info='';
	e=clearRepeat(error_info);
	for(var i=0; i<e.length; i++){
		info+='&#8226; '+e[i]+'<br />';
	}
	
	ly200DivMask=document.createElement('div');
	ly200DivMask.setAttribute('id', 'ly200DivMask');
	ly200DivMask.style.cssText='z-index:1000; background-color:#000; filter:alpha(opacity=40); opacity:0.4; -moz-opacity:0.4; left:0px; top:0px; position:absolute;';
	ly200DivMask.style.height=(document.documentElement.scrollHeight>document.documentElement.clientHeight?document.documentElement.scrollHeight:document.documentElement.clientHeight)+'px';
	ly200DivMask.style.width=document.documentElement.scrollWidth+'px';
	document.body.appendChild(ly200DivMask);
	
	ly200PopInfoTips=document.createElement('div');
	ly200PopInfoTips.setAttribute('id', 'ly200PopInfoTips');
	ly200PopInfoTips.style.cssText='position:absolute; z-index:1001; border:4px solid #333; background:#fff; left:0px; top:0px; width:550px;';
	ly200PopInfoTips.innerHTML='\
		<div style="height:25px; line-height:25px; background:#f1f1f1; font-size:14px; font-weight:bold; text-indent:8px; color:#FF6600;">'+Ly200JsLang._windows._tips+'</div>\
		<div style="padding:8px;"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td style="font-size:14px; line-height:150%; height:50px;" valign="top">'+info+'</td></tr></table></div>\
		<div style="height:34px; padding-right:6px; background:#ccc; text-align:right;"><input type="button" id="PopInfoTipsCloseButton" value="'+Ly200JsLang._windows._close+'" onclick="close_PopInfoTips();" style="width:72px; height:22px; line-height:22px; text-align:center; border:1px solid #000; display:block; float:right; background:#eee; font-weight:bold; color:#333; cursor:pointer; margin-top:6px;"></div>';
	document.body.appendChild(ly200PopInfoTips);
	scroll_ly200PopInfoTips();	//先马上执行一次，要不看起来会在左上角飞到中间来
	scroll_ly200PopInfoTips_timer=setInterval('scroll_ly200PopInfoTips()', 50);
	$_('PopInfoTipsCloseButton').focus();
	$_('PopInfoTipsCloseButton').blur();
	
	document.onkeyup=function(evt){
		evt=evt||event;
		key=evt.keyCode||evt.which||evt.charCode;
		key==27 && close_PopInfoTips();
	}
}

function scroll_ly200PopInfoTips(){	//弹出框跟随滚动
	ly200PopInfoTips.style.left=document.documentElement.scrollLeft+document.documentElement.clientWidth/2-ly200PopInfoTips.offsetWidth/2+'px';
	ly200PopInfoTips.style.top=document.documentElement.scrollTop+document.documentElement.clientHeight/2-ly200PopInfoTips.offsetHeight/2+'px';
}

function close_PopInfoTips(){	//关闭弹出窗口
	first_error_input!=null && first_error_input.focus();
	document.body.removeChild(ly200DivMask);
	document.body.removeChild(ly200PopInfoTips);
	document.onkeyup=null;
	clearInterval(scroll_ly200PopInfoTips_timer);
}

function clearRepeat(array){   	//清除数组重复项
	for(var i=0; i<array.length; i++){
		for(var j=i+1; j<array.length; j++){
			if(array[j]===array[i]){
				array.splice(j, 1);
				j--;
			}
		}
	}
	return array;
}