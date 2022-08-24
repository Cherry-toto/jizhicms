<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2022/01
// +----------------------------------------------------------------------


namespace app\admin\c;


use frphp\extend\Page;
class TemplateController extends CommonController
{
	
	private $backupPath = '';
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
    private $template_name = '';//模板文件夹
	
	public function index(){
		//检查更新链接是否可以访问
		$webapi = $this->webconf['template_config'];
		if(!$webapi){
			$webapi = 'http://api.jizhicms.cn/template.php';
			if(!M('sysconfig')->find(['field'=>'template_config'])){
				M('sysconfig')->add(['title'=>JZLANG('插件配置'),'field'=>'template_config','type'=>2,'data'=>$webapi,'typeid'=>0]);
				setCache('webconfig',null);
			}
		}
		if($this->frparam('set')){
            if($this->admin['isadmin']!=1){
                JsonReturn(['code'=>1,'msg'=>JZLANG('非超级管理员无法设置！')]);
            }
			$webapi = $this->frparam('webapi',1);
			M('sysconfig')->update(['field'=>'template_config'],['data'=>$webapi]);
			setCache('webconfig',null);
			JsonReturn(['code'=>0,'msg'=>JZLANG('配置成功！')]);
		}
		$this->webapi = $webapi;
		$api = $webapi.'?version='.$this->webconf['web_version'];
		$templates = getCache('templatelist');
		if(!$templates){
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch,CURLOPT_URL,$api);
			$res = curl_exec($ch);
			$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
			curl_close($ch);
			if($httpcode==200){
				$isok = true;
				$res1 = json_decode($res,1);
				if($res1['code']!=0){
					$isok = false;
				}else{
					$templates = $res1['data'];
					setCache('templatelist',$templates,86400);
				}
			}else{
				$isok = false;
			}
		}else{
			$isok = true;
		}
		
		$this->title = $this->frparam('title',1,'');
		$dir = APP_PATH.'static';
		$isdown = $this->frparam('isdown');
		$pc_template = $this->webconf['pc_template'];
		$iswap = $this->webconf['iswap'];
		$wap_template = $iswap ? $this->webconf['wap_template'] : $pc_template;
		$weixin_template = $iswap ? $this->webconf['weixin_template'] : $pc_template;
		$installtemplate = [$pc_template,$wap_template,$weixin_template];
		switch($isdown){
			case 1:
			//本地-已下载
				$fileArray=array();
				if (false != ($handle = opendir ( $dir ))) {
					while ( false !== ($file = readdir ( $handle )) ) {
						//去掉"“.”、“..”以及带“.xxx”后缀的文件
						if ($file != "." && $file != ".."  && strpos($file,'.')===false && file_exists($dir.'/'.$file.'/info.php')) {
							$fileArray[]=$file;
						}
					}
					//关闭句柄
					closedir ( $handle );
				}
				
				$lists = $fileArray;
				foreach($lists as $k=>$v){
					//已下载该插件
					if(!file_exists($dir.'/'.$v.'/info.php')){
						continue;
					}
					$config = require_once($dir.'/'.$v.'/info.php');
					$lists[$k] = ['name'=>$config['name'],'template'=>$v,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'web'=>$config['web'],'isinstall'=>false,'thumbnail'=>$config['thumbnail']];
					
					if($isok && array_key_exists($v,$templates) && version_compare($config['version'],$templates[$v]['version'],'<')){
						//有更新
						$lists[$k]['isupdate'] = true;
						$lists[$k]['official'] = 2;//本地
					}else{
						//无更新
						$lists[$k]['isupdate'] = false;
						$lists[$k]['official'] =  2;
					}
					$lists[$k]['exists'] = true;
					$lists[$k]['install'] = in_array($v,$installtemplate);
				
					
				}
				
				
				
			break;
			default:
				$fileArray=array();
				if (false !== ($handle = opendir ( $dir ))) {
					while ( false !== ($file = readdir ( $handle )) ) {
						//去掉"“.”、“..”以及带“.xxx”后缀的文件
						if ($file != "." && $file != ".."  && strpos($file,'.')===false) {
							$fileArray[]=$file;
							
						}
					}
					//关闭句柄
					closedir ( $handle );
				}
				$lists = [];		
				if($isok){
					foreach($templates as $k=>$v){
						if(in_array($k,$fileArray)){
							//已下载该插件
							if(!file_exists($dir.'/'.$k.'/info.php')){
								continue;
							}
							$config = require_once($dir.'/'.$k.'/info.php');
							$lists[$k] = ['name'=>$config['name'],'template'=>$k,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'web'=>$config['web'],'isinstall'=>false,'thumbnail'=>$config['thumbnail']];
							if(version_compare($config['version'],$v['version'],'<')){
								//有更新
								$lists[$k]['isupdate'] = true;
							}else{
								//无更新
								$lists[$k]['isupdate'] = false;
							}
							$lists[$k]['exists'] = true;
							$lists[$k]['official'] = 2;
							
						}else{
							$lists[$k] = $v;
							$lists[$k]['exists'] = false;
							$lists[$k]['description'] = $v['desc'];
							$lists[$k]['isinstall'] = false;
							$lists[$k]['isupdate'] = false;
							$lists[$k]['official'] = $v['official'];
							
						}
						$lists[$k]['install'] = in_array($k,$installtemplate);
						
						
					}
					
					//检查软件是否还有自定义软件未加入进去
					foreach($fileArray as $v){
						if(!isset($lists[$v])){
							//已下载该插件
							if(!file_exists($dir.'/'.$v.'/info.php')){
								continue;
							}
							$config = require_once($dir.'/'.$v.'/info.php');
							$lists[$v] = ['name'=>$config['name'],'template'=>$v,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'web'=>$config['web'],'isinstall'=>false,'thumbnail'=>$config['thumbnail']];
							$lists[$v]['isupdate'] = false;
							$lists[$v]['exists'] = true;
							$lists[$v]['official'] = 2;
							$lists[$v]['install'] = in_array($v,$installtemplate);
						}
					}
					
					
				}else{
					
					foreach($fileArray as $k=>$v){

						//检查是否安装
						if(strpos($v,'.')===false){
							if(!file_exists($dir.'/'.$v.'/info.php')){
								continue;
							}
							$config = require_once($dir.'/'.$v.'/info.php');
							$lists[] = ['name'=>$config['name'],'template'=>$v,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'web'=>$config['web'],'isinstall'=>false];
							$lists[$k]['isupdate'] = false;
							$lists[$k]['exists'] = true;
							$lists[$k]['official'] = 2;//本地
							$lists[$k]['install'] = in_array($v,$installtemplate);
							
						}
						
						
						
					}
					
				}
				
			
			break;
			
			
		
			
		}
		
		
			if($this->title){
				$newlist = [];
				foreach($lists as $v){
					if(stripos($v['name'],$this->title)!==false){
						$newlist[]=$v;
					}
					
				}
				$lists  = $newlist;
			}
			$arraypage = new \ArrayPage($lists);
			$data = $arraypage->query(['page'=>$this->frparam('page',0,1),'title'=>$this->title,'isdown'=>$this->frparam('isdown')])->setPage(['limit'=>$this->frparam('limit',0,12),'page'=>$this->frparam('page',0,1)])->go();
			$this->pages = $arraypage->pageList();
			$this->sum = $arraypage->sum;//总数据
			$this->listpage = $arraypage->listpage;//分页数组-自定义分页可用
			$this->prevpage = $arraypage->prevpage;//上一页
			$this->nextpage = $arraypage->nextpage;//下一页
			$this->allpage = $arraypage->allpage;//总页数
			$this->lists = $data;
			
			
			$this->display('template-list');
		
		
		
		
	}

	//复制图片  file2dir("01/5.jpg", "01/successImg/a.jpg");
	function file2dir($sourcefile, $filename){
		 if( !file_exists($sourcefile)){
			 return false;
		 }
		 //$filename = basename($sourcefile);
		 return copy($sourcefile,  $filename);
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

	//安装说明
	function desc(){
		$filepath = $this->frparam('filepath',1);
		if(strpos($filepath,'.')!==false){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('参数存在安全隐患！')));
		}
		if($filepath){
			//忽略Notice报错
			error_reporting(E_ALL^E_NOTICE);
			
			//执行插件控制器卸载程序
			$dir = APP_PATH.APP_HOME.'/exts';
			require_once($dir.'/'.$filepath.'/PluginsController.php');
			$plg = new \A\exts\PluginsController($this->frparam());
			
			$plg->desc();
			exit;
		}
		JsonReturn(array('code'=>1,'msg'=>JZLANG('参数错误,必须携带插件ID！')));
	}
	
	//下载安装更新
	function update(){
		$template = $this->frparam('template',1);
		if(strpos($template,'.')!==false){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('参数存在安全隐患！')));
		}
        $this->template_name = $template;
		$dir = APP_PATH.'static';
		if($template){
			if($this->frparam('action',1)){
				$action = $this->frparam('action',1);
				// 自己获取这些信息
				$remote_url  = urldecode($this->frparam('download_url',1));
				$remote_url = strpos($remote_url,'?')!==false ? $remote_url.'&version='.$this->webconf['web_version'] : $remote_url.'?version='.$this->webconf['web_version'];
				$file_size   = $this->frparam('filesize',1);
				$tmp_path    = Cache_Path."/update_".$template.".zip";//临时下载文件路径
				switch ($action) {
				    case 'prepare-download':
				    	$code = 0;
						ob_start(); 
						$ch=curl_init($remote_url); 
						curl_setopt($ch,CURLOPT_HEADER,1); 
						curl_setopt($ch,CURLOPT_NOBODY,1); 
						$okay=curl_exec($ch); 
						curl_close($ch); 
						$head=ob_get_contents(); 
						ob_end_clean(); 
						$regex='/Content-Length:\s([0-9].+?)\s/'; 
						$count=preg_match($regex,$head,$matches); 
						$filesize = isset($matches[1])&&is_numeric($matches[1])?$matches[1]:0; 

				        JsonReturn(array('code'=>0,'size'=>$filesize));
				        break;
				    case 'start-download':
				        // 这里检测下 tmp_path 是否存在
				        try {
				            set_time_limit(0);
				            touch($tmp_path);
				            if ($fp = fopen($remote_url, "rb")) {
				                if (!$download_fp = fopen($tmp_path, "wb")) {
				                    exit;
				                }
				                while (!feof($fp)) {
				                    if (!file_exists($tmp_path)) {
				                        // 如果临时文件被删除就取消下载
				                        fclose($download_fp);
				                        exit;
				                    }
				                    fwrite($download_fp, fread($fp, 1024 * 8 ), 1024 * 8);
				                }
				                fclose($download_fp);
				                fclose($fp);
				            } else {
				                exit;
				            }
				        } catch (Exception $e) {
				            Storage::remove($tmp_path);
				            JsonReturn(['code'=>1,'msg'=>JZLANG('发生错误').'：'.$e->getMessage()]);
				        }

				        JsonReturn(['code'=>0,'tmp_path'=>$tmp_path]);
				        break;
				    case 'get-file-size':
				        // 这里检测下 tmp_path 是否存在
				        if (file_exists($tmp_path)) {
				            
				            JsonReturn(['code'=>0,'size'=>filesize($tmp_path)]);
				        }
				        break;
				    case 'file-upzip':
				    	if (!file_exists($tmp_path)) {//先判断待解压的文件是否存在
						   JsonReturn(['code'=>1,'msg'=>JZLANG('下载缓存文件不存在！')]);
						}
						//$msg = $this->upzip($tmp_path,$dir);
						$msg = $this->get_zip_originalsize($tmp_path,$dir.'/');
                        setCache('templatelist',null);
						JsonReturn(['code'=>0,'msg'=>$msg,'isinstall'=>true]);
				    	break;
				    case 'template-install':
					
						$tpl = $this->frparam('tpl',2);
						if(!count($tpl)){
							JsonReturn(array('code'=>1,'msg'=>JZLANG('请选择使用场景！')));
						}
				    	if(file_exists($dir.'/'.$template.'/install/TemplateController.php')){
							//直接设置模板
							require_once($dir.'/'.$template.'/install/TemplateController.php');
							$plg = new \TemplateController($this->frparam());
							$step1 = $plg->install();//执行安装
							if(!$step1){
								JsonReturn(array('code'=>1,'msg'=>JZLANG('执行插件安装程序失败！')));
							}
						}
						if(in_array('pc',$tpl)){
							M('sysconfig')->update(['field'=>'pc_template'],['data'=>$template]);
						}
						if(in_array('wap',$tpl)){
							M('sysconfig')->update(['field'=>'wap_template'],['data'=>$template]);
							M('sysconfig')->update(['field'=>'iswap'],['data'=>1]);
						}
						if(in_array('wechat',$tpl)){
							M('sysconfig')->update(['field'=>'weixin_template'],['data'=>$template]);
							M('sysconfig')->update(['field'=>'iswap'],['data'=>1]);
						}
						
						setCache('webconfig',null);
                        setCache('hometpl',null);
                        setCache('wxhometpl',null);
                        setCache('mobilehometpl',null);
                        setCache('templatelist',null);
						JsonReturn(array('code'=>0,'msg'=>JZLANG('安装成功！')));
				    	break;
					case 'backup':
						if(!is_dir(APP_PATH.'static/'.$template.'/backup')){
							mkdir(APP_PATH.'static/'.$template.'/backup',0777);
						}
						$this->backupPath = APP_PATH.'static/'.$template.'/backup';
						$this->toBackup();
						JsonReturn(array('code'=>0,'msg'=>JZLANG('备份成功！')));
						break;
				    default:
				        # code...
				        break;
				}
			}
			$config = require_once(APP_PATH.'static/'.$template.'/info.php');
			$webapi = $this->webconf['template_config'];
			$r = file_get_contents($webapi.'?name='.$template.'&v='.$config['version']);
			$rr = json_decode($r,1);
			if($rr['code']==0){
				$this->templatedata = $config;
				$this->data = $rr['data'];
				//获取远程文件大小
				$downurl = $rr['data']['url'];
				ob_start(); 
				$ch=curl_init($downurl); 
				curl_setopt($ch,CURLOPT_HEADER,1); 
				curl_setopt($ch,CURLOPT_NOBODY,1); 
				$okay=curl_exec($ch); 
				curl_close($ch); 
				$head=ob_get_contents(); 
				ob_end_clean(); 
				$regex='/Content-Length:\s([0-9].+?)\s/'; 
				$count=preg_match($regex,$head,$matches); 
				$filesize = isset($matches[1])&&is_numeric($matches[1])?$matches[1]:0; 
				$this->filesize = $filesize;
				$this->filepath = $template;
				$this->display('template-update');exit;
			}else{
				exit(JZLANG('该插件暂无更新！'));
			}

			
		}
		JsonReturn(array('code'=>1,'msg'=>JZLANG('参数错误,请选择对应模板！')));
	}
	
	function upzip($filename,$path){
		//先判断待解压的文件是否存在
		if(!file_exists($filename)){
			JsonReturn(['code'=>1,'msg'=>$filename.JZLANG('文件不存在！')]);
		}
		$zip = new \ZipArchive;
		$starttime = explode(' ',microtime()); //解压开始的时间
		if ($zip->open($filename) === TRUE) {//中文文件名要使用ANSI编码的文件格式
		  $zip->extractTo($path);//提取全部文件
		  //$zip->extractTo('/my/destination/dir/', array('pear_item.gif', 'testfromfile.php'));//提取部分文件
		  $zip->close();
		  $endtime = explode(' ',microtime()); //解压结束的时间
		  $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		  $thistime = round($thistime,3); //保留3为小数
		  $msg = JZLANG("解压完毕！本次解压花费")."：$thistime ".JZLANG("秒")."。";
		  return $msg;
		} else {
		  return JZLANG('解压失败！');
		  
		}
		
	}
	
	function get_zip_originalsize($filename, $path) {
	  //先判断待解压的文件是否存在
	  if(!file_exists($filename)){
		 //die("文件 $filename 不存在！");
		 JsonReturn(['code'=>1,'msg'=>$filename.JZLANG('文件不存在！')]);
	  }
	  $starttime = explode(' ',microtime()); //解压开始的时间

	  //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
	  //$filename = iconv("utf-8","gb2312",$filename);
	  //$path = iconv("utf-8","gb2312",$path);
	  //打开压缩包
	  $resource = zip_open($filename);
	  $i = 1;
	  //遍历读取压缩包里面的一个个文件
	  while ($dir_resource = zip_read($resource)) {
		//如果能打开则继续
		if (zip_entry_open($resource,$dir_resource)) {
		  //获取当前项目的名称,即压缩包里面当前对应的文件名
		  $file_name = $path.zip_entry_name($dir_resource);
		  //以最后一个“/”分割,再用字符串截取出路径部分
		  $file_path = substr($file_name,0,strrpos($file_name, "/"));
		  //如果路径不存在，则创建一个目录，true表示可以创建多级目录
		  if(!is_dir($file_path)){
			mkdir($file_path,0777,true);
		  }
		  //如果不是目录，则写入文件
		  if(!is_dir($file_name)){
			//读取这个文件
			$file_size = zip_entry_filesize($dir_resource);
			//最大读取6M，如果文件过大，跳过解压，继续下一个
			$file_content = zip_entry_read($dir_resource,$file_size);
			file_put_contents($file_name,$file_content);
		  }
		  //关闭当前
		  zip_entry_close($dir_resource);
		}
	  }
	  //关闭压缩包
	  zip_close($resource);
	  $endtime = explode(' ',microtime()); //解压结束的时间
	  $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
	  $thistime = round($thistime,3); //保留3为小数
	  $msg = JZLANG("解压完毕！本次解压花费")."：$thistime ".JZLANG("秒")."。";
	  return $msg;
	}
	

	//备份数据库
	private function toBackup(){
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
	/**
	  * 备份当前数据库
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
	   $this->error = JZLANG('数据库中没有表!');
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
	  $backfile = $this->config['target']==''? $this->template_name.'-'.$this->config['database'].'_'.date('Y_m_d_H_i_s').'_'.rand(100000,999999): $this->config['target'].date('YmdHis');//文件名
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
					$isok = file_put_contents($this->backupPath.'/'.$backfile.'.php', $str);
					if(!$isok){
						JsonReturn(['code'=>1,'msg'=>'[ '.$this->backupPath.'/'.$backfile.'.php ] '.JZLANG('写入文件失败！')]);
					}
					$filenum++;
				}else{
					$isok = file_put_contents($this->backupPath.'/'.$backfile.'_v'.$filenum.'.php', $str);
					if(!$isok){
						JsonReturn(['code'=>1,'msg'=>'[ '.$this->backupPath.'/'.$backfile.'_v'.$filenum.'.php ]'.JZLANG(' 写入文件失败！')]);
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
				$isok = file_put_contents($this->backupPath.'/'.$backfile.'.php', $str);
				if(!$isok){
					JsonReturn(['code'=>1,'msg'=>'[ '.$this->backupPath.'/'.$backfile.'.php ] '.JZLANG('写入文件失败！')]);
				}
			}else{
				$isok = file_put_contents($this->backupPath.'/'.$backfile.'_v'.$filenum.'.php', $str);
				if(!$isok){
					JsonReturn(['code'=>1,'msg'=>'[ '.$this->backupPath.'/'.$backfile.'_v'.$filenum.'.php ] '.JZLANG('写入文件失败！')]);
				}
			}
	  }

	 }
	



}