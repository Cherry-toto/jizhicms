<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/10/18
// +----------------------------------------------------------------------


namespace Home\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;
use FrPHP\Extend\ArrayPage;

class TagsController extends CommonController
{
	function index(){
		
		$keywords = $this->frparam('tagname',1);
		$id = $this->frparam('id');
		if($keywords || $id){
			if($id){
				$keywords = M('tags')->getField(['id'=>$id,'isshow'=>1],'keywords');
				if(!$keywords){
					Error('标签未找到或已删除！');
				}
			}
			$this->tagname = $keywords;
			$this->tags = M('tags')->find(['keywords'=>$keywords,'isshow'=>1]);
			if(!$this->tags){
				Error('标签未找到或已删除！');
			}
			$lists_1 = M('article')->findAll(" tags like '%,".$keywords.",%' and isshow=1");
			$lists_2 = M('product')->findAll(" tags like '%,".$keywords.",%' and isshow=1");
			
			$lists = ($lists_1 && count($lists_1) > 0) ? array_merge($lists_1,$lists_2) : array_merge($lists_2,$lists_1);
			if($lists){
				$arraypage = new \ArrayPage($lists);
				$data = $arraypage->setPage(['limit'=>100])->go();
				
				foreach($data as $k=>$v){
					$data[$k]['url'] = gourl($v['id'],$v['htmlurl']);
					$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
				}
			
				$this->pages = $arraypage->pageList();
				$this->lists = $data;
			}else{
				$this->pages = [];
				$this->lists = [];
			}
			
			
			if($this->frparam('ajax')){
				if($this->frparam('ajax_tpl')){
					$this->display($this->template.'/ajax_tags_list');
					exit;
				}
				JsonReturn(['code'=>0,'data'=>$data]);
			}
			
			
			$this->display($this->template.'/tags-details');
		}else{
			
			//$this->lists = M('tags')->findAll(['isshow'=>1]);
			$sql = 'isshow=1';
			$page = new Page('tags');
			
			//手动设置分页条数
			$limit = 10;
			if($this->frparam('limit')){
				$limit = $this->frparam('limit');
			}
			//只适合article和product
			$data = $page->where($sql)->orderby('orders desc,id desc')->limit($limit)->page($this->frpage)->go();
			
			
			$pages = $page->pageList(3,'-');
			
			$this->pages = $pages;//组合分页
			
			foreach($data as $k=>$v){
				$data[$k]['url'] = U('tags/index',['id'=>$v['id']]);
				
			}
			
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
			
			$this->display($this->template.'/tags');
		}
		
		
	}
	
	
	

	
	
}