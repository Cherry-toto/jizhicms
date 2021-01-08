<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/06/20
// +----------------------------------------------------------------------


namespace Home\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class UserController extends CommonController
{
	function _init(){
		
		if(!M('molds')->find(['biaoshi'=>'member','isopen'=>1])){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'会员中心已关闭！']);
			}
			Error('会员中心已关闭！');
			exit;
		}
		parent::_init();
		
	}

	function checklogin(){
		if(!$this->islogin){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'您还未登录，请重新登录！']);
			}
			Redirect(U('login/index'));
		}
		
	}
	
    function index(){
    	$this->checklogin();
		//统计用户订单
		$this->order_num = M('orders')->getCount(['userid'=>$this->member['id'],'isshow'=>1]);
		//统计评论数
		$this->comment_num = M('comment')->getCount(['userid'=>$this->member['id'],'isshow'=>1]);
		//统计点赞数
		if($this->member['likes']!=''){
			//,1,2,3,4,
			$this->likes_num = substr_count($this->member['likes'],'||')-1;
		}else{
			$this->likes_num = 0;
		}
		//统计收藏
		if($this->member['collection']!=''){
			//,1,2,3,4,
		$this->collect_num = substr_count($this->member['collection'],'||')-1;
		}else{
			$this->collect_num = 0;
		}
		//发布文章统计
		$this->article_num = M('article')->getCount(['member_id'=>$this->member['id']]);
		$this->product_num = M('product')->getCount(['member_id'=>$this->member['id']]);
		
		$this->display($this->template.'/user/index');
       
    }
	function userinfo(){
		$this->checklogin();
		if($_POST){
			$w = $this->frparam();
			$w = get_fields_data($w,'member',0);
			unset($w['jifen']);
			unset($w['money']);
			unset($w['openid']);
			unset($w['gid']);
			unset($w['likes']);
			unset($w['collection']);
			unset($w['regtime']);
			unset($w['logintime']);
			unset($w['isshow']);
			unset($w['fans']);
			unset($w['follow']);
			unset($w['ismsg']);
			unset($w['iscomment']);
			unset($w['iscollect']);
			unset($w['islikes']);
			unset($w['isat']);
			unset($w['isrechange']);
			unset($w['pid']);
			foreach($w as $k=>$v){
				$w[$k] = format_param($v,1);
			}
			$w['tel'] = $this->frparam('tel',1);
			$w['pass'] = $this->frparam('password',1);
			$w['sex'] = $this->frparam('sex',0,0);
			$w['repass'] = $this->frparam('repassword',1);
			$w['username'] = $this->frparam('username',1);
			$w['email'] = $this->frparam('email',1);
			$w['litpic'] = $this->frparam('litpic',1);
			$w['signature'] = $this->frparam('signature',1);
			
			if($w['tel']!=''){
				if(preg_match("/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\\d{8}$/",$w['tel'])){  
				
				}else{  
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'手机号码格式错误！']);
					}
					Error('手机号码格式错误！');
					
				}
				//檢查是否已經註冊
				$r = M('member')->find(['tel'=>$w['tel']]);
				if($r){
					if($r['id']!=$this->member['id']){
						
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'手机号已被注册！']);
						}
						Error('手机号已被注册！');
					}
				}
			}
			if($w['username']==''){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'账户不能为空！']);
				}
				Error('账户不能为空！');
			}
			if($w['pass']!=$w['repass'] && $w['pass']!=''){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'两次密码不同！']);
				}
				Error('两次密码不同！');
			}
			if($w['email']){
				$r = M('member')->find(['email'=>$w['email']]);
				if($r){
					if($r['id']!=$this->member['id']){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'邮箱已被使用！']);
						}
						Error('邮箱已被使用！');
					}
				}
			}
			
			$r = M('member')->find(['username'=>$w['username']]);
			if($r){
				if($r['id']!=$this->member['id']){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'昵称已被使用！']);
					}
					Error('昵称已被使用！');
				}
			}
			if($w['pass']!=''){
				$w['pass'] = md5(md5($w['pass']).md5($w['pass']));
			}else{
				unset($w['pass']);
				unset($w['repass']);
			}
			$re = M('member')->update(['id'=>$this->member['id']],$w);
			$member = M('member')->find(['id'=>$this->member['id']]);
			unset($member['pass']);
			$_SESSION['member'] = array_merge($_SESSION['member'],$member);
			if($this->frparam('ajax')){
				JsonReturn(['code'=>0,'msg'=>'修改成功！']);
			}
			Error('修改成功！');
			
		}
		
		$this->display($this->template.'/user/userinfo');
       
    }
	function orders(){
		$this->checklogin();
		$page = new Page('Orders');
		$this->type = $this->frparam('type',0,0);
		if($this->type){
			//1未支付,2已支付,3超时,4待发货,5已发货,6已废弃失效,0删除订单
			switch ($this->type) {
				case 1:
					$sql = 'userid='.$this->member['id'].' and isshow=1 ';
					break;
				case 2:
					$sql = 'userid='.$this->member['id'].' and isshow=2 ';
					break;
				case 3:
					$sql = 'userid='.$this->member['id'].' and isshow=3 ';
					break;
				case 4:
					$sql = 'userid='.$this->member['id'].' and isshow=4 ';
					break;
				case 5:
					$sql = 'userid='.$this->member['id'].' and isshow=5 ';
					break;
				case 6:
					$sql = 'userid='.$this->member['id'].' and isshow=6 ';
					break;
				
				default:
					$sql = 'userid='.$this->member['id'].' and isshow!=0 ';
					break;
			}
		}else{
			$sql = 'userid='.$this->member['id'].' and isshow!=0 ';
		}
		$sql.=" and ptype=1 ";
		$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
		$page->file_ext = '';
		$pages = $page->pageList(5,'?page=');
		$this->pages = $pages;
		foreach($data as $k=>$v){
			$data[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
			$data[$k]['details'] =  U('user/orderdetails',['orderno'=>$v['orderno']]);
			$data[$k]['del'] =  U('user/orderdel',['orderno'=>$v['orderno']]);
		}
		if($this->frparam('ajax')){
			
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		
		
		
		$this->display($this->template.'/user/order');
       
    }
    //订单详情
    function orderdetails(){
		$this->checklogin();
    	$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno,'userid'=>$this->member['id']]);
		if($orderno && $order){
			/*
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
			*/
			$carts = explode('||',$order['body']);
			$new = [];
			foreach($carts as $k=>$v){
				$d = explode('-',$v);
				if($d[0]!=''){
					//兼容多模块化
					if(isset($this->classtypedata[$d[0]])){
						$type = $this->classtypedata[$d[0]];
						$res = M($type['molds'])->find(['id'=>$d[1]]);
						$new[] = ['info'=>$res,'num'=>$d[2],'tid'=>$d[0],'id'=>$d[1],'price'=>$d[3]];
					}else{
						$new[] = ['info'=>false,'num'=>$d[2],'tid'=>$d[0],'id'=>$d[1],'price'=>$d[3]];
					}					
				}
				
			}
			$this->carts = $new;
			$this->order = $order;
			$this->display($this->template.'/user/orderdetails');
		}
    	
    }
	//支付页面
	function payment(){
		$this->checklogin();
		$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno,'userid'=>$this->member['id']]);
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
			$qianbao = 0;
			$jifen = 0;
			foreach($carts as $k=>$v){
				$d = explode('-',$v);
				if($d[0]!=''){
					//兼容多模块化
					if(isset($this->classtypedata[$d[0]])){
						$type = $this->classtypedata[$d[0]];
						$res = M($type['molds'])->find(['id'=>$d[1]]);
						$new[] = ['info'=>$res,'num'=>$d[2],'tid'=>$d[0],'id'=>$d[1],'price'=>$d[3]];
						if(isset($res['jifen']) && $res['jifen']!=0){
							$jifen+=$d[2]*$res['jifen'];
						}else{
							$jifen+=$d[2]*$d[3]*($this->webconf['jifen_exchange']);
						}
					}else{
						$new[] = ['info'=>false,'num'=>$d[2],'tid'=>$d[0],'id'=>$d[1],'price'=>$d[3]];
						$jifen+=$d[2]*$d[3]*($this->webconf['jifen_exchange']);
					}	
					$qianbao+=$d[2]*$d[3]*($this->webconf['money_exchange']);				
				}
				
			}
			
			$this->qianbao = $qianbao+$order['discount']*($this->webconf['money_exchange'])-$order['yunfei']*($this->webconf['money_exchange']);
			$this->jifen = $jifen+$order['discount']*($this->webconf['jifen_exchange'])-$order['yunfei']*($this->webconf['jifen_exchange']);
			if($this->webconf['isopenjifen']==1){
				M('orders')->update(['id'=>$order['id']],['jifen'=>$this->jifen]);
			}
			if($this->webconf['isopenqianbao']==1){
				M('orders')->update(['id'=>$order['id']],['qianbao'=>$this->qianbao]);
			}
			$this->carts = $new;
			$this->order = $order;
			$this->display($this->template.'/user/payment');
		}
		
	}

	//删除订单
	function orderdel(){
		$this->checklogin();
		$orderno = $this->frparam('orderno',1);
		if(!$orderno){ Error('缺少订单号！');}
		$order = M('orders')->find(" orderno='".$orderno."' and userid=".$this->member['id']." and isshow!=0 ");
		if(!$order){ Error('订单号错误！');}
		$r = M('orders')->update(['orderno'=>$orderno,'userid'=>$this->member['id']],['isshow'=>0]);
		if($r){
			Success('删除成功！',U('user/orders'));
		}else{
			Error('删除失败！');
		}
		
	}
	function changeimg(){
		$this->checklogin();
		$url = $this->frparam('url',1);
		if($url!=''){
			$r = M('member')->update(['id'=>$this->member['id']],['litpic'=>$url]);
			if($r){
				$_SESSION['member']['litpic'] = $url;
				JsonReturn(['code'=>0,'msg'=>'success']);
			}else{
				JsonReturn(['code'=>1,'msg'=>'网络错误，请刷新后重试！']);
			}
		}else{
			JsonReturn(['code'=>1,'msg'=>'上传错误！']);
		}
		
	}
	function comment(){
		$this->checklogin();
		$page = new Page('Comment');
		$sql = 'userid='.$this->member['id'].' and isshow!=2 ';
		$data = $page->where($sql)->orderby('addtime desc')->limit(5)->page($this->frparam('page',0,1))->go();
		$page->file_ext = '';
		$pages = $page->pageList(5,'?page=');
		$pages = $page->pageList();
		$this->pages = $pages;
		
		$this->sum = $page->sum;
		foreach($data as $k=>$v){
			if(isset($this->classtypedata[$v['tid']])){
				$xmolds = M($this->classtypedata[$v['tid']]['molds'])->find(['id'=>$v['aid']]);
				$data[$k]['date'] = date('Y/m/d H:i:s',$v['addtime']);
				$data[$k]['body'] = newstr($v['body'],60);
				$data[$k]['comment_num'] =  get_comment_num($v['tid'],$v['aid']);
				$data[$k]['del'] =  U('user/commentdel',['id'=>$v['id']]);
				if($xmolds){
					$data[$k]['title'] = $xmolds['title'];
					$data[$k]['url'] =  gourl($xmolds['id'],$xmolds['htmlurl']);
				}
			}
			
			
		}
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/comment');
       
    }
	function commentdel(){
		$this->checklogin();
		$id = $this->frparam('id');
		if(!$id){ Error('缺少ID！');}
		$comment = M('comment')->find(['id'=>$id,'isshow'=>1,'userid'=>$this->member['id']]);
		if(!$comment){ Error('未找到评论！');}
		$r = M('comment')->update(['id'=>$id,'userid'=>$this->member['id']],['isshow'=>2]);
		if($r){
			Success('删除成功！',U('user/comment'));
		}else{
			Error('删除失败！');
		}
		
	}
	function likesAction(){
		
		if(!isset($_SESSION['return_url'])){
			$referer = ($_SERVER['HTTP_REFERER']=='') ? U('user/likes') : $_SERVER['HTTP_REFERER'];
			$_SESSION['return_url'] = $referer;
		
		}
		$tid = $this->frparam('tid');
		$id = $this->frparam('id');
		if(!$tid || !$id){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>0,'msg'=>'参数错误！','url'=>$_SESSION['return_url']]);
			}
			Error('参数错误！');
		}
		if(!$this->islogin){
			if(isset($_SESSION['likes'])){
				$likes = $_SESSION['likes'];
			}else{
				$likes = [];
			}
			$lk = $tid.'-'.$id;
			$u = M('member')->find(['username'=>'jzcustomer']);
			if(!$u){
				$w = [];
				$w['username'] = 'jzcustomer';
				$r = M('member')->add($w);
				$u['id'] = $r;
				$u['likes'] = '';
			}
			if(in_array($lk,$likes)){
				$newlikes = [];
				foreach($likes as $v){
					if($v!=$lk){
						$newlikes[]=$v;
					}
				}
				$msg = '已取消点赞！';
				$likes = $newlikes;
				$ulikes = explode('||',$u['likes']);
				$isdo = 0;
				$ulk = [];
				foreach($ulikes as $k=>$v){
					if($v){
						if($v==$lk && !$isdo){
							$isdo = 1;
							continue;
						}
						$ulk[]=$v;
						
						
					}
					
				}
				if(count($ulk)){
					$u['likes'] = '||'.implode('||',$ulk).'||';
				}else{
					$u['likes'] = '';
				}
				
				
			}else{
				$msg = '点赞成功！';
				$likes[]=$lk;
				if($u['likes']){
					$u['likes'] .= $lk.'||';
				}else{
					$u['likes'] = '||'.$lk.'||';
				}
				
			}
			$_SESSION['likes'] = $likes;
			M('member')->update(['id'=>$u['id']],['likes'=>$u['likes']]);
			if($this->frparam('ajax')){
				JsonReturn(['code'=>0,'msg'=>$msg,'url'=>$_SESSION['return_url']]);
			}
			Success($msg,$_SESSION['return_url']);
		}
		
		$likes = explode('||',$this->member['likes']);
		$new = [];
		$add = $tid.'-'.$id;
		$isadd = true;
		if($likes && $this->member['likes']!=''){
			foreach($likes as $k=>$v){
				if($v!=''){
					
					if($v==$add){
						$isadd = false;
					}else{
						$new[]=$v;
					}
					
				}
				
			}
			if($isadd){
				$new[]=$add;
				$msg = '点赞成功！';
			}else{
				$msg = '已取消点赞！';
			}
			$xnew = '||'.implode('||',$new).'||';
		}else{
			$xnew = '||'.$add.'||';
			$msg = '点赞成功！';
		}
		M('member')->update(['id'=>$this->member['id']],['likes'=>$xnew]);
		//检查是否收藏的站内用户发布的信息
		$molds = $this->classtypedata[$tid]['molds'];
		$data = M($molds)->find(['id'=>$id]);
		if(isset($data['member_id']) && $data['member_id']!=0 && $data['member_id']!=$this->member['id']){
			if($isadd){
				$task['aid'] = $id;
				$task['tid'] = $tid;
				$task['userid'] = $data['member_id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = $molds;
				$task['type'] = 'likes';
				$task['addtime'] = time();
				$task['body'] = $data['title'];
				$task['url'] = gourl($data['id'],$data['htmlurl']);
				M('task')->add($task);
			}else{
				$task['aid'] = $id;
				$task['tid'] = $tid;
				$task['userid'] = $data['member_id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = $molds;
				$task['type'] = 'likes';
				M('task')->delete($task);
			}
			
		}
		if($this->webconf['likes_award_open']==1 && $tid!=0){
			
			$award = round($this->webconf['likes_award'],2);
			$max_award = round($this->webconf['likes_max_award'],2);
			$molds = $this->classtypedata[$tid]['molds'];
			$member_id = M($molds)->getField(['id'=>$id],'member_id');
			if($member_id!=0 && $award>0){
				$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>$molds,'aid'=>$id,'msg'=>'点赞奖励']);
				if(!$rr){
					$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

					$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='点赞奖励' ";
					$all = M('buylog')->findAll($sql,null,'amount');
					$all_jifen = 0;
					if($all){
						foreach($all as $v){
							$all_jifen+=$v['amount'];
						}
					}
					if($isadd){
						//奖励
						if($max_award==0 || ($all_jifen<$max_award && $max_award!=0)){
							$w['userid'] = $member_id;
							$w['buytype'] = 'jifen';
							$w['type'] = 3;
							$w['molds'] = $molds;
							$w['aid'] = $id;
							$w['msg'] = '点赞奖励';
							$w['addtime'] = time();
							$w['orderno'] = 'No'.date('YmdHis');
							$w['amount'] = $award;
							$w['money'] = $w['amount']/($this->webconf['money_exchange']);
							$r = M('buylog')->add($w);
							M('member')->goInc(['id'=>$member_id],'jifen',$award);	
						}
						
					}else{
						//扣除
						$w['userid'] = $member_id;
						$w['buytype'] = 'jifen';
						$w['type'] = 3;
						$w['molds'] = $molds;
						$w['aid'] = $id;
						$w['msg'] = '取消点赞';
						$w['addtime'] = time();
						$w['orderno'] = 'No'.date('YmdHis');
						$w['amount'] = -$award;
						$w['money'] = $w['amount']/($this->webconf['money_exchange']);
						$r = M('buylog')->add($w);
						M('member')->goDec(['id'=>$member_id],'jifen',$award);
						
					}
					
				}
				
			}
			
			
		}

		$_SESSION['member']['likes'] = $xnew;
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'msg'=>$msg,'url'=>$_SESSION['return_url']]);
		}
		Success($msg,$_SESSION['return_url']);
		
	}
	
	function likes(){
		$this->checklogin();
		$lists = [];
		if($this->member['likes']!=''){
	
			$likes = explode('||',$this->member['likes']);

			foreach($likes as $v){
				if($v!=''){
					$d = explode('-',$v);
					//tid-id
					if(is_array($d) && isset($this->classtypedata[$d[0]])){
						$xdata=M($this->classtypedata[$d[0]]['molds'])->find(['id'=>$d[1]]);
						if($xdata){
							$lists[]=$xdata;
						}
					}
				}
			}
		}
		
		$arraypage = new \ArrayPage($lists);
		$data = $arraypage->query(['page'=>$this->frparam('page',0,1)])->setPage(['limit'=>$this->frparam('limit',0,10)])->go();
		foreach($data as $k=>$v){
			$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
			if(isset($this->classtypedata[$v['tid']])){
				$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
			}else{
				$data[$k]['classname'] = '[ 已被删除 ]';
			}
			
			$data[$k]['del'] = U('user/likesdel',['id'=>$v['id'],'tid'=>$v['tid']]);
		}
		$this->lists = $data;
		$this->pages = $arraypage->pageList();
		$this->listpage = $arraypage->listpage;//分页数组-自定义分页可用
		$this->prevpage = $arraypage->prevpage;//上一页
		$this->nextpage = $arraypage->nextpage;//下一页
		$this->allpage = $arraypage->allpage;//总页数
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/likes');
       
    }
	function likesdel(){
		$this->checklogin();
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		if($id && $tid){
			$ids = str_replace('||'.$tid.'-'.$id.'||','',$this->member['likes']);
			M('member')->update(['id'=>$this->member['id']],['likes'=>$ids]);
			$_SESSION['member']['likes'] = $ids;
			
			Success('删除成功！',U('user/likes'));
		}else{
			Error('参数错误！');
		}
	}
	
	function collectAction(){
		$this->checklogin();
		if(!isset($_SESSION['return_url'])){
			$referer = (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') ? U('user/collect') : $_SERVER['HTTP_REFERER'];
			$_SESSION['return_url'] = $referer;
		
		}
		$tid = $this->frparam('tid');
		$id = $this->frparam('id');
		if(!$tid || !$id){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>0,'msg'=>'参数错误！','url'=>$_SESSION['return_url']]);
			}
			Error('参数错误！');
		}
		$collection = explode('||',$this->member['collection']);
		$new = [];
		$add = $tid.'-'.$id;
		$isadd = true;
		if($collection && $this->member['collection']!=''){
			foreach($collection as $k=>$v){
				if($v!=''){
					
					if($v==$add){
						$isadd = false;
					}else{
						$new[]=$v;
					}
					
				}
				
			}
			if($isadd){
				$new[]=$add;
				$msg = '收藏成功！';
			}else{
				$msg = '已移出我的收藏！';
			}
			$xnew = '||'.implode('||',$new).'||';
		}else{
			$xnew = '||'.$add.'||';
			$msg = '收藏成功！';
		}
		M('member')->update(['id'=>$this->member['id']],['collection'=>$xnew]);
		//检查是否收藏的站内用户发布的信息
		$molds = $this->classtypedata[$tid]['molds'];
		$data = M($molds)->find(['id'=>$id]);
		if(isset($data['member_id']) && $data['member_id']!=0 && $data['member_id']!=$this->member['id']){
			if($isadd){
				$task['aid'] = $id;
				$task['tid'] = $tid;
				$task['userid'] = $data['member_id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = $molds;
				$task['type'] = 'collect';
				$task['addtime'] = time();
				$task['body'] = $data['title'];
				$task['url'] = gourl($data['id'],$data['htmlurl']);
				M('task')->add($task);
			}else{
				$task['aid'] = $id;
				$task['tid'] = $tid;
				$task['userid'] = $data['member_id'];
				$task['puserid'] = $this->member['id'];
				$task['molds'] = $molds;
				$task['type'] = 'collect';
				M('task')->delete($task);
			}
			
		}
		if($this->webconf['collect_award_open']==1 && $tid!=0){
			$award = round($this->webconf['collect_award'],2);
			$max_award = round($this->webconf['collect_max_award'],2);
			$molds = $this->classtypedata[$tid]['molds'];
			$member_id = M($molds)->getField(['id'=>$id],'member_id');
			
			if($member_id!=0 && $award>0){
				$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>$molds,'aid'=>$id,'msg'=>'收藏奖励']);
				if(!$rr){
					$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

					$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='收藏奖励' ";
					$all = M('buylog')->findAll($sql,null,'amount');
					$all_jifen = 0;
					if($all){
						foreach($all as $v){
							$all_jifen+=$v['amount'];
						}
					}
					if($isadd){
						if($max_award==0 || ($all_jifen<$max_award && $max_award!=0)){
							$w['userid'] = $member_id;
							$w['buytype'] = 'jifen';
							$w['type'] = 3;
							$w['molds'] = $molds;
							$w['aid'] = $id;
							$w['msg'] = '收藏奖励';
							$w['addtime'] = time();
							$w['orderno'] = 'No'.date('YmdHis');
							$w['amount'] = $award;
							$w['money'] = $w['amount']/($this->webconf['money_exchange']);
							$r = M('buylog')->add($w);
							M('member')->goInc(['id'=>$member_id],'jifen',$award);
						}
					}else{
						$w['userid'] = $member_id;
						$w['buytype'] = 'jifen';
						$w['type'] = 3;
						$w['molds'] = $molds;
						$w['aid'] = $id;
						$w['msg'] = '取消收藏';
						$w['addtime'] = time();
						$w['orderno'] = 'No'.date('YmdHis');
						$w['amount'] = -$award;
						$w['money'] = $w['amount']/($this->webconf['money_exchange']);
						$r = M('buylog')->add($w);
						M('member')->goDec(['id'=>$member_id],'jifen',$award);
					}
					
				}
				
			}
		}

		$_SESSION['member']['collection'] = $xnew;
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'msg'=>$msg,'url'=>$_SESSION['return_url']]);
		}
		Success($msg,$_SESSION['return_url']);
		
	}
	
	function collect(){
		$this->checklogin();
		$lists = [];
		if($this->member['collection']!=''){
			$collection = explode('||',$this->member['collection']);

			foreach($collection as $v){
				if($v!=''){
					$d = explode('-',$v);
					//tid-id
					if(is_array($d) && isset($this->classtypedata[$d[0]])){
						$xdata=M($this->classtypedata[$d[0]]['molds'])->find(['id'=>$d[1]]);
						if($xdata){
							$lists[] = $xdata;
						}
					}
				}
			}
		}
		
		$arraypage = new \ArrayPage($lists);
		$data = $arraypage->query(['page'=>$this->frparam('page',0,1)])->setPage(['limit'=>$this->frparam('limit',0,10)])->go();
		foreach($data as $k=>$v){
			$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
			if(isset($this->classtypedata[$v['tid']])){
				$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
			}else{
				$data[$k]['classname'] = '[ 已被删除 ]';
			}
			
			$data[$k]['del'] = U('user/collectdel',['id'=>$v['id'],'tid'=>$v['tid']]);
		}
		$this->lists = $data;
		$this->pages = $arraypage->pageList();
		$this->listpage = $arraypage->listpage;//分页数组-自定义分页可用
		$this->prevpage = $arraypage->prevpage;//上一页
		$this->nextpage = $arraypage->nextpage;//下一页
		$this->allpage = $arraypage->allpage;//总页数
		if($this->frparam('ajax')){
			
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/collect');
       
    }
	function collectdel(){
		$this->checklogin();
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		if($id && $tid){
			$ids = str_replace('||'.$tid.'-'.$id.'||','',$this->member['collection']);
			M('member')->update(['id'=>$this->member['id']],['collection'=>$ids]);
			$_SESSION['member']['collection'] = $ids;
			
			Success('删除成功！',U('user/collect'));
		}else{
			Error('参数错误！');
		}
	}
	
	//购物车
	function cart(){
		$this->checklogin();
		if(!isset($_SESSION['cart'])){
			$_SESSION['cart'] = '';
		}
		$this->member_group = M('member_group')->find(['id'=>$this->member['gid']]);
		//tid-id-num
		$cart = explode('||',$_SESSION['cart']);
		$carts = [];
		if($cart){
			foreach($cart as $k=>$v){
				$d = explode('-',$v);
				if($d[0]!=''){
					//兼容多模块化
					if(isset($this->classtypedata[$d[0]])){
						$type = $this->classtypedata[$d[0]];
						$res = M($type['molds'])->find(['id'=>$d[1]]);
						$carts[] = ['info'=>$res,'num'=>$d[2],'tid'=>$d[0],'id'=>$d[1],'price'=>$d[3]];
					}else{
						$carts[] = ['info'=>false,'num'=>$d[2],'tid'=>$d[0],'id'=>$d[1],'price'=>$d[3]];
					}					
				}
				
			}
		}
		$this->carts = $carts;
		$this->display($this->template.'/user/cart');
	}
	
	//购物车 tid-id-num
	function addcart(){
		$this->checklogin();
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		$num = $this->frparam('num');
		if(!$id || !$tid || !$num){
			JsonReturn(['code'=>1,'msg'=>'参数错误！']);
		}
		//检查库存
		$product = M($this->classtypedata[$tid]['molds'])->find(['id'=>$id]);
		if($product['stock_num']<$num){
			JsonReturn(['code'=>1,'msg'=>'库存不足！']);
		}
		
		//session存储
		if(!isset($_SESSION['cart'])){
			//id-tid-num
			$cart = $tid.'-'.$id.'-'.$num.'-'.$product['price'];
		}else{
			$cart = $_SESSION['cart'];
			$carts = explode('||',$cart);
			$new = [];
			$isnew = true;
			foreach($carts as $v){
				$d = explode('-',$v);
				if($d[0]!=''){
					if($d[0]==$tid && $d[1]==$id){
					   $d[2] = $num;
					   $isnew = false;
					}
					$new[]=$d[0].'-'.$d[1].'-'.$d[2].'-'.$d[3];
				}
				
			}
			if($isnew){
				$new[]=$tid.'-'.$id.'-'.$num.'-'.$product['price'];
			}
			
			$cart = implode('||',$new);
		}
		$_SESSION['cart'] = $cart;
		JsonReturn(['code'=>0,'msg'=>'success','url'=>U('user/cart')]);
		
		
	}
	//删除购物车商品
	function delcart(){
		$this->checklogin();
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		if(!$id || !$tid){
			JsonReturn(['code'=>1,'msg'=>'参数错误！']);
		}
		$cart = $_SESSION['cart'];
		$carts = explode('||',$cart);
		$new = [];
		
		foreach($carts as $v){
			$d = explode('-',$v);
			if(($d[0]!=$tid || $d[1]!=$id) && $d[0]!=''){
			   $new[]=$d[0].'-'.$d[1].'-'.$d[2];
			}
			
		}
		
		
		$cart = implode('||',$new);
		$_SESSION['cart'] = $cart;
		JsonReturn(['code'=>0,'msg'=>'success','url'=>$cart]);
	}

	//文章列表
	function posts(){
		$this->checklogin();
		$molds = $this->frparam('molds',1,'article');
		$this->molds = $molds;
		$this->moldsname = M('molds')->getField(['biaoshi'=>$molds],'name');
		$page = new Page($molds);
		$this->type = $this->frparam('type');
		$sql = 'member_id='.$this->member['id'].'  ';
		switch ($this->type) {
			case 1:
				$sql.=" and isshow=2 ";
				break;
			case 2:
				$sql.=" and isshow=0 ";
				break;
			case 3:
				$sql.=" and isshow=1 ";
				break;
			
			default:
				# code...
				break;
		}
		
		$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
		$page->file_ext = '';
		$pages = $page->pageList(5,'?page=');
		
		foreach($data as $k=>$v){
			if(isset($this->classtypedata[$v['tid']])){
				$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
			}else{
				$data[$k]['classname'] = '[ 未分类 ]';
			}
			
			$data[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
			$data[$k]['edit'] =  U('user/release',['id'=>$v['id'],'molds'=>$molds]);
			$data[$k]['del'] =  U('user/del',['id'=>$v['id'],'molds'=>$molds]);
			$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
			
		}
		$this->pages = $pages;
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		if($this->frparam('ajax')){

			JsonReturn(['code'=>0,'data'=>$data]);
		}
		$this->display($this->template.'/user/article');
       
    }
    //文章发布和修改
    function release(){
    	$this->checklogin();
    	
    	if($_POST){
    		$data = $this->frparam();
			$w = [];
			$w['molds'] = $this->frparam('molds',1);
			$release_table = explode('|',$this->webconf['release_table']);
			if(!in_array($w['molds'],$release_table)){
				JsonReturn(array('code'=>1,'msg'=>'该模块不允许发布！'));
			}
			
			$w = get_fields_data($data,$w['molds']);
			unset($w['userid']);
			unset($w['ishot']);
			unset($w['istuijian']);
			unset($w['istop']);
			unset($w['hits']);
			unset($w['htmlurl']);
			unset($w['orders']);
			unset($w['comment_num']);
			unset($w['tags']);
			unset($w['target']);
			unset($w['ownurl']);
			foreach($w as $k=>$v){
				$w[$k] = format_param($v,4);
			}
			$w['molds'] = $this->frparam('molds',1);
			//违禁词检测
			if(isset($this->webconf['mingan']) && $this->webconf['mingan']!=''){
				$mingan = explode(',',$this->webconf['mingan']);
				foreach($data as $v){
					if($v){
						if(is_array($v)){
							foreach($v as $vv){
								if($vv){
									
									foreach($mingan as $s){
										if(strpos($s,'{xxx}')!==false){
											$pattern = '/'.str_replace('{xxx}','(.*)',$s).'/';
											if(preg_match($pattern, $vv)){
												JsonReturn(array('code'=>1,'msg'=>'添加失败，存在敏感词 [ '.$s.' ]'));
											}
											
										}else{
											if(strpos($vv,$s)!==false){
												JsonReturn(array('code'=>1,'msg'=>'添加失败，存在敏感词 [ '.$s.' ]'));
											}
											
										}
									}
								}
							}
							
						}else{
							foreach($mingan as $s){
								if(strpos($s,'{xxx}')!==false){
									$pattern = '/'.str_replace('{xxx}','(.*)',$s).'/';
									if(preg_match($pattern, $v)){
										JsonReturn(array('code'=>1,'msg'=>'添加失败，存在敏感词 [ '.$s.' ]'));
									}
									
								}else{
									if(strpos($v,$s)!==false){
										JsonReturn(array('code'=>1,'msg'=>'添加失败，存在敏感词 [ '.$s.' ]'));
									}
									
								}
							}
						}
						
					}
				}
				
			}
			
			
			$w['tid'] = $this->frparam('tid');
			if(!$w['tid']){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'请选择分类！']);
				}else{
					Error('请选择分类！');
				}
				
			}
			if(!isset($this->classtypedata[$w['tid']])){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'分类错误！']);
				}else{
					Error('分类错误！');
				}
				
			}
			if($this->classtypedata[$w['tid']]['ishome']!=1){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'msg'=>'该分类不允许发布！']);
				}else{
					Error('该分类不允许发布！');
				}
			}
			//检查权限
			if($this->classtypedata[$w['tid']]['gid']!=0){
				if($this->classtypedata[$w['tid']]['gid']>$this->member['gid']){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'您没有权限在该分类发布内容！']);
					}else{
						Error('您没有权限在该分类发布内容！');
					}
				}
				
			}
			
			
			$w['htmlurl'] = $this->classtypedata[$w['tid']]['htmlurl'];
			$sql = array();
			if($w['tid']!=0){
				$sql[] = " tids like '%,".$w['tid'].",%' "; 
			}
			$sql[] = " molds = '".$w['molds']."' and isshow=1 ";
			$sql = implode(' and ',$sql);
			$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
			if($fields_list){
				foreach($fields_list as $v){
					if($v['ismust']==1){
						if($data[$v['field']]==''){
							if(in_array($v['fieldtype'],array(6,10))){
								if($data[$v['field'].'_urls']==''){
									
									if($this->frparam('ajax')){
										JsonReturn(['code'=>1,'msg'=>$v['fieldname'].'不能为空！']);
									}else{
										Error($v['fieldname'].'不能为空！');
									}
								}
							}else{
								if($this->frparam('ajax')){
									JsonReturn(['code'=>1,'msg'=>$v['fieldname'].'不能为空！']);
								}else{
									Error($v['fieldname'].'不能为空！');
								}
							}
							
						}
					}
				}
			}
			

			switch($w['molds']){
				case 'article':
					if(!$data['body']){
						
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'内容不能为空！']);
						}else{
							Error('内容不能为空！');
						}
					}
					if(!$data['title']){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'标题不能为空！']);
						}else{
							Error('标题不能为空！');
						}
					}
					$data['body'] = $this->frparam('body',4);
					$w['title'] = $this->frparam('title',1);
					$w['seo_title'] = $w['title'];
					$w['keywords'] = $this->frparam('keywords',1);
					$w['litpic'] = $this->frparam('litpic',1);
					$w['body'] = $data['body'];
					$w['description'] = newstr(strip_tags($data['body']),200);
					

				break;
				case 'product':
					if(!$data['body']){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'内容不能为空！']);
						}else{
							Error('内容不能为空！');
						}
					}
					if(!$data['title']){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'标题不能为空！']);
						}else{
							Error('标题不能为空！');
						}
					}
					
					if(!$data['stock_num']){
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'库存不能为0！']);
						}else{
							Error('库存不能为0！');
						}
					}
					$w['title'] = $this->frparam('title',1);
					$w['stock_num'] = $this->frparam('stock_num',0,0);
					$w['price'] = $this->frparam('price',3,0);
					$w['seo_title'] = $w['title'];
					$w['litpic'] = $this->frparam('litpic',1);
					$w['keywords'] = $this->frparam('keywords',1);
					$w['pictures'] = $this->frparam('pictures',1);
					if($this->frparam('pictures_urls',2)){
						if($this->webconf['ispicsdes']==1){
							 $pic_des = $this->frparam('pictures_des',2);
							 $pics = $this->frparam('pictures_urls',2);
							 foreach($pics as $k=>$v){
								 if($pic_des[$k]){
									 $pics[$k] = $v.'|'.$pic_des[$k];
								 }
							 }
							 $w['pictures'] = implode('||',$pics);
						}else{
							$w['pictures'] = implode('||',$this->frparam('pictures_urls',2));
						}
						
					}
					$data['body'] = $this->frparam('body',4);
					$w['body'] = $data['body'];
					if($this->frparam('description',1)){
						$w['description'] = $this->frparam('description',1);
					}else{
						$w['description'] = newstr(strip_tags($data['body']),200);
					}
					
				break;
				default:

				break;
			}
			$w['isshow'] = 0;//修改后的文章一律为未审核
			$w['member_id'] = $this->member['id'];
			
			$w['addtime'] = time();
			
			if($this->frparam('id')){
				$w['id'] = $this->frparam('id',1);
				$a = M($w['molds'])->update(['id'=>$this->frparam('id')],$w);
				if(!$a){ 
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'未修改内容，不能提交！']);
					}else{
						Error('未修改内容，不能提交！');
					}
				}
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>'修改成功！','url'=>U('user/posts',['molds'=>$w['molds']])]);
				}else{
					Success('修改成功！',U('user/posts',['molds'=>$w['molds']]));
				}
				
			}else{
				$a = M($w['molds'])->add($w);
				if(!$a){
					if($this->frparam('ajax')){
						JsonReturn(['code'=>1,'msg'=>'发布失败，请重试！']);
					}else{
						Error('发布失败，请重试！');
					}
				}
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'msg'=>'发布成功！','url'=>U('user/posts',['molds'=>$w['molds']])]);
				}else{
					Success('发布成功！',U('user/posts',['molds'=>$w['molds']]));
				}
				
			}

    	}
    	$molds = $this->frparam('molds',1,'article');
    	$tid = $this->frparam('tid',0,0);
    	if($this->frparam('id')){
    		$this->data = M($molds)->find(['id'=>$this->frparam('id'),'member_id'=>$this->member['id']]);
    		$molds = $this->data['molds'];
			$this->moldsdata = M('molds')->find(['biaoshi'=>$molds]);
    		$tid = $this->data['tid'];
    	}else{
    		$this->data = false;
    	}
    	$this->molds = $molds;
    	$this->tid = $tid;
    	$this->classtypetree =  get_classtype_tree();
    	$this->display($this->template.'/user/article-add');

    }
	
	//删除文章
	function del(){
		$this->checklogin();
		$molds = $this->frparam('molds',1,'article');
		$id = $this->frparam('id');
		if(!$id){ Error('链接错误！');}
		$res = M($molds)->find(['id'=>$id,'member_id'=>$this->member['id']]);
		if(!$res){ Error('未找到您要的文章！');}
		$r = M($molds)->delete(['id'=>$id]);
		if($r){
			Success('删除成功！',U('user/posts',['molds'=>$molds]));
		}else{
			Error('删除失败！');
		}
		
	}

	function uploads(){
		$this->checklogin();
		$file = $this->frparam('filename',1);
		if ($_FILES[$file]["error"] > 0){
		  $data['error'] =  "Error: " . $_FILES[$file]["error"];
		  $data['code'] = 1000;
		}else{
		 
		  $pix = explode('.',$_FILES[$file]['name']);
		  $pix = end($pix);
		  //检测是否允许前台上传文件
		  if(!$this->webconf['isopenhomeupload']){
			  $data['error'] =  "Error: 已关闭前台上传文件功能";
			  $data['code'] = 1004;
			  JsonReturn($data);
		  }
		 
			$fileType = webConf('fileType');
			if(strpos($fileType,strtolower($pix))===false   || stripos($pix,'php')!==false){
				$data['error'] =  "Error: 文件类型不允许上传！";
				$data['code'] = 1002;
				JsonReturn($data);
			}
			$fileSize = (int)webConf('fileSize');
			if($fileSize!=0 && ($_FILES[$file]["size"]/1024)>$fileSize){
				$data['error'] =  "Error: 文件大小超过网站内部限制！";
				$data['code'] = 1003;
				JsonReturn($data);
			}
		  if(isset($this->webconf['home_save_path'])){
			  //替换日期事件
				$t = time();
				$d = explode('-', date("Y-y-m-d-H-i-s"));
				$format = $this->webconf['home_save_path'];
				$format = str_replace("{yyyy}", $d[0], $format);
				$format = str_replace("{yy}", $d[1], $format);
				$format = str_replace("{mm}", $d[2], $format);
				$format = str_replace("{dd}", $d[3], $format);
				$format = str_replace("{hh}", $d[4], $format);
				$format = str_replace("{ii}", $d[5], $format);
				$format = str_replace("{ss}", $d[6], $format);
				$format = str_replace("{time}", $t, $format);
				if($format!=''){
					//检查文件是否存在
					if(strpos($format,'/')!==false && !file_exists(APP_PATH.$format)){
						$path = explode('/',$format);
						$path1 = APP_PATH;
						foreach($path as $v){
							if($path1==APP_PATH){
								if(!file_exists($path1.$v)){
									mkdir($path1.$v,0777);
								}
								$path1.=$v;
							}else{
								if(!file_exists($path1.'/'.$v)){
									mkdir($path1.'/'.$v,0777);
								}
								$path1.='/'.$v;
							}
						}
					}else if(!file_exists(APP_PATH.$format)){
						mkdir(APP_PATH.$format,0777);
					}
					$home_save_path = $format;
					
				}else{
					$home_save_path = 'Public/Home';
				}
				
				
			  }else{
				 $home_save_path = 'Public/Home';
			  }
			 
		  
		    $filename =  $home_save_path.'/'.date('Ymd').rand(1000,9999).'.'.$pix;
		  
			if(move_uploaded_file($_FILES[$file]['tmp_name'],$filename)){
				$data['url'] = $filename;
				$data['code'] = 0;
				if(isset($_SESSION['member'])){
					$userid = $_SESSION['member']['id'];
				}else{
					$userid = 0;
				}
				$filesize = round(filesize(APP_PATH.$filename)/1024,2);
				M('pictures')->add(['litpic'=>'/'.$filename,'addtime'=>time(),'userid'=>$userid,'size'=>$filesize,'path'=>'Home','filetype'=>strtolower($pix),'molds'=>'member']);
				
			}else{
				$data['error'] =  "Error: 请检查目录[Public/Home]写入权限";
				$data['code'] = 1001;
				  
			} 

			  
		  
		  }
		  
		  
		  JsonReturn($data,true);
		  exit;
		  
		
		
	}


	function jizhi(){
		$this->display($this->template.'/404');
	}
	
	function follow(){
		$this->checklogin();
		$uid = $this->frparam('uid');
		if($uid){
			$member_id = $this->member['id'];
			$follow = M('member')->getField(['id'=>$member_id],'follow');
			if(strpos($follow,','.$uid.',')!==false){
				Error('您已经关注了该用户！');
			}
			if($uid==$member_id){
				Error('您不能关注自己！');
			}
			//拼接方式 [ , ]
			if($follow==''){
				$follow = ','.$uid.',';
			}else{
				$follow.=$uid.',';
			}
			//需要更新用户的关注数及关注人的关注列表
			M('member')->update(['id'=>$member_id],['follow'=>$follow]);
			$_SESSION['member']['follow'] = $follow;
			M('member')->goInc(['id'=>$uid],'fans',1);
			if($this->webconf['follow_award_open']==1){
				$award = round($this->webconf['follow_award'],2);
				$max_award = round($this->webconf['follow_max_award'],2);
				$molds = 'member';
				$member_id = $uid;
				$id = $uid;
				
				if($member_id!=0 && $award>0){
					$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>$molds,'aid'=>$id,'msg'=>'关注奖励']);
					if(!$rr){
						$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
						$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='关注奖励' ";
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
				   	  		$w['msg'] = '关注奖励';
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
			Success('关注成功！',U('user/follow'));

		}else{
			$this->frpage = $this->frparam('page',0,1);
			$page = new Page('member');
			$member_id = $this->member['id'];
			$follow = M('member')->getField(['id'=>$member_id],'follow');
			if($follow!=''){
				//,1,2,2,4,
				$ids = trim($follow,',');

			}else{
				$ids = 0;
			}
			$sql = " id in(".$ids.") " ;
			$data = $page->where($sql)->orderby('fans desc,regtime desc,id desc')->limit(12)->page($this->frpage)->go();
			$pages = $page->pageList(5,'?page=');
			$this->pages = $pages;//组合分页
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
			$this->display($this->template.'/user/follow');

		}
	}
	//取消关注
	function nofollow(){
		$this->checklogin();
		$uid = $this->frparam('uid');
		if($uid){
			$member_id = $this->member['id'];
			$follow = M('member')->getField(['id'=>$member_id],'follow');
			if(strpos($follow,','.$uid.',')===false){
				Error('您没有关注该用户，无法操作！'.$uid);
			}
			$follow = explode(',',$follow);
			$f = [];
			foreach ($follow as $key => $value) {
				if($value!='' && (int)$value!=$uid){
					$f[]=$value;
				}
			}
			if(!count($f)){
				$follow = '';
			}else{
				$follow = ','.implode(',',$f).',';
			}
			
			M('member')->update(['id'=>$member_id],['follow'=>$follow]);
			$_SESSION['member']['follow'] = $follow;
			M('member')->goDec(['id'=>$uid],'fans',1);
			Success('取关成功！',U('user/follow'));


		}else{
			Error('链接错误！');
		}
	}
	function fans(){
		$this->checklogin();
		$this->frpage = $this->frparam('page',0,1);
		$page = new Page('member');
		$member_id = $this->member['id'];
		$sql = " follow like '%,".$member_id.",%'" ;
		$data = $page->where($sql)->orderby('fans desc,regtime desc,id desc')->limit(15)->page($this->frpage)->go();
		$pages = $page->pageList(3,'?page=');
		$this->pages = $pages;//组合分页
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		$this->display($this->template.'/user/fans');
	}
	/*
	
	以下序号跟msgtype无关，仅表示可能的消息
	1、发布的文章/商品/其他被评论消息  article/product  comment
	2、发布的文章/商品/其他被点赞消息  article/product  likes
	3、发布的文章/商品/其他被收藏消息  article/product  collect
	4、被别人@消息 comment at
	5、留言被别人回复消息 comment reply
	6、交易消息  buylog rechange
	*7、官方消息  news channel
	*8、私信消息  member msg
	

	*/
	function notify(){
		$this->checklogin();
		$page = new Page('task');
		$msgtype = $this->frparam('msgtype');
		$this->msgtype = $msgtype;
		if($msgtype){
			switch ($msgtype) {
				case 1:
					$sql = 'userid='.$this->member['id']." and isshow=1 and (type = 'comment' or type = 'reply') ";
					break;
				case 2:
					$sql = 'userid='.$this->member['id']." and isshow=1 and type = 'collect'";
					break;
				case 3:
					$sql = 'userid='.$this->member['id']." and isshow=1 and type = 'likes'";
					break;
				case 4:
					$sql = 'userid='.$this->member['id']." and isshow=1 and type = 'at'";
					break;
				case 5:
					$sql = 'userid='.$this->member['id']." and isshow=1 and type = 'rechange'";
					break;
				default:
					$sql = 'userid='.$this->member['id'].' and isshow=1 ';
					break;
			}
			
		}else{
			$sql = 'userid='.$this->member['id'].' and isshow=1 ';
		}
		
		//$sql = 'userid='.$this->member['id'];
		$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
		$page->file_ext = '';
		$pages = $page->pageList(5,'?page=');
		foreach($data as $k=>$v){
			$data[$k] = $v;
			$data[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
			$data[$k]['turl'] =  U('user/notifyto',['id'=>$v['id']]);
			$data[$k]['del'] =  U('user/notifydel',['id'=>$v['id']]);
			
		}
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/notify');
       
    }

    function notifyto(){
    	$this->checklogin();
    	$id = $this->frparam('id');
    	if(!$id){
    		Error('链接错误！');
    	}
    	$notify = M('task')->find(['id'=>$id,'isshow'=>1,'userid'=>$this->member['id']]);
    	if(!$notify){
    		Error('消息已被删除！');
    	}
    	$task['readtime'] = time();
    	$task['isread'] = 1;
    	M('task')->update(['id'=>$id],$task);
    	Redirect($notify['url']);

    }

    function notifydel(){
    	$this->checklogin();
    	$id = $this->frparam('id');
    	if(!$id){
    		Error('链接错误！');
    	}
    	$notify = M('task')->find(['id'=>$id,'isshow'=>1,'userid'=>$this->member['id']]);
    	if(!$notify){
    		Error('消息已被删除！');
    	}
    	$r = M('task')->update(['id'=>$id],['isshow'=>0]);
    	if($r){
    		Success('删除成功！',U('user/notify'));
    	}else{
    		Error('删除失败！');
    	}
    }
    //个人中心公共页
    function active(){
    	$username = $this->frparam('uname',1);
    	$uid = $this->frparam('uid');
    	if($username || $uid){
			if($username){
				$this->user = M('member')->find(['username'=>$username]);
			}else{
				$this->user = M('member')->find(['id'=>$uid]);
			}
		}else{
    		if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'链接错误！','data'=>'']);
			}
			Error('链接错误！');
    	}

		if(!$this->user){
			Error('用户未找到！');
		}
		//统计评论数
		$this->comment_num = M('comment')->getCount(['userid'=>$this->user['id'],'isshow'=>1]);
		//统计点赞数
		if($this->user['likes']!=''){
			//,1,2,3,4,
			$this->likes_num = substr_count($this->user['likes'],'||')-1;
		}else{
			$this->likes_num = 0;
		}
		//统计收藏
		if($this->user['collection']!=''){
			//,1,2,3,4,
		$this->collect_num = substr_count($this->user['collection'],'||')-1;
		}else{
			$this->collect_num = 0;
		}
		//发布文章统计
		$this->article_num = M('article')->getCount(['member_id'=>$this->user['id']]);
		$this->product_num = M('product')->getCount(['member_id'=>$this->user['id']]);
		
		$this->type = $this->frparam('type',0,1);
		
		switch($this->type){
			
			case 1:
				$molds = $this->frparam('molds',1,'article');
				if($molds!='article'){
					Error('链接错误！');
				}
				$this->molds = $molds;
				$this->moldsname = M('molds')->getField(['biaoshi'=>$molds],'name');
				$page = new Page($molds);
				$this->type = $this->frparam('type');
				$sql = 'member_id='.$this->user['id'].' and isshow=1  ';
				
				
				$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
				$page->file_ext = '';
				$pages = $page->pageList(5,'?page=');
				
				foreach($data as $k=>$v){
					if(isset($this->classtypedata[$v['tid']])){
						$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
					}else{
						$data[$k]['classname'] = '[ 未分类 ]';
					}
					
					$data[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
					
					$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
					
				}
				$this->pages = $pages;
				$this->lists = $data;//列表数据
				$this->sum = $page->sum;//总数据
				$this->listpage = $page->listpage;//分页数组-自定义分页可用
				$this->prevpage = $page->prevpage;//上一页
				$this->nextpage = $page->nextpage;//下一页
				$this->allpage = $page->allpage;//总页数
				if($this->frparam('ajax')){

					JsonReturn(['code'=>0,'data'=>$data]);
				}
			break;
			case 2:
				$molds = $this->frparam('molds',1,'article');
				if($molds!='product'){
					Error('链接错误！');
				}
				$this->molds = $molds;
				$this->moldsname = M('molds')->getField(['biaoshi'=>$molds],'name');
				$page = new Page($molds);
				$this->type = $this->frparam('type');
				$sql = 'member_id='.$this->user['id'].' and isshow=1  ';
				
				
				$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
				$page->file_ext = '';
				$pages = $page->pageList(5,'?page=');
				
				foreach($data as $k=>$v){
					if(isset($this->classtypedata[$v['tid']])){
						$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
					}else{
						$data[$k]['classname'] = '[ 未分类 ]';
					}
					
					$data[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
					
					$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
					
				}
				$this->pages = $pages;
				$this->lists = $data;//列表数据
				$this->sum = $page->sum;//总数据
				$this->listpage = $page->listpage;//分页数组-自定义分页可用
				$this->prevpage = $page->prevpage;//上一页
				$this->nextpage = $page->nextpage;//下一页
				$this->allpage = $page->allpage;//总页数
				if($this->frparam('ajax')){

					JsonReturn(['code'=>0,'data'=>$data]);
				}
			break;
			case 3:
				$this->frpage = $this->frparam('page',0,1);
				$page = new Page('member');
				$member_id = $this->user['id'];
				$follow = M('member')->getField(['id'=>$member_id],'follow');
				if($follow!=''){
					//,1,2,2,4,
					$ids = trim($follow,',');

				}else{
					$ids = 0;
				}
				$sql = " id in(".$ids.") " ;
				$data = $page->where($sql)->orderby('fans desc,regtime desc,id desc')->limit(12)->page($this->frpage)->go();
				$pages = $page->pageList(5,'?page=');
				$this->pages = $pages;//组合分页
				$this->lists = $data;//列表数据
				$this->sum = $page->sum;//总数据
				$this->listpage = $page->listpage;//分页数组-自定义分页可用
				$this->prevpage = $page->prevpage;//上一页
				$this->nextpage = $page->nextpage;//下一页
				$this->allpage = $page->allpage;//总页数
			break;
			case 4:
				$this->frpage = $this->frparam('page',0,1);
				$page = new Page('member');
				$member_id = $this->user['id'];
				$sql = " follow like '%,".$member_id.",%'" ;
				$data = $page->where($sql)->orderby('fans desc,regtime desc,id desc')->limit(15)->page($this->frpage)->go();
				$pages = $page->pageList(3,'?page=');
				$this->pages = $pages;//组合分页
				$this->lists = $data;//列表数据
				$this->sum = $page->sum;//总数据
				$this->listpage = $page->listpage;//分页数组-自定义分页可用
				$this->prevpage = $page->prevpage;//上一页
				$this->nextpage = $page->nextpage;//下一页
				$this->allpage = $page->allpage;//总页数
			break;
			case 5:
				$lists = [];
				if($this->user['collection']!=''){
					
					$collection = explode('||',$this->user['collection']);

					foreach($collection as $v){
						if($v!=''){
							$d = explode('-',$v);
							//tid-id
							if($d!=''){
								$xdata=M($this->classtypedata[$d[0]]['molds'])->find(['id'=>$d[1]]);
								if($xdata){
									$lists[] = $xdata;
								}
							}
						}
					}
				}
				
				$arraypage = new \ArrayPage($lists);
				$data = $arraypage->query(['page'=>$this->frparam('page',0,1),'uname'=>$username,'type'=>$this->frparam('type',0,1)])->setPage(['limit'=>10])->go();
				foreach($data as $k=>$v){
					$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
					if(isset($this->classtypedata[$v['tid']])){
						$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
					}else{
						$data[$k]['classname'] = '[ 已被删除 ]';
					}
					
					
				}
				$this->lists = $data;
				$this->pages = $arraypage->pageList();
				$this->listpage = $arraypage->listpage;//分页数组-自定义分页可用
				$this->prevpage = $arraypage->prevpage;//上一页
				$this->nextpage = $arraypage->nextpage;//下一页
				$this->allpage = $arraypage->allpage;//总页数
				if($this->frparam('ajax')){
					
					JsonReturn(['code'=>0,'data'=>$data]);
				}
			break;
			case 6:
				$page = new Page('Comment');
				$sql = 'userid='.$this->user['id'].' and isshow=1 ';
				$data = $page->where($sql)->orderby('addtime desc')->limit(5)->page($this->frparam('page',0,1))->go();
				$page->file_ext = '';
				$pages = $page->pageList(5,'?page=');
				$pages = $page->pageList();
				$this->pages = $pages;
				
				$this->sum = $page->sum;
				foreach($data as $k=>$v){
					if(isset($this->classtypedata[$v['tid']])){
						$xmolds = M($this->classtypedata[$v['tid']]['molds'])->find(['id'=>$v['aid']]);
						if($xmolds){
							$data[$k]['title'] = $xmolds['title'];
							$data[$k]['date'] = date('Y/m/d H:i:s',$v['addtime']);
							$data[$k]['url'] =  gourl($xmolds['id'],$xmolds['htmlurl']);
							$data[$k]['body'] = newstr($v['body'],60);
							$data[$k]['comment_num'] =  get_comment_num($v['tid'],$v['aid']);
							
						}
					}
					
					
				}
				$this->lists = $data;//列表数据
				$this->sum = $page->sum;//总数据
				$this->listpage = $page->listpage;//分页数组-自定义分页可用
				$this->prevpage = $page->prevpage;//上一页
				$this->nextpage = $page->nextpage;//下一页
				$this->allpage = $page->allpage;//总页数
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'data'=>$data]);
				}
	
			break;
			case 7:
				$lists = [];
				if($this->user['likes']!=''){
			
					$likes = explode('||',$this->user['likes']);

					foreach($likes as $v){
						if($v!=''){
							$d = explode('-',$v);
							//tid-id
							if($d!=''){
								$xdata=M($this->classtypedata[$d[0]]['molds'])->find(['id'=>$d[1]]);
								if($xdata){
									$lists[]=$xdata;
								}
							}
						}
					}
				}
				
				$arraypage = new \ArrayPage($lists);
				$data = $arraypage->query(['page'=>$this->frparam('page',0,1),'uname'=>$username,'type'=>$this->frparam('type',0,1)])->setPage(['limit'=>10])->go();
				foreach($data as $k=>$v){
					$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
					if(isset($this->classtypedata[$v['tid']])){
						$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
					}else{
						$data[$k]['classname'] = '[ 已被删除 ]';
					}
					
					
				}
				$this->lists = $data;
				$this->pages = $arraypage->pageList();
				$this->listpage = $arraypage->listpage;//分页数组-自定义分页可用
				$this->prevpage = $arraypage->prevpage;//上一页
				$this->nextpage = $arraypage->nextpage;//下一页
				$this->allpage = $arraypage->allpage;//总页数
				if($this->frparam('ajax')){
					JsonReturn(['code'=>0,'data'=>$data]);
				}
			break;
		}
		
		
		$this->display($this->template.'/user/people');
    	
    }

    //消息提醒设置
    function setmsg(){
    	$this->checklogin();
    	if($_POST){
    		$data['ismsg'] = $this->frparam('ismsg',0,0);
    		$data['iscomment'] = $this->frparam('iscomment',0,0);
    		$data['iscollect'] = $this->frparam('iscollect',0,0);
    		$data['islikes'] = $this->frparam('islikes',0,0);
    		$data['isat'] = $this->frparam('isat',0,0);
    		$data['isrechange'] = $this->frparam('isrechange',0,0);
    		M('member')->update(['id'=>$this->member['id']],$data);
    		$_SESSION['member'] = array_merge($_SESSION['member'],$data);
    		if($this->frparam('ajax')){
				JsonReturn(['code'=>0,'msg'=>'设置成功','data'=>'']);
			}
			Success('设置成功！',U('user/setmsg'));
    	}
    	$this->display($this->template.'/user/setmsg');
    }

    function getclass(){
    	$molds = $this->frparam('molds',1,'article');
    	$data = M('classtype')->findAll(['molds'=>$molds,'ishome'=>1],'orders desc');
    	if(!$data){
    		$data = [];
    	}
		$data = getTree($data);
    	JsonReturn(['code'=>0,'data'=>$data]);
    }

    //钱包
    function wallet(){
    	$this->checklogin();
    	$lists = M('buylog')->findAll(['userid'=>$this->member['id'],'type'=>1]);
    	$cz_money = 0;
    	$cz_jifen = 0;
    	foreach ($lists as $key => $value) {
    		if($value['buytype']=='money'){
    			$cz_money += $value['amount'];
    		}else{
    			$cz_jifen += $value['amount'];
    		}

    	}
    	$this->user = M('member')->find(['id'=>$this->member['id']]);
    	$this->cz_money = $cz_money;
    	$this->cz_jifen = $cz_jifen;
    	$this->display($this->template.'/user/wallet');
    }
    //购买
    function buy(){
    	$this->checklogin();
    	if($_POST){
    		if($this->webconf['paytype']==0){
				JsonReturn(['code'=>1,'msg'=>'未开启在线支付！','data'=>'']);
			}
    		$money = $this->frparam('allmoney',3);
    		$number = $this->frparam('number');
    		if(!$money || !$number){
    			JsonReturn(['code'=>1,'msg'=>'参数错误！','data'=>'']);
    		}
    		$w['jifen'] = $number;
    		$w['price'] = $money;
    		$w['orderno'] = 'No'.date('YmdHis');
    		$paytype = $this->frparam('paytype',0,1);
    		$w['userid'] = $this->member['id'];
    		$w['paytype'] = $paytype;
			$buytarget = $this->frparam('buytarget',0,1);
    		if($buytarget==1){
    			$w['ptype'] = 2;
    		}else{
    			$w['ptype'] = 3;
    		}
    		
    		$w['addtime'] = time();
    		$res = M('orders')->add($w);
    		if($res){
    			JsonReturn(['code'=>0,'msg'=>'success','data'=>$w]);
    		}else{
    			JsonReturn(['code'=>1,'msg'=>'参数错误！','data'=>'']);
    		}

    	}
    	$this->display($this->template.'/user/buy');
    }
    //购买列表
    function buylist(){
    	$this->checklogin();
    	//兑换记录
    	$page1 = new Page('buylog');
		$this->type = $this->frparam('type',0,1);
		if($this->type==1){
			$sql ="  type=2 ";
		}else if($this->type==2){
			$sql ="  type=1 ";
		}else{
			$sql = " type=3 ";
		}
		$sql.=" and userid=".$this->member['id'];
		$data1 = $page1->where($sql)->orderby('addtime desc')->page($this->frparam('p',0,1))->go();
		$page1->file_ext = '';
		$pages1 = $page1->pageList(5,'?p=');
		$this->pages1 = $pages1;
		foreach($data1 as $k=>$v){
			$data1[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
			$data1[$k]['details'] = U('user/buydetails',['id'=>$v['id']]);
		}
		$this->lists1 = $data1;//列表数据
		$this->sum1 = $page1->sum;//总数据
		$this->listpage1 = $page1->listpage;//分页数组-自定义分页可用
		$this->prevpage1 = $page1->prevpage;//上一页
		$this->nextpage1 = $page1->nextpage;//下一页
		$this->allpage1 = $page1->allpage;//总页数
    	//订单记录
    	$page = new Page('orders');
		$this->type = $this->frparam('type',0,1);
		if($this->type==1){
			$sql =" ptype=1 ";
		}else{
			$sql =" ptype=2 ";
		}
		$sql.="  and isshow!=0 and userid=".$this->member['id'];
		$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
		$page->file_ext = '';
		$pages = $page->pageList(5,'?page=');
		$this->pages = $pages;
		foreach($data as $k=>$v){
			$data[$k]['date'] = date('Y-m-d H:i:s',$v['addtime']);
			$data[$k]['orderdetails'] =  U('user/orderdetails',['orderno'=>$v['orderno']]);
			$data[$k]['orderdel'] =  U('user/orderdel',['orderno'=>$v['orderno']]);
			$data[$k]['buytype'] = M('buylog')->getField(['orderno'=>$v['orderno']],'type');
		}
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数

    	$this->display($this->template.'/user/buy-list');
    }
    //交易详情
    function buydetails(){
    	$this->checklogin();
    	$id = $this->frparam("id");
    	if($id){
    		$data = M('buylog')->find(['id'=>$id,'userid'=>$this->member['id']]);
    		if($data){
    			$this->data = $data;
    			$this->display($this->template.'/user/buy-view');
    		}else{
    			Error('记录不存在！');
    		}
    	}else{
    		Error('链接错误！');
    	}
    	
    }


}