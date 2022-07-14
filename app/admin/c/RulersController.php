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

class RulersController extends CommonController
{
	public function index(){
		
		$rulers = M('Ruler')->findAll();
		$rulers = set_class_haschild($rulers);
		$rulers = getTree($rulers);
		
		$this->lists = $rulers;
		$this->display('ruler-list');
	}
	
	public function addrulers(){
		
		$this->fields_biaoshi = 'ruler';
		if($this->frparam('go',1)==1){
			
			$data['name'] = $this->frparam('name',1);
			$data['fc'] = $this->frparam('fc',1);
			$data['pid'] = $this->frparam('pid');
			$data['sys'] = $this->frparam('sys');
			$data['isdesktop'] = $this->frparam('isdesktop');
			
			if(M('Ruler')->add($data)){
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('添加成功！')));
				exit;
			}else{
				//Error('添加失败！');
				JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败！')));
				exit;
			}
			
			
			
		}
		
		$rulers = M('Ruler')->findAll('pid=0');
		//$rulers = getTree($rulers);
		$this->pid = $this->frparam('pid');
		$this->rulers = $rulers;
		
		$this->display('ruler-add');
	}
	
	public function editrulers(){

		$this->fields_biaoshi = 'ruler';
		if($this->frparam('go')==1){
			$data['name'] = $this->frparam('name',1);
			$data['fc'] = $this->frparam('fc',1);
			$data['pid'] = $this->frparam('pid');
			$data['sys'] = $this->frparam('sys');
			$data['isdesktop'] = $this->frparam('isdesktop');
			
			$a = M('Ruler')->update(array('id'=>$this->frparam('id')),$data);
			if($a){
				JsonReturn(array('status'=>1));
			}else{
				JsonReturn(array('status'=>1,'info'=>JZLANG('修改失败！')));
			}
		}
		$this->data = M('Ruler')->find(array('id'=>$this->frparam('id')));
		
		$rulers = M('Ruler')->findAll('pid=0');
		//$rulers = getTree($rulers);
	
		$this->rulers = $rulers;
		
		$this->display('ruler-edit');
	}
	
	public function deleterulers(){
		$id = $this->frparam('id');
		if($id){
			//判断是否为系统功能，系统功能不能删除
			$ruler = M('Ruler')->find(array('id'=>$id));
			if($ruler['sys']==1){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败，系统功能不能删除！')));
			}
			$n = M('Ruler')->find(array('pid'=>$id));
			if($n){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败，该分类有下级功能，请先删除下级功能！')));
			}else{
				$m = M('Ruler')->delete(array('id'=>$id));
				if($m){
                    $w['molds'] = 'ruler';
                    $w['data'] = serialize($ruler);
                    $w['title'] = '['.$ruler['id'].']'.$ruler['name'];
                    $w['addtime'] = time();
                    $x = M('recycle')->add($w);
					JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
				}
			}
			
			
			
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('未选择删除对象！')));
		}
	}
	
	
}