<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/01-2019/02
// +----------------------------------------------------------------------


namespace A\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class ClasstypeController extends CommonController
{


	function index(){
		//$sql = null;
		//栏目不需要搜索
		// $data = $this->frparam();
		// $res = molds_search('classtype',$data);
		// $sql = ($res['fields_search_check']!='')?$res['fields_search_check']:null;
		// $this->fields_search = $res['fields_search'];
		//$classtype = M('classtype')->findAll($sql,'orders desc');
		//$classtype = getTree($classtype);
		$this->classtypes = $this->classtypetree;
		//模块
		$molds = M('Molds')->findAll(['isopen'=>1]);
		$fs = array();
		foreach($molds as $v){
			$fs[$v['biaoshi']] = $v;
		}
		$this->molds = M('molds')->find(['biaoshi'=>'classtype']);
		
		$this->moldslist = $fs;
		$this->display('classtype-list');
	}
	
	function addclass(){
		$this->fields_biaoshi = 'classtype';
		
		if($this->frparam('go')==1){

			$htmlurl = $this->frparam('htmlurl',1);
			if($htmlurl==''){
				$htmlurl = str_replace(' ','',pinyin($this->frparam('classname',1)));
			}
			if($this->webconf['islevelurl'] && $this->frparam('pid')!=0){
				//层级
				$classtypetree = classTypeData();
				$htmlurl = $classtypetree[$this->frparam('pid')]['htmlurl'].'/'.$htmlurl;
			}
			
			

			$w['pid'] = $this->frparam('pid');
			$w['orders'] = $this->frparam('orders');
			$w['classname'] = $this->frparam('classname',1);
			$w['molds'] = $this->frparam('molds',1);
			$w['description'] = $this->frparam('description',1);
			$w['keywords'] = $this->frparam('keywords',1);
			$w['litpic'] = $this->frparam('litpic',1);
			$w['body'] = $this->frparam('body',4);
			$w['htmlurl'] = $htmlurl;
			$w['iscover'] = $this->frparam('iscover');
			$w['lists_html'] = $this->frparam('lists_html',1);
			$w['details_html'] = $this->frparam('details_html',1);
			$w['gourl'] = $this->frparam('gourl',1);
			$w['lists_num'] = $this->frparam('lists_num');
			if($w['lists_html']=='' && $w['details_html']==''){
				$parent = M('classtype')->find(array('id'=>$w['pid']));
				if($parent['iscover']==1){
					$w['lists_html']=$parent['lists_html'];
					$w['details_html']=$parent['details_html'];
					$w['lists_num']=$parent['lists_num'];
				}
			}
			
			
			$data = $this->frparam();
			$data = get_fields_data($data,'classtype');
			$w = array_merge($data,$w);
			$a = M('classtype')->add($w);
			if($a){
				
				setCache('classtypetree',null);
				setCache('classtype',null);
				setCache('mobileclasstype',null);
				JsonReturn(array('status'=>1,'info'=>'添加栏目成功，继续添加~','url'=>U('addclass',array('pid'=>$w['pid'],'biaoshi'=>$w['molds']))));
			}else{
				JsonReturn(array('status'=>0,'info'=>'新增失败！'));
			}
		}
		//模块
		$this->molds = M('Molds')->findAll(['isopen'=>1]);
		
		$this->pid = $this->frparam('pid');
		$this->biaoshi = $this->frparam('biaoshi',1);
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->classtypes = $this->classtypetree;;
			//var_dump($this->classtypes);
		$this->display('classtype-add');
		
	}
	function editclass(){
		$this->data = M('classtype')->find(array('id'=>$this->frparam('id')));
		$this->fields_biaoshi = 'classtype';
		if($this->frparam('go')==1){
			$htmlurl = $this->frparam('htmlurl',1);
			if($htmlurl==''){
				$htmlurl = str_replace(' ','',pinyin($this->frparam('classname',1)));
			}
			
			
			$w['pid'] = $this->frparam('pid');
			$w['orders'] = $this->frparam('orders');
			$w['classname'] = $this->frparam('classname',1);
			$w['molds'] = $this->frparam('molds',1);
			$w['description'] = $this->frparam('description',1);
			$w['keywords'] = $this->frparam('keywords',1);
			$w['id'] = $this->frparam('id');
			$w['litpic'] = $this->frparam('litpic',1);
			$w['body'] = $this->frparam('body',4);
			$w['htmlurl'] = $htmlurl;
			$w['iscover'] = $this->frparam('iscover');
			$w['lists_html'] = $this->frparam('lists_html',1);
			$w['details_html'] = $this->frparam('details_html',1);
			$w['lists_num'] = $this->frparam('lists_num');
			$w['gourl'] = $this->frparam('gourl',1);
			
			
			
			$data = $this->frparam();
			$data = get_fields_data($data,'classtype');
			$w = array_merge($data,$w);
			
			//检测pid是否为该栏目下级
			if(checkClass($w['pid'],$this->data['id']) || ($w['pid']==$this->data['id'])){
				JsonReturn(array('status'=>0,'info'=>'不能选择当前栏目及下级为顶级栏目'));
			}
			
			
			$a = M('classtype')->update(array('id'=>$w['id']),$w);
			if($a){
				if($w['iscover']==1){
					$children = M('classtype')->update(array('pid'=>$w['id']),array('lists_html'=>$w['lists_html'],'details_html'=>$w['details_html'],'lists_num'=>$w['lists_num']));
				}
				//批量修改栏目对应的模块内容htmlurl
				if($this->data['htmlurl']!=$data['htmlurl']){
					M($data['molds'])->update(array('tid'=>$data['id']),array('htmlurl'=>$data['htmlurl']));
				}
				setCache('classtypetree',null);
				setCache('classtype',null);
				setCache('mobileclasstype',null);
				JsonReturn(array('status'=>1));
			}else{
				JsonReturn(array('status'=>0,'info'=>'修改失败！'));
			}
		}
		
		//模块
		$this->molds = M('Molds')->findAll(['isopen'=>1]);
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
	
		$this->classtypes = $this->classtypetree;;
		$this->display('classtype-edit');
		
	}
	
	function editClassOrders(){
		$w['orders'] = $this->frparam('orders');
		
		$r = M('classtype')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>'修改失败！'));
		}
		setCache('classtypetree',null);
		setCache('classtype',null);
		setCache('mobileclasstype',null);
		JsonReturn(array('code'=>0,'info'=>'修改成功！'));
		
	}
	function deleteclass(){
		$id = $this->frparam('id');
		if($id){
			//检测栏目是否有下级
			if(M('classtype')->find(['pid'=>$id])){
				JsonReturn(array('status'=>0,'info'=>'该栏目有子栏目，请先删除子栏目！'));
			}
			
			$a = M('classtype')->delete(array('id'=>$id));
			if($a){
				setCache('classtypetree',null);
				setCache('classtype',null);
				setCache('mobileclasstype',null);
				JsonReturn(array('status'=>1));
			}else{
				JsonReturn(array('status'=>0,'info'=>'删除失败！'));
			}
		}
		
	}
	function change_status(){
		$id = $this->frparam('id',1);
		if(!$id){
			JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
		}
		
		$x = M('Classtype')->find('id='.$id);
		if($x['isshow']==1){
			$x['isshow']=0;
		}else{
			$x['isshow']=1;
		}
		M('Classtype')->update(array('id'=>$id),array('isshow'=>$x['isshow']));
		setCache('classtypetree',null);
		setCache('classtype',null);
		setCache('mobileclasstype',null);
	}
	
	function get_pinyin(){
		
		$classname = $this->frparam('classname',1);
		if($classname){
			$data = pinyin($classname,'first');
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
	}
	
	function addmany(){
		if($_POST){
			$data_0 = $this->frparam('data_0',2);
			$data_1 = $this->frparam('data_1',2);
			$data_2 = $this->frparam('data_2',2);
			$data_3 = $this->frparam('data_3',2);
			$data_4 = $this->frparam('data_4',2);
			$data_5 = $this->frparam('data_5',2);
			$data_6 = $this->frparam('data_6',2);
			$data_7 = $this->frparam('data_7',2);
			$data_8 = $this->frparam('data_8',2);
			$classtypetree = classTypeData();
			foreach($data_1 as $k=>$v){
				if($v && $v!=''){
					
					$w['molds'] = $data_0[$k];
					$w['classname'] = $v;
					$w['pid'] = $data_2[$k];
					if($this->webconf['islevelurl'] && $w['pid']!=0){
						//层级
						$html = $classtypetree[$w['pid']]['htmlurl'].'/'.$data_3[$k];
					}else{
						$html = $data_3[$k];
					}
							
					$w['htmlurl'] = $html;
					$w['lists_num'] = $data_4[$k];
					$w['lists_html'] = $data_5[$k];
					$w['details_html'] = $data_6[$k];
					$w['isshow'] = $data_7[$k];
					$w['orders'] = $data_8[$k];
					M('classtype')->add($w);
					$w = [];
				}
				
				
			}
			setCache('classtypetree',null);
			setCache('classtype',null);
			setCache('mobileclasstype',null);
			JsonReturn(['code'=>0,'msg'=>'success']);
		}
		$this->molds = M('molds')->find(['biaoshi'=>'classtype']);
		$this->moldslist = M('molds')->findAll(['isopen'=>1]);
		$this->classtypes = $this->classtypetree;;
		
		$this->display('classtype-addmany');
		
		
	}
	
	
}
	