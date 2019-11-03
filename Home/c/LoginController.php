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


namespace Home\c;

use FrPHP\lib\Controller;

class LoginController extends Controller
{
	function _init(){

		$webconf = webConf();
		$template = get_template();
		$this->webconf = $webconf;
		$this->template = $template;
		if(isset($_SESSION['terminal'])){
			$classtypedata = $_SESSION['terminal']=='mobile' ? classTypeDataMobile() : classTypeData();
		}else{
			$classtypedata = (isMobile() && $webconf['iswap']==1)?classTypeDataMobile():classTypeData();
		}
		
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
	
	public function index(){
		//检测是否已经设置过return_url,防止多次登录覆盖
		if(!isset($_SESSION['return_url'])){
			$referer = (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') ? U('user/index') : $_SERVER['HTTP_REFERER'];
			$_SESSION['return_url'] = $referer;
		
		}
		
		if($_POST){
			$data['username'] = str_replace("'",'',$this->frparam('tel',1));//进行二次过滤校验
			$data['password'] = str_replace("'",'',$this->frparam('password',1));
			
			if($data['username']=='' || $data['password']==''){
				$xdata = array('code'=>1,'msg'=>'账户密码不能为空！');
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error('账户密码不能为空！');
			}
			
			
			$where['pass'] = md5(md5($data['password']).md5($data['password']));
			$where['tel'] = $data['username'];
			$res = M('member')->find($where);
			//unset($where['tel']);
			//$where['username'] = $data['username'];
			unset($where['pass']);
			$where['token'] = $data['password'];//token登录
			$res1 =  M('member')->find($where);
			
			
			if($res || $res1){
				$res = $res?$res:$res1;
				unset($res['password']);
				//检测权限
				if($res['isshow']!=1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'您的账户已被冻结！','url'=>$_SESSION['return_url']]);
					}
					Error('您的账户已被冻结！');
				}

				$group = M('member_group')->find(array('id'=>$res['gid']));
				if(!$group){
					JsonReturn(['code'=>1,'msg'=>'未找到您所在分组，请联系管理员处理！','url'=>$_SESSION['return_url']]);
				}
				unset($group['id']);
				//检测分组权限
				if($group['isagree']!=1){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'您所在的分组被限制登录！','url'=>$_SESSION['return_url']]);
					}
					Error('您所在的分组被限制登录！');
				}
				
				$_SESSION['member'] = array_merge($res,$group);
				//$_SESSION['member'] = $res;
				$update['logintime'] = time();
                //是否记住密码登录,更新token
				if($this->frparam('isremember')){
					$update['token'] = $_SESSION['token'];
				}
                M('member')->update(array('id'=>$res['id']),$update);
				//兼容ajax登录
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>'登录成功！','url'=>$_SESSION['return_url']]);
				}
				Redirect(U('Home/index'));
			}else{
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'账户密码错误！','url'=>$_SESSION['return_url']]);
				}
				Error('账户密码错误！');
			}
			
		}
		
		$token = getRandChar(32);//系统内置32位随机数,混淆前台规则猜测(MD5)
		$_SESSION['token'] = $token;
		$this->token = $token;
     
      
		$this->display($this->template.'/login');
	}

  function register(){
	  
	  if($_POST){
		  
		  $w['email'] = $this->frparam('email',1,'');
		  $w['password'] = $this->frparam('password',1);
		  $w['repassword'] = $this->frparam('repassword',1);
		  $w['tel'] = $this->frparam('tel',1);
		  if($w['password']=='' || $w['tel']==''){
				$xdata = array('code'=>1,'msg'=>'账户密码不能为空！');
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error('账户密码不能为空！');
		  }
		  if($w['password']!=$w['repassword']){
				$xdata = array('code'=>1,'msg'=>'两次密码不同！');
				if($this->frparam('ajax')){
					JsonReturn($xdata);
				}
				Error('两次密码不同！');
		  }
		if(preg_match("/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\\d{8}$/", $w['tel'])){  
			
		}else{  
			$xdata = array('code'=>1,'msg'=>'手机号格式不正确！');
			if($this->frparam('ajax')){
				JsonReturn($xdata);
			}
			Error('手机号格式不正确！');
		}  
		$w['regtime'] = time();
		//检查是否已被注册
		if(M('member')->find(['tel'=>$w['tel']])){
			$xdata = array('code'=>1,'msg'=>'您的手机号码已注册！');
			if($this->frparam('ajax')){
				JsonReturn($xdata);
			}
			Error('您的手机号码已注册！');
		}
		$w['username'] = $w['tel'];
		$w['pass'] =  md5(md5($w['password']).md5($w['password']));
		$r = M('member')->add($w);
		if($r){
			$xdata = array('code'=>0,'msg'=>'注册成功！','url'=>U('Login/index'));
			if($this->frparam('ajax')){
				JsonReturn($xdata);
			}
			Success('注册成功！',U('Login/index'));
		}else{
			$xdata = array('code'=>1,'msg'=>'注册失败，请重试~');
			if($this->frparam('ajax')){
				JsonReturn($xdata);
			}
			Error('注册失败，请重试~');
		}
		  
	  }
	  
	  $this->display($this->template.'/register');
  }
  
  function forget(){
	  if($_POST && !isset($_POST['reset'])){
		  $tel = $this->frparam('tel',1);
		  $vercode = $this->frparam('vercode',1);
		  if(!$tel){
			  Error('请输入账号！');
		  }
		  if($_SESSION['forget_code']!=md5(md5($vercode))){
			  Error('图形验证码错误！');
		  }
		  $user = M('member')->find(['tel'=>$tel]);
		  if($user){
			  if($user['email']==''){
				   Error('该账号未填写邮箱，无法发送邮件找回，请联系管理员找回密码！');
			  }else{
				  //生成随机秘钥
				  $w['logintime'] = time();
				  $w['token'] = getRandChar(32);
				  M('member')->update(['id'=>$user['id']],$w);
				  //发送邮件
				  if($this->webconf['email_server'] && $this->webconf['email_port'] &&  $this->webconf['send_email'] &&  $this->webconf['send_pass']){
					$title = '找回密码-'.$this->webconf['web_name'];
					$body = '您的账号正在进行找回密码操作，如果确定是本人操作，请在10分钟内点击<a href="'.U('Login/forget',['token'=>$w['token'],'tel'=>$tel]).'" target="_blank">《立即找回密码》</a>，过期失效！';
					
					send_mail($this->webconf['send_email'],$this->webconf['send_pass'],$this->webconf['send_name'],$user['email'],$title,$body);
					Success('找回密码邮件已发送，请到您的邮箱查看！',U('index'));
					 
					
				 }else{
					 Error('邮箱服务器未配置，无法发送邮件，请联系管理员找回密码！');
				 }
			  }
			  
		  }else{
			   Error('账号未找到！');
		  }
	  }
	  if(!isset($_POST['reset'])&& isset($_GET['token']) && isset($_GET['tel'])){
		  //检查token是否正确
		  if($this->frparam('token',1)!='' && $this->frparam('tel',1)!=''){
			  $user = M('member')->find(['token'=>$this->frparam('token',1),'tel'=>$this->frparam('tel',1)]);
			  if($user){
				  //检查是否已过期
				  $t = (time()-$user['logintime'])/60;
				  if($t>10){
					  Error('token已失效！',U('forget'));
				  }
				  $this->user = $user;
				  $this->display($this->template.'/reset_password');
				  exit;
			  }
		  }
		  
	  }
	  
	  if($_POST && isset($_POST['reset'])){
		  $w['token'] = $this->frparam('reset',1);
		  $w['tel'] = $this->frparam('tel',1);
		  $pass = $this->frparam('password',1);
		  if($w['token']!='' && $w['tel']!='' && $pass!=''){
			 $user = M('member')->find($w);
			 if(!$user){
				 Error('参数错误！',U('forget'));
			 }
			 $pass = md5(md5($pass).md5($pass));
			 if(M('member')->update(['id'=>$user['id']],['pass'=>$pass])){
				 Success('密码重置成功,请重新登录！',U('index'));
				 
			 }else{
				 Error('新密码不能与旧密码相同！');
			 }
			  
		  }
		  
 	  }
	  
	  $this->display($this->template.'/forget');
  }
  
  
  function loginout(){
  	  $_SESSION['member'] = null;
      Error('安全退出~',U('index'));
  }
  
}




