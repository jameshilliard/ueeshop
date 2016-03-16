/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791

Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license

帮助文档：http://docs.fckeditor.net/ckeditor_api/symbols/CKEDITOR.config.html
*/

CKEDITOR.editorConfig=function(config){
	config.filebrowserUploadUrl='/js/ckeditor/upload.php?file_type=attach';
	config.filebrowserImageUploadUrl='/js/ckeditor/upload.php?file_type=img';
	config.filebrowserFlashUploadUrl='/js/ckeditor/upload.php?file_type=flash';
	
	config.resize_enabled=false;
	config.toolbarCanCollapse=false;
	config.language='zh-cn';
	config.skin='v2';
	config.colorButton_enableMore=false;
	config.enterMode=CKEDITOR.ENTER_BR;
	config.font_names='黑体;宋体;新宋体;Arial;Times New Roman;Times;serif';
	config.fontSize_sizes='10px;12px;14px;16px;18px;20px;22px;24px;28px';
	config.undoStackSize=200;
	config.height=400;
	config.width=screen.width>1024?'80%':'90%';
	if(document.documentElement.scrollWidth*parseInt(config.width, 10)/100<650){
		config.width=600;
	}
	
	config.toolbar='ly200';
	config.toolbar_ly200=[
		['Source','-'],
		['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['TextColor','BGColor'],
		['Image','Flash','Table','Smiley','SpecialChar'],
		'/',
		['Bold','Italic','Underline','Strike'],
		['Outdent','Indent'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink'],
		['Font','FontSize'],
		['Maximize']
    ];
}