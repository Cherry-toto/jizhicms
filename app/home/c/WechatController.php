<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.inchinalife.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2020/01/01
// +----------------------------------------------------------------------


namespace app\home\c;


use frphp\extend\Page;
 
class WechatController extends CommonController
{
	public function _init(){
		parent::_init();
	}
	
	
	public function index(){
		if (isset($_GET['echostr'])){
			$this->checkWeixin();
		}else{
			$this->responseMsg();
		}
	}
	
	//微信登录
	public function login(){
		
		//存储当前页面链接
		if(!isset($_SESSION['return_url'])){
			$referer = ($_SERVER['HTTP_REFERER']=='') ? U('user/index') : $_SERVER['HTTP_REFERER'];
			$_SESSION['return_url'] = $referer;
		}
		
		//检查微信配置
		if($this->webconf['wx_login_appid']!='' && $this->webconf['wx_login_appsecret']!='' && $this->webconf['wx_login_token']!=''){
			
			//获取openid，并检测是否已存在
			if(!isset($_GET['code'])){
				
					//进入授权登录
					$appid = $this->webconf['wx_login_appid'];
					
					$localhost = get_domain().$_SERVER['REQUEST_URI'];//当前页面url
					//echo $localhost;exit;
					$redirect_uri = urlencode($localhost);
					$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
					//echo $url;exit;
					header('location:'.$url);
				
			}else{
				$res = $this->getopenid();
				$openid = $res['openid'];
				if(!$openid){
					$_GET['code']=null;
					//授权失败重新登录
					Redirect(U('login'));
				}
				
				$islive = M('member')->find(array('openid'=>$openid));
				if($islive){
					//已存在的用户，直接登录系统
					unset($islive['pass']);
					//更新登录时间
					M('member')->update(['id'=>$islive['id']],['logintime'=>time()]);
					$_SESSION['member'] = $islive;
					Redirect($_SESSION['return_url']);
					
				}else{
					//未绑定，进行授权登录，获取用户信息
					$_GET['code']=null;
					$this->register();
				}
				
				
			}
			
			
		}else{
			exit(JZLANG('系统未配置微信登录！'));
		}
		
	}
	
	
	//绑定微信号
	public function bindinguser(){
		
		if(!isset($_GET['code'])){
			
				//进入授权登录
				$appid = $this->webconf['wx_login_appid'];
				
				$localhost = get_domain().$_SERVER['REQUEST_URI'];//当前页面url
				//echo $localhost;exit;
				$redirect_uri = urlencode($localhost);
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
				//echo $url;exit;
				header('location:'.$url);
			
		}else{
			$res = $this->getopenid();
			$openid = $res['openid'];
			if(!$openid){
				$_GET['code']=null;
				//Error('授权失败，重新登录~',U('bindinguser'));
				Redirect(U('bindinguser'));
			}
			
			$islive = M('member')->find(array('openid'=>$openid));
			if($islive){
				//绑定账户
				if($this->member['openid']==$islive['openid']){
					Success(JZLANG('微信已绑定！'),U('User/index'));
				}else{
					Error(JZLANG('您的微信已被绑定，不能再绑定！'),U('User/index'));
				}
				
				
			}else{
				//未绑定，进行授权绑定
				$_GET['code']=null;
				$this->register();
			}
			
			
		}
		
		
		
	}
	
	//验证微信公众号
	public function checkWeixin(){
		//微信会发送4个参数到我们的服务器后台 签名 时间戳 随机字符串 随机数

			$signature = $_GET["signature"];
			$timestamp = $_GET["timestamp"];
			$nonce = $_GET["nonce"];
			$echostr = format_param($_GET["echostr"],1);
			$token = $this->webconf['wx_login_token'];

			// 1）将token、timestamp、nonce三个参数进行字典序排序
			$tmpArr = array($nonce,$token,$timestamp);
			sort($tmpArr,SORT_STRING);

			// 2）将三个参数字符串拼接成一个字符串进行sha1加密
			$str = implode($tmpArr);
			$sign = sha1($str);

			// 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
			if ($sign == $signature) {
				echo $echostr;
			}
	}
	
	public function responseMsg(){
	
		//get post data, May be due to the different environments
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = file_get_contents('php://input');
		
		//extract post data
		if (!empty($postStr)){
			
			/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
			   the best way is to check the validity of xml by yourself */
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->postObj = $postObj;
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$time = time();
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
			if($postObj->MsgType=='event'){
				switch ($postObj->Event){
					case 'CLICK':
					/*
					if($postObj->EventKey == 'xxx'){
						
					}
					*/
					break;
					case 'subscribe':
					//获取用户信息，并存入数据库
					$openid = $fromUsername;
					//查询是否已有账号
					$openid = format_param($openid,1);
					$islive = M('member')->find(array('openid'=>$openid));
					if(!$islive){
						$access_token = $this->getAccessToken();
						$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
						$user = file_get_contents($url);
						$user = json_decode($user,true);

                        $n = M('member')->add(['username'=>'微信用户'.date('YmdHis'),'openid'=>$openid,'regtime'=>$time]);
						
						if($n){
							if($this->webconf['huanying']!=''){
								$contentStr = htmlspecialchars_decode($this->webconf['huanying']);
								$msgType = "text";
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
								echo $resultStr;
								
							}
						}
						
					}
					
					break;
					
					
				}
				
				
				
				
			}else{
				/*
				if($keyword){
					$contentStr = '已收到您的信息：'.$keyword;
				}else{
					$contentStr = '您好，请问我可以为你做什么呢？';
				}
				
				$msgType = "text";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				*/
				
				
			}
			

		}else {
			echo "";
			exit;
		}
	}
	
	public function getAccessToken(){
	
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->webconf['wx_login_appid']."&secret=".$this->webconf['wx_login_appsecret'];
		$json = file_get_contents($url);
		//解析json
		//var_dump($json);
		$obj = json_decode($json);
		return  $obj->access_token; 
	}
	//获取用户的详情
	public	function getopenid(){
			
		$code = $_GET['code'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->webconf['wx_login_appid']."&secret=".$this->webconf['wx_login_appsecret']."&code=".$code."&grant_type=authorization_code ";

		$res = file_get_contents($url);
		
		$json_obj = json_decode($res,true);
		//var_dump($json_obj);exit;
		if(isset($json_obj['openid'])){
			$openid = $json_obj['openid'];
			$_SESSION['openid'] = $openid;
			
			$access_token = $json_obj['access_token'];
		
			$openid = $json_obj['openid'];
			return array('openid'=>$openid,'access_token'=>$access_token);
		}else{
			//return false;
			exit($res);
		}
		
	}
	
		
	
	
	//用户注册
	public function register(){
		if(!isset($_GET['code'])){
			
			$appid = $this->webconf['wx_login_appid'];
			
			$redirect_uri = urlencode(U('register'));
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			header('location:'.$url);
		}else{
			$res = $this->getopenid();
			$openid = $res['openid'];
			if(!$openid){
				$_GET['code']=null;
				//Error('登录失败，重新登录~',U('register'));
				Redirect(U('register'));
				
			}
			$islive = M('member')->find(array('openid'=>$openid));
			//查询是否已存在
            if(!$islive){
				$access_token  =  $res['access_token']; 
				$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
				$user = file_get_contents($url);
				$user = json_decode($user,true);
			    $time = time();
				$n = M('member')->add(['username'=>$user['nickname'],'openid'=>$openid,'litpic'=>$user['headimgurl'],'sex'=>$user['sex'],'logintime'=>$time]);
				
				if($n){
					$user = M('member')->find(['id'=>$n]);
					unset($user['pass']);
					$_SESSION['member'] = $user;
					//Success('登录成功！',U('User/index'));
					Redirect(U('User/index'));
					
				}else{
					$_GET['code']=null;
					//未知错误，重新注册
					Redirect(U('register'));
					
				}
				
				
			}else{
				
				//已存在的用户，直接登录系统
				unset($islive['pass']);
				//更新登录时间
				M('member')->update(['id'=>$islive['id']],['logintime'=>time()]);
				$_SESSION['member'] = $islive;
				Redirect(U('user/index'));
					
				
			}
			
			
			
		}
	}
	
	
	

	//发送模板消息
	function sendTemplate($data){
		$access_token = $this->getAccessToken();
		$api = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		$res = curl_http($api,json_encode($data,JSON_UNESCAPED_UNICODE),'POST');
		$res = json_decode($res,1);
		if($res['errcode']==0){
			return true;
		}else{
			Error($res['errmsg']);
		}
		
	}
	
	//发红包--涉及支付的账户，请填写系统设置中的支付相关微信商户
	function hongbao($title,$money,$openid,$biaoyu='恭喜发财'){
		//$money = 100; //最低1元，单位分
		$sender = $title;
		$obj = array();
		$obj['wxappid'] = $this->webconf['wx_appid']; //appid
		$obj['mch_id'] = $this->webconf['wx_mchid'];//商户id
		$obj['mch_billno'] = $this->webconf['wx_mchid'].date('YmdHis').rand(1000,9999);//组合成28位，根据官方开发文档，可以自行设置
		$obj['client_ip'] = $_SERVER['REMOTE_ADDR'];
		$obj['re_openid'] = $openid;//接收红包openid
		$obj['total_amount'] = $money;
		$obj['min_value'] = $money;
		$obj['max_value'] = $money;
		$obj['total_num'] = 1;
		$obj['nick_name'] = $sender;
		$obj['send_name'] = $sender;
		$obj['wishing'] = $biaoyu;
		$obj['act_name'] = $sender;
		$obj['remark'] = $sender;
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
		$obj['nonce_str'] = $this->create_noncestr();  //创建随机字符串
		$stringA = $this->create_qianming($obj,false);  //创建签名
		$stringSignTemp = $stringA."&key=".$this->webconf['key'];  //签名后加api
		$sign = strtoupper(md5($stringSignTemp));  //签名加密并大写
		$obj['sign'] = $sign;  //将签名传入数组
		$postXml = $this->arrayToXml($obj);  //将参数转为xml格式
		//var_dump($postXml);
		$responseXml = $this->curl_post_ssl($url,$postXml);  //提交请求
		//var_dump($responseXml);
		//return $responseXml;
		$xml = simplexml_load_string($responseXml);
		$res = json_decode(json_encode($xml),TRUE);
		return $res;
		
		
	}
	
	//生成随机字符串，默认32位

	function create_noncestr($length=32) {
		//创建随机字符
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$str = "";
		for($i=0;$i<$length;$i++) {
		  $str.=substr($chars, mt_rand(0,strlen($chars)-1),1);
		}
		return $str;

	}
	//数组转xml

	function arrayToXml($arr) {
		$xml = "<xml>";
		foreach ($arr as $key=>$val) {
		  if (is_numeric($val)) {
			$xml.="<".$key.">".$val."</".$key.">";
		  } else {
			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		  }
		}
		$xml.="</xml>";
		return $xml;
	}
	
	//post请求网站，需要证书

	function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
	  {
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		//cert 与 key 分别属于两个.pem文件
		//请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
		curl_setopt($ch,CURLOPT_SSLCERT,$this->webconf['apiclient_cert']);
		curl_setopt($ch,CURLOPT_SSLKEY,$this->webconf['apiclient_key']);
		curl_setopt($ch,CURLOPT_CAINFO,$this->webconf['rootca']);
		if( count($aHeader) >= 1 ){
		  curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
		  curl_close($ch);
		  return $data;
		}else {
		  $error = curl_errno($ch);
		  echo "call faild, errorCode:$error\n";
		  curl_close($ch);
		  return false;
		}

	}
	

	
	
	
	
	
}