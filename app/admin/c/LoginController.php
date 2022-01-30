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


namespace app\admin\c;


use frphp\lib\Controller;
class LoginController extends Controller
{
	public function _init(){
		  $webconf = webConf();
		  $template = TEMPLATE;
		  $this->webconf = $webconf;
		  $this->template = $template;
		  $this->tpl = Tpl_style.$template.'/';
		  $customconf = get_custom();
		  $this->customconf = $customconf;
		
	}
	public function index(){
		
		if($_POST){
			//$data = $this->frparam();//去除全局获取
			$data['username'] = str_replace("'",'',$this->frparam('username',1));//进行二次过滤校验
			$data['password'] = str_replace("'",'',$this->frparam('password',1));
			
			if($data['username']=='' || $data['password']==''){
				$xdata = array('code'=>1,'msg'=>JZLANG('账户密码不能为空！'));
				JsonReturn($xdata);
			}
			if(!isset($this->webconf['closeadminvercode']) || $this->webconf['closeadminvercode']!=1){
				if(md5(md5(strtolower($this->frparam('vercode',1))))!=$_SESSION['frcode']){
					$xdata = array('code'=>1,'msg'=>JZLANG('验证码错误！'));
					JsonReturn($xdata);
				}
			}
			$_SESSION['frcode'] = getRandChar(32);
			$where['pass'] = md5(md5($data['password']).'YF');
			$where['name'] = $data['username'];
			
			$res1 = M('level')->find($where);
			unset($where['name']);
			$where['tel'] = $data['username'];
			$res2 = M('level')->find($where);
			unset($where['tel']);
			$where['email'] = $data['username'];
			$res3 = M('level')->find($where);
		
			
			
			
			if($res1 || $res2 || $res3){
			
				$res = ($res1) ? $res1 :($res2 ? $res2 : $res3);
				unset($res['pass']);
				if($res['status']==0){
					$data = array('code'=>1,'msg'=>JZLANG('该账户已被封禁！'));
				}else{
					$group = M('level_group')->find(array('id'=>$res['gid']));
					if($group['isagree']==0){
						$data = array('code'=>1,'msg'=>JZLANG('该账户已被封禁！'));
					}else{
						unset($group['id']);
						$group['group_name'] = $group['name'];
						unset($group['name']);
						$_SESSION['admin'] = array_merge($res,$group);
						M('level')->update(array('id'=>$res['id']),array('logintime'=>time()));
						setCache(session_id(),GetIP());
						//写入日志
						if(!StopLog){
							$log['user'] = $_SESSION['admin']['name'];
							$log['userid'] = $_SESSION['admin']['id'];
							register_log($_SESSION['admin'],'login');
						}

						$data = array('code'=>0,'msg'=>JZLANG('登录成功！'));
					}
					
				}
               
			}else{
				$data = array('code'=>1,'msg'=>JZLANG('账户密码错误！'));
			}
			JsonReturn($data);
		}
		
     
      
		$this->display('login');
	}

  function vercode(){
		$w = $this->frparam('w',0,160);
		$h = $this->frparam('h',0,50);
		$n = $this->frparam('n',0,4);
		//frcode
		$name = $this->frparam('name',1,$this->frparam('code_name',1,'frcode'));
		
		$imagecode=new \Imagecode($w,$h,$n,$name,APP_PATH."frphp/extend/AdobeGothicStd-Bold.ttf");
		$imagecode->imageout();
  }
  
  
  function loginout(){
  	  $_SESSION['admin'] = null;
      Error(JZLANG('安全退出~'),U('index'));
  }
  
}




