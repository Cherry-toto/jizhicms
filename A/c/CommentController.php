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

class CommentController extends CommonController
{
	

	function addcomment(){
		$this->fields_biaoshi = 'comment';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);
			$data['userid'] = $_SESSION['admin']['id'];
			
			
			$data = get_fields_data($data,'comment');
			
			if(M('Comment')->add($data)){
				//Success('添加成功！',U('index'));
				JsonReturn(array('code'=>0,'msg'=>'添加成功！'));
				exit;
			}else{
				//Error('添加失败！');
				JsonReturn(array('code'=>1,'msg'=>'添加失败！'));
				exit;
			}
			
			
			
		}
		
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
	
		$this->classtypes = $this->classtypetree;;
		$this->display('comment-add');
	}

	//批量删除评论
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if(M('comment')->delete('id in('.$data.')')){
				JsonReturn(array('code'=>0,'msg'=>'批量删除成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量操作失败！'));
			}
		}
	}
	
	//评论管理
	function commentlist(){
		$page = new Page('Comment');
		$sql = '1=1';
		
		if($this->frparam('tid')){
			$sql .= ' and tid='.$this->frparam('tid');
		}
		if($this->frparam('aid')){
			$sql.=" and aid = ".$this->frparam('aid')." ";
		}
		if($this->frparam('userid')!=0){
			$sql.=" and userid = ".$this->frparam('userid')." ";
		}
		$data = $page->where($sql)->orderby('id desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		
		
		$this->tid=  $this->frparam('tid');
		$this->aid = $this->frparam('aid');
		$this->userid = $this->frparam('userid');
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
	
		$this->classtypes = $this->classtypetree;;
		
		
		$this->display('comment-list');
		
		
	}
	
	public function editcomment(){
		$this->fields_biaoshi = 'comment';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);
			$data = get_fields_data($data,'comment');
			if($this->frparam('id')){
				if(M('Comment')->update(array('id'=>$this->frparam('id')),$data)){
					//Success('修改成功！',U('index'));
					JsonReturn(array('code'=>0,'msg'=>'修改成功！','url'=>U('index')));
					exit;
				}else{
					//Error('修改失败！');
					JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
					exit;
				}
			
			}
			
			
			
		}
		if($this->frparam('id')){
			$this->data = M('Comment')->find(array('id'=>$this->frparam('id')));
		}
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
	
		$this->classtypes = $this->classtypetree;;
		$this->display('comment-details');
		
	}
	function deletecomment(){
		$id = $this->frparam('id');
		if($id){
			if(M('Comment')->delete('id='.$id)){
				//Success('删除成功！',U('index'));
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				//Error('删除失败！');
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
}