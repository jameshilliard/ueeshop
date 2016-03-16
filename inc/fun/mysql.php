<?php
/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

$db=new db_class();

class db_class{
	var $link_id  	=	0;
	var $query_id 	=	0;
	var $record   	=	array();
	var $errno		=	0;
	var $error		=	'';
	
	function connect(){	//连接数据库
		global $db_host, $db_username, $db_password, $db_database, $db_port, $db_char;
			
		$this->link_id=@mysql_connect($db_host.':'.$db_port, $db_username, $db_password);
		$this->link_id || $this->haltmsg('无法链接数据库，请检查数据库链接配置文件！');
		
		$db_char && @mysql_query("set names '$db_char'");
		@mysql_select_db($db_database) || $this->haltmsg('无法选择数据库，请检查数据库名称是否正确！');
	}
	
	function next_record(){	//返回记录集
		$this->record=@mysql_fetch_assoc($this->query_id);
		return is_array($this->record);
	}
	
	function haltmsg($msg){	//消息提示
		$this->errno=@mysql_errno($this->link_id);
		$this->error=@mysql_error($this->link_id);
		echo "$msg<br>错误代码：<i>#{$this->errno}</i> - {$this->error}";
	}
	
	function query($sql){	//直接执行SQL语句
		!$this->link_id && $this->connect();
		$this->query_id=mysql_query($sql, $this->link_id);
		!$this->query_id && $this->haltmsg('SQL语句出错：'.$sql);
		return $this->query_id;
	}
	
	//------------------------------------------------------------------------以下为查询相关的函数---------------------------------------------------------------
	
	function get_all($table, $where=1, $field='*', $order=1){	//返回整个数据表
		$this->query("select $field from $table where $where order by $order");
		while($this->next_record()){
			$result[]=$this->record;
		}
		return $result;
	}
	
	function get_limit($table, $where=1, $field='*', $order=1, $start_row=0, $row_count=20){	//分页返回记录集
		$this->query("select $field from $table where $where order by $order limit $start_row, $row_count");
		$result=array();
		while($this->next_record()){
			$result[]=$this->record;
		}
		return $result;
	}
	
	function get_one($table, $where=1, $field='*', $order=1){	//返回一条记录
		$this->query("select $field from $table where $where order by $order limit 1");
		$this->next_record();
		$result=$this->record;
		return $result;
	}
	
	function get_value($table, $where=1, $field, $order=1){	//返回一个字段值
		$this->query("select $field from $table where $where order by $order limit 1");
		$this->next_record();
		$result=$this->record;
		return $result[$field];
	}
	
	function get_row_count($table, $where=1){	//返回总记录数
		$this->query("select count(*) as row_count from $table where $where");
		$this->next_record();
		$result=$this->record;
		return $result['row_count'];
	}
	
	function get_sum($table, $where=1, $field){
		$this->query("select sum($field) as sum_count from $table where $where");
		$this->next_record();
		$result=$this->record;
		return $result['sum_count'];
	}
	
	function get_max($table, $where=1, $field){
		$this->query("select max($field) as max_value from $table where $where");
		$this->next_record();
		$result=$this->record;
		return $result['max_value'];
	}
	
	function get_insert_id(){	//最后一次操作关联ID号
		return mysql_insert_id($this->link_id);
	}
	
	function show_columns($table, $only_return_field_name=0){	//返回数据表字段
		$this->query("show columns from $table");
		$result=array();
		while($this->next_record()){
			$result[]=$only_return_field_name==0?$this->record:$this->record['Field'];
		}
		return $result;
	}
	
	function insert($table, $data){	//插入记录
		while(list($field, $value)=each($data)){
			$fields.="$field,";
			$values.="'$value',";
		}
		$fields=substr($fields, 0, -1);
		$values=substr($values, 0, -1);
		$this->query("insert into $table($fields) values($values)");
	}
	
	function update($table, $where=0, $data){	//更新数据表
		while(list($field, $value)=each($data)){
			$upd_data.="$field='$value',";
		}
		$upd_data=substr($upd_data, 0, -1);
		$this->query("update $table set $upd_data where $where");
	}
	
	function delete($table, $where=0){	//删除数据
		$this->query("delete from $table where $where");
	}
}
?>