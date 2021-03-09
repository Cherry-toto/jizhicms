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

class ScreenController extends CommonController
{
	function index(){
		//接收前台所有的请求
		$request_url = REQUEST_URI;
		
		//检测三个参数是否存在
		if(!$this->frparam('molds',1) || !$this->frparam('tid') || !$this->frparam('jz_screen',1)){
			$this->error('参数错误！');
		}
		if(!M('molds')->find(['biaoshi'=>$this->frparam('molds',1)])){
			$this->error('非法参数！');
		}
		if(!isset($_SESSION['screen'])){
			$_SESSION['screen'] = [];
		}
		$session_screen = $_SESSION['screen'];
		//查询扩展字段
		$fields = M('fields')->findAll(['molds'=>$this->frparam('molds',1)]);
		$newfield = [];
		foreach($fields as $k=>$v){
			$newfield[$v['field']] = $v;
		}
		
		$res = M('classtype')->find(array('id'=>$this->frparam('tid')));
		
		//面包屑导航
		$classtypetree = array_reverse($this->classtypetree);
		$isgo = false;
		$newarray = [];
		$parent = [];//标记父类
		$istop = false;
		foreach($classtypetree as $k=>$v){
			if($v['id']==$res['id'] && !$isgo){
				$isgo = true;
				$res['level'] = $v['level'];
				$newarray[]=$v;
			}
			if($v['id']==$res['id'] && $v['level']==0){
				break;
			}
			if($v['level']==0 && $v['id']!=$res['id'] && $v['id']!=$res['pid']){
				if(!$istop && $isgo && $parent['level']!=0){
					$newarray[]=$v;
					$istop = true;
				}
				$isgo = false;
			}
			if($isgo &&  $v['id']!=$res['id'] && $res['level']>$v['level'] ){
				if(count($parent['pid'])){
					if($parent['level']>$v['level'] && $parent['pid']!=$v['pid']){
						$newarray[]=$v;
						$parent = $v;
					}
				}else{
					$newarray[]=$v;
					$parent = $v;
				}
			}
		}
		$newarray2 = array_reverse($newarray);
		$positions='<a href="'.get_domain().'">首页</a>';
		foreach($newarray2 as $v){
			$positions.='  &gt;  <a href="'.$v['url'].'">'.$v['classname'].'</a>';
		}
		$this->positions_data = $newarray2;
		$this->positions = $positions;
	
		//解析jz_screen
		//检测是否有page分页参数
		$this->frpage = 1;
		if(strpos($this->frparam('jz_screen',1),'page')!==false){
			$jz_screen_arr = explode('-page-',$this->frparam('jz_screen',1));
			$jz_screen = explode('-',$jz_screen_arr[0]);
			$this->frpage = (int)$jz_screen_arr[1];
		}else{
			$jz_screen = explode('-',$this->frparam('jz_screen',1));
		}
		
		if($this->frparam('page')){
			$this->frpage = $this->frparam('page');
		}
		
		$jz_screen_key = [];
		$jz_screen_value = [];
		foreach($jz_screen as $k=>$v){
			if($k%2==0){
				$jz_screen_key[]=$v;
			}else{
				$jz_screen_value[]=$v;
			}

		}
		$screen = array_combine($jz_screen_key,$jz_screen_value);
		foreach($screen as $k=>$v){
			
			if($v==0 || $v==''){
				if(isset($session_screen[$k])){
					unset($session_screen[$k]);
				}
			}else{
				$session_screen[$k] = $v;
			}
			
			
		}
		
		$sql = '1=1 and isshow=1';
		//组合搜索内容
		foreach($session_screen as $k=>$v){
			if(!array_key_exists($k,$newfield)){
				continue;
			}
			if($newfield[$k]['fieldtype']==7 || $newfield[$k]['fieldtype']==12){
				//单选字段
				
				//多选框判断
				if(strpos($v,',')!==false){
					$vv = explode(',',$v);
					$vv_arr = [];
					foreach($vv as $vs){
						$vv_arr[]=" ".$k."='".$vs."' ";
					}
					$sql.=" and (".implode(' or ',$vv_arr).") ";
					$vv = null;
					$vv_arr = null;
				}else{
					$sql.=" and ".$k."='".$v."' ";
				}
				
				
			}else{
				//多选字段
				if(strpos($v,',')!==false){
					$vv = explode(',',$v);
					$vv_arr = [];
					foreach($vv as $vs){
						$vv_arr[]=" ".$k." like '%,".$vs.",%' ";
					}
					$sql.=" and (".implode(' or ',$vv_arr).") ";
					$vv = null;
					$vv_arr = null;
				}else{
					$sql.=" and ".$k." like '%,".$v.",%' ";
				}
				
			}
			
		}
		$this->filters = $session_screen;
		//dump($session_screen);
		$_SESSION['screen'] = $session_screen;
		$molds = $this->frparam('molds',1);
		$sql .= ' and tid in ('.implode(',',$this->classtypedata[$res['id']]['children']['ids']).') ';
		$page = new Page($molds);
		//手动设置分页条数
		$limit = $res['lists_num'];
		if($this->frparam('limit')){
			$limit = $this->frparam('limit');
		}
		//echo $sql;
		//筛选分页的特殊性
		$page->typeurl = 'screen';
		
		$orders = 'istop desc,orders desc,addtime desc,id desc';
		$ot = $this->frparam('orders') ? $this->frparam('orders') : $res['orderstype'];
		switch($ot){
			case 1:
				$orders = 'istop desc,orders desc,addtime desc,id desc';
			break;
			case 2:
				$orders = 'istop desc,orders desc,id asc';
			break;
			case 3:
				$orders = 'istop desc,orders asc';
			break;
			case 4:
				$orders = 'istop desc,addtime desc';
			break;
			case 5:
				$orders = 'istop desc,id asc';
			break;
			case 6:
				$orders = 'istop desc,hits desc';
			break;
			case 7:
				$orders = 'istop desc,addtime asc';
			break;
		}
		$this->currentpage = $this->frpage;
		$data = $page->where($sql)->orderby($orders)->limit($limit)->page($this->frpage)->go();
		$pages = $page->pageList(3,'-page-');
		
		$this->pages = $pages;//组合分页
		
		foreach($data as $k=>$v){
			$data[$k]['url'] = gourl($v,$v['htmlurl']);
		}
		$this->type = $res;
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		if($this->frparam('ajax') && $this->webconf['isajax']){
			if($this->frparam('ajax_tpl',1)){
				$this->display($this->template.'/'.$res['molds'].'/screen_list_'.$res['lists_html']);
				exit;
			}
			JsonReturn(['code'=>0,'data'=>$data,'sum'=>$this->sum,'allpage'=>$this->allpage,'listpage'=>$this->listpage]);
			
		}
	
		$this->display($this->template.'/'.$res['molds'].'/'.$res['lists_html']);
		
		
		
		
		
		
	}

	
}