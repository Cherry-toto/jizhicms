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

class ArticleController extends CommonController
{
	
	
	//内容管理
	function articlelist(){
		$page = new Page('Article');
		$classtypedata = classTypeData();
		foreach($classtypedata as $k=>$v){
			$classtypedata[$k]['children'] = get_children($v,$classtypedata);
		}
		
		$sql = ' 1=1 ';
		if($this->frparam('isshow')){
			$isshow = $this->frparam('isshow')==1 ? 1 : 0;
			$sql .= ' and isshow='.$isshow;
		}
		$this->isshow = $this->frparam('isshow');
		if($this->frparam('tid')){
			$sql .= ' and tid in('.implode(",",$classtypedata[$this->frparam('tid')]["children"]["ids"]).')';
			//$sql .= ' and tid='.$this->frparam('tid');
		}
		$data = $this->frparam();
		$res = molds_search('article',$data);
		$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
		$sql .= $get_sql;
		
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>'article','islist'=>1),'orders desc');
		if($this->frparam('title',1)!=''){
			$sql.=" and title like '%".$this->frparam('title',1)."%' ";
		}
		
		$data = $page->where($sql)->orderby('orders desc,addtime desc,id desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		
		$this->tid=  $this->frparam('tid');
		$this->title = $this->frparam('title',1);
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->classtypes = $this->classtypetree;
		$this->display('article-list');
		
		
	}

	function addarticle(){
		$this->fields_biaoshi = 'article';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);
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
			$pclass = get_info_table('classtype',array('id'=>$data['tid']));
			$data['molds'] = $pclass['molds'];
			$data['htmlurl'] = $pclass['htmlurl'];
			$data = get_fields_data($data,'article');
			
			if(M('Article')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>'添加成功,继续添加~','url'=>U('addarticle',array('tid'=>$data['tid']))));
				exit;
			}
			
			
			
		}
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->tid = $this->frparam('tid');
		$this->classtypes = $this->classtypetree;;
		$this->display('article-add');
	}
	public function editarticle(){
		$this->fields_biaoshi = 'article';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['addtime'] = strtotime($data['addtime']);
			$data['body'] = $this->frparam('body',4);

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
			
			$pclass = get_info_table('classtype',array('id'=>$data['tid']));
			$data['molds'] = $pclass['molds'];
			$data['htmlurl'] = $pclass['htmlurl'];
			$data = get_fields_data($data,'article');
			if($this->frparam('id')){
				if(M('Article')->update(array('id'=>$this->frparam('id')),$data)){
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
			$this->data = M('Article')->find(array('id'=>$this->frparam('id')));
		}
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		$this->classtypes = $this->classtypetree;;
		$this->display('article-edit');
		
	}
	function deletearticle(){
		$id = $this->frparam('id');
		if($id){
			if(M('Article')->delete('id='.$id)){
				//Success('删除成功！',U('index'));
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				//Error('删除失败！');
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	
	//复制文章
	function copyarticle(){
		$id = $this->frparam('id');
		if($id){
			$data = M('article')->find(['id'=>$id]);
			unset($data['id']);
			if(M('Article')->add($data)){
				
				JsonReturn(array('code'=>0,'msg'=>'复制成功！'));
				exit;
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>'复制失败！'));
				exit;
			}
			
			
		}
		
	}
	//批量删除文章
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if(M('article')->delete('id in('.$data.')')){
				JsonReturn(array('code'=>0,'msg'=>'批量删除成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量操作失败！'));
			}
		}
	}
	//批量复制文章
	function copyAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$list = M('article')->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				unset($v['id']);
				if(!M('Article')->add($v)){
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
	//批量修改栏目
	function changeType(){
		$data = $this->frparam('data',1);
		$tid = $this->frparam('tid');
		if($data!=''){
			$list = M('article')->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				$w['tid'] = $tid;
				$type = M('classtype')->find(array('id'=>$tid));
				$w['htmlurl'] = $type['htmlurl'];
				if(!M('Article')->update(array('id'=>$v['id']),$w)){
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
	function editArticleOrders(){
		$w['orders'] = $this->frparam('orders');
		
		$r = M('article')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>'修改失败！'));
		}
		JsonReturn(array('code'=>0,'info'=>'修改成功！'));
	}

	

	
	
	
	
	
	
	
	
	
	
}