/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791

各种即时通链接形式：
MSN：<a href="msnim:chat?contact=MSN帐号"><img src="/images/lib/service/msn.jpg" border="0"></a>
QQ：<a href="http://wpa.qq.com/msgrd?v=3&uin=QQ号码&menu=yes" target="_blank"><img src="http://wpa.qq.com/pa?p=2:QQ号码:1"></a>
旺旺（贸易通版）：<a href="http://scs1.sh1.china.alibaba.com/msg.atc?v=1&uid=旺旺帐号" target="_blank"><img src="http://scs1.sh1.china.alibaba.com/online.atc?v=1&uid=旺旺帐号&s=2"></a>
旺旺（淘宝版）：<a target="_blank" href="http://amos1.taobao.com/msg.ww?v=2&uid=旺旺帐号&s=1"><img src="http://amos1.taobao.com/online.ww?v=2&uid=旺旺帐号&s=1"></a>
Skype：<a href="skype:Skype帐号?call"><img src="http://mystatus.skype.com/smallclassic/yisung"></a>
雅虎通：<a href="http://edit.yahoo.com/config/send_webmesg?.target=帐号&.src=pg"><img src="http://opi.yahoo.com/online?u=帐号&m=g&t=2&l=cn"></a>

具体请看：http://www.ly200.com/help/im.php
*/

document.write('\
<div id="service_s_0" style="position:absolute; width:88px; top:0; left:0; z-index:9999; display:none;">\
	<div><img src="/images/lib/service/top.gif"></div>\
	<div style="background:#fff; border-left:1px solid #d7d7d7; border-right:1px solid #d7d7d7;">\
		<table border="0" cellpadding="5" cellspacing="0" width="90%" align="center">\
			<tr>\
				<td align="center" style="border-bottom:1px solid #d7d7d7;"><a href="msnim:chat?contact=caiyuzhuan@126.com"><img src="/images/lib/service/msn.jpg" border="0"></a></td>\
			</tr>\
			\
			<tr>\
				<td align="center" style="border-bottom:1px solid #d7d7d7;">\<a href="http://wpa.qq.com/msgrd?v=3&uin=3095024&menu=yes" target="_blank"><img src="http://wpa.qq.com/pa?p=2:3095024:1"></a>\</td>\
			</tr>\
			<tr>\
				<td align="center" style="border-bottom:1px solid #d7d7d7;">\<a href="http://wpa.qq.com/msgrd?v=3&uin=53408469&menu=yes" target="_blank"><img src="http://wpa.qq.com/pa?p=2:53408469:1"></a>\</td>\
			</tr>\
		</table>\
	</div>\
	<div style="margin-top:-1px;"><a href="#" onclick="this.blur();"><img src="/images/lib/service/buttom.gif" alt="返回顶端"></a></div>\
</div>');

setInterval('service_s_0_move()', 50);

function service_s_0_move(){
	if(doc.clientWidth>1000){
		$_('service_s_0').style.top=doc.scrollTop+150+'px';
		$_('service_s_0').style.left=doc.scrollLeft+doc.clientWidth-100+'px';
		$_('service_s_0').style.display='block';
	}else{
		$_('service_s_0').style.display='none';
	}
}