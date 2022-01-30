<?php
/**
 * 数据库备份还原类
 * @author xialeistudio<admin@xialeistudio.net>
 * Class DatabaseTool
 */
class DatabaseTool
{
 private $handler;
 private $config = array(
  'host' => 'localhost',
  'port' => 3306,
  'user' => 'root',
  'password' => 'root',
  'database' => 'test',
  'charset' => 'utf-8',
  'target' => ''
 );
 private $tables = array();
 private $limit = 300;//每个备份文件存储的sql条数
 private $error;
 private $begin; //开始时间
 /**
  * 架构方法
  * @param array $config
  */
 public function __construct($config = array())
 {
  //date_default_timezone_set('Asia/Shanghai');
  ini_set("memory_limit","800M");
  $this->begin = microtime(true);
  $config = is_array($config) ? $config : array();
  $this->config = array_merge($this->config, $config);
  //启动PDO连接
  if (!$this->handler instanceof PDO)
  {
   try
   {
    $this->handler = new PDO("mysql:host=".$this->config['host'].";port={$this->config['port']};dbname={$this->config['database']}", $this->config['user'], $this->config['password']);
    $this->handler->query("set names utf8");
   }
   catch (PDOException $e)
   {
    $this->error = $e->getMessage();
    return false;
   }
  
  }
 }
 /**
  * 备份
  * @param array $tables
  * @return bool
  */
 public function backup($tables = array())
 {
  //存储表定义语句的数组
  $ddl = array();
  //存储数据的数组
  $data = array();
  $this->setTables($tables);
  if (!empty($this->tables))
  {
   foreach ($this->tables as $table)
   {
    $ddl[] = $this->getDDL($table);
    $data[] = $this->getData($table);
   }
   //开始写入
   $this->writeToFile($this->tables, $ddl, $data);
  }
  else
  {
   $this->error = '数据库中没有表!';
   return false;
  }
 }
 /**
  * 设置要备份的表
  * @param array $tables
  */
 private function setTables($tables = array())
 {
  if (!empty($tables) && is_array($tables))
  {
   //备份指定表
   $this->tables = $tables;
  }
  else
  {
   //备份全部表
   $this->tables = $this->getTables();
  }
 }
 /**
  * 查询
  * @param string $sql
  * @return mixed
  */
 private function query($sql = '')
 {
  $stmt = $this->handler->query($sql);
  $stmt->setFetchMode(PDO::FETCH_NUM);
  $list = $stmt->fetchAll();
  return $list;
 }
 /**
  * 获取全部表
  * @return array
  */
 private function getTables()
 {
  $sql = 'SHOW TABLES';
  $list = $this->query($sql);
  $tables = array();
  foreach ($list as $value)
  {
   $tables[] = $value[0];
  }
  return $tables;
 }
 /**
  * 获取表定义语句
  * @param string $table
  * @return mixed
  */
 private function getDDL($table = '')
 {
  $sql = "SHOW CREATE TABLE `{$table}`";
  $ddl = $this->query($sql)[0][1] . ';';
  return $ddl;
 }
 /**
  * 获取表数据
  * @param string $table
  * @return mixed
  */
 private function getData($table = '')
 {
  $sql = "SHOW COLUMNS FROM `{$table}`";
  $list = $this->query($sql);
  //字段
  $columns = '';
  //需要返回的SQL
  $query = [];
  foreach ($list as $value)
  {
   $columns .= "`{$value[0]}`,";
  }
  $columns = substr($columns, 0, -1);
  $data = $this->query("SELECT * FROM `{$table}`");
  foreach ($data as $value)
  {
   $dataSql = '';
   foreach ($value as $v)
   {
    if($v==='' || $v===null){
      $dataSql .= " NULL,";
    }else{
      $dataSql .= "'{$v}',";
    }
    
   }
   $dataSql = substr($dataSql, 0, -1);
   $query[]= "INSERT INTO `{$table}` ({$columns}) VALUES ({$dataSql});\r\n";
  }
  return $query;
 }
 /**
  * 写入文件
  * @param array $tables
  * @param array $ddl
  * @param array $data
  */
 private function writeToFile($tables = array(), $ddl = array(), $data = array())
 {
  $public_str = "/*\r\nMySQL Database Backup Tools\r\n";
  $public_str .= "Server:{$this->config['host']}:{$this->config['port']}\r\n";
  $public_str .= "Database:{$this->config['database']}\r\n";
  $public_str .= "Data:" . date('Y-m-d H:i:s', time()) . "\r\n*/\r\n";
  $i = 0;
  echo '备份数据库-'.$this->config['database'].'<br />';
  $countsql = 0;//记录sql数
  $filenum = 0;//文件序号
  $backfile = $this->config['target']==''? $this->config['database'].'_'.date('Y_m_d_H_i_s').'_'.rand(100000,999999): $this->config['target'].date('YmdHis');//文件名
  $str = $public_str."SET FOREIGN_KEY_CHECKS=0;\r\n";
  foreach ($tables as $table)
  {
   echo '备份表：'.$table.'<br>';
   $str .= "-- ----------------------------\r\n";
   $str .= "-- Table structure for {$table}\r\n";
   $str .= "-- ----------------------------\r\n";
   $str .= "DROP TABLE IF EXISTS `{$table}`;\r\n";
   $str .= $ddl[$i] . "\r\n";
  
   $i++;
   //echo '备份成功！<br/>'; 
   
  }
  //优先备份表结构
    $str = '<?php die();?>'.$str;
    $isok = file_put_contents('backup/'.$backfile.'.php', $str);
	if(!$isok){
		exit('[ backup/'.$backfile.'.php ] 写入文件失败！');
	}
	$str = $public_str;
	$filenum = 1;
  $i = 0;
  foreach($tables as $table){
	echo '备份表数据：'.$table.' <br>';
	$str .= "-- ----------------------------\r\n";
	$str .= "-- Records of {$table}\r\n";
	$str .= "-- ----------------------------\r\n";
	//$str .= $data[$i] . "\r\n";
	foreach ($data[$i] as $v){
		$str .= $v;
		$countsql++;
		if($countsql%($this->limit)==0){
			$str = '<?php die();?>'.$str;
			if($filenum==0){
				$isok = file_put_contents('backup/'.$backfile.'.php', $str);
				if(!$isok){
					exit('[ backup/'.$backfile.'.php ] 写入文件失败！');
				}
				$filenum++;
			}else{
				$isok = file_put_contents('backup/'.$backfile.'_v'.$filenum.'.php', $str);
				if(!$isok){
					exit('[ backup/'.$backfile.'_v'.$filenum.'.php ] 写入文件失败！');
				}
				$filenum++;
			}
			$str = $public_str;
		}	
	}
	$i++;
	
	
  }
  if($str!='' && $str != $public_str){
	    $str = '<?php die();?>'.$str;
		if($filenum==0){
			$isok = file_put_contents('backup/'.$backfile.'.php', $str);
			if(!$isok){
				exit('[ backup/'.$backfile.'.php ] 写入文件失败！');
			}
		}else{
			$isok = file_put_contents('backup/'.$backfile.'_v'.$filenum.'.php', $str);
			if(!$isok){
				exit('[ backup/'.$backfile.'_v'.$filenum.'.php ] 写入文件失败！');
			}
		}
  }
  
  echo '备份成功！<br/>  备份成功!花费时间' . (microtime(true) - $this->begin) . 'ms';

  Redirect(U('Index/beifen'),'备份完成~',1);
 }
 /**
  * 错误信息
  * @return mixed
  */
 public function getError()
 {
  return $this->error;
 }
 
 //数据库还原
 
 public function restore($path = ''){
  if (!file_exists($path)){
	$this->error('SQL文件不存在!');
	return false;
  }else{
	$filename_arr = explode('.php',$path);
	$filename_arr2 = explode('/',$filename_arr[0]);
	$filename = end($filename_arr2);
	//读取备份数据库
	$dir = APP_PATH.'backup';
	$fileArray=array();
	$fileArray[] = $dir.'/'.$filename.'.php';
  for($i=1;file_exists($dir.'/'.$filename.'_v'.$i.'.php')===true;$i++){
      $fileArray[]=$dir.'/'.$filename.'_v'.$i.'.php';
  }
	
   foreach($fileArray as $path){
	   $sql = $this->parseSQL($path);
	   try{
		    $n = $this->handler->exec($sql);
			if(!$n){
				$msg = $this->handler->errorInfo();
				if($msg[2]) Exit('数据库错误：' . $msg[2] . end($sql));
			}
	   
		
	   }catch (PDOException $e){
			$this->error = $e->getMessage();
			return false;
	   }
	   
   }
   setCache('classtype',null);
   setCache('mobileclasstype',null);
   setCache('classtypedatamobile',null);
   setCache('classtypedatapc',null);
   echo '还原成功!花费时间', (microtime(true) - $this->begin) . 'ms';
    Redirect(U('Index/beifen'),'还原成功~',1);
   
  }
 }
 /**
  * 解析SQL文件为SQL语句数组
  * @param string $path
  * @return array|mixed|string
  */
 private function parseSQL($path = '')
 {
  $sql = file_get_contents($path);
  //替换掉15个字符串
  $sql = substr($sql,14);
  $sql = explode("\r\n", $sql);
  //先消除--注释
  $sql = array_filter($sql, function ($data)
  {
   if (empty($data) || preg_match('/^--.*/', $data))
   {
    return false;
   }
   else
   {
    return true;
   }
  });
    $sql = implode('', $sql);
	//删除/**/注释
	$sql = preg_replace('/\/\*.*\*\//', '', $sql);
	return $sql;
  
  
 }
}





//测试备份

//$database = new DatabaseTool(array('database'=>'lunwen','target'=>'lunwen'));

//$database->backup();

//$database->restore('lunwen.sql');
//$database->getError();


