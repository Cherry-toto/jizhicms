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

class CommentController extends CommonController
{

	function index(){
		
		//检查模块是否开启
		if(!M('molds')->find(['isopen'=>1,'biaoshi'=>'comment'])){
			if($this->frparam('ajax')){
				JsonReturn(array('code'=>1,'msg'=>'评论模块未开启！'));
			}
			Error('评论模块未开启！');
		}
		
		if($this->frparam('go',0,false,"POST")){
			if($this->islogin){
				
				
				if(!isset($_SESSION['message_time'])){
					$_SESSION['message_time'] = time();
					$_SESSION['message_num'] = 0;
				}
				
				if(($_SESSION['message_time']+10*60)<time()){
					$_SESSION['message_num'] = 0;
				}
				$_SESSION['message_num']++;
				if($_SESSION['message_num']>5 && ($_SESSION['message_time']+10*60)<time()){
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>1,'msg'=>'您的操作过于频繁，请十分钟后再试~'));
					}
					
					Error('您的操作过于频繁，请十分钟后再试~');
				}
			
				$w = $this->frparam();
				$w = get_fields_data($w,'comment',0);
			
				$w['tid'] = $this->frparam('tid');
				$w['aid'] = $this->frparam('aid');
				$w['zid'] = $this->frparam('zid');
				$w['pid'] = $this->frparam('pid');
				$w['body'] = $this->frparam('body',1);
				if($w['body']==''){
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>1,'msg'=>'评论内容不能为空！'));
					}
					Error('评论内容不能为空！');
				}
				if(strpos($w['body'],'[@')!==false){
					$pars = '/\[@([^]]+)]/';
					$res = preg_match($pars,$w['body'],$match);
					if($res){
						$w['body'] = str_replace($match[0],'',$w['body']);
					}
					//var_dump($match);
					
				}
				
				
				$w['userid'] = $_SESSION['member']['id'];
				$w['likes'] = $this->frparam('star',1,0);
				$w['addtime'] = time();
				$n = M('comment')->add($w);
				if($n){
					$type = M('classtype')->find(['id'=>$this->frparam('tid')]);
					//更改评分-将以前的评分清空
					if($w['likes']!=0){
					    M('comment')->update('id!='.$n.' and userid='.$w['userid'],['likes'=>0]);
					}
					
					M($type['molds'])->goInc(['id'=>$this->frparam('aid')],'comment_num',1);
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>0,'msg'=>'评价成功！','url'=>get_domain().'/'.$type['htmlurl'].'/'.$this->frparam('aid').'.html'));
					}
					Success('评价成功！',get_domain().'/'.$type['htmlurl'].'/'.$this->frparam('aid').'.html');
				}
				
			}else{
				$referer = (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') ? U('user/index') : $_SERVER['HTTP_REFERER'];
				$_SESSION['return_url'] = $referer;
				if($this->frparam('ajax')){
					JsonReturn(array('code'=>1,'msg'=>'您未登录，请重新登录~','url'=>U('login/index')));
				}
				Redirect(U('Login/index'));
			}
		}
		

		
	}
}