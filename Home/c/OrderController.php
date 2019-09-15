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


namespace Home\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class OrderController extends Controller
{
	function _init(){
		
		$webconf = webConf();
		$webcustom = get_custom();
		$template = get_template();
		$this->webconf = $webconf;
		$this->webcustom = $webcustom;
		$this->template = $template;
		$classtypedata = classTypeData();
		
		foreach($classtypedata as $k=>$v){
			$classtypedata[$k]['children'] = get_children($v,$classtypedata);
		}
		$this->classtypedata = $classtypedata;
		
		$this->tpl = Tpl_style.$template.'/';
		$this->common = Tpl_style.'common/';
		//檢測用戶是否登錄
		if(isset($_SESSION['member'])){
			$this->islogin = true;
			$this->member = $_SESSION['member'];
		}else{
			$this->islogin = false;
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'您还未登录，请重新登录！']);
			}
			Error('您还未登录，请重新登录！',U('Login/index'));
		}
	}
	
	function details(){
		$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno]);
		if($orderno && $order){
			$carts = explode('||',$order['body']);
			$new = [];
			foreach($carts as $v){
				if($v!=''){
					//tid-id-num-price
					$d = explode('-',$v);
					//兼容历史订单，可能出现栏目被删除的情况，防止报错
					if(isset($this->classtypedata[$d[0]])){
						$type = $this->classtypedata[$d[0]];//栏目
						$new[]=['info'=>M($type['molds'])->find(['id'=>$d[1]]),'num'=>$d[2],'price'=>$d[3],'tid'=>$d[0]];
					}else{
						$new[]=['info'=>false,'num'=>$d[2],'price'=>$d[3],'tid'=>$d[0]];
					}

				}
				
			}
			$this->carts = $new;
			$this->order = $order;
			$this->display($this->template.'/order_details');
			
		}else{
			Error('参数错误！');
		}
		
	}
	
	function create(){
		if($this->frparam('go') && $_POST){
			if(isset($_SESSION['cart']) && $_SESSION['cart']!=''){
				$carts = explode('||',$_SESSION['cart']);
				$group = M('member_group')->find(['id'=>$this->member['gid']]);
				$new = [];
				$price = 0.00;
				$w = [];
				$newcart = [];
				$w['orderno'] = 'No'.date('YmdHis');
				$w['userid'] = $this->member['id'];
				$w['tel'] = $this->member['tel']; 
				$w['username'] = $this->member['username']; 
				$w['addtime'] = time(); 
				foreach($carts as $v){
					$d = explode('-',$v);
					//tid-id-num
					if($d[0]!='' && $d[1]!='' && $d[2]!='' && $d[2]!=0){
						$type = $this->classtypedata[$d[0]];
						$info = M($type['molds'])->find(['id'=>$d[1]]);
						//tid-id-num-price
						$new[]=$d[0].'-'.$d[1].'-'.$d[2].'-'.$info['price'];
						$price+=$d[2]*$info['price'];
						
						$newcart[]=['info'=>M($type['molds'])->find(['id'=>$d[1]]),'num'=>$d[2],'price'=>$info['price'],'tid'=>$d[0]];
					}
					
				}
				//运费
				$yunfei = $this->webconf['yunfei'];
				//折扣
				$discount = 0.00;
				if($group['discount_type']==1){
					$discount = $group['discount'];
				}else if($group['discount_type']==2){
					$discount = round($group['discount']*$price,2);
				}
				$w['body'] = '||'.implode('||',$new).'||';
				$w['yunfei'] = $yunfei;
				$w['discount'] = $discount;
				$w['price'] = $price-$discount-$yunfei;
				$res = M('orders')->add($w);
				if($res){
					$_SESSION['cart'] = '';
					$this->carts = $newcart;
					$this->order = M('orders')->find(['id'=>$res]);
					$this->display($this->template.'/payment');
					
				}else{
					Error('创建订单失败！');
				}
			}
		}
		
	}
	
	//支付页面
	function payment(){
		$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno]);
		if($this->frparam('go') && $orderno && $order){
			if($order['isshow']!=1){
				//超时或者已支付
				if($order['isshow']==0){
					$msg = '订单已删除';
				}
				if($order['isshow']==3){
					$msg = '订单已过期，不可支付！';
				}
				if($order['isshow']==2){
					$msg = '订单已支付，请勿重复操作！';
				}
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>$msg]);
				}
				Error($msg);
				
			}
			$carts = explode('||',$order['body']);
			$new = [];
			foreach($carts as $v){
				if($v!=''){
					//tid-id-num-price
					$d = explode('-',$v);
					//兼容历史订单，可能出现栏目被删除的情况，防止报错
					if(isset($this->classtypedata[$d[0]])){
						$type = $this->classtypedata[$d[0]];//栏目
						$new[]=['info'=>M($type['molds'])->find(['id'=>$d[1]]),'num'=>$d[2],'price'=>$d[3],'tid'=>$d[0]];
					}else{
						$new[]=['info'=>false,'num'=>$d[2],'price'=>$d[3],'tid'=>$d[0]];
					}

				}
				
			}
			$this->carts = $new;
			$this->order = $order;
			$this->display($this->template.'/payment');
		}
		
	}
	
	//支付处理
	function pay(){
		if($this->frparam('go')){
			//保存提交信息
			$return_url = U('Order/details',['orderno'=>$this->frparam('orderno',1)]);
			$w['orderno'] = $this->frparam('orderno',1);
			$w['receive_username'] = $this->frparam('username',1);
			$w['receive_tel'] = $this->frparam('tel',1);
			$w['receive_email'] = $this->frparam('email',1);
			$w['receive_address'] = $this->frparam('address',1);
			$paytype = $this->frparam('paytype',0,1);//默认支付宝支付1，2微信支付
			if($w['receive_username']=='' || $w['receive_tel']=='' || $w['receive_address']==''){
				if($this->frparam('ajax')){
					
					JsonReturn(['code'=>1,'msg'=>'收件人、手机号和收货地址不能为空！','url'=>$return_url]);
					
				}
				
				Error('收件人、手机号和收货地址不能为空！',$return_url);
				
			}
			
			$order = M('orders')->find(['orderno'=>$w['orderno']]);
			if(!$order || !$w['orderno']){
				if($this->frparam('ajax')){
					
					JsonReturn(['code'=>1,'msg'=>'订单号不存在或已被删除！','url'=>$return_url]);
					
				}
				Error('订单号不存在或已被删除！',$return_url);
			}
			//保存信息
			$res = M('orders')->update(['id'=>$order['id']],$w);
			//未设置在线支付
			if($this->webconf['paytype']==0){
				//提示接收信息邮箱
				//检测是否已经配置邮件发送
				if($this->webconf['email_server'] && $this->webconf['email_port'] &&  $this->webconf['send_email'] &&  $this->webconf['send_pass']){
					$title = '您的订单提交成功通知-'.$this->webconf['web_name'];
					if($this->webconf['tj_msg']!=''){
						$body = str_replace('{xxx}',$w['receive_username'],$this->webconf['tj_msg']);
					}else{
						$body = '尊敬的'.$w['receive_username'].'我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！';
					}
					
					$body.='<br/>订单详细信息如下：<br/>';
					$body.='<table style="min-width:500px">
					<tr><th width="20%">主图</th><th width="20%">商品</th><th width="20%">价格</th><th width="20%">购买数量</th><th width="20%">总价</th></tr>';
					
					foreach(explode('||',$order['body']) as $v){
						if($v!=''){
							$d = explode('-',$v);
							//tid-id-num-price
							if($d[0]!=''){
								$type = $this->classtypedata[$d[0]];//栏目
								$product = M($type['molds'])->find(['id'=>$d[1]]);
								$body.='<tr><td width="20%"><img width="200px" src="'.get_domain().$product['litpic'].'" /></td><td width="20%">'.$product['title'].'</td><td width="20%">￥'.$d[3].'元</td><td width="20%">'.$d[2].'</td><td width="20%">￥'.($d[3]*$d[2]).'元</td></tr>';
								
							}
							
							
						}
						
					}
					
					$body.='<tr><td>折扣：</td><td colspan="4">￥'.$order['discount'].'元</td></tr><tr><td>运费：</td><td colspan="4">￥'.$order['yunfei'].'元</td></tr><tr><td>合计：</td><td colspan="4">￥'.$order['price'].'元</td></tr></table><br/>';
					$body.='收件地址：'.$w['receive_address'].' 联系电话：'.$w['receive_tel'];
					if($this->webconf['shou_email']!=''){
						send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$w['receive_email'],$title,$body,$this->webconf['shou_email']);
					}else{
					    send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$w['receive_email'],$title,$body); 
					}
					
					
					
					
				}
			
				//更新订单状态，提示收到提交订单
				M('orders')->update(['id'=>$order['id']],['isshow'=>4]);
				
				//减库存
				$allproduct = explode('||',$order['body']);
				foreach($allproduct as $v){
					if($v!=''){
						$d = explode('-',$v);
						//tid-id-num-price
						if($d[0]!=''){
							$type = $this->classtypedata[$d[0]];//栏目
							$num = (int)$d[2];
							$r = M($type['molds'])->goDec(['id'=>$d[1]],'stock_num',$num);
							
						}
						
						
					}
					
				}
				
				
				if($this->frparam('ajax')){
					
					JsonReturn(['code'=>0,'msg'=>'我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！','url'=>U('user/order')]);
				}
				
				Success('我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！',U('User/order'));
				
				
				
			}else if($this->webconf['paytype']==1){
				//检查极致平台配置
				if(!$this->webconf['jizhi_mchid'] || !$this->webconf['jizhi_appid'] || !$this->webconf['jizhi_key'] || !$this->webconf['jizhi_pay_url']){
					
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'平台支付配置未完成！','url'=>$return_url]);
					}
					
					Error('平台支付配置未完成！',$return_url);
					
					
				}
				
				
				//极致支付平台
				if($order['ispay']==1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'订单已支付！','url'=>$return_url]);
					}
					
					Error('订单已支付！',$return_url);
				}
				
				
				//检测端口环境
				if(isMobile()){
					
					if(isWeixin()){
						if($paytype==2){
							Error('支付宝支付请在手机浏览器提交订单~',$return_url);
						}
						//微信支付
						$order['paytype'] = 'wxpay';
					}else{
						$order['paytype'] = 'h5alipay';
					}
				}else{
					if($paytype==2){
						$order['paytype'] = 'scanwxpay';
					}else{
						$order['paytype'] = 'alipay';
					}
					
					
				}
				$order['mchid'] = $this->webconf['jizhi_mchid'];
				$order['appid'] = $this->webconf['jizhi_appid'];
				$order['secretkey'] = $this->webconf['jizhi_key'];
				//$order['paytype'] = 'h5alipay';//alipay h5alipay  wxpay  h5wxpay
				$order['money'] = $order['price'];
				$order['title'] = '支付订单-'.$order['orderno'];
				$order['orderno'] = $order['orderno'];//本地订单
				//setLog($order,'push_order');
				$url = $this->webconf['jizhi_pay_url'].'/Pay/onlinePay';
				$this->order = $order;
				$this->url = $url;
				$this->display($this->template.'/paytpl/pay_form');
				
			}else if($this->webconf['paytype']==2){
				//检查自主平台配置
				if($order['ispay']==1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'订单已支付！','url'=>$return_url]);
					}
					
					Error('订单已支付！',$return_url);
				}
				
				
				
				
				if(isMobile()){
					
					//手机端
					if(isWeixin()){
						if($paytype==1){
							
							//微信内访问支付宝支付链接
							//Error('支付宝支付请在手机浏览器提交订单~',$return_url);
							$order['paytype'] = 'h5alipay';
							extendFile('pay/alipay/AlipayService.php');
							$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
							$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
							$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
							$outTradeNo = $order['orderno'];     //你自己的商品订单号
							$payAmount = $order['price'];          //付款金额，单位:元
							$orderName = '支付订单-'.$order['orderno'];    //订单标题
							$signType = 'RSA2';       //签名算法类型，支持RSA2和RSA，推荐使用RSA2
							//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
							$saPrivateKey=$this->webconf['alipay_private_key'];
							$aliPay = new \AlipayService($appid,$returnUrl,$notifyUrl,$saPrivateKey);
							$payConfigs = $aliPay->doPay($payAmount,$outTradeNo,$orderName,$returnUrl,$notifyUrl);
							
							$this->display($this->template.'/pay/alipay_in_weixin');
							exit;
							
							
						}else{
							
							//微信支付
							$order['paytype'] = 'wxpay';
							extendFile('pay/wechat/WxpayService.php');
							$mchid = $this->webconf['wx_mchid'];   //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
							$appid =  $this->webconf['wx_appid'];  //微信支付申请对应的公众号的APPID
							$appKey = $this->webconf['wx_appsecret'];   //微信支付申请对应的公众号的APP Key
							$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
							//①、获取用户openid
							$wxPay = new \WxpayService($mchid,$appid,$appKey,$apiKey);
							$openId = $wxPay->GetOpenid();      //获取openid
							
							if(!$openId) exit('获取openid失败');
							//②、统一下单
							$outTradeNo = $order['orderno'];     //你自己的商品订单号
							$payAmount = $order['price'];          //付款金额，单位:元
							$orderName = '支付订单：'.$order['orderno'];    //订单标题
							$notifyUrl = U('Mypay/wechat_notify_pay');     //付款成功后的回调地址(不要有问号)
							$returnUrl = U('Mypay/check_wechat_order',['orderno'=>$order['orderno']]);     //付款成功后的回调地址(不要有问号)
							$payTime = '支付订单-'.$order['orderno'];      //付款时间
							$jsApiParameters = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
							$this->payAmount = $payAmount;
							$this->returnUrl = $returnUrl;
							$jsApiParameters = json_encode($jsApiParameters);
							$this->jsApiParameters = $jsApiParameters;
							$this->order = $order;
							$this->display($this->template.'/paytpl/wechat_pay');
							exit;
							
						}
						
						
						
					}else{
						//手机浏览器内
						if($paytype==1){
							//支付宝H5支付
							$order['paytype'] = 'h5alipay';
							/*** 请填写以下配置信息 ***/
							extendFile('pay/alipay/AlipayService.php');
							$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
							$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
							$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
							$outTradeNo = $order['orderno'];     //你自己的商品订单号
							$payAmount = $order['price'];          //付款金额，单位:元
							$orderName = '支付订单-'.$order['orderno'];    //订单标题
							$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
							$rsaPrivateKey=$this->webconf['alipay_private_key'];		//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
							/*** 配置结束 ***/
							$aliPay = new \AlipayService();
							$aliPay->setAppid($appid);
							$aliPay->setReturnUrl($returnUrl);
							$aliPay->setNotifyUrl($notifyUrl);
							$aliPay->setRsaPrivateKey($rsaPrivateKey);
							$aliPay->setTotalFee($payAmount);
							$aliPay->setOutTradeNo($outTradeNo);
							$aliPay->setOrderName($orderName);
							$sHtml = $aliPay->doPay();
							echo $sHtml;
						}else{
							//微信H5支付
							$order['paytype'] = 'h5wxpay';
							extendFile('pay/wechat/WxpayH5Service.php');
							/** 请填写以下配置信息 */
							$mchid = $this->webconf['wx_mchid'];   //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
							$appid =  $this->webconf['wx_appid'];  //微信支付申请对应的公众号的APPID
							$appKey = $this->webconf['wx_appsecret'];   //微信支付申请对应的公众号的APP Key
							$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 
							$outTradeNo = $order['orderno'];     //你自己的商品订单号
							$payAmount = $order['price'];          //付款金额，单位:元
							$orderName = '支付订单-'.$order['orderno'];    //订单标题
							$notifyUrl = U('Mypay/wechat_notify_pay');     //付款成功后的回调地址(不要有问号)
							$returnUrl = U('Mypay/wechat_return_pay').'?orderno='.$order['orderno'];     //付款成功后，页面跳转的地址
							$wapUrl = $_SERVER['HTTP_HOST'];   //WAP网站URL地址
							$wapName = $this->webconf['web_name']; //WAP 网站名
							/** 配置结束 */

							$wxPay = new \WxpayH5Service($mchid,$appid,$apiKey);
							$wxPay->setTotalFee($payAmount);
							$wxPay->setOutTradeNo($outTradeNo);
							$wxPay->setOrderName($orderName);
							$wxPay->setNotifyUrl($notifyUrl);
							$wxPay->setReturnUrl($returnUrl);
							$wxPay->setWapUrl($wapUrl);
							$wxPay->setWapName($wapName);

							$mwebUrl= $wxPay->createJsBizPackage($payAmount,$outTradeNo,$orderName,$notifyUrl);
							//echo "<h1><a href='{$mwebUrl}'>点击跳转至支付页面</a></h1>";
							header('Location:'.$mwebUrl);
							exit;
						}
						
						
						
					}
				}else{
					
					if($paytype==2){
						$order['paytype'] = 'scanwxpay';
						extendFile('pay/wechat/WxpayScan.php');
						//微信扫码支付
						$mchid = $this->webconf['wx_mchid'];   //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
						$appid =  $this->webconf['wx_appid'];  //微信支付申请对应的公众号的APPID
						$appKey = $this->webconf['wx_appsecret'];   //微信支付申请对应的公众号的APP Key
						$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
						$wxPay = new \WxpayScan($mchid,$appid,$apiKey);
						$outTradeNo = $order['orderno'];     //你自己的商品订单号
						$payAmount = $order['price'];          //付款金额，单位:元
						$orderName = '支付订单:'.$order['orderno'];    //订单标题
						$notifyUrl = U('Mypay/wechat_notify_pay');    //付款成功后的回调地址(不要有问号)
						$payTime = time();      //付款时间
						$arr = $wxPay->createJsBizPackage($payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
						//生成二维码
						$url = U('Common/qrcode').'?data='.$arr['code_url'];
						$this->url = $url;
						$this->data = $arr['code_url'];
						$this->payAmount = $payAmount;
						$this->orderno = $outTradeNo;
						$this->display($this->template.'/paytpl/wechat_scan');
						//echo "<img src='{$url}' style='width:300px;'><br>";
						//echo '二维码内容：'.$arr['code_url'];
						exit;
					}else{
						$order['paytype'] = 'alipay';
						/*** 请填写以下配置信息 ***/
						extendFile('pay/alipay/AlipayService.php');
						$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
						$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
						$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
						$outTradeNo = $order['orderno'];     //你自己的商品订单号
						$payAmount = $order['price'];          //付款金额，单位:元
						$orderName = '支付订单-'.$order['orderno'];    //订单标题
						$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
						$rsaPrivateKey = $this->webconf['alipay_private_key'];		//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
						/*** 配置结束 ***/
						$aliPay = new \AlipayService();
						$aliPay->setAppid($appid);
						$aliPay->setReturnUrl($returnUrl);
						$aliPay->setNotifyUrl($notifyUrl);
						$aliPay->setRsaPrivateKey($rsaPrivateKey);
						$aliPay->setTotalFee($payAmount);
						$aliPay->setOutTradeNo($outTradeNo);
						$aliPay->setOrderName($orderName);
						$sHtml = $aliPay->doPay();
						echo $sHtml;
						exit;
						
					}
					
					
				}
				
				
				
				
				
			}
			
			
			
		}
		
		
		
		
		
	}

	
}