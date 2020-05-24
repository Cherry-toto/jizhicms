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

class MypayController extends Controller
{
	/**
		
		自主平台支付回调
	
	**/
	
	public function _init(){
		
		$webconf = webConf();
		$template = get_template();
		$this->webconf = $webconf;
		$this->template = $template;
		$classtypedata = classTypeData();
		foreach($classtypedata as $k=>$v){
			$classtypedata[$k]['children'] = get_children($v,$classtypedata);
		}
		$this->classtypedata = $classtypedata;
		$this->common = Tpl_style.'common/';
		$this->tpl = Tpl_style.$template.'/';
		$this->frpage = $this->frparam('page');
		$customconf = get_custom();
		$this->customconf = $customconf;
		if(isset($_SESSION['member'])){
			$this->islogin = true;
			$this->member = $_SESSION['member'];
			
			
			
		}else{
			$this->islogin = false;
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