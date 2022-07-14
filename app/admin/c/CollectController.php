<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/03
// +----------------------------------------------------------------------


namespace app\admin\c;


use frphp\extend\Page;

class CollectController extends CommonController
{
	//列表
	function index(){
			$page = new Page('collect');
			$sql = '1=1';
			
			if($this->frparam('tid')){
				$sql .= ' and tid='.$this->frparam('tid');
			}
			
			$data = $this->frparam();
			$res = molds_search('collect',$data);
			$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
			$sql .= $get_sql;
			
			$this->fields_search = $res['fields_search'];
			$this->fields_list = M('Fields')->findAll(array('molds'=>'collect','islist'=>1),'orders desc');
			if($this->frparam('title',1)!=''){
				$sql.=" and title like '%".$this->frparam('title',1)."%' ";
			}
			
			
			$data = $page->where($sql)->orderby('id desc')->page($this->frparam('page',0,1))->go();
			$pages = $page->pageList();
			$this->pages = $pages;
			$this->lists = $data;
			$this->sum = $page->sum;
			
			
			$this->tid=  $this->frparam('tid');
			$this->title = $this->frparam('title',1);
			$collect_type = M('collect_type')->findAll();
		
			$this->collect_type = $collect_type;
			
			
			$this->display('collect-list');
			
			
		}
		
	function addcollect(){
		$this->fields_biaoshi = 'collect';
		if($this->frparam('go',1)==1){
			$data = $this->frparam();
			$data['addtime'] = strtotime($this->frparam('addtime',1));
			$data['title'] = $this->frparam("title",1);
			$data['description'] = $this->frparam("description",1);
			$data['litpic'] = $this->frparam("litpic",1);
			$data['url'] = $this->frparam("url",1);
			$data['w'] = $this->frparam("w",1);
			$data['h'] = $this->frparam("h",1);
			$data['tid'] = $this->frparam("tid");
			$data['isshow'] = $this->frparam("isshow");
			$data['orders'] = $this->frparam("orders");
			if(!$data['tid']){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('请选择分类！')));
				exit;
			}
			$data = get_fields_data($data,'collect');
			if(M('collect')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('添加成功！继续添加~'),'url'=>U('addcollect',array('tid'=>$data['tid']))));
				exit;
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败！')));
				exit;
			}
			
			
		}
		
		$collect_type = M('collect_type')->findAll(null);
		$this->tid = $this->frparam('tid') ? $this->frparam('tid') : 0;
		$this->collect_type = $collect_type;
		$this->display('collect-add');
	}
	
	function copycollect(){
		$id = $this->frparam('id');
		if($id){
			$data = M('collect')->find(['id'=>$id]);
			unset($data['id']);
			if(M('collect')->add($data)){
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('复制成功！')));
				exit;
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>JZLANG('复制失败！')));
				exit;
			}
			
			
		}
		
	}

	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$all = M('collect')->findAll('id in('.$data.')');
			if(M('collect')->delete('id in('.$data.')')){
				foreach($all as $v){
					$w['molds'] = 'collect';
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
	
	function editcollect(){
		$this->fields_biaoshi = 'collect';
		if($this->frparam('go',1)==1){
			$data = $this->frparam();
			$data['addtime'] = strtotime($this->frparam('addtime',1));
			$data['title'] = $this->frparam("title",1);
			$data['description'] = $this->frparam("description",1);
			$data['litpic'] = $this->frparam("litpic",1);
			$data['url'] = $this->frparam("url",1);
			$data['w'] = $this->frparam("w",1);
			$data['h'] = $this->frparam("h",1);
			$data['tid'] = $this->frparam("tid");
			$data['isshow'] = $this->frparam("isshow");
			$data['orders'] = $this->frparam("orders");
			if(!$data['tid']){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('请选择分类！')));
				exit;
			}
			$data = get_fields_data($data,'collect');
			if($this->frparam('id')){
				if(M('collect')->update(array('id'=>$this->frparam('id')),$data)){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！'),'url'=>U('index')));
					exit;
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败！')));
					exit;
				}
			}
			
			
		}
		if($this->frparam('id')){
			$this->data = M('collect')->find(array('id'=>$this->frparam('id')));
		}
		$collect_type = M('collect_type')->findAll(null);
		
		$this->collect_type = $collect_type;
		$this->display('collect-edit');
		
	}
	
	function deletecollect(){
		$id = $this->frparam('id');
		if($id){
			$data = M('collect')->find(['id'=>$id]);
			if(M('collect')->delete(['id'=>$id])){
				$w['molds'] = 'collect';
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
	
	function collectType(){
		$lists = M("collect_type")->findAll();
		$this->lists = $lists;
		$this->display('collecttype-list');
	}
	
	function collectTypeAdd(){
		if($this->frparam('go')==1){
			$data['name'] = $this->frparam('name',1);
				$data['addtime'] = time();
				if(M('collect_type')->add($data)){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('新增成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('新增失败！')));
				}
		}
		$this->display('collecttype-add');
	}

	function collectTypeEdit(){
		$id = $this->frparam('id');
		if($id){
			if($this->frparam('go')==1){
				$data['name'] = $this->frparam('name',1);
				$data['addtime'] = time();
				if(M('collect_type')->update(array('id'=>$id),$data)){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败！')));
				}
			}
			$data =  M("collect_type")->find(array('id'=>$id));
			$this->data = $data;
			$this->display('collecttype-edit');
		}

	}

	function collectTypeDelete(){
		$id = $this->frparam('id');
		if($id){
			//检测该分类下是否存在内容
			$r = M('collect')->getCount(array('tid'=>$id));
			if($r){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('该分类下存在内容，请先删除该分类下的内容！')));
			}
            $data = M('collect_type')->find(['id'=>$id]);
			if(M('collect_type')->delete(['id'=>$id])){
                $w['molds'] = 'collect_type';
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
	
	
	
	
	
	
	
	
	
}