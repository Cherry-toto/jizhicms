<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2022/01
// +----------------------------------------------------------------------


namespace app\admin\c;


use frphp\extend\Page;

class RecycleController extends CommonController
{
	public function index(){
		
		if($this->frparam('ajax',1)){
			$page = new Page('recycle');
			$sql = '1=1';
			$data = $this->frparam();
			if($this->frparam('molds',1)){
				$sql.=" and molds='".$this->frparam('molds',1)."'";
			}
			$data = $page->where($sql)->limit($this->frparam('limit',0,10))->orderby('id desc')->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			$molds = M('molds')->findAll();
			$moldsname = [];
			foreach($molds as $v){
				$moldsname[$v['biaoshi']] = $v['name'];
			}
			foreach($data as $v){
				$v['moldsname'] = $moldsname[$v['molds']];
				$ajaxdata[]=$v;
			}
			$pages = $page->pageList();
			$this->pages = $pages;
			$this->lists = $data;
			$this->sum = $page->sum;
			JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
			
		}
		
		$this->molds = $this->frparam('molds',1);
		
		
		$this->display('recycle-list');
	
		
	}

	public function restore(){
		$id = $this->frparam('id');
		if($id){
			$recycle = M('recycle')->find(['id'=>$id]);
			if($recycle){
				$data = unserialize($recycle['data']);
				$r = M($recycle['molds'])->add($data);
				if($r){
					M('recycle')->delete(['id'=>$id]);
					$relative = M('recycle')->findAll(['aid'=>$id]);
					$ids = [];
					foreach($relative as $v){
						$d = unserialize($v['data']);
						$rr = M($v['molds'])->add($d);
						if($rr){
							M('recycle')->delete(['id'=>$v['id']]);
						}else{
							$ids[]=$v['id'];
						}
					}
					if(count($ids)){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('部分未执行成功！').'['.implode(',',$ids).']'));
					}
					JsonReturn(array('code'=>0,'msg'=>JZLANG('操作成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('还原失败，可能是ID已经存在！')));
				}
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('数据不存在！')));
			}
			
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('参数错误！')));
		}
		
		
	}
	
	public function restoreAll(){
		$data = $this->frparam('data',1);
		if($data){
			$recycles = M('recycle')->findAll('id in('.$data.')');
			$ids = [];
			foreach($recycles as $v){
				$data = unserialize($v['data']);
				$r = M($v['molds'])->add($data);
				if($r){
					M('recycle')->delete(['id'=>$v['id']]);
					$relative = M('recycle')->findAll(['aid'=>$v['id']]);
					foreach($relative as $vv){
						$d = unserialize($vv['data']);
						$rr = M($vv['molds'])->add($d);
						if($rr){
							M('recycle')->delete(['id'=>$vv['id']]);
						}else{
							$ids[]=$vv['id'];
						}
					}
					
				}else{
					$ids[]=$v['id'];
				}
				
				
			}
			
			if(count($ids)){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('部分未执行成功！').'['.implode(',',$ids).']'));
			}
			JsonReturn(array('code'=>0,'msg'=>JZLANG('操作成功！')));
			
			
		}
		
	}

	public function del(){
		$id = $this->frparam('id');
		if($id){
			$data = M('recycle')->find(['id'=>$id]);
			if(M('recycle')->delete(['id'=>$id])){
				M('recycle')->delete(['aid'=>$id]);
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
		
		
	}
	
	public function delAll(){
		$data = $this->frparam('data',1);
		if($data){
			if(M('recycle')->delete('id in('.$data.')')){
				M('recycle')->delete('aid in('.$data.')');
				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
			}
		}
	}
	
	

}