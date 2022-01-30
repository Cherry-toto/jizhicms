<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/8
// +----------------------------------------------------------------------




class DB_API
{
    // 数据库表名
    protected $table;
 
    // 数据库主键
    protected $primary = 'id';
	
	//表前缀
	protected $prefix = '';
 
    // WHERE和ORDER拼装后的条件
    private $filter = array();
 
	//PDO
	private $pdo;
	
	//PDOStatement
	private $Statement;
	
	//PDO链接数据库
	public function __construct($config){
		class_exists('PDO') or exit("not found PDO");
		
		try{
			$this->pdo = new PDO("mysql:host=".$config['db_host'].";port=".$config['db_port'].";dbname=".$config['db_name'],$config['db_user'], $config['db_pass']); 
		}catch(PDOException $e){
			//数据库无法链接，如果您是第一次使用，请先配置数据库！
			exit($e->getMessage());
		}
		$this->prefix = $config['db_prefix'];
		$this->pdo->exec("SET NAMES UTF8");
		
		
	}
	//配置表信息
    public function set_table($table=null,$primary='id'){
        if($table==null){ exit('Not found Table');}
		
		$this->primary = $primary;
		$this->table = $this->prefix.$table;
        return $this;
    }
	
	
	//获取数据
	public function getData($sql)
	{
		if(!$result = $this->query($sql))return array();
		if(!$this->Statement->rowCount())return array();
		$rows = array();
		while($rows[] = $this->Statement->fetch(PDO::FETCH_ASSOC)){}
		$this->Statement=null;
		array_pop($rows);
		return $rows;
	}
	
	//查询数据条数
	public function getCount($conditions){
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
		$sql = "SELECT count(*) as Frcount FROM {$this->table} {$where}";
        $result = $this->getData($sql);
		return $result[0]['Frcount'];
		
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
		$sql = "UPDATE {$this->table} SET {$values} {$where}";
		
		return $this->pdo->exec($sql);
		
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
		if(empty($row)){
        return FALSE;
        }
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = '\''.$condition.'\'';
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions){
              $where = "WHERE ".$conditions;
            }
		}
		foreach($row as $key => $value){
			$value = '\''.$value.'\'';
			$vals[] = "{$key} = {$value}";
		}
		$values = join(", ",$vals);
		$sql = "UPDATE {$this->table} SET {$values} {$where}";
        //echo $sql.'<br/>';
		$res = $this->pdo->exec($sql);
      if($res){
      	return $res;
      }else{
      	var_dump($this->pdo->errorInfo());
      }
		
		
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
		
		if(!empty($limit))$where .= " LIMIT {$limit}";
		$fields = empty($fields) ? "*" : $fields;
 
		$sql = "SELECT {$fields} FROM {$this->table} {$where}";
		
        return $this->getData($sql);
 
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
	
	//执行SQL语句并检查是否错误
	public function query($sql){
		$this->filter[] = $sql;
        $this->Statement = $this->pdo->query($sql);
        if ($this->Statement) {
			return $this;
        }else{
			$msg = $this->pdo->errorInfo();
			if($msg[2]) exit('数据库错误：' . $msg[2] . end($this->filter));
		}
	}
 
	//执行SQL语句函数
	public function findSql($sql)
	{
		return $this->getData($sql);
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
		$sql = "DELETE FROM {$this->table} {$where}";
		return $this->pdo->exec($sql);
    }
 
    // 新增数据
    public function add($row)
    {
       if(!is_array($row)){
         return FALSE;
       }
		$row = $this->__prepera_format($row);
		if(empty($row)){
         return FALSE;
        }
		foreach($row as $key => $value){
			$cols[] = $key;
			$vals[] = '\''.$value.'\'';
		}
		$col = join(',', $cols);
		$val = join(',', $vals);
 
		$sql = "INSERT INTO {$this->table} ({$col}) VALUES ({$val})";
		if( FALSE != $this->pdo->exec($sql) ){
			if( $newinserid = $this->pdo->lastInsertId() ){
				return $newinserid;
			}else{
				$a=$this->find($row, "{$this->primary} DESC",$this->primary);
				return array_pop($a);
			}
		}
		return FALSE;
    }
 
 
 
	private function __prepera_format($rows)
	{
		$stmt = $this->pdo->prepare('DESC '.$this->table);  
		$stmt->execute();  
		$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
		$newcol = array();
		foreach( $columns as $col ){
			$newcol[$col] = null;
		}
		return array_intersect_key($rows,$newcol);
	}
	
	
}


/*

//配置一个数据库
$config  = [
    'db_host' => 'localhost',//或者127.0.0.1 或指定的数据库地址
    'db_port' => 3306,//默认mysql数据库端口
    'db_name' => 'test',//数据库名字
    'db_user' => 'root',//数据库用户名
    'db_pass' => 'root',//数据库密码
	'db_prefix' => '',//表前缀
];

$db = new DB_API($config);
 
//将传入表数据的对象类放入一个变量中，方便多次使用
$article = $db->set_table('article');

//新增一条数据
$newdata = ['title'=>'测试1','addtime'=>time()];
$r = $article->add($newdata);
if($r){
    echo '新增成功！';
}else{
    echo '操作失败！';
}

//查询一条数据
$where = ['id'=>1];// 也支持字符串：$where = ' id = 1 ';
$find = $article->find($where);//查询一条数据
//$find = $article->findAll($where);查询多条数据
print_r($find);

//更新数据-更新id为1的数据
$where = ['title'=>'测试1122'];
$update = $article->update(['id'=>1],$where);
if($update ){
    echo '更新成功！';
    //查询对应数据并打印
    $newdata = $article->find('id=1');
    print_r($newdata);
}else{
    echo '更新失败！';
}

//删除一条数据
$where = ['id'=>1];//也支持字符串：$where = 'id=1';
$del =  $article->delete($where);
if($del ){
    echo '删除成功！';
}else{
    echo '删除失败！';
}

//额外功能

//根据查询条件，获取条数
$where = ' id>0 ';//也可以是数组形式
$count = $article->getCount($where);
print_r($count);

//根据条件，递增数据库内整数类型字段值
$a = $article->find(['id'=>1]);
if(!$a){ exit('缺少ID为1的数据！');}
echo '原数据：'.$a['addtime'].'<br/>';
$r = $article->goInc(['id'=>1],'addtime',1);
$a = $article->find(['id'=>1]);
echo '新数据：'.$a['addtime'];

//同理，根据条件，递减数据库内整数类型字段值
$a = $article->find(['id'=>1]);
if(!$a){ exit('缺少ID为1的数据！');}
echo '原数据：'.$a['addtime'].'<br/>';
$r = $article->goDec(['id'=>1],'addtime',1);
$a = $article->find(['id'=>1]);
echo '新数据：'.$a['addtime'];

//执行原生SQL语句
$sql = 'select *  from article where id>0 ';
$lists = $article->findSql($sql);
print_r($lists);

//根据条件查询出对应的字段的值
$where = ['id'=>1];//可以是字符串：'id=1';
$res = $article->getField($where,'title');//未找到返回false
print_r($res);
*/