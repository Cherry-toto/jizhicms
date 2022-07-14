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

class MessageController extends CommonController
{

	function index(){
		
		if($_POST){
			
			$w = $this->frparam();
			$w = get_fields_data($w,'message',0);
			
			$w['body'] = $this->frparam('body',1,'','POST');
			$w['user'] = $this->frparam('user',1,'','POST');
			$w['tel'] = $this->frparam('tel',1,'','POST');
			$w['aid'] = $this->frparam('aid',0,0,'POST');
			$w['tid'] = $this->frparam('tid',0,0,'POST');
			$w['email'] = $this->frparam('email',1,'','POST');
			$w['orders'] = 0;
			$w['istop'] = 0;
			$w['hits'] = 0;
			
			if($this->webconf['autocheckmessage']==1){
				$w['isshow'] = 1;
			}else{
				$w['isshow'] = 0;
			}
			
			if(!isset($this->webconf['messageyzm']) || $this->webconf['messageyzm']){
				$vercode = strtolower($this->frparam('vercode',1));
				if(!$vercode || md5(md5($vercode))!=$_SESSION['message_vercode']){
					$xdata = array('code'=>1,'msg'=>JZLANG('验证码错误！'));
					if($this->frparam('ajax')){
						JsonReturn($xdata);
					}
					Error(JZLANG('验证码错误！'));
				}
				
			}
			
			
			$w['ip'] = GetIP();
			$w['addtime'] = time();
			if(isset($_SESSION['member'])){
				$w['userid'] = $_SESSION['member']['id'];
			}else{
				$w['userid'] = 0;
			}
			
			if($this->frparam('title',1,'','POST')==''){
				//$this->error('标题不能为空！');
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>JZLANG('标题不能为空！')]);
				}
				Error(JZLANG('标题不能为空！'));
			}
			if($w['user']==''){
				//$this->error('姓名不能为空！');
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'称呼不能为空！']);
				}
				Error(JZLANG('称呼不能为空！'));
			}
			
			
			$w['title'] = $this->frparam('title',1);
			//仅在存在手机号的情况进行检测手机号是否有效-可自由设置
			if($w['tel']){
				if(!preg_match("/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[1-9])\\d{8}$/",$w['tel'])){
					//$this->error('您的手机号格式不正确！');
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>JZLANG('您的手机号格式不正确！')]);
					}
					Error(JZLANG('您的手机号格式不正确！'));
				}
				
			}
			// 不为空检测
			$sql = " molds='message' and isshow=1 ";
			$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
			if($fields_list){
				foreach($fields_list as $v){
					if($v['ismust']==1){
						if($w[$v['field']]==''){
							if(in_array($v['fieldtype'],array(6,10))){
								if($w[$v['field'].'_urls']==''){
									
									if($this->frparam('ajax')){
										JsonReturn(['code'=>1,'msg'=>$v['fieldname'].JZLANG('不能为空！')]);
									}else{
										Error($v['fieldname'].JZLANG('不能为空！'));
									}
								}
							}else{
								if($this->frparam('ajax')){
									JsonReturn(['code'=>1,'msg'=>$v['fieldname'].JZLANG('不能为空！')]);
								}else{
									Error($v['fieldname'].JZLANG('不能为空！'));
								}
							}
							
						}
					}
				}
			}
			
			
			
			
			if(!isset($_SESSION['message_time'])){
				$_SESSION['message_time'] = time();
				$_SESSION['message_num'] = 0;
			}
			
			if(($_SESSION['message_time']+10*60)<time()){
				$_SESSION['message_num'] = 0;
				$_SESSION['message_time'] = time();
			}
			$_SESSION['message_num']++;
			if($_SESSION['message_num']>5 && ($_SESSION['message_time']+10*60)>=time()){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>JZLANG('您操作过于频繁，请10分钟后再尝试！')]);
				}
				Error(JZLANG('您操作过于频繁，请10分钟后再尝试！'));
			}
			
			
			$res = M('message')->add($w);
			if($res){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>JZLANG('提交成功！我们会尽快回复您！'),'url'=>get_domain()]);
				}
				Success(JZLANG('提交成功！我们会尽快回复您！'),get_domain());
			}else{
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>JZLANG('提交失败，请重试！')]);
				}
				//$this->error('提交失败，请重试！');
				Error(JZLANG('提交失败，请重试！'));
			}
			
			
			
		}
		

		
	}
	
	function details(){
		$id = $this->frparam('id');
		if(!$id){
			$error = JZLANG('链接错误');
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>$error]);
			}
			Error($error);
		}
		if($this->webconf['autocheckmessage']==1){
			$msg = M('message')->find(['id'=>$id]);
		}else{
			$msg = M('message')->find(['id'=>$id,'isshow'=>1]);
		}
		if(!$msg){
			$error = JZLANG('留言未找到或者未审核');
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>$error]);
			}
			Error($error);
		}
		$this->data = $msg;
		
		if($this->classtypedata[$msg['tid']]){
			$this->type = $this->classtypedata[$msg['tid']];
			$details_html = $this->type['details_html'];
		}else{
			$details_html =  M('molds')->getField(['biaoshi'=>'message'],'details_html');
		}
		$this->display($this->template.'/message/'.$details_html);
		
		
	}
	
	
	
	
}