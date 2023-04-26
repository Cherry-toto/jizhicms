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



/*****************
 * 核心公共函数集*
 *****************/


/**
 * 实例化一个没有模型文件的Model
 *@name   string   模块名
 *
 */


function M($name=null) {
	if(empty($name)){
		$path = 'frphp\\lib\\Model';
		return $path::getInstance();
	}
    $name = ucfirst($name);
	if($name==''){
		return '缺少模型类！';
	}else{
		$table = $name;
		$name = APP_HOME.'\\'.HOME_MODEL.'\\'.$name.'Model';
		if(!class_exists($name)){
			$path = 'frphp\\lib\\Model';
			return $path::getInstance($table);
		}else{
			return $name::getInstance($table);
		}
		
	}
}


/**
	参数过滤，格式化
**/
function format_param($value=null,$int=0,$default=false){
	if($value==null){ return '';}
	if($value===false && $default!==false){ return $default;}
	switch ($int){
		case 0://整数
			return (int)$value;
		case 1://字符串
			$value = SafeFilter($value);
			$value=htmlspecialchars(trim($value), ENT_QUOTES);
            $value = addslashes($value);
			
			return $value;
		case 2://数组
			if($value=='')return '';
			array_walk_recursive($value, "array_format");
			return $value;
		case 3://浮点
			return (float)$value;
		case 4:
            $value = addslashes($value);
            $value = SafeFilter($value);
			return trim($value);
        case 5:
            $value = SafeFilter($value);
            $value=htmlspecialchars(trim($value), ENT_QUOTES);
            $value = addslashes($value);
            $ra=Array('select','insert','update','delete');
            return str_ireplace($ra,'',$value);
        case 6:
            $value = addslashes($value);
            $value= strip_tags($value, "<a><p><img><table><span><strong><h1><h2><h3><h4><h5><h6><div><ul><ol><li><form><input><header><td><tr><th><thead><tbody><source><area><aside><video><pre><code><i><font><audio><b><article><cite><dd><dl><em><section><small><del><hr><br>");
            $value = SafeFilter($value);
            return trim($value);
	}
}
//过滤XSS攻击
function SafeFilter($arr) 
{
   $ra=Array('/([\x00-\x08])/','/([\x0b-\x0c])/','/([\x0e-\x19])/');
   $arr = preg_replace($ra,'',$arr);   
   return $arr;
}
function array_format(&$item, $key)
{
	$item=trim($item);
    $item = SafeFilter($item);
	$item=htmlspecialchars($item, ENT_QUOTES);
    $item = addslashes($item);
}

function unicodeEncode($str){
    //split word
    preg_match_all('/./u',$str,$matches);
 
    $unicodeStr = "";
    foreach($matches[0] as $m){
        //拼接
        $unicodeStr .= "&#".base_convert(bin2hex(iconv('UTF-8',"UCS-4",$m)),16,10);
    }
    return $unicodeStr;
}

function unicodeDecode($unicode_str){
    $json = '{"str":"'.$unicode_str.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}

/**
	
	格式化数组为pathinfo格式

**/

function array_pathInfo($url){
	foreach($url as $k=>$v){
		if($v!=''){
			$url[$k] = $v;
		}
	}
	//$url = http_build_query(array_filter($url));
	$url = http_build_query($url);
	$url = str_ireplace(array('&','='),'/',$url);
	return $url;
}

/**

系统内置URL生成方法
@action   string   支持两种格式生成  controllorName/actionName或actionName自动调用当前控制器  

**/


function U($action=null,$field=null){
	
	if(APP_URL=='/index.php'){
		if(strpos($action,'/')!==FALSE){
			//存在'/' 取消首字母大写限制
			//$action  = ucfirst($action);
			$url =  get_domain().'/'.$action;
		}else if($action!=null){
			$url =  get_domain().'/'.APP_CONTROLLER.'/'.$action;
		}else{
			$url =  get_domain().'/'.APP_CONTROLLER.'/'.APP_ACTION;
		}
	}else{
		if(strpos($action,'/')!==FALSE){
			//存在'/'
			$action  = ucfirst($action);
			$url =  get_domain().APP_URL.'/'.$action;
		}else if($action!=null){
			$url =  get_domain().APP_URL.'/'.APP_CONTROLLER.'/'.$action;
		}else{
			$url =  get_domain().APP_URL.'/'.APP_CONTROLLER.'/'.APP_ACTION;
		}
	}
	
	
	
	if($field!=null){
		if(is_array($field)){
			$url.='/'.array_pathInfo($field);
		}else{
			$url.='/'.$field;
		}
	}
	return $url.'.html';
	
}

/**

系统内部错误提示
@info   string    文字描述

**/
function Error_msg($msg,$url=null){
	//检测是否定义了错误处理--2019/2/24  by 留恋风
	$controller = str_replace('/','\\',APP_HOME.'\\'.HOME_CONTROLLER.'\\ErrorController');
	if (!class_exists($controller) || !method_exists($controller,'index')) {
		$traces = debug_backtrace();
		$bufferabove = ob_get_clean();
		require_once(CORE_PATH.'/common/Error.php');
		exit;
	}else{
		(new $controller('ErrorController', 'index'))->index($msg);
		exit;
	}
	
}


/**

自定义成功后跳转方法
@info  string  提示信息
@url   string  链接   default 空
@delay int     时间   default 2s 

**/
function Success($info, $url=null){
	if($url==null){
		$url = get_domain().'/'.APP_CONTROLLER.'/'.APP_ACTION;
	}
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><script type=text/javascript>alert("'.$info.'");window.location.href="'.$url.'";</script></body></html>';
	exit;
}
/**

自定义失败后跳转方法
@info  string   提示信息

**/
function Error($info, $url=null){
  if($url==null){
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo "<script>alert('".$info."'); javascript:history.go(-1);</script>";
	exit;
  }else{

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><script type=text/javascript>
	alert("'.$info.'"); window.location.href="'.$url.'";</script></body></html>';
	exit;
  }
	
}

/**

返回json格式数组

**/

function JsonReturn($data){
	echo json_encode($data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	exit;
}


//获取IP
/*
function GetIP(){ 
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
	$ip = getenv("HTTP_CLIENT_IP"); 
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
	$ip = getenv("HTTP_X_FORWARDED_FOR"); 
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
	$ip = getenv("REMOTE_ADDR"); 
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
	$ip = $_SERVER['REMOTE_ADDR']; 
	else 
	$ip = "unknown"; 
	$ip=htmlspecialchars($ip, ENT_QUOTES);
	if(!get_magic_quotes_gpc())$ip = addslashes($ip);
	return($ip); 
}
*/
function GetIP(){ 
  static $ip = '';
  $ip = $_SERVER['REMOTE_ADDR'];
  if(isset($_SERVER['HTTP_CDN_SRC_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CDN_SRC_IP'])) {
    $ip = $_SERVER['HTTP_CDN_SRC_IP'];
  } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
    foreach ($matches[0] AS $xip) {
      if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
        $ip = $xip;
        break;
      }
    }
  }
  return $ip;
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
	elseif ( ! empty($_SERVER['HTTP_FROM_HTTPS']) && strtolower($_SERVER['HTTP_FROM_HTTPS']) !== 'off')
	{
		$protocol = "https://";
	}
	elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
	{
		$protocol = "https://";
	}else if(!empty($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME']=='https'){
		$protocol = "https://";
	}else{
		$protocol = "http://";
	}
	if(isset($_SERVER['SERVER_PORT'])) {
		$port = ':' . $_SERVER['SERVER_PORT'];
		if((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
			$port = '';
		}
	}else{
		$port = '';
	}
    if(isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'].$port;
    }else if (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    }else if(isset($_SERVER['SERVER_NAME'])) {
		$host = $_SERVER['SERVER_NAME'].$port;
	}else if(isset($_SERVER['SERVER_ADDR'])) {
		$host = $_SERVER['SERVER_ADDR'].$port;
    }
    return $protocol.$host;
}
//当前页面URL
function current_url(){
	return get_domain().$_SERVER["REQUEST_URI"];
}



/**

自定义重定向跳转方法
  *@param $url 目标地址
  *@param $info 提示信息
  *@param $sec 等待时间
  *return void 

**/
function Redirect($url,$info=null,$sec=3){
	if(is_null($info)){
		header("Location:$url");
		}else{
			//header("Refersh:$sec;URL=$url");
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="refresh" content="'.$sec.';URL='.$url.'">';
			echo $info;
		}
		die; 
	
}

/**
	设置Session过期时间

**/

function start_session($expire = 0)  {
	
	if ($expire == 0) {
			//$expire = ini_get('session.gc_maxlifetime');
			$expire = SessionTime;
		} else {
			
			ini_set('session.gc_maxlifetime', $expire);
		}
	$session_cache_dir = APP_PATH.'cache/tmp';
	if(!file_exists($session_cache_dir)){
		mkdir($session_cache_dir,0777,true);
	}
	ini_set('session.save_path',$session_cache_dir);
	
	
	if (!isset($_COOKIE['PHPSESSID'])) {
		session_set_cookie_params($expire);
		session_start();
	} else {
		session_start();
		setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], time() + $expire,'/',null,null,true);
	}

} 

/**

	自定义添加事件日志
	@param  data   日志内容
	@param  dataname  日志名称

**/

function register_log($data=null,$dataname=null){
    if($dataname==null){
        Error_msg('日志名称不能为空！');
    }
    
    $st = array('m'=>APP_CONTROLLER,'a'=>APP_ACTION,'t'=>date('Y-m-d H:i:s',time()),'ip'=>GetIP(),'data'=>$data);
    if(!is_dir(APP_PATH.'cache/log')){
        mkdir(APP_PATH.'cache/log');
    }
    //读取日志文件
    $logurl = APP_PATH.'cache/log/'.$dataname.'.php';
    if(file_exists($logurl)){
        $log = @fopen($logurl,"r");
        $log_txt=@fread($log,filesize($logurl));
        @fclose($log);
    }else{
        $log_txt = '';
    }
    if($log_txt!=''){
        $log_txt = substr($log_txt,14);
        $log_arr = json_decode($log_txt,true);
    }
    $log_arr[]=$st;
    $log_txt = json_encode($log_arr,JSON_UNESCAPED_UNICODE);
    $log_txt = '<?php die();?>'.$log_txt;
    $log_x=@fopen($logurl,"w");
    @fwrite($log_x,$log_txt);
    @fclose($log_x);
}


/**

	输出日志事件列表
	@param  dataname   日志名称   默认空，输出主日志记录
**/

function show_log($dataname=null){
	if($dataname!=null){
		//主日志记录
		$logurl = APP_PATH.'cache/log/'.$dataname.'.php';
		if(!file_exists($logurl)){
			return false;
		}
		// $log = @fopen($logurl,"r");
		// $logdata=@fread($log,filesize($logurl));
		// @fclose($log);
		$logdata = file_get_contents($logurl);
		$logdata = substr($logdata,14);
		$logdata = json_decode($logdata,true);
		//dump($logdata);
		return $logdata;
	}else{
		Error_msg('请输入日志名称！');
	}
}


/**
	记录操作的模块--操作的方法（函数）--事件--操作人 
	记录报错
**/
function actionLog(){
    if(APP_DEBUG === true){
        //开启调试模式自动记录事件,可以手动关闭
        if(!StopLog){
            
            $st = array('m'=>APP_CONTROLLER,'a'=>APP_ACTION,'t'=>date('Y-m-d H:i:s',time()),'ip'=>GetIP(),'data'=>'');
            //读取日志文件
            $logurl = APP_PATH.'cache/log/memberAction.php';
            
            if(file_exists($logurl)){
                $log = @fopen($logurl,"r");
                $log_txt=@fread($log,filesize($logurl));
                @fclose($log);
            }else{
                $log_txt = '';
            }
            if($log_txt!=''){
                $log_txt = substr($log_txt,14);
                $log_arr = json_decode($log_txt,true);
            }
            $log_arr[]=$st;
            $log_txt = json_encode($log_arr,JSON_UNESCAPED_UNICODE);
            $log_txt = '<?php die();?>'.$log_txt;
            $log_x=@fopen($logurl,"w");
            @fwrite($log_x,$log_txt);
            @fclose($log_x);
            
        }
    }
}

/**
 * 随机生成字符串
 * @param int $length
 * @return null|string
 */
function getRandChar($length = 8){
  $str = null;
  $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
  $max = strlen($strPol)-1;
  
  for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
  }
  
  return $str;
}

/**
	字符截断,中文算2个字符
**/
function newstr($string, $length, $dot="...") {
	if(strlen($string) <= $length) {return $string;}
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;' ,'&nbsp;'), array('&','"','<','>',''), $string);
	$strcut = '';$n = $tn = $noc = $noct = $nc = $tnc =0;
	while($n < strlen($string)) {
		$t = ord($string[$n]);
		if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$tn = 1; $n++; $noct++;
		} elseif(194 <= $t && $t <= 223) {
			$tn = 2; $n += 2; $noct += 2;
		} elseif(224 <= $t && $t <= 239) {
			$tn = 3; $n += 3; $noct += 2;
		} elseif(240 <= $t && $t <= 247) {
			$tn = 4; $n += 4; $noct += 2;
		} elseif(248 <= $t && $t <= 251) {
			$tn = 5; $n += 5; $noct += 2;
		} elseif($t == 252 || $t == 253) {
			$tn = 6; $n += 6; $noct += 2;
		} else {$n++;}
		if($noct >= $length){if($noct==0)$noc=$noct;if($nc==0)$nc=$n;if($tnc==0)$tnc=$tn;}
	}
	if($noct<=$length){return str_replace(array('&','"','<','>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);}
	if($noc > $length) {$nc -= $tnc;}
	$strcut = substr($string, 0, $nc);
	$strcut = str_replace(array('&','"','<','>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
} 
 

//系统加密解密
function frencode($str)     
{  
   $yuan = 'abA!c1dB#ef2@Cg$h%iD_3jkl^E:m}4n.o{&F*p)5q(G-r[sH]6tuIv7w+Jxy8z9K0';

   $jia = 'zAy%0Bx+1C$wDv^Eu2-t3(F{sr&G4q_pH5*on6I)m:l7.Jk]j8K}ih@gf9#ed!cb[a';
 $results = '';
        if ( strlen($str) == 0) return false;

		for($i = 0;$i<strlen($str);$i++){

			for($j = 0;$j<strlen($yuan);$j++){

				if($str[$i]==$yuan[$j]){

					$results.= $jia[$j];

					break;

				}

			}

		}

       return $results;
}      
function frdecode($str)  
{         
  $yuan = 'abA!c1dB#ef2@Cg$h%iD_3jkl^E:m}4n.o{&F*p)5q(G-r[sH]6tuIv7w+Jxy8z9K0';

  $jia = 'zAy%0Bx+1C$wDv^Eu2-t3(F{sr&G4q_pH5*on6I)m:l7.Jk]j8K}ih@gf9#ed!cb[a';

  If (strlen($str)==0) return false;
 $results = '';
  for($i = 0;$i< strlen($str);$i++){

      for($j = 0;$j<strlen($jia);$j++){

          if($str[$i]==$jia[$j]){

              $results .= $yuan[$j];

              break;
          }
      }

  }

  return $results;
}

/**
	格式化打印变量
**/
function dump($vars){
   $content = "<div align=left><pre>\n" . htmlspecialchars(print_r($vars, true)) . "\n</pre></div>\n";
   echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head><body>{$content}</body></html>"; 
   return;
}

/**
	本地缓存
	@param str  设置索引
	@param data 存储数据
	@param timeout  设置过期时间，单位秒(s) 默认-1,永久存储
**/
function setCache($str,$data,$timeout=-1){

	//设置
	$rdata['frcache_time'] = $timeout;
	$rdata['frcache_data'] = $data;
	$str = get_domain().$str;
	$s = md5(md5($str.'frphp'.$str));
	$cache_file_data = Cache_Path.'/data/'.$s.'.php';
	if(!file_exists(Cache_Path.'/data')){
		mkdir (Cache_Path.'/data',0777,true);
	}
	//如果为null,则直接删除缓存
	if(!isset($data)){
		if(file_exists($cache_file_data)){
			unlink($cache_file_data);
		}
		return true;
	}
	
	$res = json_encode($rdata,JSON_UNESCAPED_UNICODE);
	$res = '<?php die();?>'.$res;
	$r = file_put_contents($cache_file_data,$res);
	if($r){
		return true;
	}else{
		Error_msg('数据缓存失败，'.Cache_Path.'/data文件夹的读写权限设置为777！');
	}

	
}

function getCache($str=false){
	if(!$str){
		return false;
	}
	$str = get_domain().$str;
	//获取
	$s = md5(md5($str.'frphp'.$str));
	$cache_file_data = Cache_Path.'/data/'.$s.'.php';
	if(!file_exists($cache_file_data)){
		return false;
	}
	$last_time = filemtime($cache_file_data);//创建文件时间
	$res = file_get_contents($cache_file_data);
	$res = substr($res,14);
	$data = json_decode($res,true);
	
	if(($data['frcache_time']+$last_time)<time() && $data['frcache_time']>=0){
		
		unlink($cache_file_data);
		return false;
	}else{
		
		return $data['frcache_data'];
	}
}

//引入扩展文件
function extendFile($filepath){
	if(strpos($filepath,'.')!==false){
		require_once(CORE_PATH.'/extend/'.$filepath);
	}else{
        if(substr($filepath,1)=='/'){
            $Extend = scandir(CORE_PATH.'/extend'.$filepath);
        }else{
            $Extend = scandir(CORE_PATH.'/extend/'.$filepath);
        }
		
		foreach($Extend as $v){
			if(strpos($v,'.php')!==false){
				require_once CORE_PATH.'/extend/'.$filepath.'/'.$v;
			}
		}
	}
	
}

//多语言方法定义
function JZLANG($str=null){
	if($str){
		//读取当前语言包环境
		if(isset($_SESSION['lang'])){
			$_lang = $_SESSION['lang'];
		}else{
			$_lang = LANG;
		}
		
		$lang_common_file = APP_PATH.APP_HOME.'/lang/common.php';//公共语言包
		$lang_current_file = APP_PATH.APP_HOME.'/lang/'.$_lang.'.php';//当前语言包
		if(file_exists($lang_common_file)){
			$common = include($lang_common_file);
		}else{
			$common = [];
		}
		if(file_exists($lang_current_file)){
			$current = include($lang_current_file);
		}else{
			$current = [];
		}
		$lang = empty($common) ? $current : (empty($current) ? $common : array_merge($common,$current));
		if(array_key_exists($str,$lang)){
			return $lang[$str];
		}else{
			return $str;
		}

	}else{
		return '';
	}
}

