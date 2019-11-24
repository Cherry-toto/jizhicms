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
		foreach($fileArray as $k=>$v){
			//检查是否安装
			if(isset($plugins[$v])){
				$lists[$k] = $plugins[$v];
				$lists[$k]['isinstall'] = true;
			}else{
				$config = require_once($dir.'/'.$v.'/config.php');
				$lists[] = ['name'=>$config['name'],'filepath'=>$v,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module'],'isopen'=>0,'config'=>'','isinstall'=>false];
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
				$w = ['name'=>$config['name'],'filepath'=>$filepath,'description'=>$config['desc'],'version'=>$config['version'],'author'=>$config['author'],'update_time'=>strtotime($config['update_time']),'module'=>$config['module']];
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
	



	
}