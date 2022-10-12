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

class CommentController extends CommonController
{

	function index(){
		
		//检查模块是否开启
		if(!M('molds')->find(['isopen'=>1,'biaoshi'=>'comment'])){
			if($this->frparam('ajax')){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('评论模块未开启！')));
			}
			Error(JZLANG('评论模块未开启！'));
		}
		
		if($this->frparam('go',0,false,"POST")){
			if($this->islogin){
				
				
				if(!isset($_SESSION['message_time'])){
					$_SESSION['message_time'] = time();
					$_SESSION['message_num'] = 0;
				}
				
				if(($_SESSION['message_time']+10*60)<time()){
					$_SESSION['message_num'] = 0;
					$_SESSION['message_time'] = time();
				}
				$_SESSION['message_num']++;
				if($_SESSION['message_num']>10 && ($_SESSION['message_time']+10*60)>time()){
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('您的操作过于频繁，请十分钟后再试~')));
					}
					
					Error(JZLANG('您的操作过于频繁，请十分钟后再试~'));
				}
			
				//$w = $this->frparam();
				//$w = get_fields_data($w,'comment',0);
				$w['tid'] = $this->frparam('tid',0,0);
				$w['aid'] = $this->frparam('aid',0,0);
				$w['zid'] = $this->frparam('zid',0,0);
				$w['pid'] = $this->frparam('pid',0,0);
				$w['body'] = $this->frparam('body',1,null);
				$w['reply'] = null;
				if(!$w['body']){
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('评论内容不能为空！')));
					}
					Error(JZLANG('评论内容不能为空！'));
				}
				if(!$w['tid']){
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('请提交栏目ID')));
					}
					Error(JZLANG('栏目ID不能为空！'));
				}
				//是否主帖子
				if($w['zid']){
					$z_userid = M('comment')->getField(['id'=>$w['zid']],'userid');
				}else{
					$z_userid = 0;
				}
				//是否贴中贴
				if($w['pid']){
					$p_userid = M('comment')->getField(['id'=>$w['pid']],'userid');
				}else{
					$p_userid = 0;
				}
				$about = [];
				if(strpos($w['body'],'[@')!==false){
					$pars = '/\[@([^]]+)]/';
					$res = preg_match_all($pars,$w['body'],$match);
					if($res){
						foreach($match[0] as $k=>$v){
							$w['body'] = str_replace($v,' @'.$match[1][$k].' ',$w['body']);
						}

					}
					foreach($match[1] as $v){
					    $v = format_param($v,1);
						$r = M('member')->getField(['username'=>$v],'id');
						if($r && $r!=$z_userid && $r!=$p_userid){
							$about[] = $r;
						}
					}
					
				}
				if($this->webconf['autocheckcomment']==1){
					$w['isshow'] = 1;
				}else{
					$w['isshow'] = 0;
				}
				
				$w['userid'] = $_SESSION['member']['id'];
				$w['likes'] = $this->frparam('star',1,0);
				$w['isread'] = 0;
				$w['zan'] = 0;
				$w['addtime'] = time();
				$n = M('comment')->add($w);
				if($n){
					//内容URL
					$molds = $this->classtypedata[$w['tid']]['molds'];
					if(!$w['aid']){
						//栏目评论
						$url = $this->classtypedata[$w['tid']]['url'];
					}else{
						//非栏目评论
						$htmlurl=M($molds)->getField(['id'=>$w['aid']],'htmlurl');
						$url = get_domain().'/'.$htmlurl.'/'.$w['aid'];
					}
					//检查是否用户发布的内容，提示被评论
					if($w['aid']){
						$member_id=M($molds)->getField(['id'=>$w['aid']],'member_id');
						if($member_id){
							$task['aid'] = $n;
							$task['tid'] = $w['tid'];
							$task['userid'] = $member_id;
							$task['puserid'] = $this->member['id'];
							$task['molds'] = $molds;
							$task['type'] = 'comment';
							$task['addtime'] = time();
							$task['body'] = $w['body'];
							$task['url'] = $url;
							M('task')->add($task);
						}
					}
					//检查是否回复帖子
					if($w['zid']){
						if($z_userid){
							$task['aid'] = $n;
							$task['tid'] = $w['tid'];
							$task['userid'] = $z_userid;
							$task['puserid'] = $this->member['id'];
							$task['molds'] = $molds;
							$task['type'] = 'comment';
							$task['addtime'] = time();
							$task['body'] = $w['body'];
							$task['url'] = $url;
							M('task')->add($task);
						}
					}
					//检查是否回复帖子
					if($w['pid']){
						if($p_userid && $z_userid!=$p_userid){
							$task['aid'] = $n;
							$task['tid'] = $w['tid'];
							$task['userid'] = $p_userid;
							$task['puserid'] = $this->member['id'];
							$task['molds'] = $molds;
							$task['type'] = 'reply';
							$task['addtime'] = time();
							$task['body'] = $w['body'];
							$task['url'] = $url;
							M('task')->add($task);

							
						}
					}
					//检查是否@用户
					if(count($about)>0){

						$task = [];
						$task['aid'] = $n;
						$task['tid'] = $w['tid'];
						$task['molds'] = $molds;
						$task['type'] = 'at';
						$task['addtime'] = time();
						$task['body'] = $w['body'];
						$task['url'] = $url;
						$task['puserid'] = $this->member['id'];
						foreach ($about as $value) {
							$task['userid'] = $value;
							M('task')->add($task);
						}
					}

					//更改评分-将以前的评分清空
					if($w['likes']!=0){
					    M('comment')->update('id!='.$n.' and userid='.$w['userid'],['likes'=>0]);
					}
					//评论奖励
					if($this->webconf['comment_award_open']==1 && $w['tid']!=0 && $w['aid']!=0){
						$award = round($this->webconf['comment_award'],2);
						$id = $w['aid'];
						$max_award = round($this->webconf['comment_max_award'],2);
						$member_id = M($molds)->getField(['id'=>$id],'member_id');
						$molds = $this->classtypedata[$w['tid']]['molds'];
						//去除自己写的评论
						if($member_id!=0 && $award>0 && $member_id!=$this->member['id']){
							$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>$molds,'aid'=>$id,'msg'=>JZLANG('评论奖励')]);
							if(!$rr){
								$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
								$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

								$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='".JZLANG("评论奖励")."' ";
								$all = M('buylog')->findAll($sql,null,'amount');
								$all_jifen = 0;
								if($all){
									foreach($all as $v){
										$all_jifen+=$v['amount'];
									}
								}
								
								if($max_award==0 || ($all_jifen<$max_award && $max_award!=0)){
									$w['userid'] = $member_id;
				        			$w['buytype'] = 'jifen';
						   	  		$w['type'] = 3;
						   	  		$w['molds'] = $molds;
						   	  		$w['aid'] = $id;
						   	  		$w['msg'] = JZLANG('评论奖励');
						   	  		$w['addtime'] = time();
						   	  		$w['orderno'] = 'No'.date('YmdHis');
						   	  		$w['amount'] = $award;
						   	  		$w['money'] = $w['amount']/($this->webconf['money_exchange']);
						   	  		$r = M('buylog')->add($w);
						   	  		M('member')->goInc(['id'=>$member_id],'jifen',$award);
								}
							}
							
						}
					}

					
					M($molds)->goInc(['id'=>$this->frparam('aid')],'comment_num',1);
					if($this->frparam('ajax')){
						JsonReturn(array('code'=>0,'msg'=>JZLANG('评价成功！'),'url'=>$url));
					}
					Success(JZLANG('评价成功！'),$url);
				}
				
			}else{
				$referer = (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') ? U('user/index') : $_SERVER['HTTP_REFERER'];
				$_SESSION['return_url'] = $referer;
				if($this->frparam('ajax')){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('您未登录，请重新登录~'),'url'=>U('login/index')));
				}
				Redirect(U('login/index'));
			}
		}
		

		
	}

	//获取评论列表
	function getlist(){
		//检查模块是否开启
		if(!M('molds')->find(['isopen'=>1,'biaoshi'=>'comment'])){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('评论模块未开启！')));
		}
		$aid = $this->frparam('aid',0,0);
		$tid = $this->frparam('tid',0,0);
		$limit = $this->frparam('limit',0,10);
		$page = $this->frparam('page',0,1);
		$comment = new Page('Comment');
		$sql = "isshow=1 and pid=0 and aid=".$aid." and tid=".$tid;
		$data = $comment->where($sql)->orderby('likes desc,id desc')->limit($limit)->page($page)->go();
		foreach($data as $k=>$v){
			$data[$k]['classname'] = $v['tid'] ? $v['tid'] : $this->classtypedata[$v['tid']];
			$data[$k]['article'] = !$v['aid'] ? [] : M($this->classtypedata[$v['tid']]['molds'])->find(['id'=>$v['aid'],'isshow'=>1]);
			$data[$k]['user'] = !$v['userid'] ? [] : M('member')->find(['id'=>$v['userid']],null,'id,username,litpic');
			$data[$k]['addtime'] = formatTime($v['addtime']);
			$children = M('comment')->findAll(['zid'=>$v['id'],'isshow'=>1]);
			if($children){
				foreach($children as $kk=>$vv){
					$children[$kk]['classname'] = $vv['tid'] ? $vv['tid'] : $this->classtypedata[$vv['tid']];
					$children[$kk]['article'] = !$vv['aid'] ? [] : M($this->classtypedata[$vv['tid']]['molds'])->find(['id'=>$vv['aid'],'isshow'=>1]);
					$children[$kk]['user'] = !$vv['userid'] ? [] : M('member')->find(['id'=>$vv['userid']],null,'id,username,litpic');
					$children[$kk]['addtime'] = formatTime($vv['addtime']);
				}
			}
			
			$data[$k]['children'] = $children;
		}
		$count = M('comment')->getCount(['isshow'=>1,'aid'=>$aid,'tid'=>$tid]);
		JsonReturn(['code'=>0,'data'=>[
			'list'=>$data,
			'count'=>$count,
			'allpage'=>$comment->allpage,
		],'msg'=>'success']);
		
	}

}