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


namespace A\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class ProductController extends CommonController
{
	
	
	function productlist(){
		$page = new Page('product');
		$sql = ' 1=1 ';
		if($this->frparam('isshow')){
			$isshow = $this->frparam('isshow')==1 ? 1 : 0;
			$sql .= ' and isshow='.$isshow;
		}
		$this->isshow = $this->frparam('isshow');
		
		if($this->frparam('tid')){
			$sql .= ' and tid='.$this->frparam('tid');
		}
		$data = $this->frparam();
		$res = molds_search('product',$data);
		$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
		$sql .= $get_sql;
		
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>'product','islist'=>1),'orders desc');
		if($this->frparam('title',1)){
			$sql.=" and title like '%".$this->frparam('title',1)."%' ";
		}
		
		$data = $page->where($sql)->orderby('orders desc,addtime desc,id desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		
		$this->molds = M('molds')->find(['biaoshi'=>'product']);
		$this->tid=  $this->frparam('tid');
		$this->title = $this->frparam('title',1);
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->classtypes = $this->classtypetree;
		$this->display('product-list');
		
		
	}

	function addproduct(){
		$this->fields_biaoshi = 'product';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);
			$data['price'] = $this->frparam('price',3);
			$data['userid'] = $_SESSION['admin']['id'];
			$data['description'] = ($this->frparam('description',1)=='') ? newstr(strip_tags($data['body']),200) : $this->frparam('description',1);
			if($this->frparam('litpic',1)==''){
				$pattern='/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|PNG))\"?.+>/i';
				if($this->frparam('body',1)!=''){
					preg_match_all($pattern,$_POST['body'],$matchContent);
				
					if(isset($matchContent[1][0])){
						$data['litpic'] = $matchContent[1][0];
					}else{
						$data['litpic'] = '';
					}
				}else{
					$data['litpic'] = '';
				}
				
			}
			if(array_key_exists('pictures_urls',$data) && $data['pictures_urls']!=''){
				 $data['pictures'] = implode('||',format_param($data['pictures_urls'],2));
			}else{
				$data['pictures'] = '';
			}
			
			
			$pclass = get_info_table('classtype',array('id'=>$data['tid']));
			$data['molds'] = $pclass['molds'];
			$data['htmlurl'] = $pclass['htmlurl'];
			$data = get_fields_data($data,'product');
			
			if(M('product')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>'添加成功,继续添加~','url'=>U('addarticle',array('tid'=>$data['tid']))));
				exit;
			}
			
			
			
		}
		$this->molds = M('molds')->find(['biaoshi'=>'product']);
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->tid = $this->frparam('tid');
		$this->classtypes = $this->classtypetree;
		$this->display('product-add');
	}
	public function editproduct(){
		$this->fields_biaoshi = 'product';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);
			$data['price'] = $this->frparam('price',3);
			$data['description'] = ($this->frparam('description',1)=='') ? newstr(strip_tags($data['body']),200) : $this->frparam('description',1);
			if($this->frparam('litpic',1)==''){
				$pattern='/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|PNG))\"?.+>/i';
				if($this->frparam('body',1)!=''){
					preg_match_all($pattern,$_POST['body'],$matchContent);
				
					if(isset($matchContent[1][0])){
						$data['litpic'] = $matchContent[1][0];
					}else{
						$data['litpic'] = '';
					}
				}else{
					$data['litpic'] = '';
				}
			}
			if(array_key_exists('pictures_urls',$data) && $data['pictures_urls']!=''){
				 $data['pictures'] = implode('||',format_param($data['pictures_urls'],2));
			}else{
				$data['pictures'] = '';
			}
			$pclass = get_info_table('classtype',array('id'=>$data['tid']));
			$data['molds'] = $pclass['molds'];
			$data['htmlurl'] = $pclass['htmlurl'];
			$data = get_fields_data($data,'product');
			if($this->frparam('id')){
				if(M('product')->update(array('id'=>$this->frparam('id')),$data)){
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
			$this->data = M('product')->find(array('id'=>$this->frparam('id')));
		}
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->molds = M('molds')->find(['biaoshi'=>'product']);
		$this->classtypes = $this->classtypetree;
		$this->display('product-edit');
		
	}
	function deleteproduct(){
		$id = $this->frparam('id');
		if($id){
			if(M('product')->delete('id='.$id)){
				//Success('删除成功！',U('index'));
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				//Error('删除失败！');
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	
	//复制文章
	function copyproduct(){
		$id = $this->frparam('id');
		if($id){
			$data = M('product')->find(['id'=>$id]);
			unset($data['id']);
			if(M('product')->add($data)){
				
				JsonReturn(array('code'=>0,'msg'=>'复制成功！'));
				exit;
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>'复制失败！'));
				exit;
			}
			
			
		}
		
	}
	//批量删除
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if(M('product')->delete('id in('.$data.')')){
				JsonReturn(array('code'=>0,'msg'=>'批量删除成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量操作失败！'));
			}
		}
	}
	//批量复制
	function copyAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$list = M('product')->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				unset($v['id']);
				if(!M('product')->add($v)){
					$r = false;break;
				}
			}
			if($r){
				JsonReturn(array('code'=>0,'msg'=>'批量复制成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量复制失败！'));
			}
		}
	}
	//批量修改
	function changeType(){
		$data = $this->frparam('data',1);
		$tid = $this->frparam('tid');
		if($data!=''){
			$list = M('product')->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				$w['tid'] = $tid;
				$type = M('classtype')->find(array('id'=>$tid));
				$w['htmlurl'] = $type['htmlurl'];
				if(!M('product')->update(array('id'=>$v['id']),$w)){
					$r = false;break;
				}
			}
			if($r){
				JsonReturn(array('code'=>0,'msg'=>'批量修改成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量修改失败！'));
			}
		}
	}
	//修改排序
	function editProductOrders(){
		$w['orders'] = $this->frparam('orders');
		
		$r = M('product')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>'修改失败！'));
		}
		JsonReturn(array('code'=>0,'info'=>'修改成功！'));
	}

	

	
	
	
	
	
	
	
	
	
	
}