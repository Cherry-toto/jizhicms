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

class ArticleController extends CommonController
{
	
	
	//内容管理
	function articlelist(){
		$page = new Page('Article');
		$this->fields_list = M('Fields')->findAll(array('molds'=>'article','islist'=>1),'listorders desc');
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
			if($this->admin['classcontrol']==1 && $this->admin['isadmin']!=1 && $this->molds['iscontrol']!=0 && $this->molds['isclasstype']==1){
				$a1 = explode(',',$this->tids);
				$a2 = array_filter($a1);
				$tids = implode(',',$a2);
				$sql.=' and tid in('.$tids.') ';


			}
			$sql .= $get_sql;
			$data = $page->where($sql)->orderby('orders desc,id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			foreach($data as $k=>$v){
				
				$v['view_url'] = gourl($v,$v['htmlurl']);
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
			$data = get_fields_data($data,'article');
            check_field_must($data,'article');
			if(!$this->frparam('seo_title',1) && $this->frparam('config_seotitle')==1){
				$data['seo_title'] = $data['title'];
			}
			if(!$this->frparam('description',1) && $this->frparam('config_description')==1){
				$data['description'] = newstr(strip_tags($_POST['body']),200);
			}
			$water_models = explode(',',$this->webconf['text_molds']);
			if(in_array('article',$water_models)){
                if(!$this->frparam('litpic',1) && $this->webconf['text_waterlitpic'] && $this->webconf['text_litpic']){
                    $data['litpic'] = waterwordmark($data['title'],APP_PATH.$this->webconf['text_litpic']);
                    //存储
                    $filesize = round(filesize(APP_PATH.$data['litpic'])/1024,2);
                    $pix_arr = explode('.',$data['litpic']);
                    $pix = end($pix_arr);
                    M('pictures')->add(['litpic'=>$data['litpic'],'addtime'=>time(),'userid'=>$_SESSION['admin']['id'],'size'=>$filesize,'filetype'=>strtolower($pix),'tid'=>$this->frparam('tid'),'molds'=>'article']);
                }else if($this->frparam('litpic',1) && $this->webconf['text_waterlitpic']){
                    $data['litpic'] = waterwordmark($data['title'],APP_PATH.$this->frparam('litpic',1),0);
                }
            }
			
			if(!$data['litpic'] && $this->frparam('config_litpic')==1){
				$pattern='/<img.*?src="(.*?)".*?>/is';
				if($this->frparam('body',1)){
					$r = preg_match($pattern,stripslashes($data['body']),$matchContent);
					if($r){
						$data['litpic'] = $matchContent[1];
					}else{
						$data['litpic'] = '';
					}
					
				}else{
					$data['litpic'] = '';
				}
			}
			if(!$this->frparam('tags',1) && $this->frparam('config_tags')==1){
				$data['tags'] = str_replace('，',',',$data['keywords']);
				if($data['tags']){
					$data['tags'] = ','.$data['tags'].',';
				}
			}
			
			$data['userid'] = $_SESSION['admin']['id'];
			$data['htmlurl'] = $data['tid'] ? $this->classtypedata[$data['tid']]['htmlurl'] : null;
			
			//违禁词检测
			if($this->webconf['mingan'] && $this->frparam('config_filter',1)){
				$mingan = explode(',',$this->webconf['mingan']);
				$filter = explode(',',$this->frparam('config_filter',1));
				$fields = $this->getTableFields('article');
				foreach($mingan as $s){
					if(strpos($s,'{xxx}')!==false){
						$pattern = '/'.str_replace('{xxx}','(.*)',$s).'/';
						foreach($filter as $vv){
							if($vv && preg_match($pattern, $data[$vv])){
								JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败').'，【'.$fields[$vv].'】'.JZLANG('存在敏感词').' [ '.$s.' ]'));
							}
							
						}
						

					}else{
						foreach($filter as $vv){
							if($vv && strpos($data[$vv],$s)!==false ){
								JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败').'，【'.$fields[$vv].'】'.JZLANG('存在敏感词').' [ '.$s.' ]'));
							}
							
						}
						
					}
				}
			}
			//处理自定义URL
			if($data['ownurl']){
				if(M('customurl')->find(['url'=>$data['ownurl']])){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('已存在相同的自定义URL！')));
				}
				
			}
			
			if($this->admin['isadmin']==1 || ($this->admin['isadmin']!=1 && $this->admin['ischeck']==0)){
				$data['isshow'] = $this->frparam('isshow',0,1);
			}else{
				$data['isshow'] = 0;
			}
			$data['addtime'] =  isset($data['addtime']) ? $data['addtime'] : time();
            //检查是否重复
            if($this->webconf['hidetitleonliy']){
                $hidetitleonly = explode('|',$this->webconf['hidetitleonliy']);
                $onliyfield = '';
                foreach ($hidetitleonly as $s){
                    $d = explode('-',$s);
                    if(strtolower($d[0])=='article'){
                        $onliyfield = strtolower($d[1]);
                        break;
                    }
                }
                if($onliyfield){
                    if(M('article')->find([$onliyfield=>$data[$onliyfield]])){
                        JsonReturn(array('code'=>1,'msg'=>$onliyfield.JZLANG('重复！')));
                    }
                }
            }
			$r = M('Article')->add($data);
			if($r){
				if($data['ownurl']){
					M('customurl')->add(['molds'=>'article','url'=>$data['ownurl'],'tid'=>$data['tid'],'addtime'=>time(),'aid'=>$r]);
				}
				//tags处理
				if($data['tags']){
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
								$w['tids'] = $data['tid'] ? ','.$data['tid'].',' : '';
								$w['target'] = '_blank';
								M('tags')->add($w);
							}else{
							    $tags_tids = $r['tids'] ? $r['tids'].$data['tid'].',' : ','.$data['tid'].',';
							    $ww['tids'] = $tags_tids;
							    $ww['number'] = $r['number']+1;
								M('tags')->update(['keywords'=>$v],$ww);
							}
						}
					}
				}
				//处理配置信息
				$config = $this->webconf['article_config'];
				$configdata = json_decode($config,1);
				if($configdata['seotitle']!=$this->frparam('config_seotitle') || $configdata['litpic']!=$this->frparam('config_litpic') || $configdata['tags']!=$this->frparam('config_tags') || $configdata['filter']!=$this->frparam('config_filter',1)){
					$configdata = [
						'seotitle'=>$this->frparam('config_seotitle'),
						'litpic'=>$this->frparam('config_litpic'),
						'description'=>$this->frparam('config_description'),
						'tags'=>$this->frparam('config_tags'),
						'filter'=>$this->frparam('config_filter',1),
					];
					M('sysconfig')->update(['field'=>'article_config'],['data'=>json_encode($configdata,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
					setCache('webconfig',null);
				}
				
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('添加成功,继续添加~'),'url'=>U('addarticle',array('tid'=>$data['tid']))));
				exit;
			}
			
			
			
		}
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		$this->tid = $this->frparam('tid');
		$this->classtypes = $this->classtypetree;
		
		$config = $this->webconf['article_config'];
		if(!$config){
			$configdata = [
				'seotitle'=>1,
				'litpic'=>1,
				'description'=>1,
				'tags'=>1,
				'filter'=>'title,keywords,body',
			];
			M('sysconfig')->add(['title'=>JZLANG('内容配置'),'field'=>'article_config','type'=>3,'data'=>json_encode($configdata,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),'typeid'=>0]);
			setCache('webconfig',null);
		}else{
			$configdata = json_decode($config,1);
		}
		$this->configdata = $configdata;
		
		$this->display('article-add');
	}
	
	function editarticle(){
		$this->fields_biaoshi = 'article';
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			$data = get_fields_data($data,'article');
            check_field_must($data,'article');
			if(!$this->frparam('seo_title',1) && $this->frparam('config_seotitle')==1){
				$data['seo_title'] = $data['title'];
			}
			if(!$this->frparam('description',1) && $this->frparam('config_description')==1){
				$data['description'] = newstr(strip_tags($_POST['body']),200);
			}
            $water_models = explode(',',$this->webconf['text_molds']);
            if(in_array('article',$water_models)){
                if(!$this->frparam('litpic',1) && $this->webconf['text_waterlitpic'] && $this->webconf['text_litpic']){
                    $data['litpic'] = waterwordmark($data['title'],APP_PATH.$this->webconf['text_litpic']);
                    //存储
                    $filesize = round(filesize(APP_PATH.$data['litpic'])/1024,2);
                    $pix_arr = explode('.',$data['litpic']);
                    $pix = end($pix_arr);
                    M('pictures')->add(['litpic'=>$data['litpic'],'addtime'=>time(),'userid'=>$_SESSION['admin']['id'],'size'=>$filesize,'filetype'=>strtolower($pix),'tid'=>$this->frparam('tid'),'molds'=>'article']);
                }
            }
			if(!$data['litpic'] && $this->frparam('config_litpic')==1){
				$pattern='/<img.*?src="(.*?)".*?>/is';
				if($this->frparam('body',1)){
					$r = preg_match($pattern,stripslashes($data['body']),$matchContent);
					if($r){
						$data['litpic'] = $matchContent[1];
					}else{
						$data['litpic'] = '';
					}
					
				}else{
					$data['litpic'] = '';
				}
			}
			if(!$this->frparam('tags',1) && $this->frparam('config_tags')==1){
				$data['tags'] = str_replace('，',',',$data['keywords']);
				if($data['tags']){
					$data['tags'] = ','.$data['tags'].',';
				}
			}
			
			$data['userid'] = $_SESSION['admin']['id'];
			$data['htmlurl'] = $data['tid'] ? $this->classtypedata[$data['tid']]['htmlurl'] : null;
			//违禁词检测
			if($this->webconf['mingan'] && $this->frparam('config_filter',1)){
				$mingan = explode(',',$this->webconf['mingan']);
				$filter = explode(',',$this->frparam('config_filter',1));
				$fields = $this->getTableFields('article');
				foreach($mingan as $s){
					if(strpos($s,'{xxx}')!==false){
						$pattern = '/'.str_replace('{xxx}','(.*)',$s).'/';
						foreach($filter as $vv){
							if($vv && preg_match($pattern, $data[$vv])){
								JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败').'，【'.$fields[$vv].'】'.JZLANG('存在敏感词').' [ '.$s.' ]'));
							}
							
						}
						

					}else{
						foreach($filter as $vv){
							if($vv && strpos($data[$vv],$s)!==false ){
								JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败').'，【'.$fields[$vv].'】'.JZLANG('存在敏感词').' [ '.$s.' ]'));
							}
							
						}
						
					}
				}
			}

			if($this->frparam('id')){
				$old_tags = M('Article')->getField(['id'=>$this->frparam('id')],'tags');
				//处理自定义URL
				
				if($data['ownurl']){
					$customurl = M('customurl')->find(['url'=>$data['ownurl']]);
					if($customurl){
						if($customurl['aid']!=$this->frparam('id')){
							JsonReturn(array('code'=>1,'msg'=>JZLANG('已存在相同的自定义URL！')));
						}else{
							M('customurl')->update(['id'=>$customurl['id']],['url'=>$data['ownurl'],'tid'=>$data['tid'],'molds'=>'article']);
						}
						
					}else{
						if(M('customurl')->find(['aid'=>$this->frparam('id'),'molds'=>'article'])){
							M('customurl')->update(['aid'=>$this->frparam('id'),'molds'=>'article'],['url'=>$data['ownurl'],'molds'=>'article','tid'=>$data['tid']]);
						}else{
							M('customurl')->add(['molds'=>'article','tid'=>$data['tid'],'url'=>$data['ownurl'],'addtime'=>time(),'aid'=>$this->frparam('id')]);
						}
						
					}
					
				}else{
					M('customurl')->delete(['molds'=>'article','aid'=>$this->frparam('id')]);
				}
				if($this->admin['isadmin']==1 || ($this->admin['isadmin']!=1 && $this->admin['ischeck']==0)){
					$data['isshow'] = $this->frparam('isshow',0,1);
				}else{
					$data['isshow'] = 0;
				}
                //检查是否重复
                if($this->webconf['hidetitleonliy']){
                    $hidetitleonly = explode('|',$this->webconf['hidetitleonliy']);
                    $onliyfield = '';
                    foreach ($hidetitleonly as $s){
                        $d = explode('-',$s);
                        if(strtolower($d[0])=='article'){
                            $onliyfield = strtolower($d[1]);
                            break;
                        }
                    }
                    if($onliyfield){
                        $sql = $onliyfield."='".$this->frparam($onliyfield,1)."' and id!=".$this->frparam('id');
                        if(M('article')->find($sql)){
                            JsonReturn(array('code'=>1,'msg'=>$onliyfield.JZLANG('重复！')));
                        }
                    }
                }
                $data['addtime'] = isset($data['addtime']) ? $data['addtime'] : time();
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
                                    $w['tids'] = $data['tid'] ? ','.$data['tid'].',' : '';
									$w['target'] = '_blank';
									M('tags')->add($w);
								}else{

                                    if(strpos($old_tags,','.$v.',')===false){

                                        if($data['tid']){
                                            $tags_tids = $r['tids'] ? $r['tids'].$data['tid'].',' : ','.$data['tid'].',';
                                            $ww['tids'] = $tags_tids;
                                        }

                                        $ww['number'] = $r['number']+1;
                                        M('tags')->update(['keywords'=>$v],$ww);
                                    }else if(strpos($data['tags'],','.$v.',')===false && strpos($old_tags,','.$v.',')!==false){

                                        if($data['tid']){
                                            $tags_tids = str_replace(','.$data['tid'].',',',',$r['tids']);
                                            $ww['tids'] = $tags_tids==',' ? '' : $tags_tids;
                                        }

                                        $ww['number'] = $r['number']-1;
                                        M('tags')->update(['keywords'=>$v],$ww);
                                    }
									
								}
								
								$new[]=$v;
							}
						}
						
						
						
						
					}
					if($this->webconf['release_award_open']==1 && $data['isshow']==1){
						$award = round($this->webconf['release_award'],2);
						$max_award = round($this->webconf['release_max_award'],2);
						$member_id = M('Article')->getField(['id'=>$this->frparam('id')],'member_id');
						
						if($member_id!=0 && $award>0){
							$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>'article','aid'=>$this->frparam('id'),'msg'=>JZLANG('发布奖励')]);
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
						   	  		$w['molds'] = 'article';
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

					
					$config = $this->webconf['article_config'];
					$configdata = json_decode($config,1);
					if($configdata['seotitle']!=$this->frparam('config_seotitle') || $configdata['litpic']!=$this->frparam('config_litpic') || $configdata['tags']!=$this->frparam('config_tags') || $configdata['filter']!=$this->frparam('config_filter',1)){
						$configdata = [
							'seotitle'=>$this->frparam('config_seotitle'),
							'litpic'=>$this->frparam('config_litpic'),
							'description'=>$this->frparam('config_description'),
							'tags'=>$this->frparam('config_tags'),
							'filter'=>$this->frparam('config_filter',1),
						];
						M('sysconfig')->update(['field'=>'article_config'],['data'=>json_encode($configdata,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
						setCache('webconfig',null);
					}
					
					JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！'),'url'=>U('index')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('您未做任何修改，不能提交！')));
				}
			}
			
			
			
		}
		if($this->frparam('id')){
			$this->data = M('Article')->find(array('id'=>$this->frparam('id')));
		}
		$this->molds = M('molds')->find(['biaoshi'=>'article']);
		$this->classtypes = $this->classtypedata;
		$config = $this->webconf['article_config'];
		if(!$config){
			$configdata = [
				'seotitle'=>1,
				'litpic'=>1,
				'description'=>1,
				'tags'=>1,
				'filter'=>'title,keywords,body',
			];
			M('sysconfig')->add(['title'=>JZLANG('内容配置'),'field'=>'article_config','type'=>3,'data'=>json_encode($configdata,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),'typeid'=>0]);
			setCache('webconfig',null);
		}else{
			$configdata = json_decode($config,1);
		}
		$this->configdata = $configdata;
		
		$this->display('article-edit');
		
	}
	
	function deletearticle(){
		$id = $this->frparam('id');
		if($id){
			$data = M('article')->find(['id'=>$id]);
			if(M('Article')->delete(['id'=>$id])){
				$customurl = M('customurl')->find(['molds'=>'article','aid'=>$id]);
				M('customurl')->delete(['molds'=>'article','aid'=>$id]);
				$w['molds'] = 'article';
				$w['title'] = '['.$data['id'].']'.$data['title'];
				$w['data'] = serialize($data);
				$w['addtime'] = time();
				$r = M('recycle')->add($w);
				if($customurl){
					$w['molds'] = 'customurl';
					$w['data'] = serialize($customurl);
                    $w['title'] = '['.$customurl['id'].']'.JZLANG('自定义链接');
					$w['addtime'] = time();
					$w['aid'] = $r;
					M('recycle')->add($w);
				}
				
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
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
				JsonReturn(array('code'=>0,'msg'=>JZLANG('复制成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('复制失败！')));
			}
		}
		
	}
	//批量删除文章
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$all = M('article')->findAll('id in('.$data.')');
			if(M('article')->delete('id in('.$data.')')){
				$customurls = M('customurl')->findAll(" aid in(".$data.") and molds='article' ");
				M('customurl')->delete(" aid in(".$data.") and molds='article' ");
				$newcustomurl = [];
				if($customurls){
					foreach($customurls as $v){
						$newcustomurl[$v['aid']] = $v;
					}
				}
				
				foreach($all as $v){
					$w['molds'] = 'article';
					$w['data'] = serialize($v);
					$w['addtime'] = time();
					$w['title'] = '['.$v['id'].']'.$v['title'];
					$x = M('recycle')->add($w);
					if($x && $newcustomurl[$v['id']]){
						$w['molds'] = 'customurl';
						$w['data'] = serialize($newcustomurl[$v['id']]);
						$w['addtime'] = time();
						$w['title'] = '['.$newcustomurl[$v['id']]['id'].']自定义链接';
						$w['aid'] = $x;
						M('recycle')->add($w);
					}
				}
				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
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
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量复制成功！')));
				
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
				if($v['ownurl']){
					M('customurl')->update(['aid'=>$v['id'],'molds'=>'article'],['tid'=>$tid]);
				}
			}
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量修改成功！')));
		}
	}
	
	//修改排序
	function editArticleOrders(){
		$field = $this->frparam('field',1);
		$w[$field] = $this->frparam('value',1);
		$r = M('article')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));
	}
	//批量修改推荐属性
	function changeAttribute(){
		$data = $this->frparam('data',1);
		$tj = $this->frparam('tj');
		if($data!=''){
			$list = M('article')->findAll('id in('.$data.')');
			
			foreach($list as $v){
				if(strpos($v['jzattr'],','.$tj.',')!==false){
					$attr = str_replace(','.$tj.',','',$v['jzattr']);
					if(!$attr){
						$w['jzattr'] = '';
					}else{
						$w['jzattr'] = ','.trim($attr,',').',';
					}
				}else{
					if($v['jzattr']){
						$w['jzattr'] = $v['jzattr'].$tj.',';
					}else{
						$w['jzattr'] = ','.$tj.',';
					}
				}
				M('Article')->update(array('id'=>$v['id']),$w);
			}
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量修改成功！')));
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
				$all = M('article')->findAll('id in('.$data.')');
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
								$rr = M('buylog')->find(['userid'=>$member_id,'type'=>3,'molds'=>'article','aid'=>$v['id'],'msg'=>JZLANG('发布奖励')]);
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
							   	  		$w['molds'] = 'article';
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
			
			M('article')->update('id in('.$data.')',['isshow'=>$isshow]);
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量操作成功！')));
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
		}
	}
	
	private function getTableFields($table){
		$sql="select distinct * from information_schema.columns where table_schema = '".DB_NAME."' and  table_name = '".DB_PREFIX.$table."'";
        $list = M()->findSql($sql);
        $isgo = true;
        $fields = [];
		
        foreach($list as $v){
			$len = 0;
			$s = preg_match('/\((.*)\)/',$v['COLUMN_TYPE'],$math);
			if($s){
				$len = $math[1];
			}
			$fields[$v['COLUMN_NAME']] = $v['COLUMN_COMMENT'] ? $v['COLUMN_COMMENT'] : $v['COLUMN_NAME'];
			
            

        }
        return $fields;

    }
	
	
	
	
	
	
	
}