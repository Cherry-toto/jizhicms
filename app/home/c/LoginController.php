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



class LoginController extends CommonController
{
	function _init(){
		
		if(!M('molds')->find(['biaoshi'=>'member','isopen'=>1])){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>JZLANG('会员中心已关闭！')]);
			}
			Error(JZLANG('会员中心已关闭！'));
			exit;
		}
		parent::_init();

    
    }
	
	public function index(){
		//检测是否已经设置过return_url,防止多次登录覆盖
		if(!isset($_SESSION['return_url'])){
			$referer = (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') ? U('user/index') : $_SERVER['HTTP_REFERER'];
			$_SESSION['return_url'] = $referer;
		
		}
		
		if($_POST){
			$data['username'] = str_replace("'",'',$this->frparam('tel',1));//进行二次过滤校验
			$data['password'] = str_replace("'",'',$this->frparam('password',1));
			if(!isset($this->webconf['closehomevercode']) || $this->webconf['closehomevercode']!=1){
				$vercode = strtolower($this->frparam('vercode',1));
				if(!$vercode || md5(md5($vercode))!=$_SESSION['login_vercode']){
					$xdata = array('code'=>1,'msg'=>JZLANG('验证码错误！'));
					if($this->frparam('ajax')){
						JsonReturn($xdata);
					}
					Error(JZLANG('验证码错误！'));
				}
			}
			$_SESSION['login_vercode'] = getRandChar(32);
			if($data['username']=='' || $data['password']==''){
				$xdata = array('code'=>1,'msg'=>JZLANG('账户密码不能为空！'));
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error(JZLANG('账户密码不能为空！'));
			}
			
			
			$where['pass'] = md5(md5($data['password']).md5($data['password']));
			$where['tel'] = $data['username'];
			$res = M('member')->find($where);
			//unset($where['tel']);
			//$where['username'] = $data['username'];
			unset($where['pass']);
			$where['token'] = $data['password'];//token登录
			$res1 =  M('member')->find($where);
			$where['email'] = $data['username'];
			unset($where['tel']);
			unset($where['token']);
			$where['pass'] = md5(md5($data['password']).md5($data['password']));
			$res2 = M('member')->find($where);

			
			if($res || $res1 || $res2){
				if($res1){
					$res = $res1;
				}
				if($res2){
					$res = $res2;
				}
				unset($res['password']);
				//检测权限
				if($res['isshow']!=1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('您的账户已被冻结！'),'url'=>$_SESSION['return_url']]);
					}
					Error(JZLANG('您的账户已被冻结！'));
				}

				$group = M('member_group')->find(array('id'=>$res['gid']));
				if(!$group){
                    if($this->frparam('ajax')) {
                        JsonReturn(['code' => 1, 'msg' => JZLANG('未找到您所在分组，请联系管理员处理！'), 'url' => $_SESSION['return_url']]);
                    }
                    Error(JZLANG('未找到您所在分组，请联系管理员处理！'));
				}
				unset($group['id']);
				//检测分组权限
				if($group['isagree']!=1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('您所在的分组被限制登录！'),'url'=>$_SESSION['return_url']]);
					}
					Error(JZLANG('您所在的分组被限制登录！'));
				}
				
				$_SESSION['member'] = array_merge($res,$group);
				//$_SESSION['member'] = $res;
				$update['logintime'] = time();
                //是否记住密码登录,更新token
				if($this->frparam('isremember')){
					$update['token'] = $_SESSION['token'];
				}
               
                //检查是否开启登录奖励
                if($this->webconf['login_award_open']==1){
                	$awoard = floatval($this->webconf['login_award']);
                	if($awoard>0){
                		$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                		$sql = " msg = '".JZLANG('登录奖励')."' and addtime>=".$start." and addtime<".$end." and userid=".$_SESSION['member']['id'];
                		if(!M('buylog')->find($sql)){
                			$w['userid'] = $_SESSION['member']['id'];
                			$w['buytype'] = 'jifen';
				   	  		$w['type'] = 3;
				   	  		$w['msg'] = JZLANG('登录奖励');
				   	  		$w['addtime'] = time();
				   	  		$w['orderno'] = 'No'.date('YmdHis');
				   	  		$w['amount'] = $awoard;
				   	  		$w['money'] = $w['amount']/($this->webconf['money_exchange']);
				   	  		$r = M('buylog')->add($w);
				   	  		if($r){
				   	  			$update['jifen'] = $_SESSION['member']['jifen']+$awoard;
				   	  			$_SESSION['member']['jifen'] = $update['jifen'];
				   	  		}
                		}
                	}
                }
                M('member')->update(array('id'=>$res['id']),$update);
				//兼容ajax登录
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>JZLANG('登录成功！'),'url'=>$_SESSION['return_url']]);
				}
				Redirect($_SESSION['return_url']);
			}else{
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>JZLANG('账户密码错误！'),'url'=>$_SESSION['return_url']]);
				}
				Error(JZLANG('账户密码错误！'));
			}
			
		}
		
		$token = getRandChar(32);//系统内置32位随机数,混淆前台规则猜测(MD5)
		$_SESSION['token'] = $token;
		$this->token = $token;
     
      
		$this->display($this->template.'/user/login');
	}

  function register(){
	  if($this->webconf['isregister']==0){
		  Error(JZLANG('系统已关闭会员注册！'));
	  }
	  $_SESSION['return_url'] = U('user/index');
	  if($_POST){
		  //检查邀请链接的合法性
		  if($this->webconf['onlyinvite']==1){
			  if(!M('member')->find(['id'=>$this->frparam('pid'),'isshow'=>1])){
				    $xdata = array('code'=>1,'msg'=>JZLANG('您的邀请链接不合法！'));
				    if($this->frparam('ajax')){
						JsonReturn($xdata);
					}
					Error(JZLANG('您的邀请链接不合法！'));
			  }
			
		  }
		  if(!isset($this->webconf['closehomevercode']) || $this->webconf['closehomevercode']!=1){
			    $vercode = strtolower($this->frparam('vercode',1));
			    if(!$vercode || md5(md5($vercode))!=$_SESSION['reg_vercode']){
					$xdata = array('code'=>1,'msg'=>JZLANG('验证码错误！'));
					if($this->frparam('ajax')){
						JsonReturn($xdata);
					}
					Error(JZLANG('验证码错误！'));
				}
		  }
          $_SESSION['reg_vercode'] = getRandChar(32);
		  $w['email'] = $this->frparam('email',1,'');
		  $w['password'] = $this->frparam('password',1);
		  $w['repassword'] = $this->frparam('repassword',1);
		  $w['tel'] = $this->frparam('tel',1);
		  if($w['password']=='' || $w['tel']==''){
				$xdata = array('code'=>1,'msg'=>JZLANG('账户密码不能为空！'));
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error(JZLANG('账户密码不能为空！'));
		  }
		  if($w['password']!=$w['repassword']){
				$xdata = array('code'=>1,'msg'=>JZLANG('两次密码不同！'));
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error(JZLANG('两次密码不同！'));
		  }
          if($w['tel']){
              if (preg_match("/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[0-9])\\d{8}$/", $w['tel'])) {
              }else {
                  if ($this->frparam('ajax')) {
                      JsonReturn(['code' => 1, 'msg' => JZLANG('手机号格式不正确！')]);
                  }
                  Error(JZLANG('手机号格式不正确！'));
              }
          }
		$w['regtime'] = time();
		//检查邮箱
		if($w['email']){
			if(M('member')->find(['email'=>$w['email']])){
				$xdata = array('code'=>1,'msg'=>JZLANG('您的邮箱已注册！'));
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error(JZLANG('您的邮箱已注册！'));
			}
		}
		//检查是否已被注册
		if(M('member')->find(['tel'=>$w['tel']])){
			$xdata = array('code'=>1,'msg'=>JZLANG('您的手机号码已注册！'));
			if($this->frparam('ajax')){
				JsonReturn($xdata);
			}
			Error(JZLANG('您的手机号码已注册！'));
		}
		$w['username'] = getRandChar(6);
		$w['pass'] =  md5(md5($w['password']).md5($w['password']));
		$w['pid'] = $_SESSION['invite'];
		$r = M('member')->add($w);
		if($r){
			$userid = $r;
			//检查是否开启邀请奖励
			if($this->webconf['invite_award_open']==1 && $w['pid'] && $this->webconf['invite_award']){
				$ww['userid'] = $w['pid'];
				$ww['buytype'] = $this->webconf['invite_type'];
				$ww['type'] = 3;
				$ww['msg'] = JZLANG('邀请奖励');
				$ww['addtime'] = time();
				$ww['orderno'] = 'No'.date('YmdHis');
				$ww['amount'] = $this->webconf['invite_award'];
				if($ww['buytype']=='jifen'){
					$ww['money'] = $ww['amount']/($this->webconf['jifen_exchange']);
				}else{
					$ww['money'] = $ww['amount']/($this->webconf['money_exchange']);
				}
				M('member')->goInc(['id'=>$w['pid']],$ww['buytype'],$ww['amount']);
				M('buylog')->add($ww);
			}
			//自动登录
			if($this->frparam('autologin')){
				$member = M('member')->find(['id'=>$userid]);
				$member_group = M('member_group')->find(['id'=>$member['gid']]);
				if($member_group['isagree']!=1){
					$xdata = array('code'=>1,'msg'=>JZLANG('注册成功，等待审核！'),'url'=>U('login/index'));
				}else{
					unset($member['pass']);
					unset($member_group['id']);
					$_SESSION['member'] = array_merge($member,$member_group);
					$xdata = array('code'=>0,'msg'=>JZLANG('注册成功！'),'url'=>U('user/index'));
				}
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Success($xdata['msg'],$xdata['url']);
				
				
			}else{
				$xdata = array('code'=>0,'msg'=>JZLANG('注册成功！'),'url'=>U('login/index'));
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Success(JZLANG('注册成功！'),U('login/index'));
			}
			
		}else{
			$xdata = array('code'=>1,'msg'=>JZLANG('注册失败，请重试~'));
			if($this->frparam('ajax')){
				JsonReturn($xdata);
			}
			Error(JZLANG('注册失败，请重试~'));
		}
		  
	  }
	  $invite = $this->frparam('invite',0,0);
	  if(!$invite){
		  if($this->webconf['onlyinvite']==1){
			  Error(JZLANG('必须通过邀请链接进行注册！'));
		  }
		  
	  }else{
		  //检查邀请链接的合法性
		  if(!M('member')->find(['id'=>$invite,'isshow'=>1])){
			  if($this->webconf['onlyinvite']==1){
				  Error(JZLANG('您的邀请链接不合法！'));
			  }
			  $invite = 0;
		  }
	  }
	  $_SESSION['invite'] = $invite;
	  $this->invite = $invite;
	  $this->display($this->template.'/user/register');
  }
  
  function forget(){
	  if($_POST && !isset($_POST['reset'])){
		
		  $email = $this->frparam('email',1);
		  $vercode = strtolower($this->frparam('vercode',1));
		  if(!$email){
			  Error(JZLANG('请输入账号和邮箱！'));
		  }
		  if($_SESSION['forget_code']!=md5(md5($vercode))){
			  Error(JZLANG('图形验证码错误！'));
		  }
		  $user = M('member')->find(['email'=>$email]);
		  if($user){
			  //生成随机秘钥
			  $w['logintime'] = time();
			  $w['token'] = getRandChar(32);
			  M('member')->update(['id'=>$user['id']],$w);
			  //发送邮件
			  if($this->webconf['email_server'] && $this->webconf['email_port'] &&  $this->webconf['send_email'] &&  $this->webconf['send_pass']){
				$title = JZLANG('找回密码').'-'.$this->webconf['web_name'];
				$url = U('login/forget').'?token='.$w['token'].'&email='.$email;
				$body = JZLANG('您的账号正在进行找回密码操作，如果确定是本人操作，请在10分钟内点击').'<a href="'.$url.'" target="_blank">['.JZLANG('立即找回密码').']</a>，'.JZLANG('过期失效！');
				
				send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$user['email'],$title,$body);
				if(!isset($_SESSION['forget_time'])){
					$_SESSION['forget_time'] = time();
					$_SESSION['forget_num'] = 0;
				}
				
				if(($_SESSION['forget_time']+10*60)<time()){
					$_SESSION['forget_num'] = 0;
					$_SESSION['forget_time'] = time();
				}
				$_SESSION['forget_num']++;
				if($_SESSION['forget_num']>5 && ($_SESSION['forget_time']+10*60)>time()){
					//$this->error('您操作过于频繁，请10分钟后再尝试！');
					if($this->frparam('ajax')){
						JsonReturn(['code'=>0,'msg'=>JZLANG('您操作过于频繁，请10分钟后再尝试！')]);
					}
					Error(JZLANG('您操作过于频繁，请10分钟后再尝试！'));
				}

				Success(JZLANG('找回密码邮件已发送，请到您的邮箱查看！'),get_domain());
				 
				
			 }else{
				 Error(JZLANG('邮箱服务器未配置，无法发送邮件，请联系管理员找回密码！'));
			 }
			  
		  }else{
			   Error(JZLANG('输入的信息有误！'));
		  }
	  }
	  if(!isset($_POST['reset']) && $this->frparam('token',1) && $this->frparam('email',1)){
		  //检查token是否正确
		  if($this->frparam('token',1)!='' && $this->frparam('email',1)!=''){
			  $user = M('member')->find(['token'=>$this->frparam('token',1),'email'=>$this->frparam('email',1)]);
			  if($user){
				  //检查是否已过期
				  $t = (time()-$user['logintime'])/60;
				  if($t>10){
					  Error(JZLANG('token已失效！'),U('login/forget'));
				  }
				  $this->user = $user;
				  $this->display($this->template.'/user/reset_password');
				  exit;
			  }
		  }
		  
	  }
	  
	  if($_POST && isset($_POST['reset'])){
		  $w['token'] = $this->frparam('reset',1);
		  $w['username'] = $this->frparam('username',1);
		  $pass = $this->frparam('password',1);
		  if($w['token']!='' && $w['username']!='' && $pass!=''){
			 $user = M('member')->find($w);
			 if(!$user){
				 Error(JZLANG('参数错误！'),U('login/forget'));
			 }
			 $pass = md5(md5($pass).md5($pass));
			 if(M('member')->update(['id'=>$user['id']],['pass'=>$pass])){
				 Success(JZLANG('密码重置成功,请重新登录！'),get_domain());
				 
			 }else{
				 Error(JZLANG('新密码不能与旧密码相同！'));
			 }
			  
		  }
		  
 	  }
	  
	  $this->display($this->template.'/user/forget');
  }
  
  
  function nologin(){
  		if($this->islogin){
  			Redirect(U('user/index'));
  		}
  		$this->display($this->template.'/user/nologin');
  }
  
  function loginout(){
  	  $_SESSION['member'] = null;
	  $_SESSION['return_url'] = null;
      Error(JZLANG('安全退出~'),get_domain());
  }
  
}




