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


namespace app\home\c;


use frphp\extend\Page;

class OrderController extends CommonController
{
	function _init(){
		parent::_init();
		if(!$this->islogin){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>JZLANG('您还未登录，请重新登录！')]);
			}
			Error(JZLANG('您还未登录，请重新登录！'),U('Login/index'));
		}
		
	}
	
	
	
	function create(){
		if($this->frparam('go')){
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
				$qianbao = 0;
				$jifen = 0;
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
						if(isset($info['jifen']) && $info['jifen']!=0){
							$jifen+=$d[2]*$info['jifen'];
						}else{
							$jifen+=$d[2]*$d[3]*($this->webconf['jifen_exchange']);
						}
						$qianbao+=$d[2]*$d[3]*($this->webconf['money_exchange']);
					}

					
				}
				//运费
				$yunfei = $this->webconf['yunfei'];
				//折扣
				$discount = 0.00;
				if($group['discount_type']==1){
					$discount = $group['discount'];
				}else if($group['discount_type']==2){
					$discount = round((1-$group['discount'])*$price,2);
				}
				$w['body'] = '||'.implode('||',$new).'||';
				$w['yunfei'] = $yunfei;
				$w['discount'] = $discount;
				$w['price'] = $price-$discount+$yunfei;
				if($w['price']<0){
					$w['price'] = 0;
				}
				$res = M('orders')->add($w);
				if($res){
					//减库存
					$allproduct = $carts;
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
					$_SESSION['cart'] = '';
					$this->carts = $newcart;
					$this->qianbao = $qianbao+$discount*($this->webconf['money_exchange'])-$yunfei*($this->webconf['money_exchange']);
					$this->jifen = $jifen+$discount*($this->webconf['jifen_exchange'])-$yunfei*($this->webconf['jifen_exchange']);
					$this->order = M('orders')->find(['id'=>$res]);
					if($this->webconf['isopenjifen']==1){
						M('orders')->update(['id'=>$res],['jifen'=>$this->jifen]);
					}
					if($this->webconf['isopenqianbao']==1){
						M('orders')->update(['id'=>$res],['qianbao'=>$this->qianbao]);
					}
					$this->display($this->template.'/user/payment');
					
				}else{
					Error(JZLANG('创建订单失败！'));
				}
			}else{
				Redirect(U('user/cart'));
			}
		}else{
			Redirect(U('user/cart'));
		}
		
	}
	
	
	//支付处理
	function pay(){
		if($this->frparam('go')){
			//保存提交信息
			$return_url = U('user/orderdetails',['orderno'=>$this->frparam('orderno',1)]);
			$w['orderno'] = $this->frparam('orderno',1);
			$w['receive_username'] = $this->frparam('username',1);
			$w['receive_tel'] = $this->frparam('tel',1);
			$w['receive_email'] = $this->frparam('email',1);
			$w['receive_address'] = $this->frparam('address',1);
			$paytype = $this->frparam('paytype',0,1);//默认支付宝支付1，2微信支付
			
			$order = M('orders')->find(['orderno'=>$w['orderno']]);
			if(!$order || !$w['orderno']){
				if($this->frparam('ajax')){
					
					JsonReturn(['code'=>1,'msg'=>JZLANG('订单号不存在或已被删除！'),'url'=>$return_url]);
					
				}
				Error(JZLANG('订单号不存在或已被删除！'),$return_url);
			}
			//购物订单
			if($order['ptype']==1){
				if($w['receive_username']=='' || $w['receive_tel']=='' || $w['receive_address']==''){
					if($this->frparam('ajax')){
						
						JsonReturn(['code'=>1,'msg'=>JZLANG('收件人、手机号和收货地址不能为空！'),'url'=>$return_url]);
						
					}
					
					Error(JZLANG('收件人、手机号和收货地址不能为空！'),$return_url);
					
				}
			}else{
				//充值订单
				//检查是否支持在线支付
				if($this->webconf['paytype']==0){
					Error(JZLANG('未开启在线支付！'),$return_url);
				}
			}
			
			
			//保存信息
			$res = M('orders')->update(['id'=>$order['id']],$w);

			//未设置在线支付
			//提示接收信息邮箱
			//检测是否已经配置邮件发送
			if($this->webconf['isopenemail']==1 && $order['ptype']==1){
				if($this->webconf['email_server'] && $this->webconf['email_port'] &&  $this->webconf['send_email'] &&  $this->webconf['send_pass']){
					$title = JZLANG('您的订单提交成功通知').'-'.$this->webconf['web_name'];
					if($this->webconf['tj_msg']!=''){
						$body = str_replace('{xxx}',$w['receive_username'],$this->webconf['tj_msg']);
					}else{
						$body = JZLANG('尊敬的').$w['receive_username'].JZLANG('我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！');
					}
					
					$body.='<br/>'.JZLANG('订单详细信息如下').'：<br/>';
					$body.='<table style="min-width:500px">
					<tr><th width="20%">'.JZLANG('主图').'</th><th width="20%">'.JZLANG('商品').'</th><th width="20%">'.JZLANG('价格').'</th><th width="20%">'.JZLANG('购买数量').'</th><th width="20%">'.JZLANG('总价').'</th></tr>';
					
					foreach(explode('||',$order['body']) as $v){
						if($v!=''){
							$d = explode('-',$v);
							//tid-id-num-price
							if($d[0]!=''){
								$type = $this->classtypedata[$d[0]];//栏目
								$product = M($type['molds'])->find(['id'=>$d[1]]);
								$body.='<tr><td width="20%"><img width="200px" src="'.get_domain().$product['litpic'].'" /></td><td width="20%">'.$product['title'].'</td><td width="20%">'.JZLANG('￥').$d[3].JZLANG('元').'</td><td width="20%">'.$d[2].'</td><td width="20%">'.JZLANG('￥').($d[3]*$d[2]).JZLANG('元').'</td></tr>';
								
							}
							
							
						}
						
					}
					
					$body.='<tr><td>'.JZLANG('折扣').'：</td><td colspan="4">'.JZLANG('￥').$order['discount'].JZLANG('元').'</td></tr><tr><td>'.JZLANG('运费').'：</td><td colspan="4">'.JZLANG('￥').$order['yunfei'].JZLANG('元').'</td></tr><tr><td>'.JZLANG('合计').'：</td><td colspan="4">￥'.$order['price'].'元</td></tr></table><br/>';
					$body.=JZLANG('收件地址').'：'.$w['receive_address'].' '.JZLANG('联系电话').'：'.$w['receive_tel'];
					if($this->webconf['shou_email']!=''){
						send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$w['receive_email'],$title,$body,$this->webconf['shou_email']);
					}else{
					    send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$w['receive_email'],$title,$body); 
					}
					
					
					
					
				}
			}

			if($this->webconf['paytype']==0 && $order['ptype']==1){
				
				//更新订单状态，提示收到提交订单
				M('orders')->update(['id'=>$order['id']],['isshow'=>4,'paytype'=>JZLANG('线下支付')]);
				
				
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，我们会尽快给您发货！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);

				if($this->frparam('ajax')){
				
					JsonReturn(['code'=>0,'msg'=>JZLANG('我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！'),'url'=>U('user/orders')]);
				}
				
				Success(JZLANG('我们已经收到您的订单，我们会尽快给你发货，请密切关注您的邮箱以获得订单的最新消息，谢谢合作！'),U('User/orders'));
			
				
				
			}else if($paytype==1){
				//支付宝
				//检查自主平台配置
				if($order['ispay']==1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('订单已支付！'),'url'=>$return_url]);
					}
					
					Error(JZLANG('订单已支付！'),$return_url);
				}
				
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，请尽快支付！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);
				if(isMobile()){
					//手机端
					if(isWeixin()){
						//微信内
						$order['paytype'] = 'h5alipay';
						M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('支付宝H5支付')]);
						extendFile('pay/alipay/AlipayService.php');
						$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
						$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
						$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
						$outTradeNo = $order['orderno'];     //你自己的商品订单号
						$payAmount = $order['price'];          //付款金额，单位:元
						$orderName = JZLANG('支付订单').'-'.$order['orderno'];    //订单标题
                        $rsaPrivateKey = $this->webconf['alipay_private_key'];
                        $aliPay = new \AlipayService();
                        $aliPay->setAppid($appid);
                        $aliPay->setReturnUrl($returnUrl);
                        $aliPay->setNotifyUrl($notifyUrl);
                        $aliPay->setRsaPrivateKey($rsaPrivateKey);
                        $aliPay->setTotalFee($payAmount);
                        $aliPay->setOutTradeNo($outTradeNo);
                        $aliPay->setOrderName($orderName);
                        $payConfigs = $aliPay->wxPay();
                        $this->queryStr = http_build_query($payConfigs);
                        $this->display($this->template.'/paytpl/alipay_in_weixin');
						exit;
					}else{
						//支付宝H5支付
						$order['paytype'] = 'h5alipay';
						M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('支付宝H5支付')]);
						/*** 请填写以下配置信息 ***/
						extendFile('pay/alipay/AlipayService.php');
						$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
						$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
						$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
						$outTradeNo = $order['orderno'];     //你自己的商品订单号
						$payAmount = $order['price'];          //付款金额，单位:元
						$orderName = JZLANG('支付订单').'-'.$order['orderno'];    //订单标题
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
						$sHtml = $aliPay->mPay();
						echo $sHtml;exit;
					}
					
				}else{
					//PC
					$order['paytype'] = 'alipay';
					M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('电脑支付宝支付')]);
					/*** 请填写以下配置信息 ***/
					extendFile('pay/alipay/AlipayService.php');
					$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
					$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
					$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
					$outTradeNo = $order['orderno'];     //你自己的商品订单号
					$payAmount = $order['price'];          //付款金额，单位:元
					$orderName = JZLANG('支付订单').'-'.$order['orderno'];    //订单标题
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
				
				
			}else if($paytype==2){
				//微信
				//检查自主平台配置
				if($order['ispay']==1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('订单已支付！'),'url'=>$return_url]);
					}
					
					Error(JZLANG('订单已支付！'),$return_url);
				}
				
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，请尽快支付！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);
				if(isMobile()){
					//手机端
					if(isWeixin()){
						//微信内
						$order['paytype'] = 'wxpay';
						M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('微信内支付')]);
						$url = U('order/wxpay').'?'.http_build_query($order);
						Redirect($url);
						
						exit;
					}else{
						//微信H5支付
						$order['paytype'] = 'h5wxpay';
						M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('微信H5支付')]);
						extendFile('pay/wechat/WxpayH5Service.php');
						/** 请填写以下配置信息 */
						$mchid = $this->webconf['wx_mchid'];   //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
						$appid =  $this->webconf['wx_appid'];  //微信支付申请对应的公众号的APPID
						$appKey = $this->webconf['wx_appsecret'];   //微信支付申请对应的公众号的APP Key
						$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 
						$outTradeNo = $order['orderno'];     //你自己的商品订单号
						$payAmount = $order['price'];          //付款金额，单位:元
						$orderName = JZLANG('支付订单').'-'.$order['orderno'];    //订单标题
						$notifyUrl = U('Mypay/wechat_notify_pay');     //付款成功后的回调地址(不要有问号)
						//$returnUrl = U('Mypay/check_wechat_order').'?orderno='.$order['orderno'];     //付款成功后，页面跳转的地址
						$returnUrl = U('order/wxh5pay').'?orderno='.$order['orderno'];     //付款成功后，页面跳转的地址
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
					M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('微信扫码支付')]);
					extendFile('pay/wechat/WxpayScan.php');
					//微信扫码支付
					$mchid = $this->webconf['wx_mchid'];   //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
					$appid =  $this->webconf['wx_appid'];  //微信支付申请对应的公众号的APPID
					$appKey = $this->webconf['wx_appsecret'];   //微信支付申请对应的公众号的APP Key
					$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
					$wxPay = new \WxpayScan($mchid,$appid,$apiKey);
					$outTradeNo = $order['orderno'];     //你自己的商品订单号
					$payAmount = $order['price'];          //付款金额，单位:元
					$orderName = JZLANG('支付订单').':'.$order['orderno'];    //订单标题
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
				
			}else if($paytype==3){
				if($this->webconf['isopenqianbao']!=1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('未开启钱包支付！'),'url'=>$return_url]);
					}
					Error(JZLANG('未开启钱包支付！'),$return_url);
				}
				//钱包支付
				$money = M('member')->getField(['id'=>$this->member['id']],'money');
				
				$allmoney = $order['price']*$this->webconf['money_exchange'];
				if($money<$allmoney){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('钱包金额不足，请充值！'),'url'=>$return_url]);
					}
					
					Error(JZLANG('钱包金额不足，请充值！'),$return_url);
				}

				$money_x = $money-$allmoney;
				$paytime = time();
				M('orders')->update(['id'=>$order['id']],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime,'paytype'=>JZLANG('钱包支付')]);
				M('member')->update(['id'=>$order['userid']],['money'=>$money_x]);
				$ww['userid'] = $order['userid'];
				$ww['amount'] = $allmoney;
				$ww['money'] = $order['price'];
				$ww['type'] = 2;
				$ww['msg'] = JZLANG('钱包支付');
				$ww['orderno'] = $order['orderno'];
				$ww['buytype'] = 'money';
				$ww['addtime'] = $paytime;
				M('buylog')->add($ww);

				$_SESSION['member']['money'] = $money_x;
				$order['ispay'] = 1;
				$order['isshow'] = 2;
				$order['paytime'] = $paytime;
				$order['paytype'] = JZLANG('钱包支付');
				$this->order = $order;
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，我们会尽快给您发货！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);

				$this->display($this->template.'/paytpl/overpay');
				exit;

			}else if($paytype==4){
				if($this->webconf['isopenjifen']!=1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('未开启积分支付！'),'url'=>$return_url]);
					}
					Error(JZLANG('未开启积分支付！'),$return_url);
					
				}
				//积分支付
				$jifen = M('member')->getField(['id'=>$this->member['id']],'jifen');
				$allmoney = $order['price']*$this->webconf['jifen_exchange'];
				//$allmoney = $order['jifen'];
				if($jifen<$order['jifen']){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('积分不足，请充值！'),'url'=>$return_url]);
					}
					
					Error(JZLANG('积分不足，请充值！'),$return_url);
				}
				$money_x = $jifen-$allmoney;
				$paytime = time();
				M('orders')->update(['id'=>$order['id']],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime,'paytype'=>JZLANG('积分兑换')]);
				
				M('member')->update(['id'=>$order['userid']],['jifen'=>$money_x]);
				$ww['userid'] = $order['userid'];
				$ww['amount'] = $allmoney;
				$ww['money'] = $order['price'];
				$ww['type'] = 2;
				$ww['msg'] = JZLANG('积分兑换');
				$ww['orderno'] = $order['orderno'];
				$ww['buytype'] = 'jifen';
				$ww['addtime'] = $paytime;
				M('buylog')->add($ww);

				$_SESSION['member']['jifen'] = $money_x;
				$order['ispay'] = 1;
				$order['isshow'] = 2;
				$order['paytime'] = $paytime;
				$order['paytype'] = JZLANG('积分兑换');
				$this->order = $order;
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，我们会尽快给您发货！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);
				$this->display($this->template.'/paytpl/overpay');
				exit;
			}else if($paytype==5){
				// 支付宝当面付
				//检查自主平台配置
				if($order['ispay']==1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('订单已支付！'),'url'=>$return_url]);
					}
					
					Error(JZLANG('订单已支付！'),$return_url);
				}
				
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，请尽快支付！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);
				
				$order['paytype'] = 'dmfalipay';
				M('orders')->update(['id'=>$order['id']],['paytype'=>JZLANG('支付宝当面付')]);
				/*** 请填写以下配置信息 ***/
				extendFile('pay/alipay/AlipayService.php');
				$appid = $this->webconf['alipay_partner'];  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
				$returnUrl = U('Mypay/alipay_return_pay');     //付款成功后的同步回调地址
				$notifyUrl = U('Mypay/alipay_notify_pay');     //付款成功后的异步回调地址
				$outTradeNo = $order['orderno'];     //你自己的商品订单号
				$payAmount = $order['price'];          //付款金额，单位:元
				$orderName = JZLANG('支付订单').'-'.$order['orderno'];    //订单标题
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
					//echo '<img src="'.$url.'" style="width:300px;"><br>';
					$this->url = $url;
					$this->payAmount = $payAmount;
					$this->order = $order;
					$this->orderno = $order['orderno'];
					$this->display($this->template.'/paytpl/dmf');
					exit;
				}else{
					echo $result['msg'].' : '.$result['sub_msg'];
				}
				
			}else {
				
				//交易提醒
				$task['aid'] = $order['id'];
				$task['tid'] = 0;
				$task['userid'] = $this->member['id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = 'orders';
				$task['type'] = 'rechange';
				$task['addtime'] = time();
				$task['body'] = JZLANG('您的订单').'-'.$order['orderno'].JZLANG('已经提交，请尽快支付！');
				$task['url'] = U('user/orderdetails',['orderno'=>$order['orderno']]);
				M('task')->add($task);
				
				//进入第三方支付内
				$order['paytype'] = $this->frparam('payname',1,JZLANG('其他平台支付'));
				M('orders')->update(['id'=>$order['id']],['paytype'=>$order['paytype']]);
				$controller = $this->frparam('c',1);
				$url = U($controller.'/pay').'?'.http_build_query($order);
				Redirect($url);
				
			}
			
			
			
		}
		
		
		
		
		
	}
	
	//jsapi
	function wxpay(){
			
			//微信支付
			
			extendFile('pay/wechat/WxpayService.php');
			$mchid = $this->webconf['wx_mchid'];   //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
			$appid =  $this->webconf['wx_appid'];  //微信支付申请对应的公众号的APPID
			$appKey = $this->webconf['wx_appsecret'];   //微信支付申请对应的公众号的APP Key
			$apiKey = $this->webconf['wx_key'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
			//①、获取用户openid
			$wxPay = new \WxpayService($mchid,$appid,$appKey,$apiKey);
			$openId = $wxPay->GetOpenid();      //获取openid
			
			if(!$openId) exit(JZLANG('获取openid失败'));
			//②、统一下单
			$outTradeNo = $this->frparam('orderno',1);     //你自己的商品订单号
			$payAmount = $this->frparam('price',3);          //付款金额，单位:元
			$orderName = JZLANG('支付订单').'：'.$outTradeNo;    //订单标题
			$notifyUrl = U('Mypay/wechat_notify_pay');     //付款成功后的回调地址(不要有问号)
			$returnUrl = U('Mypay/check_wechat_order',['orderno'=>$outTradeNo]);     //付款成功后的回调地址(不要有问号)
			$payTime = JZLANG('支付订单').'-'.$outTradeNo;      //付款时间
			$jsApiParameters = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
			
			$this->payAmount = $payAmount;
			$this->returnUrl = $returnUrl;
			$jsApiParameters = json_encode($jsApiParameters);
			$this->jsApiParameters = $jsApiParameters;
			$this->order = M('orders')->find(['orderno'=>$outTradeNo]);
			$this->display($this->template.'/paytpl/wechat_pay');
			exit;

	}

	//微信h5支付验证
	function wxh5pay(){
		$this->orderno = $this->frparam('orderno',1);
		if(!$this->orderno){
			Error(JZLANG('链接错误！'));
		}
		$this->display($this->template.'/paytpl/wechat_h5_pay');
		exit;
	}
	
	
	
}