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

use frphp\extend\Page;

class JzchainController extends CommonController
{
	
	public function index(){
		$molds = 'chain';
		$this->molds = M('Molds')->find(array('biaoshi'=>$molds));
		$data = $this->frparam();
		$this->title = $this->frparam('title',1);
		$this->url = $this->frparam('url',1);
		$this->isshow = $this->frparam('isshow');
		if($this->frparam('ajax')){

			$page = new Page($molds);
			$sql = ' 1=1 ';
			if($this->frparam('title',1)){
				$sql.=" and title like '%".$this->frparam('title',1)."%' ";
			}
			if($this->frparam('url',1)){
				$sql.=" and url like '%".$this->frparam('url',1)."%' ";
			}
			
			if($this->frparam('isshow')){
				$t = $this->frparam('isshow')==2 ? 0 : 1;
				$sql.=" and isshow=".$t;
			}
			
			$data = $page->where($sql)->orderby('id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			foreach($data as $k=>$v){
				
				$v['isshow'] = $v['isshow']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['newtitle'] = $v['newtitle'] ? $v['newtitle'] : '';
				$ajaxdata[]=$v;
				
			}
			
			$pages = $page->pageList();
			$this->pages = $pages;
			$this->lists = $data;
			$this->sum = $page->sum;
			JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
			
			
		}
		
		
		$this->display('chain-list');
		
	}
	
	public function addchain(){
		$w['title'] = $this->frparam('title',1);
		if(!$w['title']){
			JsonReturn(['code'=>1,'msg'=>JZLANG('请输入内链词！')]);
		}
		$w['newtitle'] = $this->frparam('newtitle',1);
		$w['url'] = $this->frparam('url',1);
		if(!$w['url']){
			JsonReturn(['code'=>1,'msg'=>JZLANG('请输入内链！')]);
		}
		$w['num'] = ($this->frparam('num')<=-1 || $this->frparam('num',1)=='' ) ? -1 : $this->frparam('num');
		if(M('chain')->find(['title'=>$w['title']])){
			JsonReturn(['code'=>1,'msg'=>JZLANG('内链词已存在！')]);
			
		}
		$w['isshow'] = 1;
		M('chain')->add($w);
		JsonReturn(['code'=>0,'msg'=>JZLANG('添加成功！')]);
		
		
	}
	public function editchain(){
		$id = $this->frparam('id');
		if(!$id){
			JsonReturn(['code'=>1,'msg'=>JZLANG('参数错误！')]);
		}
		$w['title'] = $this->frparam('title',1);
		if(!$w['title']){
			JsonReturn(['code'=>1,'msg'=>JZLANG('请输入内链词！')]);
		}
		$w['newtitle'] = $this->frparam('newtitle',1);
		$w['url'] = $this->frparam('url',1);
		if(!$w['url']){
			JsonReturn(['code'=>1,'msg'=>JZLANG('请输入内链！')]);
		}
		$w['num'] = ($this->frparam('num')<=-1 || $this->frparam('num',1)=='' ) ? -1 : $this->frparam('num');
		$sql = " title='".$w['title']."' and id!=".$id;
		if(M('chain')->find($sql)){
			JsonReturn(['code'=>1,'msg'=>JZLANG('属性已存在！')]);
			
		}
		
		M('chain')->update(['id'=>$id],$w);
		JsonReturn(['code'=>0,'msg'=>JZLANG('修改成功！')]);
		
		
	}
	
	
	//单一删除
	function delchain(){
		$id = $this->frparam('id');
		if($id){
		    $data = M('chain')->find(['id'=>$id]);
			if(M('chain')->delete(['id'=>$id])){
                $w['molds'] = 'chain';
                $w['data'] = serialize($data);
                $w['title'] = '['.$data['id'].']'.$data['title'];
                $w['addtime'] = time();
                M('recycle')->add($w);
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
	}
	
	function changeStatus(){

		$w['isshow'] = $this->frparam('value',0,0);
		$r = M('chain')->update(array('id'=>$this->frparam('id',0,0)),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));

	}
	
	function delAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
		    $list = M('chain')->findAll('id in('.$data.')');
			if(M('chain')->delete('id in('.$data.')')){
			    foreach ($list as $v){
                    $w['molds'] = 'chain';
                    $w['data'] = serialize($v);
                    $w['title'] = '['.$v['id'].']'.$v['title'];
                    $w['addtime'] = time();
                    M('recycle')->add($w);
                }
				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
			}
		}
	}



}