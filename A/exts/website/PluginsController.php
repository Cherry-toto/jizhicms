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
		
		return true;
		
	}
	
	//卸载程序,对新增字段、表等进行删除SQL操作，或者其他操作
	public function uninstall(){
		
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
		//处理数据
		$data_1 = format_param($data['data_1'],2);
		$data_2 = format_param($data['data_2'],2);
		$data_3 = format_param($data['data_3'],2);
		//json存储
		$newdata = [];
		foreach($data_1 as $k=>$v){
			if($v && $v!=''){
				$newdata[]=['website'=>$v,'model'=>$data_2[$k],'tpl'=>$data_3[$k]];
			}
		}
	//	$create = json_encode($newdata,JSON_UNESCAPED_UNICODE);
		
		
		M('plugins')->update(['id'=>$data['id']],['config'=>json_encode($newdata,JSON_UNESCAPED_UNICODE)]);
		setCache('hook',null);//清空hook缓存
		JsonReturn(['code'=>0,'msg'=>'设置成功！']);
		
	}
	
	
	
}




