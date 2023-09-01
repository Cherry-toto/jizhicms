<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2022/02/10
// +----------------------------------------------------------------------

namespace app\admin\exts;

use frphp\lib\Controller;
class PluginsController extends Controller {
	
	private $tables = array();
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
	private $limit = 300;//每个备份文件存储的sql条数
	//自动执行
	public function _init(){
		/**
			继承系统默认配置
		
		**/
		
		//检查当前账户是否合乎操作
		if(!isset($_SESSION['admin']) || $_SESSION['admin']['id']==0){
			Redirect(U('Login/index'));
			
		}
 
	    if($_SESSION['admin']['isadmin']!=1){
			if(strpos($_SESSION['admin']['paction'],','.APP_CONTROLLER.',')!==false){
			   
			}else{
				$action = APP_CONTROLLER.'/'.APP_ACTION;
				if(strpos($_SESSION['admin']['paction'],','.$action.',')===false){
				   $ac = M('Ruler')->find(array('fc'=>$action));
				   if($this->frparam('ajax')){
					   
					   JsonReturn(['code'=>1,'msg'=>'您没有【'.$ac['name'].'】的权限！','url'=>U('Index/index')]);
				   }
				   Error('您没有【'.$ac['name'].'】的权限！',U('Index/index'));
				}
			}
		   
		  	
		}
	  	
	    $webconf = webConf();
	    $this->webconf = $webconf;
	    $customconf = get_custom();
	    $this->customconf = $customconf;
		
		//插件模板页目录
		
		$this->tpl = '@'.dirname(__FILE__).'/tpl/';
		
		/**
			在下面添加自定义操作
		**/
		
		
	}
	
	//执行SQL语句在此处处理,或者移动文件也可以在此处理
	public  function install(){
		//下面是新增test表的SQL操作
		
	
		return true;
		
	}
	
	//mysql版本
	private function JZ_mysql(){
        
  
        
		/**
			文件转移
		**/
		
		
        
	}
	
	//批量转移文件
	private function removeFile($from,$to){
		//移动后台插件控制器
		$sourcefile = $from;
		$target = $to;
		if(is_dir($sourcefile) && is_dir($target)){
			if (false != ($handle = opendir ( $sourcefile ))) {
				
				while ( false !== ($file = readdir ( $handle )) ) {
					//去掉"“.”、“..”以及带“.xxx”后缀的文件
					if ($file != "." && $file != ".." && !is_dir($sourcefile.'/'.$file) ) {
						$fs = $sourcefile.'/'.$file;
						$ft = $target.'/'.$file;
						 //备份源文件以防更新覆盖
						copy($ft,  $sourcefile.'/back/'.$file);
						$r = $this->file2dir($fs,$ft);
						if(!$r){
							JsonReturn(array('code'=>1,'msg'=>'文件转移失败！sourcefile:'.$fs.' targetfile:'.$ft));
						}
					}
				}
				//关闭句柄
				closedir ( $handle );
			}
		
		}
				
	}
	
	// 原目录，复制到的目录
	function recurse_copy($src,$dst) {  
	 
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
	//复制文件并转移
	function file2dir($sourcefile, $filename){
		 if( !file_exists($sourcefile)){
			 return false;
		 }
		 //$filename = basename($sourcefile);
		
		 return copy($sourcefile,  $filename);
	}
	
	//返回表字段
	private function getTableFields($table){
		if(defined('DB_TYPE') && DB_TYPE=='sqlite'){
			$sql = "pragma table_info(".DB_PREFIX.$table.")";
			
			$list = M()->findSql($sql);
			$fields = [];
			foreach($list as $v){
				$fields[]=$v['name'];
				
			}
		}else{
			$sql = 'SHOW COLUMNS FROM '.DB_PREFIX.$table;
			$list = M()->findSql($sql);
			$isgo = true;
			$fields = [];
			foreach($list as $v){
				$fields[]=$v['Field'];
				
			}
		}
		
		
		
		return $fields;
		
	}
	//返回数据库表
	private function getTableData(){
		if(defined('DB_TYPE') && DB_TYPE=='sqlite'){
			$sql = "select name from sqlite_master where type='table' order by name";
		}else{
			$sql = "SHOW TABLES";
		}
		
		
		$tables = M()->findSql($sql);
		$ttable = array();
		foreach($tables as $value){
			foreach($value as $vv){
				$ttable[] = $vv;
			}
			
		}
		return $ttable;
	}
	
	//备份数据库
	private function JZ_backup(){
		$pconfig = array(
			'host' =>DB_HOST,
			'port' =>DB_PORT,
			'user' =>DB_USER,
			'password' =>DB_PASS,
			'database' =>DB_NAME
		);
		$this->config = array_merge($this->config, $pconfig);
		$this->handler = new \PDO("mysql:host=".$this->config['host'].";port={$this->config['port']};dbname={$this->config['database']}", $this->config['user'], $this->config['password']);
		$this->handler->query("set names utf8");
		
		$this->backup();
	}
	
	//卸载程序,对新增字段、表等进行删除SQL操作，或者其他操作
	public function uninstall(){
		//下面是删除test表的SQL操作
		
		return true;
	}
	
	//安装页面介绍,操作说明
	public function desc(){
		$this->display($this->tpl.'plugins-description.html');
	}
	
	//配置文件,插件相关账号密码等操作
	public  function setconf($plugins){
		//将插件赋值到模板中
		$this->plugins = $plugins;
		$this->config = json_decode($plugins['config'],1);
		
		$this->display($this->tpl.'plugins-body.html');
	}
	
	//获取插件内提交的数据处理
	public function setconfigdata($data){
		//ids
		
		$config = $data;

		M('plugins')->update(['id'=>$data['id']],['config'=>json_encode($config,JSON_UNESCAPED_UNICODE)]);
		setCache('hook',null);//清空hook缓存
		JsonReturn(['code'=>0,'msg'=>'设置成功！']);
	}
	
	 /**
	  * 备份
	  * @param array $tables
	  * @return bool
	  */
	 private function backup($tables = array())
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
	  $stmt->setFetchMode(\PDO::FETCH_NUM);
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
	  //echo '备份数据库-'.$this->config['database'].'<br />';
	  $countsql = 0;//记录sql数
	  $filenum = 0;//文件序号
	  $backfile = $this->config['target']==''? $this->config['database'].'_'.date('Y_m_d_H_i_s').'_'.rand(100000,999999): $this->config['target'].date('YmdHis');//文件名
	  $str = $public_str."SET FOREIGN_KEY_CHECKS=0;\r\n";
	  foreach ($tables as $table)
	  {
	  // echo '备份表：'.$table.'<br>';
	   $str .= "-- ----------------------------\r\n";
	   $str .= "-- Table structure for {$table}\r\n";
	   $str .= "-- ----------------------------\r\n";
	   $str .= "DROP TABLE IF EXISTS `{$table}`;\r\n";
	   $str .= $ddl[$i] . "\r\n";
	  
	   $i++;
	   //echo '备份成功！<br/>'; 
	   
	  }
	  $i = 0;
	  foreach($tables as $table){
		//echo '备份表数据：'.$table.' <br>';
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
						JsonReturn(['code'=>1,'msg'=>'[ backup/'.$backfile.'.php ] 写入文件失败！']);
					}
					$filenum++;
				}else{
					$isok = file_put_contents('backup/'.$backfile.'_v'.$filenum.'.php', $str);
					if(!$isok){
						JsonReturn(['code'=>1,'msg'=>'[ backup/'.$backfile.'_v'.$filenum.'.php ] 写入文件失败！']);
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
					JsonReturn(['code'=>1,'msg'=>'[ backup/'.$backfile.'.php ] 写入文件失败！']);
				}
			}else{
				$isok = file_put_contents('backup/'.$backfile.'_v'.$filenum.'.php', $str);
				if(!$isok){
					JsonReturn(['code'=>1,'msg'=>'[ backup/'.$backfile.'_v'.$filenum.'.php ] 写入文件失败！']);
				}
			}
	  }

	 }
	
}




