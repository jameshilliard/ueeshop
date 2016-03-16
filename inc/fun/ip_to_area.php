<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

function ip_to_area($ip){
	if($ip==''){
		return '';
	}
	
	$iploca=new ip_to_area_class;
	@$iploca->init();
	@$iploca->getiplocation($ip);
	
	global $site_root_path;
	include_once($site_root_path.'/inc/fun/encode.php');
	$chs=new iconver();
	
	$area=array();
	$area['country']=str_replace(array('未知', 'CZ88.NET'), '', $chs->Convert(trim($iploca->get('country')), 'GBK', 'UTF8'));
	$area['area']=str_replace(array('未知', 'CZ88.NET'), '', $chs->Convert(trim($iploca->get('area')), 'GBK', 'UTF8'));
	
	return $area;
}

class ip_to_area_class{
	function init(){
		global $site_root_path;
		$this->wrydat=$site_root_path . '/inc/file/ip.dat';
		
		$this->fp=fopen($this->wrydat, 'rb');
		$this->getipnumber();
		$this->getwryversion();
		
		$this->REDIRECT_MODE_0=0;
		$this->REDIRECT_MODE_1=1;
		$this->REDIRECT_MODE_2=2;
	}
	
	function get($str){
		return $this->$str;
	}
	
	function set($str, $val){
		$this->$str=$val;
	}
	
	function getbyte($length, $offset=null){
		!is_null($offset) && fseek($this->fp, $offset, SEEK_SET);		
		return fread($this->fp, $length);
	}
	
	function packip($ip){
		return pack('N', intval(ip2long($ip)));
	}
	
	function getlong($length=4, $offset=null){
		$chr=null;
		for($c=0; $length%4!=0&&$c<(4-$length%4); $c++){
			$chr.=chr(0);
		}
		$var=unpack('Vlong', $this->getbyte($length, $offset).$chr);
		return $var['long'];
	}
	
	function getwryversion(){
		$length=preg_match("/coral/i", $this->wrydat)?26:30;
		$this->wrydat_version=$this->getbyte($length, $this->firstip-$length);
	}
	
	function getipnumber(){
		$this->firstip=$this->getlong();
		$this->lastip=$this->getlong();
		$this->ipnumber=($this->lastip-$this->firstip)/7+1;
	}
	
	function getstring($data='', $offset=NULL){
		$char=$this->getbyte(1, $offset);
		while(ord($char)>0){
			$data.=$char;
			$char=$this->getbyte(1);
		}
		return $data;
	}
	
	function iplocaltion($ip){
		$ip=$this->packip($ip);
		$low=0;
		$high=$this->ipnumber-1;
		$ipposition=$this->lastip;
		while($low<=$high){
			$t=floor(($low+$high)/2);
			if($ip<strrev($this->getbyte(4, $this->firstip+$t*7))){
				$high=$t - 1;
			}else{
				if($ip>strrev($this->getbyte(4, $this->getlong(3)))){
					$low=$t+1;
				}else{
					$ipposition=$this->firstip+$t*7;
					break;
				}
			}
		}
		return $ipposition;
	}
	
	function getarea(){
		$b=$this->getbyte(1);
		switch(ord($b)){
			case $this->REDIRECT_MODE_0 :
				return '';
				break;
			case $this->REDIRECT_MODE_1:
			case $this->REDIRECT_MODE_2:
				return $this->getstring('', $this->getlong(3));
				break;
			default:
				return $this->getstring($b);
				break;
		}
	}
	
	function getiplocation($ip){
		$ippos=$this->iplocaltion($ip);
		$this->ip_range_begin=long2ip($this->getlong(4, $ippos));
		$this->ip_range_end=long2ip($this->getlong(4, $this->getlong(3)));
		$b=$this->getbyte(1);
		switch(ord($b)){
			case $this->REDIRECT_MODE_1:
				$b=$this->getbyte(1, $this->getlong(3));
				if(ord($b)==$this->REDIRECT_MODE_2){
					$countryoffset=$this->getlong(3);
					$this->area=$this->getarea();
					$this->country=$this->getstring('', $countryoffset);
				}else{
					$this->country=$this->getstring($b);
					$this->area=$this->getarea();
				}
				break;
			case $this->REDIRECT_MODE_2:
				$countryoffset=$this->getlong(3);
				$this->area=$this->getarea();
				$this->country=$this->getstring('', $countryoffset);
				break;
			default:
				$this->country=$this->getstring($b);
				$this->area=$this->getarea();
				break;
		}
	}
}
?>