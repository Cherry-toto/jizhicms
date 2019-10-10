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

class UserController extends Controller
{
	function _init(){
		
		$webconf = webConf();
		$webcustom = get_custom();
		$template = get_template();
		$this->webconf = $webconf;
		$this->webcustom = $webcustom;
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
		
		$this->tpl = Tpl_style.$template.'/';
		$this->common = Tpl_style.'common/';
		//檢測用戶是否登錄
		if(isset($_SESSION['member'])){
			$this->islogin = true;
			$this->member = $_SESSION['member'];
			
			if($this->webconf['isopenhomepower']==1){
				if(strpos($_SESSION['member']['paction'],','.APP_CONTROLLER.',')!==false){
				   
				}else{
					$action = APP_CONTROLLER.'/'.APP_ACTION;
					if(strpos($_SESSION['member']['paction'],','.$action.',')==false){
						$ac = M('Power')->find(array('action'=>$action));
						
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'您没有【'.$ac['name'].'】的权限！','url'=>U('Home/index')]);
						}
						Error('您没有【'.$ac['name'].'】的权限！',U('Home/index'));
					}
				}
			   
			  
			}
			
		}else{
			$this->islogin = false;
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'您还未登录，请重新登录！']);
			}
			Error('您还未登录，请重新登录！',U('Login/index'));
		}
	}
	
		//读评论
	function read_comment(){
	    $id = $this->frparam('id');
	    if($id){
	        
	        $comment = M('comment')->find(['id'=>$id]);
	        $molds = $this->classtypedata[$comment['tid']]['molds'];
	        $data = M($molds)->find(['id'=>$comment['aid']]);
	        M('comment')->update(['id'=>$comment['id']],['isread'=>1]);
	        Redirect(gourl($data['id'],$data['htmlurl']));
	        
	    }else{
	        Error('链接错误！');
	    }
	}
	
	
    function index(){
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
		
		$this->display($this->template.'/user/index');
       
    }
	function userinfo(){
		
		if($_POST && $this->frparam('ajax')){
			$w['tel'] = $this->frparam('tel',1);
			$w['pass'] = $this->frparam('password',1);
			$w['repass'] = $this->frparam('repassword',1);
			$w['username'] = $this->frparam('username',1);
			$w['email'] = $this->frparam('email',1);
			if($w['tel']!=''){
				if(preg_match("/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\\d{8}$/",$w['tel'])){  
				
				}else{  
					JsonReturn(['code'=>1,'msg'=>'手機號碼格式錯誤！']);
				}
				//檢查是否已經註冊
				$r = M('member')->find(['tel'=>$w['tel']]);
				if($r){
					if($r['id']!=$this->member['id']){
						JsonReturn(['code'=>1,'msg'=>'手機號已被註冊！']);
					}
				}
			}
			if($w['username']==''){
				JsonReturn(['code'=>1,'msg'=>'賬戶不能為空！']);
			}
			if($w['pass']!=$w['repass'] && $w['pass']!=''){
				JsonReturn(['code'=>1,'msg'=>'兩次密碼不相同']);
			}
			if($w['email']==''){
				JsonReturn(['code'=>1,'msg'=>'郵箱不能為空！']);
			}
			//檢查郵箱是否已經註冊
			$r = M('member')->find(['email'=>$w['email']]);
			if($r){
				if($r['id']!=$this->member['id']){
					JsonReturn(['code'=>1,'msg'=>'郵箱已被註冊！']);
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
			$_SESSION['member'] = array_merge($_SESSION['member'],$member);
			JsonReturn(['code'=>0,'msg'=>'success']);
			
		}
		
		$this->display($this->template.'/user/userinfo');
       
    }
	function order(){
		
		$page = new Page('Orders');
		$sql = 'userid='.$this->member['id'].' and isshow!=0 ';
		//$sql = 'userid='.$this->member['id'];
		$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		if($this->frparam('ajax')){
			foreach($data as $k=>$v){
				$data[$k]['addtime'] = date('Y/m/d',$v['addtime']);
				$data[$k]['order_details'] =  U('User/order_details',['orderno'=>$v['orderno']]);
				$data[$k]['order_del'] =  U('User/order_del',['orderno'=>$v['orderno']]);
			}
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		
		$this->display($this->template.'/user/order');
       
    }
	
	//刪除訂單
	function order_del(){
		$orderno = $this->frparam('orderno',1);
		if(!$orderno){ Error('缺少订单号！');}
		$order = M('orders')->find(" orderno='".$orderno."' and isshow!=0 ");
		if(!$order){ Error('订单号错误！');}
		//軟刪除
		$r = M('orders')->update(['orderno'=>$orderno],['isshow'=>0]);
		if($r){
			Success('删除成功！',U('order'));
		}else{
			Error('删除失败！');
		}
		
	}
	function change_img(){
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
		
		$page = new Page('Comment');
		$sql = 'userid='.$this->member['id'].' and isshow=1 ';
		//$sql = 'userid='.$this->member['id'];
		$data = $page->where($sql)->orderby('addtime desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		
		$this->sum = $page->sum;
		foreach($data as $k=>$v){
			$xmolds = M($this->classtypedata[$v['tid']]['molds'])->find(['id'=>$v['aid']]);
			if($xmolds){
				$data[$k]['title'] = $xmolds['title'];
				$data[$k]['date'] = date('Y/m/d H:i:s',$v['addtime']);
				$data[$k]['url'] =  U('User/read_comment',['id'=>$v['id']]);
				$data[$k]['body'] = newstr($v['body'],60);
				$data[$k]['comment_num'] =  get_comment_num($v['tid'],$v['aid']);
				$data[$k]['comment_del'] =  U('User/comment_del',['id'=>$v['id']]);
			}
			
		}
		$this->lists = $data;
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/comment');
       
    }
	//刪除评论
	function comment_del(){
		$id = $this->frparam('id');
		if(!$id){ Error('缺少ID！');}
		$comment = M('comment')->find(['id'=>$id,'isshow'=>1]);
		if(!$comment){ Error('未找到评论！');}
		//軟刪除
		$r = M('comment')->update(['id'=>$id],['isshow'=>0]);
		if($r){
			Success('删除成功！',U('comment'));
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
		$_SESSION['member']['likes'] = $xnew;
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'msg'=>$msg,'url'=>$_SESSION['return_url']]);
		}
		Success($msg,$_SESSION['return_url']);
		
	}
	function likes(){
		$lists = [];
		if($this->member['likes']!=''){
	
			$likes = explode('||',$this->member['likes']);

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
		$data = $arraypage->setPage(['limit'=>10])->go();
		foreach($data as $k=>$v){
			$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
			$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
			$data[$k]['likes_del'] = U('likes_del',['id'=>$v['id'],'tid'=>$v['tid']]);
		}
		$this->lists = $data;
		$this->pages = $arraypage->pageList();
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/likes');
       
    }
	function likes_del(){
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		if($id && $tid){
			$ids = str_replace('||'.$tid.'-'.$id.'||','',$this->member['likes']);
			M('member')->update(['id'=>$this->member['id']],['likes'=>$ids]);
			$_SESSION['member']['likes'] = $ids;
			
			Success('删除成功！',U('likes'));
		}else{
			Error('参数错误！');
		}
	}
	
	function collectAction(){
		if(!isset($_SESSION['return_url'])){
			$referer = (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') ? U('user/collect') : $_SERVER['HTTP_REFERER'];
			$_SESSION['return_url'] = $referer;
		
		}
		$tid = $this->frparam('tid');
		$id = $this->frparam('id');
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
		$_SESSION['member']['collection'] = $xnew;
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'msg'=>$msg,'url'=>$_SESSION['return_url']]);
		}
		Success($msg,$_SESSION['return_url']);
		
	}
	
	function collect(){
		$lists = [];
		if($this->member['collection']!=''){
			//$ids = substr($this->member['collection'],1,strlen($this->member['collection'])-2);
			$collection = explode('||',$this->member['collection']);

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
		$data = $arraypage->setPage(['limit'=>10])->go();
		foreach($data as $k=>$v){
			$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
			$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
			$data[$k]['collect_del'] = U('collect_del',['id'=>$v['id'],'tid'=>$v['tid']]);
		}
		$this->lists = $data;
		$this->pages = $arraypage->pageList();
		if($this->frparam('ajax')){
			
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
		$this->display($this->template.'/user/collect');
       
    }
	function collect_del(){
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		if($id && $tid){
			$ids = str_replace('||'.$tid.'-'.$id.'||','',$this->member['collection']);
			M('member')->update(['id'=>$this->member['id']],['collection'=>$ids]);
			$_SESSION['member']['collection'] = $ids;
			
			Success('删除成功！',U('collect'));
		}else{
			Error('参数错误！');
		}
	}
	
	//购物车
	function cart(){
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
					$type = $this->classtypedata[$d[0]];
					$carts[] = ['info'=>M($type['molds'])->find(['id'=>$d[1]]),'num'=>$d[2],'tid'=>$d[0]];
				}
				
			}
		}
		$this->carts = $carts;
		$this->display($this->template.'/cart');
	}
	
	//购物车 tid-id-num
	function addcart(){
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		$num = $this->frparam('num');
		if(!$id || !$tid || !$num){
			JsonReturn(['code'=>1,'msg'=>'参数错误！']);
		}
		//检查库存
		$product = M('product')->find(['id'=>$id]);
		if($product['stock_num']<$num){
			JsonReturn(['code'=>1,'msg'=>'库存不足！']);
		}
		
		//session存储
		if(!isset($_SESSION['cart'])){
			//id-tid-num
			$cart = $tid.'-'.$id.'-'.$num;
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
					$new[]=$d[0].'-'.$d[1].'-'.$d[2];
				}
				
			}
			if($isnew){
				$new[]=$tid.'-'.$id.'-'.$num;
			}
			
			$cart = implode('||',$new);
		}
		$_SESSION['cart'] = $cart;
		JsonReturn(['code'=>0,'msg'=>'success','url'=>U('User/cart')]);
		
		
	}
	//删除购物车商品
	function delcart(){
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
	


}