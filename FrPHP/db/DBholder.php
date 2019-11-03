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


namespace FrPHP\db;

use PDO;
use PDOException;
use PDOStatement;

class DBholder{
	
	private static $instance = false;
	private $pdo;
	private $Statement;
	private $arrSql;
	
	private function __construct(){
		class_exists('PDO') or exit("not found PDO");
		try{
			$this->pdo = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME,DB_USER, DB_PASS); 
		}catch(PDOException $e){
			//数据库无法链接，如果您是第一次使用，请先配置数据库！
			exit('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />数据库无法链接，如果您是第一次使用，请先执行<a href="/install/">安装程序</a><br /><br /><a href="http://jizhicms.com" target="_blank">极致CMS建站程序 jizhicms.com</a>');
		}
		

		ini_set("memory_limit","800M");
		$this->pdo->exec("SET NAMES UTF8");
	}
	
	public static function getInstance(){
		if(self::$instance===false){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	//执行 SQL 语句，返回PDOStatement对象,可以理解为结果集
	public function query($sql){
		$this->arrSql[] = $sql;
        $this->Statement = $this->pdo->query($sql);
        if ($this->Statement) {
			return $this;
        }else{
			$msg = $this->pdo->errorInfo();
			if($msg[2]){
				Error_msg('数据库错误：' . $msg[2] . end($this->arrSql));
			}
		}
	}
	
	
	//执行SQL语句返回数组
	public function getArray($sql){
		if(!$result = $this->query($sql))return array();
		if(!$this->Statement->rowCount())return array();
		$rows = array();
		while($rows[] = $this->Statement->fetch(PDO::FETCH_ASSOC)){}
		$this->Statement=null;
		array_pop($rows);
		return $rows;
	}
	
	//执行一条 SQL 语句，并返回受影响的行数
	public function exec($sql)
	{
		$this->arrSql[] = $sql;
		$n = $this->pdo->exec($sql);
        if(!$n){
			$msg = $this->pdo->errorInfo();
			if($msg[2]) Error_msg('数据库错误：' . $msg[2] . end($this->arrSql));
		}
		return $n;
	}
	
	//获取插入影响行数
	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
	
	//获取表信息
	public function getTable($table){
		$stmt = $this->pdo->prepare("DESC {$table}");  
		return $stmt;
	}
	
	
	
	
}