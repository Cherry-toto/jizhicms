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
		$this->fields_list = M('Fields')->findAll(array('molds'=>'article','islist'=>1),'orders desc');
		$this->isshow = $this->frparam('isshow');
		$this->tid=  $this->frparam('tid');
		$this->title = $this->frparam('title',1);
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		$this->classtypes = $this->classtypetree;
		$data = $this->frparam();
		$res = molds_search('article',$data);
		$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
		$this->fields_search = $res['fields_search'];
		
		if($this->frparam('ajax')){
			$sql = ' 1=1 ';
			if($this->frparam('isshow')){
				$isshow = $this->frparam('isshow')==1 ? 1 : 0;
				$sql .= ' and isshow='.$isshow;
			}
			
			if($this->frparam('tid')){
				$sql .= ' and tid in('.implode(",",$classtypedata[$this->frparam('tid')]["children"]["ids"]).')';
			}
			
			
			$sql .= $get_sql;
			
			
			if($this->frparam('title',1)!=''){
				$sql.=" and title like '%".$this->frparam('title',1)."%' ";
			}
			if($this->frparam('shuxing')){
				if($this->frparam('shuxing')==1){
					$sql.=" and istop=1 ";
				}
				if($this->frparam('shuxing')==2){
					$sql.=" and ishot=1 ";
				}
				if($this->frparam('shuxing')==3){
					$sql.=" and istuijian=1 ";
				}
				
			}
			$data = $page->where($sql)->orderby('istop desc,orders desc,addtime desc,id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			foreach($data as $k=>$v){
				if($v['ishot']==1){
					$v['title'] = '<span class="layui-badge">热</span>'.$v['title'];
				}
				if($v['istuijian']==1){
					$v['title'] = '<span class="layui-badge layui-bg-green">荐</span>'.$v['title'];
				}
				if($v['istop']==1){
					$v['title'] = '<span class="layui-badge layui-bg-black">顶</span>'.$v['title'];
				}
				if(isset($classtypedata[$v['tid']])){
					$v['new_tid'] = $classtypedata[$v['tid']]['classname'];
				}else{
					$v['new_tid'] = '[未分类]';
				}
				
				$v['new_litpic'] = $v['litpic']!='' ? '<a href="'.$v['litpic'].'" target="_blank"><img src="'.$v['litpic'].'" width="100px" /></a>':'无';
				$v['new_isshow'] = $v['isshow']==1 ? '<span class="layui-badge layui-bg-green">显示</span>' : '<span class="layui-badge">不显示</span>';
				$v['new_addtime'] = date('Y-m-d H:i:s',$v['addtime']);
				$v['view_url'] = get_domain().'/'.$v['htmlurl'].'/'.$v['id'];
				$v['edit_url'] = U('Article/editarticle',array('id'=>$v['id']));
				
				foreach($this->fields_list as $vv){
					$v[$vv['field']] = format_fields($vv,$v[$vv['field']]);
				}
				$ajaxdata[]=$v;
				
			}
			$pages = $page->pageList();
			$this->pages = $pages;
			$this->lists = $data;
			$this->sum = $page->sum;
			
			JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
			
		}
		
		
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
			$data['istop'] = $this->frparam('istop',0,0);
			$data['ishot'] = $this->frparam('ishot',0,0);
			$data['istuijian'] = $this->frparam('istuijian',0,0);
			$data = get_fields_data($data,'article');
			if($data['tags']!=''){
				$data['tags'] = ','.$data['tags'].',';
			}
			if(M('Article')->add($data)){
				//tags处理
				if($data['tags']!=''){
					$tags = explode(',',$data['tags']);
					foreach($tags as $v){
						if($v!=''){
							$r = M('tags')->find(['keywords'=>$v]);
							if(!$r){
								$w['keywords'] = $v;
								$w['newname'] = '';
								$w['url'] = '';
								$w['num'] = -1;
								$w['isshow'] = 1;
								$w['number'] = 1;
								$w['target'] = '_blank';
								M('tags')->add($w);
							}else{
								M('tags')->goInc(['keywords'=>$v],'number',1);
							}
						}
					}
				}
				
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
			$data['istop'] = $this->frparam('istop',0,0);
			$data['ishot'] = $this->frparam('ishot',0,0);
			$data['istuijian'] = $this->frparam('istuijian',0,0);
			$data = get_fields_data($data,'article');
			if($data['tags']!=''){
				$data['tags'] = ','.$data['tags'].',';
			}
			if($this->frparam('id')){
				$old_tags = M('Article')->getField(['id'=>$this->frparam('id')],'tags');
				if(M('Article')->update(array('id'=>$this->frparam('id')),$data)){
					if($old_tags!=$data['tags']){
						
						$a = $old_tags.$data['tags'];
						$new = [];
						$a = explode(',',$a);
						foreach($a as $v){
							if($v!='' && !in_array($v,$new)){
								
								$r = M('tags')->find(['keywords'=>$v]);
								if(!$r){
									$w['keywords'] = $v;
									$w['newname'] = '';
									$w['url'] = '';
									$w['num'] = -1;
									$w['isshow'] = 1;
									$w['number'] = 1;
									$w['target'] = '_blank';
									M('tags')->add($w);
								}else{
									
									$num1 = M('article')->getCount(" tags like '%,".$v.",%' ");
									$num2 = M('product')->getCount(" tags like '%,".$v.",%' ");
									M('tags')->update(['keywords'=>$v],['number'=>$num1+$num2]);
								}
								
								$new[]=$v;
							}
						}
						
						
						
						
					}
					
					JsonReturn(array('code'=>0,'msg'=>'修改成功！','url'=>U('index')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
				}
			}
			
			
			
		}
		if($this->frparam('id')){
			$this->data = M('Article')->find(array('id'=>$this->frparam('id')));
		}
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		$this->classtypes = $this->classtypetree;;
		$this->display('article-edit');
		
	}
	function deletearticle(){
		$id = $this->frparam('id');
		if($id){
			if(M('Article')->delete('id='.$id)){
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
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
			}else{
				JsonReturn(array('code'=>1,'msg'=>'复制失败！'));
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
				M('Article')->add($v);
			}
			JsonReturn(array('code'=>0,'msg'=>'批量复制成功！'));
				
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
				M('Article')->update(array('id'=>$v['id']),$w);
			}
			JsonReturn(array('code'=>0,'msg'=>'批量修改成功！'));
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
	//批量修改推荐属性
	function changeAttribute(){
		$data = $this->frparam('data',1);
		$tj = $this->frparam('tj');
		if($data!=''){
			$list = M('article')->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				if($tj==1){
				   $w['istop'] = $v['istop']==1 ? 0 : 1;
				}
				if($tj==2){
				   $w['ishot'] = $v['ishot']==1 ? 0 : 1;
				}
				if($tj==3){
				   $w['istuijian'] = $v['istuijian']==1 ? 0 : 1;
				}
				
				
				M('Article')->update(array('id'=>$v['id']),$w);
			}
			JsonReturn(array('code'=>0,'msg'=>'批量修改成功！'));
		}
	}

	
	
	
	
	
	
	
	
	
	
}