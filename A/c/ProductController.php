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
		
		$classtypedata = $this->classtypedata;
		$this->molds = M('molds')->find(['biaoshi'=>'product']);
		$this->tid=  $this->frparam('tid');
		$this->title = $this->frparam('title',1);
		$this->isshow = $this->frparam('isshow');
		$data = $this->frparam();
		$res = molds_search('product',$data);
		$this->classtypes = $this->classtypetree;
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>'product','islist'=>1),'orders desc');
		if($this->frparam('ajax')){
			
			$page = new Page('product');
			$sql = ' 1=1 ';
			if($this->admin['classcontrol']==1 && $this->admin['isadmin']!=1 && $this->molds['iscontrol']!=0 && $this->molds['isclasstype']==1){
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
			if($this->frparam('tid')){
				$sql .= ' and tid in('.implode(",",$classtypedata[$this->frparam('tid')]["children"]["ids"]).')';
			}
			$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
			$sql .= $get_sql;
			
			if($this->frparam('title',1)){
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
			$data = $page->where($sql)->orderby('istop desc,orders desc,id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			
			$ajaxdata = [];
			foreach($data as $k=>$v){
				if($v['ishot']==1){
					$v['tuijian'] = '热';
				}else if($v['istuijian']==1){
					$v['tuijian'] = '荐';
				}else if($v['istop']==1){
					$v['tuijian'] = '顶';
				}else{
					$v['tuijian'] = '无';
				}
				if(isset($classtypedata[$v['tid']])){
					$v['new_tid'] = $classtypedata[$v['tid']]['classname'];
				}else{
					$v['new_tid'] = '[未分类]';
				}
				
				$v['new_litpic'] = $v['litpic']=='' ? '' : get_domain().$v['litpic'];
				$v['new_addtime'] = "\t".date('Y-m-d H:i:s',$v['addtime'])."\t";
				$v['view_url'] = gourl($v,$v['htmlurl']);
				$v['edit_url'] = U('Product/editproduct',array('id'=>$v['id']));
				
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
			$data['title'] = $this->frparam('title',1);
			$data['keywords'] = $this->frparam('keywords',1);
			$data['seo_title'] = $this->frparam('seo_title',1) ? $this->frparam('seo_title',1) : $this->frparam('title',1);
			$data['description'] = ($this->frparam('description',1)=='') ? newstr(strip_tags($data['body']),160) : $this->frparam('description',1);
			if(strlen($data['description'])>255){
				$data['description'] = newstr($data['description'],160);
			}
			
			if($this->frparam('litpic',1)==''){
				$pattern='/<img.*?src="(.*?)".*?>/is';
				if($this->frparam('body',1)!=''){
					$r = preg_match($pattern,$_POST['body'],$matchContent);
					if($r){
						$data['litpic'] = $matchContent[1];
					}else{
						$data['litpic'] = '';
					}
					
				}else{
					$data['litpic'] = '';
				}
			}
			
			if($this->webconf['ispicsdes']==1){
				if(array_key_exists('pictures_urls',$data) && $data['pictures_urls']!=''){
					
					$pics =  $this->frparam('pictures_urls',2);
					$pics_des = $this->frparam('pictures_des',2);
					foreach($pics as $k=>$v){
						if($pics_des[$k]){
							$pics[$k] = $v.'|'.$pics_des[$k];
						}
					}
					$data['pictures'] = implode('||',$pics);
					 
				}else{
					$data['pictures'] = '';
				}
			}else{
				if(array_key_exists('pictures_urls',$data) && $data['pictures_urls']!=''){
					 $data['pictures'] = implode('||',format_param($data['pictures_urls'],2));
				}else{
					$data['pictures'] = '';
				}
			}
			
			$pclass = get_info_table('classtype',array('id'=>$data['tid']));
			$data['molds'] = $pclass['molds'];
			$data['htmlurl'] = $pclass['htmlurl'];
			$data['istop'] = $this->frparam('istop',0,0);
			$data['ishot'] = $this->frparam('ishot',0,0);
			$data['istuijian'] = $this->frparam('istuijian',0,0);
			$data = get_fields_data($data,'product');
			$data['tags'] = $data['tags'] ? $data['tags'] : str_replace('，',',',$data['keywords']);
			if($data['tags']!=''){
				$data['tags'] = ','.$data['tags'].',';
			}
			//处理自定义URL
			if($data['ownurl']){
				if(M('customurl')->find(['molds'=>'product','url'=>$data['ownurl']])){
					JsonReturn(array('code'=>1,'msg'=>'已存在相同的自定义URL！'));
				}
				
			}
			if($this->admin['isadmin']==1 || ($this->admin['isadmin']!=1 && $this->admin['ischeck']==0)){
				$data['isshow'] = $this->frparam('isshow',0,1);
			}else{
				$data['isshow'] = 0;
			}
			$r = M('product')->add($data);
			if($r){
				if($data['ownurl']){
					M('customurl')->add(['molds'=>'product','tid'=>$data['tid'],'url'=>$data['ownurl'],'addtime'=>time(),'aid'=>$r]);
				}
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
				JsonReturn(array('code'=>0,'msg'=>'添加成功,继续添加~','url'=>U('addproduct',array('tid'=>$data['tid']))));
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
			$data['title'] = $this->frparam('title',1);
			$data['tid'] = $this->frparam('tid',0,0);
			$data['keywords'] = $this->frparam('keywords',1);
			$data['seo_title'] = $this->frparam('seo_title',1) ? $this->frparam('seo_title',1) : $this->frparam('title',1);
			$data['description'] = ($this->frparam('description',1)=='') ? newstr(strip_tags($data['body']),160) : $this->frparam('description',1);
			if(strlen($data['description'])>255){
				$data['description'] = newstr($data['description'],160);
			}
			
			if($this->frparam('litpic',1)==''){
				$pattern='/<img.*?src="(.*?)".*?>/is';
				if($this->frparam('body',1)!=''){
					$r = preg_match($pattern,$_POST['body'],$matchContent);
					if($r){
						$data['litpic'] = $matchContent[1];
					}else{
						$data['litpic'] = '';
					}
					
				}else{
					$data['litpic'] = '';
				}
			}
			if($this->webconf['ispicsdes']==1){
				if(array_key_exists('pictures_urls',$data) && $data['pictures_urls']!=''){
					
					$pics =  $this->frparam('pictures_urls',2);
					$pics_des = $this->frparam('pictures_des',2);
					foreach($pics as $k=>$v){
						if($pics_des[$k]){
							$pics[$k] = $v.'|'.$pics_des[$k];
						}
					}
					$data['pictures'] = implode('||',$pics);
					 
				}else{
					$data['pictures'] = '';
				}
			}else{
				if(array_key_exists('pictures_urls',$data) && $data['pictures_urls']!=''){
					 $data['pictures'] = implode('||',format_param($data['pictures_urls'],2));
				}else{
					$data['pictures'] = '';
				}
			}
			
			$pclass = get_info_table('classtype',array('id'=>$data['tid']));
			$data['molds'] = $pclass['molds'];
			$data['htmlurl'] = $pclass['htmlurl'];
			$data['istop'] = $this->frparam('istop',0,0);
			$data['ishot'] = $this->frparam('ishot',0,0);
			$data['istuijian'] = $this->frparam('istuijian',0,0);
			$data = get_fields_data($data,'product');
			$data['tags'] = $data['tags'] ? $data['tags'] : str_replace('，',',',$data['keywords']);
			if($data['tags']!=''){
				$data['tags'] = ','.$data['tags'].',';
			}
			if($this->frparam('id')){
				
				$old_tags = M('product')->getField(['id'=>$this->frparam('id')],'tags');
				//处理自定义URL
				
				if($data['ownurl']){
					$customurl = M('customurl')->find(['url'=>$data['ownurl']]);
					if($customurl){
						if($customurl['aid']!=$this->frparam('id')){
							JsonReturn(array('code'=>1,'msg'=>'已存在相同的自定义URL！'));
						}else if($customurl['url']!=$data['ownurl']){
							M('customurl')->update(['tid'=>$data['tid'],'aid'=>$this->frparam('id')],['url'=>$data['ownurl'],'molds'=>'product']);
						}
						
					}else{
						if(M('customurl')->find(['aid'=>$this->frparam('id'),'molds'=>'product'])){
							M('customurl')->update(['aid'=>$this->frparam('id'),'molds'=>'product'],['url'=>$data['ownurl'],'molds'=>'product','tid'=>$data['tid']]);
						}else{
							M('customurl')->add(['molds'=>'product','tid'=>$data['tid'],'url'=>$data['ownurl'],'addtime'=>time(),'aid'=>$this->frparam('id')]);
						}
					}
					
				}else{
					M('customurl')->delete(['molds'=>'product','aid'=>$this->frparam('id')]);
				}
				if($this->admin['isadmin']==1 || ($this->admin['isadmin']!=1 && $this->admin['ischeck']==0)){
					$data['isshow'] = $this->frparam('isshow',0,1);
				}else{
					$data['isshow'] = 0;
				}
				if(M('product')->update(array('id'=>$this->frparam('id')),$data)){
					//tags处理
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
									if(strpos(','.$v.',',$old_tags)===false){
										M('tags')->goInc(['keywords'=>$v],'number');
									}else if(strpos(','.$v.',',$data['tags'])===false){
										M('tags')->goDec(['keywords'=>$v],'number');
									}
								}
								
								$new[]=$v;
							}
						}
						
						
						
						
					}
					if($this->webconf['release_award_open']==1 && $data['isshow']==1){
						$award = round($this->webconf['release_award'],2);
						$max_award = round($this->webconf['release_max_award'],2);
						$member_id = M('product')->getField(['id'=>$this->frparam('id')],'member_id');
						
						if($member_id!=0 && $award>0){
							$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>'product','aid'=>$this->frparam('id'),'msg'=>'发布奖励']);
							if(!$rr){
								$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
								$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

								$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='发布奖励' ";
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
						   	  		$w['molds'] = 'product';
						   	  		$w['aid'] = $this->frparam('id');
						   	  		$w['msg'] = '发布奖励';
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
					
					JsonReturn(array('code'=>0,'msg'=>'修改成功！','url'=>U('index')));
					exit;
				}else{
					
					JsonReturn(array('code'=>1,'msg'=>'您未做任何修改，不能提交！'));
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
				M('customurl')->delete(['molds'=>'product','aid'=>$id]);
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
				M('customurl')->delete(" aid in(".$data.") and molds='product' ");
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
				M('product')->update(array('id'=>$v['id']),$w);
			}
			JsonReturn(array('code'=>0,'msg'=>'批量修改成功！'));
		}
	}
	//修改排序
	function editProductOrders(){
		$field = $this->frparam('field',1);
		$w[$field] = $this->frparam('value',1);
		$r = M('product')->update(array('id'=>$this->frparam('id')),$w);
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
			$list = M('Product')->findAll('id in('.$data.')');
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
				
				
				M('Product')->update(array('id'=>$v['id']),$w);
			}
			JsonReturn(array('code'=>0,'msg'=>'批量修改成功！'));
		}
	}

	//批量审核
	function checkAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if($this->frparam('isshow')==1){
				$isshow = 1;
			}else if($this->frparam('isshow')==2){
				$isshow = 0;
			}else{
				$isshow = 2;
			}
			if($isshow==1){
				$all = M('product')->findAll('id in('.$data.')');
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
								$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>'product','aid'=>$v['id'],'msg'=>'发布奖励']);
								if(!$rr){
									
									$sql = " addtime>=".$start." and addtime<".$end." and userid=".$member_id." and type=3 and msg='发布奖励' ";
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
							   	  		$w['molds'] = 'product';
							   	  		$w['aid'] = $v['id'];
							   	  		$w['msg'] = '发布奖励';
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
			M('product')->update('id in('.$data.')',['isshow'=>$isshow]);
			JsonReturn(array('code'=>0,'msg'=>'批量操作成功！'));
		}else{
			JsonReturn(array('code'=>1,'msg'=>'批量操作失败！'));
		}
	}


	
	
	
	
	
	
	
	
	
	
}