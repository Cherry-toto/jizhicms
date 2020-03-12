<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/08
// +----------------------------------------------------------------------


namespace A\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;
class PluginsController extends CommonController
{
	
	public function index(){
		
		$dir = APP_PATH.APP_HOME.'/exts';
		$fileArray=array();
		if (false != ($handle = opendir ( $dir ))) {
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != "..") {
					$fileArray[]=$file;
					
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		$pls = M('plugins')->findAll();
		$plugins = [];
		foreach($pls as $k=>$v){
			$plugins[$v['filepath']] = $v;
		}
		$lists = [];
		//检查更新链接是否可以访问
		$api = 'http://api.jizhicms.cn/plugins.php';
		$ch = curl_init();
		$timeout = 3;
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
				$allplugins = $res1['data'];
			}
		}else{
			$isok = false;
		}
		if($isok){
			foreach($allplugins as $k=>$v){
				if(in_array($k,$fileArray)){
					//已下载该插件
					$config = require_once($dir.'/'.$k.'/config.php');
					if(isset($plugins[$k])){
						$lists[$k] = $plugins[$k];
						$lists[$k]['isinstall'] = true;
					}else{
						$lists[$k] = ['name'=>$config['name'],'filepath'=>$k,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'config'=>'','isinstall'=>false];
					}
					if(!version_compare($config['version'],$v['version'],'==')){
						//有更新
						$lists[$k]['isupdate'] = true;
					}else{
						//无更新
						$lists[$k]['isupdate'] = false;
					}
					$lists[$k]['exists'] = true;
					
					
				}else{
					$lists[$k] = $v;
					$lists[$k]['exists'] = false;
					$lists[$k]['description'] = $v['desc'];
					$lists[$k]['isinstall'] = false;
					$lists[$k]['isupdate'] = false;
					
				}
				
				
			}
		}else{
			
			foreach($fileArray as $k=>$v){

				//检查是否安装
				if(strpos($v,'.')===false){
					$config = require_once($dir.'/'.$v.'/config.php');
					if(isset($plugins[$v])){
						$lists[$k] = $plugins[$v];
						$lists[$k]['isinstall'] = true;
					}else{
						$lists[] = ['name'=>$config['name'],'filepath'=>$v,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'config'=>'','isinstall'=>false];
					}
					$lists[$k]['isupdate'] = false;
					$lists[$k]['exists'] = true;
					
				}
				
				
				
			}
			
		}
		
		
		$this->lists = $lists;
		
		$this->display('plugins-list');
		
		
	}
	
	//更改软件状态
	function change_status(){
		$filepath = $this->frparam('filepath',1);
		if($filepath){
			$plugins = M('plugins')->find(['filepath'=>$filepath]);
			if($plugins['isopen']==1){
				$isopen = 0;
			}else{
				$isopen = 1;
			}
			M('plugins')->update(['id'=>$plugins['id']],['isopen'=>$isopen]);
			
			//检测插件是否注册hook
			if(M('hook')->find(['plugins_name'=>$filepath])){
				M('hook')->update(['plugins_name'=>$filepath],['isopen'=>$isopen]);
			}
			setCache('hook',null);
			JsonReturn(array('code'=>0,'msg'=>'success'));
		}
		JsonReturn(array('code'=>1,'msg'=>'参数错误！'));
		
		
	}
	
	//安装下载
	function action_do(){
		$filepath = $this->frparam('path',1);
		$type = $this->frparam('type');
		$dir = APP_PATH.APP_HOME.'/exts';
		if($filepath){
			if($type){
				//安装
				//执行插件控制器安装程序
				require_once($dir.'/'.$filepath.'/PluginsController.php');
				$plg = new \A\exts\PluginsController($this->frparam());
				
				
				$step1 = $plg->install();//执行安装
				if(!$step1){
					JsonReturn(array('code'=>1,'msg'=>'执行插件安装程序失败！'));
				}

				$config = require_once($dir.'/'.$filepath.'/config.php');
				$w = ['name'=>$config['name'],'filepath'=>$filepath,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'config'=>'','addtime'=>time()];
				if(M('plugins')->find(['filepath'=>$w['filepath']])){
					JsonReturn(array('code'=>1,'msg'=>'插件已安装，请勿重复操作！'));
				}
				//复制文件到对应文件夹
				//移动前台插件控制器
				$sourcefile = $dir.'/'.$filepath.'/controller/home';
				$target = APP_PATH.'Home/plugins';
				if(is_dir($sourcefile) && is_dir($target)){
					if (false != ($handle = opendir ( $sourcefile ))) {
					
						while ( false !== ($file = readdir ( $handle )) ) {
							//去掉"“.”、“..”以及带“.xxx”后缀的文件
							if ($file != "." && $file != "..") {
								$fs = $sourcefile.'/'.$file;
								$ft = $target.'/'.$file;
								$r = $this->file2dir($fs,$ft);
								if(!$r){
									JsonReturn(array('code'=>1,'msg'=>'插件安装失败！sourcefile:'.$fs.' targetfile:'.$ft));
								}
							}
						}
						//关闭句柄
						closedir ( $handle );
					}
					
				}
				
				//移动后台插件控制器
				$sourcefile = $dir.'/'.$filepath.'/controller/admin';
				$target = APP_PATH.'A/plugins';
				if(is_dir($sourcefile) && is_dir($target)){
					if (false != ($handle = opendir ( $sourcefile ))) {
						
						while ( false !== ($file = readdir ( $handle )) ) {
							//去掉"“.”、“..”以及带“.xxx”后缀的文件
							if ($file != "." && $file != "..") {
								$fs = $sourcefile.'/'.$file;
								$ft = $target.'/'.$file;
								$r = $this->file2dir($fs,$ft);
								if(!$r){
									JsonReturn(array('code'=>1,'msg'=>'插件安装失败！sourcefile:'.$fs.' targetfile:'.$ft));
								}
							}
						}
						//关闭句柄
						closedir ( $handle );
					}
				
				}
				
				
				//移动扩展类文件
				$src = $dir.'/'.$filepath.'/class';
				$dst = APP_PATH.'FrPHP/Extend';
				if(is_dir($src)){
					$this->recurse_copy($src,$dst);
				}
				
				
				$res = M('plugins')->add($w);
				
				setCache('hook',null);
				
				
				JsonReturn(array('code'=>0,'msg'=>'安装成功！'));
			}else{
				//卸载
				$config = require_once($dir.'/'.$filepath.'/config.php');
				$w = ['filepath'=>$filepath];
				$plugins = M('plugins')->find($w);
				if(!$plugins){
					JsonReturn(array('code'=>1,'msg'=>'插件已移除，请勿重复操作！'));
				}
				//移除文件夹
				$target = APP_PATH.'Home/plugins';
				$sourcefile = $dir.'/'.$filepath.'/controller/home';
				if(is_dir($sourcefile) && is_dir($target)){
					if (false != ($handle = opendir ( $sourcefile ))) {
						
						while ( false !== ($file = readdir ( $handle )) ) {
							//去掉"“.”、“..”以及带“.xxx”后缀的文件
							if ($file != "." && $file != "..") {
								$ft = $target.'/'.$file;
								if(file_exists($ft)){
									unlink($ft);
								}
								
							}
						}
						//关闭句柄
						closedir ( $handle );
					}
				}
				$target = APP_PATH.'A/plugins';
				$sourcefile = $dir.'/'.$filepath.'/controller/admin';
				if(is_dir($sourcefile) && is_dir($target)){
					if (false != ($handle = opendir ( $sourcefile ))) {
						
						while ( false !== ($file = readdir ( $handle )) ) {
							//去掉"“.”、“..”以及带“.xxx”后缀的文件
							if ($file != "." && $file != "..") {
								$ft = $target.'/'.$file;
								if(file_exists($ft)){
									unlink($ft);
								}
								
							}
						}
						//关闭句柄
						closedir ( $handle );
					}
				}
				//移动扩展类文件
				$src = $dir.'/'.$filepath.'/class';
				$dst = APP_PATH.'FrPHP/Extend';
				if(is_dir($src)){
					$this->recurse_copy($src,$dst);
				}
				
				
				
				
				//执行插件控制器卸载程序
				require_once($dir.'/'.$filepath.'/PluginsController.php');
				$plg = new \A\exts\PluginsController($this->frparam());
				
				$step1 = $plg->uninstall();//执行安装
				if(!$step1){
					JsonReturn(array('code'=>1,'msg'=>'执行插件卸载程序失败！'));
				}
				$res = M('plugins')->delete(['id'=>$plugins['id']]);
				
				setCache('hook',null);
				
				JsonReturn(array('code'=>0,'msg'=>'卸载成功！'));
				
				
			}
			
			
		}
		JsonReturn(array('code'=>1,'msg'=>'参数错误！'));
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

	
	//配置信息
	function setconf(){
		$id = $this->frparam('id');
		$plugins = M('plugins')->find(['id'=>$id]);
		if($id && $plugins){
			//忽略Notice报错
			error_reporting(E_ALL^E_NOTICE);
			
			//执行插件控制器卸载程序
			$dir = APP_PATH.APP_HOME.'/exts';
			require_once($dir.'/'.$plugins['filepath'].'/PluginsController.php');
			$plg = new \A\exts\PluginsController($this->frparam());
			//转入插件内部处理
			if($_POST){
				
				$plg->setconfigdata($this->frparam());//传入插件内部处理
				exit;
				
			}
			
			
			$plg->setconf($plugins);
			exit;
		}
		JsonReturn(array('code'=>1,'msg'=>'参数错误,必须携带插件ID！'));
		
		//Error('参数错误！');
		
	}
	
	//安装说明
	function desc(){
		$filepath = $this->frparam('filepath',1);
		
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
		JsonReturn(array('code'=>1,'msg'=>'参数错误,必须携带插件ID！'));
	}
	
	//更新插件
	function update(){
		$filepath = $this->frparam('filepath',1);
		if($filepath){
			if($this->frparam('action',1)){
				$action = $this->frparam('action',1);
				// 自己获取这些信息
				$remote_url  = urldecode($this->frparam('download_url',1));
				$file_size   = $this->frparam('filesize',1);
				$tmp_path    = Cache_Path."/update_".$filepath.".zip";//临时下载文件路径
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
				            // 做些日志处理
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
				            JsonReturn(['code'=>1,'msg'=>'发生错误：'.$e->getMessage()]);
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
						   JsonReturn(['code'=>1,'msg'=>'下载缓存文件不存在！']);
						}
						$path = APP_PATH.'A/exts/';
						$resource = zip_open($tmp_path);//打开压缩包
						while($dir_resource = zip_read($resource)){//遍历读取压缩包里面的一个个文件  
						if(zip_entry_open($resource,$dir_resource)){//如果能打开则继续
						$file_name = $path.zip_entry_name($dir_resource);//获取当前项目的名称,即压缩包里面当前对应的文件名  
						$file_path=substr($file_name,0,strrpos($file_name,"/"));
						if (!is_dir($file_path)){//如果路径不存在，则创建一个目录，true表示可以创建多级目录  
						mkdir($file_path, 0777, true);
						}
						if(!is_dir($file_name)){//如果不是目录，则写入文件
						$file_size=zip_entry_filesize($dir_resource);//读取这个文件
						if($file_size<(1024*1024*200)){//最大读取200M，如果文件过大，跳过解压，继续下一个
						$file_content = zip_entry_read($dir_resource, $file_size);
						file_put_contents($file_name, $file_content);
						}else{
						return false;
						}
						}
						zip_entry_close($dir_resource);//关闭当前
						}
						} 
						zip_close($resource); //关闭压缩包
						if($filepath=='jizhicmsupdate'){
							$isinstall = true;
						}else{
							if(M('plugins')->find(['filepath'=>$filepath])){
								$isinstall = true;
							}else{
								$isinstall = false;
							}
						}
						
						JsonReturn(['code'=>0,'msg'=>'解压完成！','isinstall'=>$isinstall]);
				    	break;
				    case 'plugin-install':
				    	$dir = APP_PATH.'A/exts';
				    	require_once($dir.'/'.$filepath.'/PluginsController.php');
						$plg = new \A\exts\PluginsController($this->frparam());
						
						
						$step1 = $plg->install();//执行安装
						if(!$step1){
							JsonReturn(array('code'=>1,'msg'=>'执行插件安装程序失败！'));
						}

						$config = require_once($dir.'/'.$filepath.'/config.php');
						
						$plg_old = M('plugins')->find(['filepath'=>$filepath]);
						if($plg_old){
							//保存原配置
							$w = ['name'=>$config['name'],'filepath'=>$filepath,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'config'=>$plg_old['config'],'addtime'=>time()];
						}else{
							$w = ['name'=>$config['name'],'filepath'=>$filepath,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'config'=>'','addtime'=>time()];
						}
						//复制文件到对应文件夹
						//移动前台插件控制器
						$sourcefile = $dir.'/'.$filepath.'/controller/home';
						$target = APP_PATH.'Home/plugins';
						if(is_dir($sourcefile) && is_dir($target)){
							if (false != ($handle = opendir ( $sourcefile ))) {
							
								while ( false !== ($file = readdir ( $handle )) ) {
									//去掉"“.”、“..”以及带“.xxx”后缀的文件
									if ($file != "." && $file != "..") {
										$fs = $sourcefile.'/'.$file;
										$ft = $target.'/'.$file;
										$r = $this->file2dir($fs,$ft);
										if(!$r){
											JsonReturn(array('code'=>1,'msg'=>'插件安装失败！sourcefile:'.$fs.' targetfile:'.$ft));
										}
									}
								}
								//关闭句柄
								closedir ( $handle );
							}
							
						}
						
						//移动后台插件控制器
						$sourcefile = $dir.'/'.$filepath.'/controller/admin';
						$target = APP_PATH.'A/plugins';
						if(is_dir($sourcefile) && is_dir($target)){
							if (false != ($handle = opendir ( $sourcefile ))) {
								
								while ( false !== ($file = readdir ( $handle )) ) {
									//去掉"“.”、“..”以及带“.xxx”后缀的文件
									if ($file != "." && $file != "..") {
										$fs = $sourcefile.'/'.$file;
										$ft = $target.'/'.$file;
										$r = $this->file2dir($fs,$ft);
										if(!$r){
											JsonReturn(array('code'=>1,'msg'=>'插件安装失败！sourcefile:'.$fs.' targetfile:'.$ft));
										}
									}
								}
								//关闭句柄
								closedir ( $handle );
							}
						
						}
						
						
						//移动扩展类文件
						$src = $dir.'/'.$filepath.'/class';
						$dst = APP_PATH.'FrPHP/Extend';
						if(is_dir($src)){
							$this->recurse_copy($src,$dst);
						}
						
						
						$res = M('plugins')->update(['filepath'=>$filepath],$w);
						
						setCache('hook',null);
						
						
						JsonReturn(array('code'=>0,'msg'=>'安装成功！'));
				    	break;
				    default:
				        # code...
				        break;
				}
			}
			$config = require_once(APP_PATH.'A/exts/'.$filepath.'/config.php');
			$r = file_get_contents('http://api.jizhicms.cn/plugins.php?name='.$filepath.'&v='.$config['version']);
			$rr = json_decode($r,1);
			if($rr['code']==0){
				$this->plugin = $config;
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
				$this->filepath = $filepath;
				$this->display('plugins-update');exit;
			}else{
				//JsonReturn(array('code'=>1,'msg'=>'该插件暂无更新！'));
				exit('该插件暂无更新！');
			}

			
		}
		JsonReturn(array('code'=>1,'msg'=>'参数错误,必须携带插件ID！'));
	}

	

	
}