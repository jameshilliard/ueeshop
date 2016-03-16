var arrowImageHeight=25;
var displayWaitMessage=true;
var previewImage=false;
var previewImageParent=false;
var slideSpeed=0;
var previewImagePane=false;
var slideEndMarker=false;
var galleryContainer=false;
function showPreview(newSrc,imageIndex){
	if(!previewImage){
		var images=document.getElementById('show_pho').getElementsByTagName('IMG');
		if(images.length>0){
			previewImage=images[0];
		}else{
			previewImage=document.createElement('IMG');
			document.getElementById('show_pho').appendChild(previewImage);
		}
	}
	if(displayWaitMessage){
		document.getElementById('waitMessage').style.display='inline';
	}
	previewImage.onload=function(){
		hideWaitMessage(imageIndex-1);
	};
	previewImage.src=newSrc;
	document.getElementById("magnifierImg").setAttribute("src",newSrc);
}

function hideWaitMessage(imageIndex){
	document.getElementById('waitMessage').style.display='none';
}
function getTopPos(inputObj){
	var returnValue=inputObj.offsetTop;while((inputObj=inputObj.offsetParent)!=null)returnValue+=inputObj.offsetTop;return returnValue;
}
function getLeftPos(inputObj){
	var returnValue=inputObj.offsetLeft;while((inputObj=inputObj.offsetParent)!=null)returnValue+=inputObj.offsetLeft;return returnValue;
}
function initSlide(e){
	if(document.all)e=event;if(this.src.indexOf('over')<0)this.src=this.src.replace('.gif','-over.gif');
	slideSpeed=e.clientY+Math.max(document.body.scrollTop,document.documentElement.scrollTop)-getTopPos(this);
	if(this.src.indexOf('down')>=0){
		slideSpeed=(slideSpeed)*-1;}else{slideSpeed=arrowImageHeight-slideSpeed;
	}
	slideSpeed=Math.round(slideSpeed*70/arrowImageHeight);
}
function stopSlide(){
	slideSpeed=0;this.src=this.src.replace('-over','');
}
function slidePreviewPane(){
	if(slideSpeed!=0){
		var topPos=previewImagePane.style.top.replace(/[^\-0-9]/g,'')/1;
		if(slideSpeed<0&&slideEndMarker.offsetTop<(previewImageParent.offsetHeight-topPos)){
			slideSpeed=0;
		}
	topPos=topPos+slideSpeed;
	if(topPos>0)topPos=0;previewImagePane.style.top=topPos+'px';
	}
	setTimeout('slidePreviewPane()',30);
}
function initGalleryScript(){
	previewImageParent=document.getElementById('rolling_cen');
	previewImagePane=document.getElementById('rolling_cen').getElementsByTagName('DIV')[0];
	previewImagePane.style.top='0px';
	galleryContainer=document.getElementById('show_rolling');
	slideEndMarker=document.getElementById('slideEnd');
	document.getElementById('arrow_up_image').onmousemove=initSlide;
	document.getElementById('arrow_up_image').onmouseout=stopSlide;
	document.getElementById('arrow_down_image').onmousemove=initSlide;
	document.getElementById('arrow_down_image').onmouseout=stopSlide;
	slidePreviewPane();
}