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


namespace A\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class MessageController extends CommonController
{

	//批量删除留言
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if(M('message')->delete('id in('.$data.')')){
				JsonReturn(array('code'=>0,'msg'=>'批量删除成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量操作失败！'));
			}
		}
	}
	
	//留言管理
	function messagelist(){
		$page = new Page('Message');
		$sql = ' 1=1 ';
		if($this->frparam('isshow')){
			$isshow = $this->frparam('isshow')==1 ? 1 : 0;
			$sql .= ' and isshow='.$isshow;
		}
		$this->isshow = $this->frparam('isshow');
		
		if($this->frparam('tid')!=0){
			$sql = 'tid='.$this->frparam('tid');
		}
		if($this->frparam('aid')){
			$sql.=" and aid = ".$this->frparam('aid')." ";
		}
		$data = $this->frparam();
		$res = molds_search('message',$data);
		$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
		$sql .= $get_sql;
		
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>'message','islist'=>1),'orders desc');
		
		$data = $page->where($sql)->orderby('id desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();

		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		
		
		$this->tid=  $this->frparam('tid');
		$this->aid = $this->frparam('aid');
		
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtypes = getTree($classtype);
	
		$this->classtypes = $this->classtypetree;
		
		
		$this->display('message-list');
		
		
	}
	
	public function editmessage(){
		$this->fields_biaoshi = 'message';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);
			$data = get_fields_data($data,'message');
			if($this->frparam('id')){
				if(M('Message')->update(array('id'=>$this->frparam('id')),$data)){
					JsonReturn(array('code'=>0,'msg'=>'修改成功！'));
					exit;
				}else{
					//Error('修改失败！');
					JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
					exit;
				}
			}
			
			
			
		}
		if($this->frparam('id')){
			$this->data = M('Message')->find(array('id'=>$this->frparam('id')));
		}
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
	
		$this->classtypes = $this->classtypetree;
		$this->display('message-details');
		
	}
	function deletemessage(){
		$id = $this->frparam('id');
		if($id){
			if(M('Message')->delete('id='.$id)){
				//Success('删除成功！',U('index'));
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				//Error('删除失败！');
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
}