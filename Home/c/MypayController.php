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

class MypayController extends CommonController
{
	/**
		
		自主平台支付回调
	
	**/
	
	public function _init(){
		
		parent::_init();
		
	}
	
	
	//跳过购物车直接进行支付
	public function topay(){
		//创建订单
		$w = [];
		$w['orderno'] = 'No'.date('YmdHis');
		if($this->islogin){
			$w['userid'] = $this->member['id'];
			$w['tel'] = $this->frparam('tel',1) ? $this->frparam('tel',1) : $this->member['tel']; 
			$w['username'] = $this->frparam('username',1) ? $this->frparam('username',1) : $this->member['username']; 
		}else{
			$w['tel'] = $this->frparam('tel',1,'');
			$w['username'] = $this->frparam('username',1,'匿名');
		}
		
		$w['receive_username'] = $w['username'];
		$w['receive_tel'] = $w['tel'];
		$w['receive_email'] = $this->frparam('email',1);
		$w['receive_address'] = $this->frparam('address',1);
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		if(!$id){
			Error('缺少id参数！');
		}
		if(!$tid){
			Error('缺少tid参数！');
		}
		$molds = $this->classtypedata[$tid]['molds'];
		if(!$molds){
			$molds = 'product';
		}
		$product = M($molds)->find(['id'=>$id,'isshow'=>1]);
		if(!$product){
			Error('未找到商品或者已下架！');
		}
		if(!array_key_exists('price',$product)){
			Error('该模块缺少price价格参数！');
		}
		$num = $this->frparam('num',0,1);
		$money = $product['price'] * $num;
		$w['addtime'] = time();
		//运费
		$yunfei = $this->webconf['yunfei'];
		if($this->islogin){
			$group = M('member_group')->find(['id'=>$this->member['gid']]);
			//折扣
			$discount = 0.00;
			if($group['discount_type']==1){
				$discount = $group['discount'];
			}else if($group['discount_type']==2){
				$discount = round((1-$group['discount'])*$money,2);
			}
			
		}else{
			$discount = 0;
		}
		$w['discount'] = $discount;
		//tid-id-num-price
		$w['body'] = '||'.$product['tid'].'-'.$id.'-'.$num.'-'.$product['price'].'||';
		$w['yunfei'] = $yunfei;
		$w['price'] = $money-$discount+$yunfei;
		if($w['price']<0){
			$w['price'] = 0;
		}
		
		if($this->webconf['isopenemail']==1 && $this->frparam('ismsg')){
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
				
				foreach(explode('||',$w['body']) as $v){
					if($v!=''){
						$d = explode('-',$v);
						//tid-id-num-price
						if($d[0]!=''){
							$type = $this->classtypedata[$d[0]];//栏目
							$body.='<tr><td width="20%"><img width="200px" src="'.get_domain().$product['litpic'].'" /></td><td width="20%">'.$product['title'].'</td><td width="20%">￥'.$d[3].'元</td><td width="20%">'.$d[2].'</td><td width="20%">￥'.($d[3]*$d[2]).'元</td></tr>';
							
						}
						
						
					}
					
				}
				
				$body.='<tr><td>折扣：</td><td colspan="4">￥'.$w['discount'].'元</td></tr><tr><td>运费：</td><td colspan="4">￥'.$w['yunfei'].'元</td></tr><tr><td>合计：</td><td colspan="4">￥'.$w['price'].'元</td></tr></table><br/>';
				$body.='收件地址：'.$w['receive_address'].' 联系电话：'.$w['receive_tel'];
				
				if($this->webconf['shou_email']!=''){
					send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$w['receive_email'],$title,$body,$this->webconf['shou_email']);
				}else{
					send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$w['receive_email'],$title,$body); 
				}
				
				
				
				
				
				
			}
		}
		
		
		
		
		$res = M('orders')->add($w);
		if($res){
			$ptype = $this->frparam('ptype',0,1);
			$w['id'] = $res;
			$order = $w;
			//保存提交信息
			$return_url = U('user/orderdetails',['orderno'=>$order['orderno']]);
			switch($ptype){
				case 1:
				if($this->webconf['paytype']==0){
					//线下支付
					M('orders')->update(['id'=>$order['id']],['isshow'=>4,'paytype'=>'线下支付']);
					//交易提醒
					$task['aid'] = $order['id'];
					$task['tid'] = 0;
					if($this->islogin){
						$task['userid'] = $this->member['id'];
						$task['puserid'] = $this->member['id'];
						$task['molds'] = 'orders';
						$task['type'] = 'rechange';
						$task['addtime'] = time();
						$task['body'] = '您的订单-'.$order['orderno'].'已经提交，我们会尽快给您发货！';
						$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
						M('task')->add($task);
					}
					
					

					if($this->frparam('ajax')){
					
						JsonReturn(['code'=>0,'msg'=>'我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！','url'=>U('user/orders')]);
					}
					
					Success('我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！',U('User/orders'));
				}else{
					//支付宝
					//检查自主平台配置
					if($order['ispay']==1){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'订单已支付！','url'=>$return_url]);
						}
						
						Error('订单已支付！',$return_url);
					}
					if($this->islogin){
						//交易提醒
						$task['aid'] = $order['id'];
						$task['tid'] = 0;
						$task['userid'] = $this->member['id'];
						$task['puserid'] = $this->member['id'];
						$task['molds'] = 'orders';
						$task['type'] = 'rechange';
						$task['addtime'] = time();
						$task['body'] = '您的订单-'.$order['orderno'].'已经提交，请尽快支付！';
						$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
						M('task')->add($task);
					}
					if(isMobile()){
						//手机端
						if(isWeixin()){
							//微信内
							$order['paytype'] = 'h5alipay';
							M('orders')->update(['id'=>$order['id']],['paytype'=>'支付宝H5支付']);
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
							$this->queryStr = http_build_query($payConfigs);
							$this->display($this->template.'/paytpl/alipay_in_weixin');
							exit;
						}else{
							//支付宝H5支付
							$order['paytype'] = 'h5alipay';
							M('orders')->update(['id'=>$order['id']],['paytype'=>'支付宝H5支付']);
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
							echo $sHtml;exit;
						}
						
					}else{
						//PC
						$order['paytype'] = 'alipay';
						M('orders')->update(['id'=>$order['id']],['paytype'=>'电脑支付宝支付']);
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
				
				
				break;
				case 2:
					//微信
					//检查自主平台配置
					if($order['ispay']==1){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'订单已支付！','url'=>$return_url]);
						}
						
						Error('订单已支付！',$return_url);
					}
					if($this->islogin){
						//交易提醒
						$task['aid'] = $order['id'];
						$task['tid'] = 0;
						$task['userid'] = $this->member['id'];
						$task['puserid'] = $this->member['id'];
						$task['molds'] = 'orders';
						$task['type'] = 'rechange';
						$task['addtime'] = time();
						$task['body'] = '您的订单-'.$order['orderno'].'已经提交，请尽快支付！';
						$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
						M('task')->add($task);
					}
					
					if(isMobile()){
						//手机端
						if(isWeixin()){
							//微信内
							$order['paytype'] = 'wxpay';
							M('orders')->update(['id'=>$order['id']],['paytype'=>'微信内支付']);
							$url = U('order/wxpay').'?'.http_build_query($order);
							Redirect($url);
							
							exit;
						}else{
							//微信H5支付
							$order['paytype'] = 'h5wxpay';
							M('orders')->update(['id'=>$order['id']],['paytype'=>'微信H5支付']);
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
							$returnUrl = U('Mypay/check_wechat_order').'?orderno='.$order['orderno'];     //付款成功后，页面跳转的地址
							$wapUrl = $_SERVER['HTTP_HOST'];   //WAP网站URL地址
							$wapName = $this->webconf['web_name']; //WAP 网站名
							$webip = GetIP();
							/** 配置结束 */

							$wxPay = new \WxpayH5Service($mchid,$appid,$apiKey);
							$wxPay->setTotalFee($payAmount);
							$wxPay->setOutTradeNo($outTradeNo);
							$wxPay->setOrderName($orderName);
							$wxPay->setNotifyUrl($notifyUrl);
							$wxPay->setReturnUrl($returnUrl);
							$wxPay->setWapUrl($wapUrl);
							$wxPay->setWapName($wapName);
							$wxPay->setIp($webip);

							$mwebUrl= $wxPay->createJsBizPackage($payAmount,$outTradeNo,$orderName,$notifyUrl);
							//echo "<h1><a href='{$mwebUrl}'>点击跳转至支付页面</a></h1>";
							header('Location:'.$mwebUrl);
							exit;
							
						}
					}else{
						//PC
						$order['paytype'] = 'scanwxpay';
						M('orders')->update(['id'=>$order['id']],['paytype'=>'微信扫码支付']);
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
					}
				
				
				
				break;
				case 3:
					if($this->webconf['isopenqianbao']!=1){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'未开启钱包支付！','url'=>$return_url]);
						}
						Error('未开启钱包支付！',$return_url);
					}
					if(!$this->islogin){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'您未登录，无法支付！','url'=>$return_url]);
						}
						Error('您未登录，无法支付！',$return_url);
					}
					//钱包支付
					$money = M('member')->getField(['id'=>$this->member['id']],'money');
					$paymoney = $order['price']*$this->webconf['money_exchange'];
					$allmoney = $paymoney;
					if($money<$paymoney){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'钱包金额不足，请充值！','url'=>$return_url]);
						}
						
						Error('钱包金额不足，请充值！',$return_url);
					}

					$money_x = $money-$allmoney;
					$paytime = time();
					M('orders')->update(['id'=>$order['id']],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime,'paytype'=>'钱包支付']);
					M('member')->goDec(['id'=>$order['userid']],'money',$allmoney);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $allmoney;
					$ww['money'] = $order['price'];
					$ww['type'] = 2;
					$ww['msg'] = '钱包支付';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'money';
					$ww['addtime'] = $paytime;
					M('buylog')->add($ww);

					$_SESSION['member']['money'] = $money_x;
					$order['ispay'] = 1;
					$order['isshow'] = 2;
					$order['paytime'] = $paytime;
					$order['paytype'] = '钱包支付';
					$this->order = $order;
					//交易提醒
					$task['aid'] = $order['id'];
					$task['tid'] = 0;
					$task['userid'] = $this->member['id'];
					$task['puserid'] = $this->member['id'];
					$task['molds'] = 'orders';
					$task['type'] = 'rechange';
					$task['addtime'] = time();
					$task['body'] = '您的订单-'.$order['orderno'].'已经提交，我们会尽快给您发货！';
					$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
					M('task')->add($task);

					$this->display($this->template.'/paytpl/overpay');
					exit;
				
				break;
				case 4:
					if($this->webconf['isopenjifen']!=1){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'未开启积分支付！','url'=>$return_url]);
						}
						Error('未开启积分支付！',$return_url);
						
					}
					if(!$this->islogin){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'您未登录，无法支付！','url'=>$return_url]);
						}
						Error('您未登录，无法支付！',$return_url);
					}
					//积分支付
					$jifen = M('member')->getField(['id'=>$this->member['id']],'jifen');
					$payjifen = $order['price']*$this->webconf['jifen_exchange'];
					$allmoney = $payjifen;
					if($jifen<$payjifen){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'积分不足，请充值！','url'=>$return_url]);
						}
						
						Error('积分不足，请充值！',$return_url);
					}
					$money_x = $jifen-$allmoney;
					$paytime = time();
					M('orders')->update(['id'=>$order['id']],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime,'paytype'=>'积分兑换']);
					 M('member')->goDec(['id'=>$order['userid']],'jifen',$allmoney);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $allmoney;
					$ww['money'] = $order['price'];
					$ww['type'] = 2;
					$ww['msg'] = '积分兑换';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'jifen';
					$ww['addtime'] = $paytime;
					M('buylog')->add($ww);

					$_SESSION['member']['jifen'] = $money_x;
					$order['ispay'] = 1;
					$order['isshow'] = 2;
					$order['paytime'] = $paytime;
					$order['paytype'] = '积分兑换';
					$this->order = $order;
					//交易提醒
					$task['aid'] = $order['id'];
					$task['tid'] = 0;
					$task['userid'] = $this->member['id'];
					$task['puserid'] = $this->member['id'];
					$task['molds'] = 'orders';
					$task['type'] = 'rechange';
					$task['addtime'] = time();
					$task['body'] = '您的订单-'.$order['orderno'].'已经提交，我们会尽快给您发货！';
					$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
					M('task')->add($task);
					$this->display($this->template.'/paytpl/overpay');
					exit;
				
				
				break;
				case 5:
					// 支付宝当面付
					//检查自主平台配置
					if($order['ispay']==1){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'订单已支付！','url'=>$return_url]);
						}
						
						Error('订单已支付！',$return_url);
					}
					//交易提醒
					$task['aid'] = $order['id'];
					$task['tid'] = 0;
					$task['userid'] = $this->member['id'];
					$task['puserid'] = $this->member['id'];
					$task['molds'] = 'orders';
					$task['type'] = 'rechange';
					$task['addtime'] = time();
					$task['body'] = '您的订单-'.$order['orderno'].'已经提交，请尽快支付！';
					$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
					M('task')->add($task);
					
					$order['paytype'] = 'dmfalipay';
					M('orders')->update(['id'=>$order['id']],['paytype'=>'支付宝当面付']);
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
					$result = $aliPay->dmfPay();
					$result = $result['alipay_trade_precreate_response'];
					if($result['code'] && $result['code']=='10000'){
						$url = U('common/qrcode').'?data='.$result['qr_code'];
						$this->url = $url;
						$this->payAmount = $payAmount;
						$this->order = $order;
						$this->orderno = $order['orderno'];
						$this->display($this->template.'/paytpl/dmf');
						exit;
					}else{
						echo $result['msg'].' : '.$result['sub_msg'];
					}
					exit;
				break;
				case 6:
				
					if($this->islogin){
						//交易提醒
						$task['aid'] = $order['id'];
						$task['tid'] = 0;
						$task['userid'] = $this->member['id'];
						$task['puserid'] = $this->member['id'];
						$task['molds'] = 'orders';
						$task['type'] = 'rechange';
						$task['addtime'] = time();
						$task['body'] = '您的订单-'.$order['orderno'].'已经提交，请尽快支付！';
						$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
						M('task')->add($task);
					}
					
					
					//进入第三方支付内
					$order['paytype'] = $this->frparam('payname',1,'其他平台支付');
					M('orders')->update(['id'=>$order['id']],['paytype'=>$order['paytype']]);
					$controller = $this->frparam('c',1);
					$url = U($controller.'/pay').'?'.http_build_query($order);
					Redirect($url);
				
				break;
			}
			
			
			
		}else{
			Error('订单创建失败！');
		}
	}
	
	//同步跳转
	function alipay_return_pay(){
		extendFile('pay/alipay/AlipayServiceCheck.php');
		//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥
		$alipayPublicKey=$this->webconf['alipay_public_key'];
		$aliPay = new \AlipayServiceCheck($alipayPublicKey);
		//验证签名
		$result = $aliPay->rsaCheck($_GET);

		if($result===true){
			//同步回调一般不处理业务逻辑，显示一个付款成功的页面，或者跳转到用户的财务记录页面即可。
			//echo '<h1>付款成功</h1>';
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
			$out_trade_no = format_param($out_trade_no,1);
			$orderno = $out_trade_no;
			$paytime = time();
			$order = M('orders')->find(['orderno'=>$orderno]);
			if(!$order || $_GET['total_amount']!=$order['price']){
				Error('支付成功，但是系统内没有找到相应的订单！'.$orderno,get_domain());
			}
			if($order['ispay']==1){
				//跳转对应查询详情
				//Success('支付成功！',U('User/details',['id'=>$order['id']]));
				$this->overpay($order['orderno']);
				exit;
			}
			
			$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
			
			//检查是否金币或积分充值
			if($order['ptype']==2){
					//金币充值
					M('member')->goInc(['id'=>$order['userid']],'money',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'money';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}else if($order['ptype']==3){
					//积分
					M('member')->goInc(['id'=>$order['userid']],'jifen',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'jifen';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}

			//支付成功后处理...
			$this->overpay($order['orderno']);
			exit;
			
		}
		echo '不合法的请求';
		exit();
		
		
	}
	//异步跳转--只处理状态
	function alipay_notify_pay(){
		extendFile('pay/alipay/AlipayServiceCheck.php');
		$alipayPublicKey=$this->webconf['alipay_public_key'];
		
		$aliPay = new \AlipayServiceCheck($alipayPublicKey);
		//验证签名
		$result = $aliPay->rsaCheck($_POST,$_POST['sign_type']);
		if($result===true){
			//处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
			//程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
			//echo 'success';exit();
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
			$out_trade_no = format_param($out_trade_no,1);
			$orderno = $out_trade_no;
			$paytime = time();
			$order = M('orders')->find(['orderno'=>$orderno]);
			if(!$order){
				//Error('支付成功，但是系统内没有找到相应的订单！'.$orderno,get_domain());
				exit;
			}
			if($order['ispay']==1){
				//跳转对应查询详情
				//Success('支付成功！',U('User/details',['id'=>$order['id']]));
				//$this->overpay($order['orderno']);
				exit;
			}
			
			$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
			//检查是否金币或积分充值
			if($order['ptype']==2){
					//金币充值
					M('member')->goInc(['id'=>$order['userid']],'money',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'money';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}else if($order['ptype']==3){
					//积分
					M('member')->goInc(['id'=>$order['userid']],'jifen',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'jifen';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}


			//支付成功后处理...
			//$this->overpay($order['orderno']);
			exit;
		}
		echo 'error';exit();
		
	}
	
	public function alipay_check_order(){
		/*** 请填写以下配置信息 ***/
		$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写对应应用的APPID
		$outTradeNo = $this->frparam('orderno',1);     //要查询的商户订单号。注：商户订单号与支付宝交易号不能同时为空
		$tradeNo = $this->frparam('tradeno',1,NULL);     //要查询的支付宝交易号。注：商户订单号与支付宝交易号不能同时为空
		$signType = 'RSA2';       //签名算法类型，使用RSA2
		//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
		$rsaPrivateKey=$this->webconf['alipay_private_key'];
		extendFile('pay/alipay/AlipayService.php');
		$aliPay = new \AlipayService();
		$aliPay->setAppid($appid);
		$aliPay->setRsaPrivateKey($rsaPrivateKey);
		
		 //请求参数
        $requestConfigs = array(
            'out_trade_no'=>$outTradeNo,
            'trade_no'=>$tradeNo,
        );
        $commonConfigs = array(
            //公共参数
            'app_id' => $appid,
            'method' => 'alipay.trade.query',             //接口名称
            'format' => 'JSON',
            'charset'=>'utf8',
            'sign_type'=>'RSA2',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'biz_content'=>json_encode($requestConfigs),
        );
        $commonConfigs["sign"] = $aliPay->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $aliPay->curlPost('https://openapi.alipay.com/gateway.do?charset=utf8',$commonConfigs);
        $result = json_decode($result,true);
		$msg = '';
		$code = 1;
		if($result['alipay_trade_query_response']['code']!='10000'){
			$msg = $result['alipay_trade_query_response']['msg'].'：'.$result['alipay_trade_query_response']['sub_code'].' '.$result['alipay_trade_query_response']['sub_msg'];
		}else{
			switch($result['alipay_trade_query_response']['trade_status']){
				case 'WAIT_BUYER_PAY':
					$msg = '交易创建，等待买家付款';
					break;
				case 'TRADE_CLOSED':
					$msg = '未付款交易超时关闭，或支付完成后全额退款';
					break;
				case 'TRADE_SUCCESS':
					$msg = '支付成功';
					$code = 0;
					
					$out_trade_no = $outTradeNo;
					$orderno = $out_trade_no;
					$paytime = time();
					$order = M('orders')->find(['orderno'=>$orderno]);
					
					if($order['ispay']==1){
						//跳转对应查询详情
						
						JsonReturn(['code'=>$code,'msg'=>$msg]);
					}
					
					$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
					
					//检查是否金币或积分充值
					if($order['ptype']==2){
							//金币充值
							M('member')->goInc(['id'=>$order['userid']],'money',$order['jifen']);
							$ww['userid'] = $order['userid'];
							$ww['amount'] = $order['jifen'];
							$ww['money'] = $order['money'];
							$ww['type'] = 1;
							$ww['msg'] = '在线充值';
							$ww['orderno'] = $order['orderno'];
							$ww['buytype'] = 'money';
							$ww['addtime'] = time();
							M('buylog')->add($ww);
						}else if($order['ptype']==3){
							//积分
							M('member')->goInc(['id'=>$order['userid']],'jifen',$order['jifen']);
							$ww['userid'] = $order['userid'];
							$ww['amount'] = $order['jifen'];
							$ww['money'] = $order['money'];
							$ww['type'] = 1;
							$ww['msg'] = '在线充值';
							$ww['orderno'] = $order['orderno'];
							$ww['buytype'] = 'jifen';
							$ww['addtime'] = time();
							M('buylog')->add($ww);
						}

					//支付成功后处理...
					//$this->overpay($order['orderno']);
					
					
					
					
					break;
				case 'TRADE_FINISHED':
					$msg = '交易结束，不可退款';
					break;
				default:
					$msg = '未知状态';
					break;
			}
		}
		
		JsonReturn(['code'=>$code,'msg'=>$msg]);
		
		
	}
	
	public function wechat_notify_pay(){
		extendFile('pay/wechat/WxpayServiceCheck.php');
		$mchid = $this->webconf['wx_mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
		$appid = $this->webconf['wx_appid'];  //公众号APPID 通过微信支付商户资料审核后邮件发送
		$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
		$wxPay = new \WxpayServiceCheck($mchid,$appid,$apiKey);
		$result = $wxPay->notify();
		if($result){
			//完成你的逻辑
			//例如连接数据库，获取付款金额$result['cash_fee']，获取订单号$result['out_trade_no']，修改数据库中的订单状态等;
			//现金支付金额：$result['cash_fee']
			//订单金额：$result['total_fee']
			//商户订单号：$result['out_trade_no']
			//付款银行：$result['bank_type']
			//货币种类：$result['fee_type']
			//是否关注公众账号：$result['is_subscribe']
			//用户标识：$result['openid']
			//业务结果：$result['result_code']  SUCCESS/FAIL
			//支付完成时间：$result['time_end']  格式为yyyyMMddHHmmss
			//具体详细请看微信文档：https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_7&index=8
			
			$out_trade_no = htmlspecialchars($result['out_trade_no']);
			$out_trade_no = format_param($out_trade_no,1);
			$orderno = $out_trade_no;
			$paytime = time();
			$order = M('orders')->find(['orderno'=>$orderno]);
			if(!$order){
				//Error('支付成功，但是系统内没有找到相应的订单！'.$orderno,get_domain());
				exit;
			}
			if($order['ispay']==1){
				//跳转对应查询详情
				//Success('支付成功！',U('User/details',['id'=>$order['id']]));
				//$this->overpay($order['orderno']);
				exit;
			}
			
			$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
			//检查是否金币或积分充值
			if($order['ptype']==2){
					//金币充值
					M('member')->goInc(['id'=>$order['userid']],'money',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'money';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}else if($order['ptype']==3){
					//积分
					M('member')->goInc(['id'=>$order['userid']],'jifen',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'jifen';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}


			//支付成功后处理...
			//$this->overpay($order['orderno']);
			exit;
			
			
		}else{
			echo 'pay error';
		}
		
	}
	
	public function wechat_return_pay(){
		$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno]);
		if($orderno && $order){
			extendFile('pay/wechat/WxpayServiceCheck.php');
			$mchid = $this->webconf['wx_mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
			$appid = $this->webconf['wx_appid'];  //公众号APPID 通过微信支付商户资料审核后邮件发送
			$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
			$wxPay = new \WxpayServiceCheck($mchid,$appid,$apiKey);
			$result = $wxPay->notify();
			if($result){
				$out_trade_no = htmlspecialchars($result['out_trade_no']);
				$out_trade_no = format_param($out_trade_no,1);
				$orderno = $out_trade_no;
				$paytime = time();
				
				if($order['ispay']==1){
					//跳转对应查询详情
					//Success('支付成功！',U('User/details',['id'=>$order['id']]));
					$this->overpay($order['orderno']);
					exit;
				}
				
				$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
				//检查是否金币或积分充值
			if($order['ptype']==2){
					//金币充值
					M('member')->goInc(['id'=>$order['userid']],'money',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'money';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}else if($order['ptype']==3){
					//积分
					M('member')->goInc(['id'=>$order['userid']],'jifen',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'jifen';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}


				//支付成功后处理...
				$this->overpay($order['orderno']);
				exit;
			}
			
			
		}
		
		exit('订单号错误或订单被删除！');
		
	}
	
	public function check_wechat_order(){
		extendFile('pay/wechat/WxpayCheckOrder.php');
		$mchid = $this->webconf['wx_mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
		$appid = $this->webconf['wx_appid'];  //公众号APPID 通过微信支付商户资料审核后邮件发送
		$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
		$outTradeNo = $this->frparam('orderno',1);     //要查询的订单号
		
		$order = M('orders')->find(['orderno'=>$outTradeNo]);
		if($outTradeNo && $order){
			if($order['ispay']==1){
                if($this->frparam('ajax')){
                    JsonReturn(['code'=>0,'msg'=>'success']);
                }
				$this->overpay($outTradeNo);exit;
			}
			/** 配置结束 */
			$wxPay = new \WxpayCheckOrder($mchid,$appid,$apiKey);
			$res = $wxPay->orderquery($outTradeNo);
			//echo json_encode($result);die;
			//$res = json_encode($result);
			if($res['code']==0){
				$r = M('orders')->update(['orderno'=>$outTradeNo],['ispay'=>1,'isshow'=>2,'paytime'=>$res['time']]);
				//检查是否金币或积分充值
			if($order['ptype']==2){
					//金币充值
					M('member')->goInc(['id'=>$order['userid']],'money',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'money';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}else if($order['ptype']==3){
					//积分
					M('member')->goInc(['id'=>$order['userid']],'jifen',$order['jifen']);
					$ww['userid'] = $order['userid'];
					$ww['amount'] = $order['jifen'];
					$ww['money'] = $order['money'];
					$ww['type'] = 1;
					$ww['msg'] = '在线充值';
					$ww['orderno'] = $order['orderno'];
					$ww['buytype'] = 'jifen';
					$ww['addtime'] = time();
					M('buylog')->add($ww);
				}


				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>'success']);
				}
				
				$this->overpay($outTradeNo);
			}else{
				
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>$res['msg']]);
				}
				
				Error($res['msg'],U('Order/details',['orderno'=>$outTradeNo]));
			}
			
		}
		
		
	}
	
	private function overpay($orderno){
		$order = M('orders')->find(['orderno'=>$orderno,'ispay'=>1]);

		if($orderno && $order){
			
			$this->order = $order;
			$this->display($this->template.'/paytpl/overpay');
		}else{
			exit('订单未支付或订单号错误！');
		}
		
	}
	
	public function wechat_scan_over(){
		$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno,'ispay'=>1]);

		if($orderno && $order){
			
			$this->order = $order;
			$this->display($this->template.'/paytpl/overpay');
		}else{
			exit('订单未支付或订单号错误！');
		}
	}
	
	

	
}