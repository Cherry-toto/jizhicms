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


namespace app\home\c;


use frphp\extend\Page;
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
					Error(JZLANG('标签未找到或已删除！'));
				}
			}
			$this->tagname = $keywords;
			$sql = "keywords='".$keywords."' and isshow=1  ";
			$this->tags = M('tags')->find($sql);
			if(!$this->tags){
				Error(JZLANG('标签未找到或已删除！'));
			}
			
			$tables = isset($this->webconf['tag_table']) ? ($this->webconf['tag_table'] ? explode('|',$this->webconf['tag_table']) : ['article','product']) : ['article','product'];
			$sqlx = [];
			foreach($tables as $v){
				$sqlx[] = " select id,tid,litpic,title,hits,tags,keywords,molds,htmlurl,ownurl,description,body,addtime,userid,member_id from ".DB_PREFIX.$v." where tags like '%,".$keywords.",%' and isshow=1 ";
			}
			$sql = implode(' union all ',$sqlx);
			$page = new Page();
			$this->currentpage = $this->frpage;
			$data = $page->where($sql)->limit($this->frparam('limit',0,15))->page($this->frpage)->goSql();
			foreach($data as $k=>$v){
				$data[$k]['url'] = gourl($v,$v['htmlurl']);
                $data[$k]['class_name'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['classname'] : '';
                $data[$k]['class_url'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['url'] : '';
                $data[$k]['class_litpic'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['litpic'] : '';
                $data[$k]['format_addtime'] = isset($v['addtime']) ? date('Y-m-d H:i:s',$v['addtime']) : '';
			}
			$pages = $page->pageList(5,'/page/');
			$this->pages = $pages;//组合分页
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
			
			if($this->frparam('ajax')){
				if($this->frparam('ajax_tpl')){
					$this->display($this->template.'/ajax_tags_list');
					exit;
				}
				
				JsonReturn(['code'=>0,'data'=>$data]);
			}
			
			
			$this->display($this->template.'/tags-details');
		}else{

			$sql = ' isshow=1  ';
			$page = new Page('tags');
			
			//手动设置分页条数
			$limit = 50;
			if($this->frparam('limit')){
				$limit = $this->frparam('limit');
			}
			//只适合article和product
			$data = $page->where($sql)->orderby('orders desc,id desc')->limit($limit)->page($this->frpage)->go();
			
			
			$pages = $page->pageList(5,'/page/');
			
			$this->pages = $pages;//组合分页
			
			foreach($data as $k=>$v){
				$data[$k]['url'] = U('tags/index',['id'=>$v['id']]);
				
			}
            if($this->frparam('ajax')){
                JsonReturn(['code'=>0,'data'=>$data]);
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