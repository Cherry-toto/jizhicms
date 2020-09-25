<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2018/05
// +----------------------------------------------------------------------


//安装程序
//error_reporting(0);
//检查是否已安装
if(file_exists('install.lock')){
	exit('很抱歉，程序已安装,如需重新安装请删除install/install.lock');
}

//状态
$errmsg=0;

//相关方法
function check_disable(){
		$string=ini_get("disable_functions");
		if(strpos($string,'opendir')!==false){
			$GLOBALS['errmsg']=1;
			return '<b>关闭中！</b>';
		}else{
			return '开启';
		}
	}
function check_chinese(){
	if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $_SERVER['DOCUMENT_ROOT'])>0){
		$GLOBALS['errmsg']=1;
		return '<b>网站路径中不能含有中文！</b>';
	}else{
		return $_SERVER['DOCUMENT_ROOT'];
	}
}

function check_version(){
	
	if (PHP_VERSION < 5.6) {
		$GLOBALS['errmsg']=1;
		return '<b>'.PHP_VERSION.'不满足</b>';
	}else{
	   return PHP_VERSION;
	}
}
//检查目录是否可写入
function new_is_writeable($file) {
    if (is_dir($file)){
        $dir = $file;
        if ($fp = @fopen("$dir/test.txt", 'w')) {
            @fclose($fp);
            @unlink("$dir/test.txt");
            $writeable = 1;
        } else {
            $writeable = 0;
			$GLOBALS['errmsg']=1;
        }
    } else {
        if ($fp = @fopen($file, 'a+')) {
            @fclose($fp);
            $writeable = 1;
        } else {
            $writeable = 0;
			$GLOBALS['errmsg']=1;
        }
    }
 
    return $writeable;
}

//获取后台文件名
function get_admin_url(){
	//读取根目录文件
	$admin_url = '';
	$filepath = '../';
	if (false !== ($handle = opendir ($filepath))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			//去掉"“.”、“..”以及带“.xxx”后缀的文件
			if ($file != "." && $file != ".."&&strpos($file,".")) {
				
				if(strpos($file,'.php')!==false && $file!='index.php'){
					$data = file_get_contents('../'.$file);
					if(strpos($data,"define('APP_HOME','A')")!==false){
						$admin_url = $file;
						break;
					}
					
				}
				
			}
		}
		//关闭句柄
		closedir ( $handle );
	}
	if($admin_url==''){
		exit('缺少后台文件！');
	}
	return $admin_url;
}

//获取域名
function get_domain(){
	if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
	{
		$protocol = "https://";
	}
	elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
	{
		$protocol = "https://";
	}
	elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
	{
		$protocol = "https://";
	}else{
		$protocol = "http://";
	}
	
    if(isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }elseif (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    }else{
        if(isset($_SERVER['SERVER_PORT'])) {
            $port = ':' . $_SERVER['SERVER_PORT'];
            if((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
                $port = '';
            }
        }else{
            $port = '';
        }
        if(isset($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'].$port;
        }else if(isset($_SERVER['SERVER_ADDR'])) {
            $host = $_SERVER['SERVER_ADDR'].$port;
        }
    }
    return $protocol.$host;
}

 /**
  * 解析SQL文件为SQL语句数组
  * @param string $path
  * @return array|mixed|string
  */
 function parseSQL($path = '')
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

//检查安装进度
$act = isset($_GET['act'])?$_GET['act']:'';
switch($act){
	case 'step1':
		$tpl = include('tpl/step1.jizhi');
	break;
	case 'step2':
		//检测是否有备份数据库
		//读取备份数据库
		$dir = '../backup';
		$fileArray=array();
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&& (strpos($file,".php")!==false) && (strpos($file,'_v')===false)) {
					$fileArray[$i]=$file;
					$i++;
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		
		$dblists = $fileArray;
		
		$admin_url = get_admin_url();
		$tpl = include('tpl/step2.jizhi');
	break;
	case 'step3':
		
		try{
			$pdo = new PDO("mysql:host=".$_POST['host'].";port=".$_POST['port'].";dbname=".$_POST['name'],$_POST['user'], $_POST['password']); 
			//更新db.config.php
			$config['db']['host'] = $_POST['host'];
			$config['db']['dbname'] = $_POST['name'];
			$config['db']['username'] = $_POST['user'];
			$config['db']['password'] = $_POST['password'];
			$config['db']['prefix'] = $_POST['prefix'];
			$config['db']['port'] = $_POST['port'];
			
			$config['redis'] = array(
				'SAVE_HANDLE' => 'Redis',
				'HOST' => '127.0.0.1',
				'PORT' => 6379,
				'AUTH' => null,
				'TIMEOUT' => 0,
				'RESERVED' => null,
				'RETRY_INTERVAL' => 100,
				'RECONNECT' => false,
				'EXPIRE'=>1800
			);
			$config['APP_DEBUG'] = true;
			
			$ress = file_put_contents('../Conf/config.php', '<?php return ' . var_export($config, true) . '; ?>');
			
			
			
		}catch(PDOException $e){
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><script type=text/javascript>alert("数据库连接失败！");javascript:history.go(-1);</script></body></html>';
		}
		$db = $_POST['go_backup']==1 ? $_POST['backup_db'] : '';
	
		
		$admin_url = get_admin_url();
	
		
		//传入管理员信息
		$admin_name = $_POST['admin_name'];
		$admin_pass = $_POST['admin_pass'];
		$tpl = include('tpl/step3.jizhi');
	break;
	case 'step4':
		$tpl = include('tpl/step4.jizhi');
	break;
	case 'step5':
		$admin_url = get_domain().'/'.get_admin_url();
		//生成安装锁定文件
		$res = file_put_contents('install.lock','');
		$tpl = include('tpl/step5.jizhi');
	break;
	case 'install_testdb':
		$start= ((int)$_POST['start']==0)?1:$_POST['start'];
		$to=((int)$_POST['to']==0)?1:$_POST['to'];	
		$config = include('../Conf/config.php');
		$db = new PDO("mysql:host=".$config['db']['host'].";port=".$config['db']['port'].";dbname=".$config['db']['dbname'],$config['db']['username'], $config['db']['password']);
		$sql = file_get_contents('test.php');
		$sql = str_replace('jz_',$config['db']['prefix'],$sql);
		$count=100;
		$sql = substr($sql,14);
		$sql.="UPDATE `jz_level` SET `name`='".$_POST['admin_name']."',`pass`='".md5(md5($_POST['admin_pass']).'YF')."' , `regtime` = '".time()."' , `logintime` = ".time()."   WHERE id=1";
		$db->query("set names utf8");
		$db->exec($sql);
		echo json_encode(array('count'=>$count,"start"=>0,"to"=>$count));
		exit;
	break;
	case 'go_install':
		$start= ((int)$_POST['start']==0)?1:$_POST['start'];
		$to=((int)$_POST['to']==0)?1:$_POST['to'];	
		$config = include('../Conf/config.php');
		if($_GET['db']==''){
			$sql = file_get_contents('db.php');
			$sql.="UPDATE `jz_level` SET `name`='".$_POST['admin_name']."',`pass`='".md5(md5($_POST['admin_pass']).'YF')."' , `regtime` = '".time()."' , `logintime` = ".time()."   WHERE id=1";
			$sql = substr($sql,14);
			$sql = str_replace('jz_',$config['db']['prefix'],$sql);
			$count=100;
			$db = new PDO("mysql:host=".$config['db']['host'].";port=".$config['db']['port'].";dbname=".$config['db']['dbname'],$config['db']['username'], $config['db']['password']);
			
			$db->query("set names utf8");	
			$r = $db->exec($sql);
			echo json_encode(array('count'=>$count,"start"=>0,"to"=>$count,'code'=>0));
			exit;
		}else{
			$db = new PDO("mysql:host=".$config['db']['host'].";port=".$config['db']['port'].";dbname=".$config['db']['dbname'],$config['db']['username'], $config['db']['password']);
			
			$db->query("set names utf8");
			//$sql = file_get_contents('../backup/'.$_GET['db']);
			$path = $_GET['db'];
			$filename_arr = explode('.php',$path);
			$filename = $filename_arr[0];
			
			//读取备份数据库
			$dir = '../backup';
			$fileArray=array();
			$fileArray[] = $dir.'/'.$filename.'.php';
			for($i=1;file_exists($dir.'/'.$filename.'_v'.$i.'.php')===true;$i++){
			   $fileArray[]=$dir.'/'.$filename.'_v'.$i.'.php';
			}
			
		    foreach($fileArray as $path){
			   $sql = parseSQL($path);
			   try{
				    $n = $db->exec($sql);
					if(!$n){
						$msg = $db->errorInfo();
						if($msg[2]){
							echo json_encode(array('code'=>1,'msg'=>'数据库错误：' . $msg[2] . end($sql)));exit;
						} 
					}
			   
				
			   }catch (PDOException $e){
			   		echo json_encode(array('code'=>1,'msg'=>$e->getMessage()));
			   		exit;
			   }
			   
		    }
			if($_POST['admin_pass']!='' && $_POST['admin_name']!=''){
				$sql="UPDATE `jz_level` SET `name`='".$_POST['admin_name']."',`pass`='".md5(md5($_POST['admin_pass']).'YF')."' , `regtime` = '".time()."' , `logintime` = ".time()."   WHERE id=1";
				$sql = str_replace('jz_',$config['db']['prefix'],$sql);
				$db->exec($sql);
			}

		    echo json_encode(array('count'=>100,"start"=>0,"to"=>100,'code'=>0));
			exit;
 
		}
		
	
	break;
	case 'testdb':
	try{
		//$_opts_values = array(PDO::ATTR_PERSISTENT=>true,PDO::ATTR_ERRMODE=>2,PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
		//$db = new PDO("mysql:host=".$_POST['host'].";port=".$_POST['port'].";dbname=".$_POST['name'],$_POST['user'], $_POST['password'],$_opts_values); 
		$db = new PDO("mysql:host=".$_POST['host'].";port=".$_POST['port'],$_POST['user'], $_POST['password']);
		$newtable = "CREATE DATABASE IF NOT EXISTS `" . $_POST['name'] . "` DEFAULT CHARACTER SET utf8;";
		if($db->exec($newtable)){
			$db->query("set names utf8");
			echo json_encode(['code'=>0,'msg'=>'success']);
			exit;
		}else{
			echo json_encode(['code'=>1,'msg'=>'您没有创建数据库权限，请手动填写数据库！']);
			exit;	
		}
	}catch(PDOException $e){
		echo json_encode(['code'=>1,'msg'=>'数据库连接失败，请检查数据库配置！']);
		exit;
	}
	
	break;
	default:
	$tpl = include('tpl/index.jizhi');
	break;
}















