<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/01-2019/02
// +----------------------------------------------------------------------


namespace app\home\c;

use frphp\lib\Controller;
class ErrorController extends Controller
{
	//错误处理示例
	function index($msg=''){
		header("HTTP/1.0 404");
        if(APP_DEBUG){
            $msg = format_param($msg,1);
            echo JZLANG('错误信息提示').'：<br/>';
            echo $msg;
        }else{
            echo '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>404</title>
<style>
	body{
		background-color:#444;
		font-size:14px;
	}
	h3{
		font-size:60px;
		color:#eee;
		text-align:center;
		padding-top:30px;
		font-weight:normal;
	}
</style>
</head>

<body>
<h3>404，您请求的文件不存在!</h3>
</body>
</html>';
        }
		

    }
}
	