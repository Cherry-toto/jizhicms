<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/08/24
// +----------------------------------------------------------------------

namespace A\exts;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;
class PluginsController extends Controller {
	
	
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
			if(strpos($_SESSION['admin']['paction'],','.$action.',')==false){
				$ac = M('Ruler')->find(array('fc'=>$action));
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
		//检测是否已安装前台插件
		$filepath = APP_PATH.'Home/plugins/BanipController.php';
		if(file_exists($filepath)){
			JsonReturn(array('code'=>1,'msg'=>'前台Home下面已存在相应的Banip控制器！'));
		}
		//注册到hook里面
		$w['module'] = 'Home';
		$w['namespace'] = 'Home\\\\c';
		$w['controller'] = 'Home';
		$w['action'] = '';
		$w['all_action'] = 1;
		$w['hook_namespace'] = '\\\\Home\\\\plugins';
		$w['hook_controller'] = 'Banip';
		$w['hook_action'] = 'Banip';
		$w['plugins_name'] = 'banip';//插件文件夹
		$w['addtime'] = time();
		M('hook')->add($w);
		return true;
		
	}
	
	//卸载程序,对新增字段、表等进行删除SQL操作，或者其他操作
	public function uninstall(){
		//下面是删除test表的SQL操作
		M('hook')->delete(['plugins_name'=>'banip']);
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
		
		M('plugins')->update(['id'=>$data['id']],['config'=>json_encode($data,JSON_UNESCAPED_UNICODE)]);
		setCache('hook',null);//清空hook缓存
		JsonReturn(['code'=>0,'msg'=>'设置成功！']);
		
	}
	
	
}




