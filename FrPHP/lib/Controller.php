<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2018/02
// +----------------------------------------------------------------------


namespace FrPHP\lib;

/**
 * 控制器基类
 */
class Controller
{
    protected $_controller;
    protected $_action;
    protected $_view;
    protected $_data;

    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($param=null)
    {
        $this->_controller = APP_CONTROLLER;
        $this->_action = APP_ACTION;
		$this->_data = $param;
		//对语言包获取优先处理
		if(!empty($_GET) && isset($_GET[APP_LANG_REQUREST])){
			$_SESSION['lang'] = $_GET[APP_LANG_REQUREST];
		}
		
        $this->_view = new View(APP_CONTROLLER, APP_ACTION);
		$this->_init();
		
    }
	// 自动调用方法
	public function _init(){
		
	}

    // 分配变量
	public function __set($name, $value)
	{
		 $this->$name = $value;
		 $this->_view->assign($name, $value);
		
	}
    public function assign($name, $value)
    {
		
        $this->_view->assign($name, $value);
    }

    // 渲染视图
    public function display($name=null)
    {
        $this->_view->render($name);
    }
	
	// 获取URL参数值
	public function frparam($str=null, $int=0,$default = FALSE, $method = null){
		
		$data = $this->_data;
		if($str===null) return $data;
		if(!array_key_exists($str,$data)){
			return ($default===FALSE)?false:$default;
		}
		
		if($method===null){
			$value = $data[$str];
		}else{
			$method = strtolower($method);
			switch($method){
				case 'get':
				$value = $_GET[$str];
				break;
				case 'post':
				$value = $_POST[$str];
				break;
				case 'cookie':
				$value = $_COOKIE[$str];
				break;
				
			} 
		}
		
		return format_param($value,$int,$default);
		
		
	}
	


}