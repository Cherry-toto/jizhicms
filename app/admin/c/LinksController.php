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

class LinksController extends CommonController
{
	
	public function index(){
		
		$classtypedata = $this->classtypedata;
		$molds = 'links';
		if($molds==''){
			Error(JZLANG('模块为空，请选择模块！'));
		}
		$this->molds = M('Molds')->find(array('biaoshi'=>$molds));
		$data = $this->frparam();
		$res = molds_search($molds,$data);
		$this->isshow = $this->frparam('isshow');
		$this->tid = $this->frparam('tid');
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>$molds,'islist'=>1),'orders desc,id asc');
		$this->classtypes = $this->classtypetree;
		if($this->frparam('ajax')){
			
			$sql = '1=1';
			if($this->admin['classcontrol']==1 && $this->admin['isadmin']!=1 && $this->molds['isclasstype']==1 && $this->molds['iscontrol']!=0){
				$a1 = explode(',',$this->tids);
				$a2 = array_filter($a1);
				$tids = implode(',',$a2);
				$sql.=' and tid in('.$tids.') ';
			}
			if($this->frparam('isshow')){
				if($this->frparam('isshow')==1){
					$isshow=1;
				}else if($this->frparam('isshow')==2){
					$isshow=0;
				}else{
					$isshow = 2;
				}
				$sql .= ' and isshow='.$isshow;
			}
			$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
			$sql .= $get_sql;
			if($this->frparam('tid')){
                $sql .= ' and tid in('.$this->frparam('tid').')';
				
			}
			
			$page = new Page($molds);
			$data = $page->where($sql)->orderby('orders desc,id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			foreach($data as $k=>$v){
				$v['new_tid'] = get_info_table('link_type',array('id'=>$v['tid']),'name');
				$v['new_isshow'] = $v['isshow']==1 ? JZLANG('已审') : ($v['isshow']==2 ? JZLANG('退回') : JZLANG('未审'));
				$v['view_url'] = $v['htmlurl']!='' ? get_domain().'/'.$v['htmlurl'].'/'.$v['id'] : '';
				$v['edit_url'] = U('Links/editlinks',array('id'=>$v['id'],'molds'=>$molds));
				
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
		
		
		
		
		$this->display('links-list');
		
	}
	
	public function addlinks(){
		$molds = 'links';
		$this->fields_biaoshi = $molds;
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['tid'] = $this->frparam('tid',0,0);
			
			
			$data = get_fields_data($data,$molds);
			
			//处理自定义URL
			if(isset($data['ownurl'])){
				if(M('customurl')->find(['molds'=>$molds,'url'=>$data['ownurl']])){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('已存在相同的自定义URL！')));
				}
				
			}
			$data['userid'] = $this->admin['id'];
			$data['molds'] = $molds;
			$r = M($molds)->add($data);
			if($r){
				if(isset($data['ownurl'])){
					M('customurl')->add(['molds'=>$molds,'tid'=>$data['tid'],'url'=>$data['ownurl'],'addtime'=>time(),'aid'=>$r]);
				}
				JsonReturn(array('code'=>0,'msg'=>JZLANG('添加成功,继续添加~'),'url'=>U('Links/addlinks',['tid'=>$data['tid'],'molds'=>$molds])));
				
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败！')));
				
			}
			
			
			
		}
		$this->classtypes = $this->classtypetree;
		$this->tid =  $this->frparam('tid',0,0);
		$this->molds = M('Molds')->find(array('biaoshi'=>$molds));
		
		$this->display('links-add');
	}
	
	public function editlinks(){
		$molds = 'links';
		$this->fields_biaoshi = $molds;
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data['tid'] = $this->frparam('tid',0,0);
			
			$data = get_fields_data($data,$molds);
			if($this->frparam('id')){
				
				//处理自定义URL
				if($data['ownurl']){
					$customurl = M('customurl')->find(['url'=>$data['ownurl']]);
					if($customurl){
						if($customurl['aid']!=$this->frparam('id')){
							JsonReturn(array('code'=>1,'msg'=>JZLANG('已存在相同的自定义URL！')));
						}else if($customurl['url']!=$data['ownurl']){
							M('customurl')->update(['molds'=>$molds,'tid'=>$data['tid'],'aid'=>$this->frparam('id')],['url'=>$data['ownurl']]);
						}
						
					}else{
						if(M('customurl')->find(['aid'=>$this->frparam('id')])){
							M('customurl')->update(['molds'=>$molds,'tid'=>$data['tid'],'aid'=>$this->frparam('id')],['url'=>$data['ownurl']]);
						}else{
							M('customurl')->add(['molds'=>$molds,'tid'=>$data['tid'],'url'=>$data['ownurl'],'addtime'=>time(),'aid'=>$this->frparam('id')]);
						}
					}
					
				}else{
					M('customurl')->delete(['molds'=>$molds,'aid'=>$this->frparam('id')]);
				}
				
				if(M($molds)->update(array('id'=>$this->frparam('id')),$data)){
					if($this->webconf['release_award_open']==1 && $data['isshow']==1){
						$award = round($this->webconf['release_award'],2);
						$max_award = round($this->webconf['release_max_award'],2);
						$member_id = M($molds)->getField(['id'=>$this->frparam('id')],'member_id');
						
						if($member_id!=0 && $award>0){
							$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>$molds,'aid'=>$this->frparam('id'),'msg'=>JZLANG('发布奖励')]);
							if(!$rr){
								$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
								$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

								$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='".JZLANG("发布奖励")."' ";
								$all = M('buylog')->findAll($sql,null,'amount');
								$all_jifen = 0;
								if($all){
									foreach($all as $v){
										$all_jifen+=$v['amount'];
									}
								}
								
								if($max_award==0 || ($all_jifen<$max_award && $max_award!=0)){
									$w['userid'] = $member_id;
		                			$w['buytype'] = 'jifen';
						   	  		$w['type'] = 3;
						   	  		$w['molds'] = $molds;
						   	  		$w['aid'] = $this->frparam('id');
						   	  		$w['msg'] = JZLANG('发布奖励');
						   	  		$w['addtime'] = time();
						   	  		$w['orderno'] = 'No'.date('YmdHis');
						   	  		$w['amount'] = $award;
						   	  		$w['money'] = $w['amount']/($this->webconf['money_exchange']);
						   	  		$r = M('buylog')->add($w);
						   	  		M('member')->goInc(['id'=>$member_id],'jifen',$award);
								}
							}
							
						}
					}
					JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
					
				}else{
					
					JsonReturn(array('code'=>1,'msg'=>JZLANG('您未做任何修改，不能提交！')));
					
				}
			
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('缺少ID')));
				
			}
			
			
			
		}
		$this->data = M($molds)->find(array('id'=>$this->frparam('id')));
		$this->molds = M('Molds')->find(array('biaoshi'=>$molds));
		$this->tid =  $this->data['tid'];
		$this->classtypetree =  get_classtype_tree();
		$this->classtypes = $this->classtypetree;
		$this->display('links-edit');
	}
	
	public function  copylinks(){
		$id = $this->frparam('id');
		$molds = 'links';
		if($id){
			$data = M($molds)->find(['id'=>$id]);
			unset($data['id']);
			if(M($molds)->add($data)){
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('复制成功！')));
				exit;
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>JZLANG('复制失败！')));
				exit;
			}
			
			
		}
	}
	
	//批量删除
	function deleteAll(){
		$data = $this->frparam('data',1);
		$molds = 'links';
		if($data!=''){
		    $list = M($molds)->findAll('id in('.$data.')');
			if(M($molds)->delete('id in('.$data.')')){
			    foreach ($list as $v){
                    $w['molds'] = 'links';
                    $w['data'] = serialize($v);
                    $w['title'] = '['.$v['id'].']'.$v['title'];
                    $w['addtime'] = time();
                    M('recycle')->add($w);
                }

				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
			}
		}
	}
	//单一删除
	function deletelinks(){
		$id = $this->frparam('id');
		$molds = 'links';
		if($id){
		    $data = M($molds)->find(['id'=>$id]);
			if(M($molds)->delete(['id'=>$id])){
                $w['molds'] = 'links';
                $w['data'] = serialize($data);
                $w['title'] = '['.$data['id'].']'.$data['title'];
                $w['addtime'] = time();
                M('recycle')->add($w);
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
	}
	
		//修改排序
	function editOrders(){

		$field = $this->frparam('field',1);
		$w[$field] = $this->frparam('value',1);
		$molds = 'links';
		$r = M($molds)->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));

	}
	//批量修改栏目
	function changeType(){
		$data = $this->frparam('data',1);
		$molds = $this->frparam('molds',1);
		$tid = $this->frparam('tid');
		if($data!=''){
			$list = M($molds)->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				$w['tid'] = $tid;
				M($molds)->update(array('id'=>$v['id']),$w);
			}
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量修改成功！')));
		}
	}
	//批量复制
	function copyAll(){
		$data = $this->frparam('data',1);
		$molds = 'links';
		if($data!=''){
			$list = M($molds)->findAll('id in('.$data.')');
			$r = true;
			foreach($list as $v){
				unset($v['id']);
				M($molds)->add($v);
			}
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量复制成功！')));
				
		}
	}

	//批量审核
	function checkAll(){
		$data = $this->frparam('data',1);
		$molds = $this->frparam('molds',1);
		if($data!=''){
			if($this->frparam('isshow')==1){
				$isshow = 1;
			}else if($this->frparam('isshow')==2){
				$isshow = 0;
			}else{
				$isshow = 2;
			}
			if($isshow==1){
				$all = M($molds)->findAll('id in('.$data.')');
				$award = round($this->webconf['release_award'],2);
				$max_award = round($this->webconf['release_max_award'],2);
				$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
				$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

				foreach ($all as $k => $v) {
					if($v['isshow']!=1){
						//start
						if($this->webconf['release_award_open']==1){
							$member_id = $v['member_id'];
							if($member_id!=0 && $award>0){
								$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>$molds,'aid'=>$v['id'],'msg'=>JZLANG('发布奖励')]);
								if(!$rr){
									
									$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='".JZLANG("发布奖励")."' ";
									$all = M('buylog')->findAll($sql,null,'amount');
									$all_jifen = 0;
									if($all){
										foreach($all as $vv){
											$all_jifen+=$vv['amount'];
										}
									}
									
									if($max_award==0 || ($all_jifen<$max_award && $max_award!=0)){
										$w['userid'] = $member_id;
			                			$w['buytype'] = 'jifen';
							   	  		$w['type'] = 3;
							   	  		$w['molds'] = $molds;
							   	  		$w['aid'] = $v['id'];
							   	  		$w['msg'] = JZLANG('发布奖励');
							   	  		$w['addtime'] = time();
							   	  		$w['orderno'] = 'No'.date('YmdHis');
							   	  		$w['amount'] = $award;
							   	  		$w['money'] = $w['amount']/($this->webconf['money_exchange']);
							   	  		$r = M('buylog')->add($w);
							   	  		M('member')->goInc(['id'=>$member_id],'jifen',$award);
									}
								}
								
							}
						}
						//end
					}
				}
				
			}
			M($molds)->update('id in('.$data.')',['isshow'=>$isshow]);
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量审核成功！')));
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('批量审核失败！')));
		}
	}

	function linktype(){
		$lists = M("link_type")->findAll();
		$this->lists = $lists;
		$this->display('linktype-list');
	}
	
	function linktypeadd(){
		if($this->frparam('go')==1){
			$data['name'] = $this->frparam('name',1);
				$data['addtime'] = time();
				if(M('link_type')->add($data)){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('新增成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('新增失败！')));
				}
		}
		$this->display('linktype-add');
	}

	function linktypeedit(){
		$id = $this->frparam('id');
		if($id){
			if($this->frparam('go')==1){
				$data['name'] = $this->frparam('name',1);
				$data['addtime'] = time();
				if(M('link_type')->update(array('id'=>$id),$data)){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败！')));
				}
			}
			$data =  M("link_type")->find(array('id'=>$id));
			$this->data = $data;
			$this->display('linktype-edit');
		}

	}

	function linktypedelete(){
		$id = $this->frparam('id');
		if($id){
			//检测该分类下是否存在内容
			$r = M('links')->getCount(array('tid'=>$id));
			if($r){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('该分类下存在内容，请先删除该分类下的内容！')));
			}

			if(M('link_type')->delete('id='.$id)){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
	}
	
	

}