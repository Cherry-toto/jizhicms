<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2018/03/15
// +----------------------------------------------------------------------


namespace FrPHP\lib;
use FrPHP\db\DBholder;
use PDO;
class Model {
	protected $model;
	protected static $table;
    protected $primary = 'id';
	private $db;
	private static $instance=false;//不支持单例模式
	
	public function __construct(){
		$this->db = DBholder::getInstance();
	}
	
	public static function getInstance($table=null){
		if(self::$instance===false){
			self::$instance = new self($table);
		}
		if($table!=null){
			self::$table = $table;
		}
		
		self::$table = DB_PREFIX.strtolower(self::$table);
		
		return self::$instance;
	}
	
	//查询数据条数
	public function getCount($conditions=null){
		$where = '';
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $value ){
				$value =  '\''.$value.'\'';
				$join[] = "{$key} = {$value}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions) $where = "WHERE ".$conditions;
		}
		$table = self::$table;
		$sql = "SELECT count(*) as Frcount FROM {$table} {$where}";
        $result = $this->db->getArray($sql);
		return $result[0]['Frcount'];
		
	}
	
	//递增数据
	public function goInc($conditions,$field,$vp=1){
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $value ){
				$value = '\''.$value.'\'';
				$join[] = "{$key} = {$value}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		$values = "{$field} = {$field} + {$vp}";
		$table = self::$table;
		$sql = "UPDATE {$table} SET {$values} {$where}";
		
		return $this->runSql($sql);
		
	}
	
	//递减
	public function goDec($conditions,$field,$vp=1){
		return $this->goInc($conditions,$field,-$vp);
	}
	    // 修改数据
    public function update($conditions,$row)
    {
        $where = "";
		$row = $this->__prepera_format($row);
		if(empty($row))return FALSE;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = '\''.$condition.'\'';
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		foreach($row as $key => $value){
			if($value!==null){
				$value = '\''.$value.'\'';
				$vals[] = "{$key} = {$value}";
			}else{
				$vals[] = "{$key} = null";
			}
			
		}
		$values = join(", ",$vals);
		$table = self::$table;
		$sql = "UPDATE {$table} SET {$values} {$where}";
		return $this->runSql($sql);
		
		
    }


    // 查询所有
    public function findAll($conditions=null,$order=null,$fields=null,$limit=null)
    {
		$where = '';
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $value ){
				$value =  '\''.$value.'\'';
				$join[] = "{$key} = {$value}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
      if(is_array($order)){
       		$where .= ' ORDER BY ';
            $where .= implode(',', $order);
      }else{
         if($order!=null)$where .= " ORDER BY  ".$order;
      }
		
		if(!empty($limit)){
			if(strpos($limit,',')===false){
				$limit = ($limit<=0) ? 1 : $limit;
			}
			$where .= " LIMIT {$limit}";
		}
		$fields = empty($fields) ? "*" : $fields;
		$table = self::$table;
		$sql = "SELECT {$fields} FROM {$table} {$where}";
		
        return $this->db->getArray($sql);

    }

    // 查询一条
    public function find($where=null,$order=null,$fields=null,$limit=1)
    {
	   if( $record = $this->findAll($where, $order, $fields, 1) ){
			return array_pop($record);
		}else{
			return FALSE;
		}
    }
	
	//获取单一字段内容
	public function getField($where=null,$fields=null){
		if( $record = $this->findAll($where, null, $fields, 1) ){
			$res = array_pop($record);
			return $res[$fields];
		}else{
			return FALSE;
		}
	}
	
	
	//执行 SQL 语句，返回PDOStatement对象,可以理解为结果集
	public function query($sql){
		return $this->db->query();
	}
	//执行SQL语句返回影响行数
	public function runSql($sql)
	{
		return $this->db->exec($sql);
	}
	
	//执行SQL语句函数
	public function findSql($sql)
	{
		return $this->db->getArray($sql);
	}
	
    // 根据条件 (conditions) 删除
    public function delete($conditions)
    {
       $where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = '\''.$condition.'\'';
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ( ".join(" AND ",$join). ")";
		}else{
			if(null != $conditions)$where = "WHERE ( ".$conditions. ")";
		}
		$table = self::$table;
		$sql = "DELETE FROM {$table} {$where}";
		return $this->runSql($sql);
    }

    // 新增数据
    public function add($row)
    {
       if(!is_array($row))return FALSE;
		$row = $this->__prepera_format($row);
		if(empty($row))return FALSE;
		foreach($row as $key => $value){
			if($value!==null){
				$cols[] = $key;
				$vals[] = '\''.$value.'\'';
			}
		}
		$col = join(',', $cols);
		$val = join(',', $vals);
		$table = self::$table;
		$sql = "INSERT INTO {$table} ({$col}) VALUES ({$val})";
		if( FALSE != $this->runSql($sql) ){
			if( $newinserid = $this->db->lastInsertId() ){
				return $newinserid;
			}else{
				$a=$this->find($row, "{$this->primary} DESC",$this->primary);
				return array_pop($a);
			}
		}
		return FALSE;
    }

	//预处理SQL
	private function __prepera_format($rows)
	{
		$table = self::$table;
		$stmt = $this->db->getTable($table);  
		$stmt->execute();  
		$columns = $stmt->fetchAll(PDO::FETCH_CLASS);
		$newcol = array();
		foreach ($columns as $key => $value) {
			$field = strtolower($value->Field);
			if(stripos($value->Type,'int')!==false || stripos($value->Type,'decimal')!==false){
				
				if(isset($rows[$field])){
					if($rows[$field]!=='' && $rows[$field]!==false){
						$newcol[$field] = $rows[$field];
					}else{
						$newcol[$field] = 0;
					}
				}
				
			}else{
				if(isset($rows[$field])){
					if($rows[$field]!=='' && $rows[$field]!==false ){
						$newcol[$field] = $rows[$field];
					}else{
						$newcol[$field] = null;
					}
				}
				
				
			}
		}
		return $newcol;
		//return array_intersect_key($rows,$newcol);
	}
	
	
	public function __destruct()
	{
	  $this->db = null;
	}

	
	
	
	
	
}