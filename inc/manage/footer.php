<script language="javascript">
var obj=$_('mouse_trBgcolor_table');
if(obj){
	var not_mouse_trBgcolor_tr_Value='|'+obj.getAttribute('not_mouse_trBgcolor_tr')+'|';
	var tr_obj=obj.getElementsByTagName('tr');
	
	for(var i=0; i<tr_obj.length; i++){
		if(tr_obj[i].id!=''){
			if(not_mouse_trBgcolor_tr_Value.indexOf('|'+tr_obj[i].id+'|')!=-1){
				continue;
			}
		}
		form_id=tr_obj[i].parentNode.parentNode.parentNode.id;
		
		if(form_id!='act_form' && form_id!='list_form' && form_id!='category_list_form' && form_id.substring(0, 11)!='detail_card'){
			continue;
		}
		tr_obj[i].onmouseover=function(){this.className=this.className.replace(' mouseout', '')+' mouseover';};
		tr_obj[i].onmouseout=function(){this.className=this.className.replace(' mouseover', '')+' mouseout';};
	}
}
</script>
</body>
</html>