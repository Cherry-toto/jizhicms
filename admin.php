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


// 应用目录为当前目录
define('APP_PATH', __DIR__ . '/');

// 开启调试模式
//define('APP_DEBUG', true);

//定义项目目录
define('APP_HOME','A');

//定义项目模板文件目录
define('HOME_VIEW','t');

//定义项目模板文件目录
define('Tpl_template','tpl');

//定义项目控制器文件目录
define('HOME_CONTROLLER','c');

//定义项目模型文件目录
define('HOME_MODEL','m');

//定义默认控制器
define('DefaultController','Index');

//定义默认方法
define('DefaultAction','Index');

//取消log
define('StopLog',false);

//定义静态文件路径
define('Tpl_style','/A/t/tpl');

// 加载框架文件
require(APP_PATH . 'FrPHP/Fr.php');

// 就这么简单~

