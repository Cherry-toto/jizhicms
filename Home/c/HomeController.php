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


namespace Home\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;
use FrPHP\Extend\ArrayPage;

class HomeController extends CommonController
{

	//首页
	function index(){
		//检查缓存
		if(stripos(REQUEST_URI,'.php')!==false && REQUEST_URI!='/index.php'){
			$this->error('链接错误！');
		}
		$url = current_url();
		$this->ishome = true;
		$cache_file = APP_PATH.'cache/data/'.md5($url);
		$this->cache_file = $cache_file;
		$this->start_cache($cache_file);
		$this->display($this->template.'/index');
		$this->end_cache($this->cache_file);

	}
	//栏目
	function jizhi(){
		//接收前台所有的请求
		$request_url = str_replace(APP_URL,'',REQUEST_URI);
		$position = strpos($request_url,'?');
		$url = ($position!==FALSE) ? substr($request_url,0,$position) : $request_url;
		$url = substr($url,1,strlen($url)-1);
		
		if($this->webconf['isautositemap']){
			$this->sitemap();
		}
	
		if($url=='' || $url=='/' || $url=='index.php' || $url=='index'.File_TXT){
			$this->index();exit;
		}
		$this->ishome = false;
		//检查缓存
		$cache_file = APP_PATH.'cache/data/'.md5($request_url);
		$this->cache_file = $cache_file;
		if(!$this->frparam('ajax')){
			$this->start_cache($cache_file);
		}
		//  news/123.html  news-123.html  news-list-123.html
		$url = str_ireplace(File_TXT,'',$url);
		//strripos
		if(File_TXT_HIDE && !CLASS_HIDE_SLASH){
			$url = (strripos($url,'/')+1 == strlen($url)) ? substr($url,0,strripos($url,'/')) : $url; 
		}
		
		if(!$this->webconf['islevelurl']){
			//没有开启URL层级
			if(strpos($url,'/')!==false){
				$urls = explode('/',$url);
				//内容详情页
				$html = $urls[0];
				$id = (int)$urls[1];
				$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
			}else{
				
				
				//栏目页
				$this->frpage = $this->frparam('page',0,1);
				if(strpos($url,'-')!==false){
					//检测是否为分页
					$res = M('classtype')->find(array('htmlurl'=>$url,'isclose'=>0));
					if(!$res){
						//存在分页,取最后一个字符串
						$html_x = explode('-',$url);
						$this->frpage = array_pop($html_x);
						if(!$this->frpage){
							$this->error('链接错误！');exit;
						}
						$html = implode('-',$html_x);//再次拼接
						$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
						
					}else{
						//不是分页
						
					}
					
				}else{
					$html = $url;
					$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
					
				}
			}
		
		}else{
			//开启URL层级
			//判断是否为栏目
			$html=$url;
			$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
			if(!$res){
				
				if(strpos($url,'/')!==false){
					$urls = explode('/',$url);
					$urls_end = array_pop($urls);
					if(strpos($urls_end,'-')!==false){
						//分页
						//存在分页,取最后一个字符串
						$html_x = explode('-',$urls_end);
						$this->frpage = array_pop($html_x);
						if(!$this->frpage){
							$this->error('链接错误！');exit;
						}
						$urls[] = implode('-',$html_x);//再次拼接
						$html = implode('/',$urls);
						$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
						
					}else{
						//可能是数字？
						$html = implode('/',$urls);
						$id = (int)$urls_end;
						if($id<0){
							$this->error('链接错误！');exit;
						}
						$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
					}
					
					
					
					
					
				}else{
					//栏目页
					$this->frpage = $this->frparam('page',0,1);
					if(strpos($url,'-')!==false){
						//检测是否为分页
						//存在分页,取最后一个字符串
						$html_x = explode('-',$url);
						$this->frpage = array_pop($html_x);
						if(!$this->frpage){
							$this->error('链接错误！');exit;
						}
						$html = implode('-',$html_x);//再次拼接
						$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
						
					}else{
						$html = $url;
						$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
						
					}
				}
				
			}
			
			
		}
		
		
		
		
		if($res){
			$res['url'] = $this->classtypedata[$res['id']]['url'];
			$this->type =  $res;
			//检查授权
			if($res['gid']!=0){
				if(!$this->islogin){
					Redirect(U('Login/index'));
				}else{
					if($this->member['gid']<$res['gid']){
						Error('对不起，您没有访问权限！');
					}
				}
				
			}
			
			if(isset($id)){
				
				//默认是详情页-非详情页另做处理
				$this->id = $id;
				$this->jizhi_details($this->id);
				if(!$this->frparam('ajax')){
				$this->end_cache($this->cache_file);
				}
				
			}
			$children = $this->classtypedata[$res['id']]['children']['ids'];
			$child = [];
			foreach($children as $v){
				if($this->classtypedata[$v]['gid']!=0){
					if($this->islogin && $this->member['gid']>=$this->classtypedata[$v]['gid']){
						$child[]=$v;
					}
				}else{
					$child[]=$v;
				}
			}
			$sql = ' isshow=1 ';
			$molds = $res['molds'];
			$sql .= ' and tid in ('.implode(',',$child).') ';
			$page = new Page($molds);
			
			//手动设置分页条数
			$limit = $res['lists_num'];
			if($this->frparam('limit')){
				$limit = $this->frparam('limit');
			}
			$orders = 'istop desc,orders desc,addtime desc,id desc';
			$ot = $this->frparam('orders') ? $this->frparam('orders') : $res['orderstype'];
			switch($ot){
				case 1:
					$orders = 'istop desc,orders desc,addtime desc,id desc';
				break;
				case 2:
					$orders = 'istop desc,orders desc,id asc';
				break;
				case 3:
					$orders = 'istop desc,orders asc';
				break;
				case 4:
					$orders = 'istop desc,addtime desc';
				break;
				case 5:
					$orders = 'istop desc,id asc';
				break;
				case 6:
					$orders = 'istop desc,hits desc';
				break;
				case 7:
					$orders = 'istop desc,addtime asc';
				break;
			}
			$limit = $limit<=0 ? 15 : $limit;
			$this->currentpage = $this->frpage;
			$data = $page->where($sql)->orderby($orders)->limit($limit)->page($this->frpage)->go();
			$pages = $page->pageList(5,'-');
			
			$this->pages = $pages;//组合分页
			
			foreach($data as $k=>$v){
				if(!isset($v['url'])){
					$data[$k]['url'] = gourl($v,$v['htmlurl']);
				}
				
			}
			
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
			//清空screen筛选
			if(isset($_SESSION['screen'])){
				$_SESSION['screen'] = null;
			}
			$this->filters = [];
			if($this->frparam('ajax') && $this->webconf['isajax']){
				
				if($this->frparam('ajax_tpl')){
					$this->display($this->template.'/'.$res['molds'].'/ajax_list_'.$res['lists_html']);
					exit;
				}
				$sql = [];
				$sql[] = " tids like '%,".$res['id'].",%' "; 
				$sql[] = " molds = '".$res['molds']."' and isshow=1 ";
				$sql[] = " isajax=0 ";//查询出不允许访问的字段，进行限制
				$sql = implode(' and ',$sql);
				
				$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
				if($fields_list){
					$noallow = [];
					foreach($fields_list as $v){
						$noallow[]=$v['field'];
					}
					$newdata = [];
					foreach($data as $v){
						foreach($v as $kk=>$vv){
							if(in_array($kk,$noallow)){
								unset($v[$kk]);
							}
						}
						$newdata[]=$v;
					}
					
					$data = $newdata;
				}
				
				JsonReturn(['code'=>0,'data'=>$data,'type'=>$res,'sum'=>$this->sum,'allpage'=>$this->allpage]);
				
			}
			$classtypetree = array_reverse($this->classtypetree);
			$isgo = false;
			$newarray = [];
			$parent = [];//标记父类
			$istop = false;
			foreach($classtypetree as $k=>$v){
				if($v['id']==$res['id'] && !$isgo){
					$isgo = true;
					$res['level'] = $v['level'];
					$newarray[]=$v;
				}
				if($v['id']==$res['id'] && $v['level']==0){
					break;
				}
				if($v['level']==0 && $v['id']!=$res['id'] && $v['id']!=$res['pid']){
					if(!$istop && $isgo && $parent['level']!=0){
						$newarray[]=$v;
						$istop = true;
					}
					$isgo = false;
				}
				if($isgo &&  $v['id']!=$res['id'] && $res['level']>$v['level'] ){
					if(count($parent['pid'])){
						if($parent['level']>$v['level'] && $parent['pid']!=$v['pid']){
							$newarray[]=$v;
					    	$parent = $v;
						}
					}else{
						$newarray[]=$v;
						$parent = $v;
					}
				}
			}
			$newarray2 = array_reverse($newarray);
			$positions='<a href="'.get_domain().'">首页</a>';
			foreach($newarray2 as $v){
				$positions.='  &gt;  <a href="'.$v['url'].'">'.$v['classname'].'</a>';
			}
			$this->positions_data = $newarray2;
			$this->positions = $positions;
			if(!$res['lists_html']){
				$lists_html = M('molds')->getField(['biaoshi'=>$this->type['molds']],'list_html');
				$res['lists_html'] = str_replace('.html','',$lists_html);
			}
			
			$this->display($this->template.'/'.$res['molds'].'/'.$res['lists_html']);
			if(!$this->frparam('ajax')){
			$this->end_cache($this->cache_file);
			}

			
		}else{
			
			//进入自定义页面
			/**
				规定自定义页面的文件名跟访问的URL名相同，存放在page文件夹下面
			
			**/
			//html
			$url = ($position!==FALSE) ? substr($request_url,0,$position) : $request_url;
			$url = substr($url,1,strlen($url)-1);
			$html = str_ireplace(File_TXT,'',$url);
			$indexdata = file_get_contents(APP_PATH.'index.php');
			$rr = preg_match("/define\('TPL_PATH',[\'|\"](.*?)[\'|\"]\)/",$indexdata,$matches);
			if($rr){
				$tplpath = $matches[1];
			}else{
				$tplpath = 'Home';
			}
			$filepath = APP_PATH.$tplpath.'/'.HOME_VIEW.'/'.$this->template.'/page/'.$html.'.html';
			if(file_exists($filepath)){
				$this->display($this->template.'/page/'.$html);
				exit;
			}
			
			
			//错误页面->404
			$this->error('输入url错误！');
			exit;
		}
		
		
		
				
	}
	//自由定义链接
	function auto_url(){
		$html = $this->frparam('html',1);
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		$isclass = true;
		//检查缓存
		$url = REQUEST_URI;
		$cache_file = APP_PATH.'cache/data/'.md5(frencode($url));
		$this->cache_file = $cache_file;
		if(!$this->frparam('ajax')){
		$this->start_cache($cache_file);
		}
		if($id && $html){
			//详情页+html
			$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
			if(!$res){ $this->error('链接错误！');}
			$isclass = false;
		}else if($id && $tid){
			//详情页
			$res = M('classtype')->find(array('id'=>$tid,'isclose'=>0));
			if(!$res){ $this->error('链接错误！');}
			$isclass = false;
		}else if($tid){
			//栏目页
			$res = M('classtype')->find(array('id'=>$tid,'isclose'=>0));
			if(!$res){ $this->error('链接错误！');}
			
		}else{
			//html只有栏目页
			$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
			if(!$res){ $this->error('链接错误！');}
			
		}
		
		$res['url'] = $this->classtypedata[$res['id']]['url'];
		$this->type = $res;
		//检查授权
		if($res['gid']!=0){
			if(!$this->islogin){
				Redirect(U('Login/index'));
			}else{
				if($this->member['gid']<$res['gid']){
					Error('对不起，您没有访问权限！');
				}
			}
			
		}
		if($isclass){
			
			$children = $this->classtypedata[$res['id']]['children']['ids'];
			$child = [];
			foreach($children as $v){
				if($this->classtypedata[$v]['gid']!=0){
					if($this->islogin && $this->member['gid']>=$this->classtypedata[$v]['gid']){
						$child[]=$v;
					}
				}else{
					$child[]=$v;
				}
			}
			$sql = ' isshow=1 ';
			$molds = $res['molds'];
			$sql .= ' and tid in ('.implode(',',$child).') ';
			$page = new Page($molds);
			
			
			//手动设置分页条数
			$limit = $res['lists_num'];
			if($this->frparam('limit')){
				$limit = $this->frparam('limit');
			}
			$this->currentpage = $this->frpage;
			//只适合article和product
			if($molds=='article' || $molds=='product'){
				
				$data = $page->where($sql)->orderby('istop desc,orders desc,id desc')->limit($limit)->page($this->frpage)->go();
			}else{
				$data = $page->where($sql)->orderby('orders desc,id desc')->limit($limit)->page($this->frpage)->go();
			}
			
			
			$pages = $page->pageList(5,'-');
			$this->pages = $pages;//组合分页
			foreach($data as $k=>$v){
				if(isset($v['htmlurl']) && !isset($v['url'])){
					$data[$k]['url'] = gourl($v,$v['htmlurl']);
				}
				
			}
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
			//清空screen筛选
			if(isset($_SESSION['screen'])){
				$_SESSION['screen'] = null;
			}
			$this->filters = [];
			
			if($this->frparam('ajax') && $this->webconf['isajax']){
				
				if($this->frparam('ajax_tpl')){
					$this->display($this->template.'/'.$res['molds'].'/ajax_list_'.$res['lists_html']);
					exit;
				}
				
				$sql = [];
				$sql[] = " tids like '%,".$res['id'].",%' "; 
				$sql[] = " molds = '".$res['molds']."' and isshow=1 ";
				$sql[] = " isajax=0 ";//查询出不允许访问的字段，进行限制
				$sql = implode(' and ',$sql);
				
				$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
				if($fields_list){
					$noallow = [];
					foreach($fields_list as $v){
						$noallow[]=$v['field'];
					}
					$newdata = [];
					foreach($data as $v){
						foreach($v as $kk=>$vv){
							if(in_array($kk,$noallow)){
								unset($v[$kk]);
							}
						}
						$newdata[]=$v;
					}
					
					$data = $newdata;
				}
				
				
				JsonReturn(['code'=>0,'data'=>$data,'type'=>$res,'sum'=>$this->sum,'allpage'=>$this->allpage]);
				
			}
			//面包屑导航
			$classtypetree = array_reverse($this->classtypetree);
			$isgo = false;
			$newarray = [];
			$parent = [];//标记父类
			$istop = false;
			foreach($classtypetree as $k=>$v){
				if($v['id']==$res['id'] && !$isgo){
					$isgo = true;
					$res['level'] = $v['level'];
					$newarray[]=$v;
				}
				if($v['id']==$res['id'] && $v['level']==0){
					break;
				}
				if($v['level']==0 && $v['id']!=$res['id'] && $v['id']!=$res['pid']){
					if(!$istop && $isgo && $parent['level']!=0){
						$newarray[]=$v;
						$istop = true;
					}
					$isgo = false;
				}
				if($isgo &&  $v['id']!=$res['id'] && $res['level']>$v['level'] ){
					if(count($parent['pid'])){
						if($parent['level']>$v['level'] && $parent['pid']!=$v['pid']){
							$newarray[]=$v;
					    	$parent = $v;
						}
					}else{
						$newarray[]=$v;
						$parent = $v;
					}
				}
			}
			$newarray2 = array_reverse($newarray);
			$positions='<a href="'.get_domain().'">首页</a>';
			foreach($newarray2 as $v){
				$positions.='  &gt;  <a href="'.$v['url'].'">'.$v['classname'].'</a>';
			}
			$this->positions_data = $newarray2;
			$this->positions = $positions;
			
			if(!$res['lists_html']){
				$lists_html = M('molds')->getField(['biaoshi'=>$this->type['molds']],'list_html');
				$res['lists_html'] = str_replace('.html','',$lists_html);
			}
			$this->display($this->template.'/'.$res['molds'].'/'.$res['lists_html']);
			if(!$this->frparam('ajax')){
			$this->end_cache($this->cache_file);
			}
			
		}else{
			
			//默认是详情页-非详情页另做处理
			$this->id = $id;
			$this->jizhi_details($this->id);
			if(!$this->frparam('ajax')){
			$this->end_cache($this->cache_file);
			}
			
		}
		
		
		
	}
	
	
	
	//详情
	function jizhi_details($id){
		
		if(!$id){
			$this->error('缺少ID！');
		}
		if(isset($_SESSION['admin']) && $_SESSION['admin']['id']!=0){
			$details = M($this->type['molds'])->find(array('id'=>$id,'tid'=>$this->type['id']));
		}else{
			$details = M($this->type['molds'])->find(array('id'=>$id,'isshow'=>1,'tid'=>$this->type['id']));
		}
		
		if(!$details){
			$this->error('未找到相应内容！');
			exit;
		}
		if(!isset($details['url'])){
			$details['url'] = gourl($details,$details['htmlurl']);
		}
		
		
		//body
		if(array_key_exists('body',$details)){
			$con = $details['body'];
			$sql = " isshow=1 and (url!='' or newname!='') "; 
			$tags = M('tags')->findAll($sql);
			if($tags){
				foreach($tags as $v){
					// $name = 'CMS';
					// $newname="极致CMS";
					// $url = 'http://www.jizhicms.com';
					// $num = 30;
					$url = $v['url'];
					$num = $v['num'];
					$target = $v['target'];
					$name = $v['keywords'];
					$newname = $v['newname']!='' ? $v['newname'] : $name;
					if($url!=''){
						$astr = "<a href='".$url."' target='".$target."' title='".$newname."'><strong>".$newname."</strong></a>";
					}else{
						$astr = $newname;
					}
					
					$con = preg_replace( '|(<img\b[^>]*?)('.$name.')([^>]*?\=)([^>]*?)('.$name.')([^>]*?>)|U', '$1%&&&&&%$3$4%&&&&&%$6', $con);
					$con = preg_replace( '|(<img\b[^>]*?)('.$name.')([^>]*?>)|U', '$1%&&&&&%$3', $con);
					$con = preg_replace( '|(<a\b[^>]*?)('.$name.')([^>]*?>)(<[^<]*?)('.$name.')([^>]*?>)|U', '$1%&&&&&%$3$4%&&&&&%$6', $con);

					$con = str_replace_limit($name, $astr, $con, $num);
					$con = str_replace('%&&&&&%', $newname, $con);
					
					
				}
				
			}
			
			$details['body'] = $con;
			
		}
		
		
		$this->jz = $details;
		
		$aprev_sql = ' id<'.$id.' and tid in ('.implode(',',$this->classtypedata[$this->type['id']]['children']['ids']).') ';
		$anext_sql = ' id>'.$id.' and tid in ('.implode(',',$this->classtypedata[$this->type['id']]['children']['ids']).') ';
		$aprev = M($this->type['molds'])->find($aprev_sql,'id desc');
		$anext = M($this->type['molds'])->find($anext_sql,'id asc');
		if($aprev){
			$aprev['url'] = gourl($aprev,$aprev['htmlurl']);
		}
		if($anext){
			$anext['url'] = gourl($anext,$anext['htmlurl']);
		}
		$this->aprev = $aprev;
		$this->anext = $anext;
		
		//面包屑导航
		$classtypetree = array_reverse($this->classtypetree);
		$isgo = false;
		$newarray = [];
		$parent = [];//标记父类
		$istop = false;
		foreach($classtypetree as $k=>$v){
			if($v['id']==$this->type['id'] && !$isgo){
				$isgo = true;
				$this->type['level'] = $v['level'];
				$newarray[]=$v;
			}
			if($v['id']==$this->type['id'] && $v['level']==0){
				break;
			}
			if($v['level']==0 && $v['id']!=$this->type['id'] && $v['id']!=$this->type['pid']){
				if(!$istop && $isgo && $parent['level']!=0){
					$newarray[]=$v;
					$istop = true;
				}
				$isgo = false;
			}
			if($isgo &&  $v['id']!=$this->type['id'] && $this->type['level']>$v['level'] ){
				if(count($parent['pid'])){
					if($parent['level']>$v['level'] && $parent['pid']!=$v['pid']){
						$newarray[]=$v;
						$parent = $v;
					}
				}else{
					$newarray[]=$v;
					$parent = $v;
				}
			}
		}
		$newarray2 = array_reverse($newarray);
		$positions='<a href="'.get_domain().'">首页</a>';
		foreach($newarray2 as $v){
			$positions.='  &gt;  <a href="'.$v['url'].'">'.$v['classname'].'</a>';
		}
		$this->positions_data = $newarray2;
		$this->positions = $positions;
		
		if($this->frparam('ajax') && $this->webconf['isajax'] ){
			
			$sql = [];
			$sql[] = " tids like '%,".$details['tid'].",%' "; 
			$sql[] = " molds = '".$this->type['molds']."' and isshow=1 ";
			$sql[] = " isajax=0 ";//查询出不允许访问的字段，进行限制
			$sql = implode(' and ',$sql);
			
			$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
			if($fields_list){
				$noallow = [];
				foreach($fields_list as $v){
					$noallow[]=$v['field'];
				}

				foreach($details as $kk=>$vv){
					if(in_array($kk,$noallow)){
						unset($details[$kk]);
						unset($aprev[$kk]);
						unset($anext[$kk]);
					}
				}
				

			}
			
			JsonReturn(['code'=>0,'jz'=>$details,'prev'=>$aprev,'next'=>$anext]);
		}
		if(!$this->type['details_html']){
			$details_html = M('molds')->getField(['biaoshi'=>$this->type['molds']],'details_html');
			$this->type['details_html'] = str_replace('.html','',$details_html);
		}
		$this->display($this->template.'/'.$this->type['molds'].'/'.$this->type['details_html']);
		
	}
	
	
	//搜索--单一模块搜索
	function search(){
		$tables = explode('|',$this->webconf['search_table']);
		$molds = $this->frparam('molds',1);//搜索的模块
		$tid = $this->frparam('tid',1);
		if(in_array($molds,$tables) && $molds!=''){
			$word = $this->frparam('word',1);
			if($word==''){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'data'=>'','msg'=>'请输入关键词搜索！']);
				}
				Error('请输入关键词搜索！');
				
			}
			$this->word = $word;
			
			if(strpos($tid,',')!==false){
				$tid_arr = explode(',',$tid);
				$tids = [];
				foreach($tid_arr as $v){
					$tids[]=format_param($v,0);
				}
				$tid = implode(',',$tids);
			}else{
				$tid = format_param($tid,0);
			}
			
			$search_words = (isset($this->webconf['search_words'])&& $this->webconf['search_words']) ? explode('|',$this->webconf['search_words']) : ['title'];
			$sql = ' isshow=1 ';
			$sq = [];
			foreach($search_words as $v){
				$sq[]= " ".$v." like '%".$word."%' ";
			}
			if(count($sq)){
				$sql.=" and (".implode(' or ',$sq).") ";
			}
			if($this->frparam('isall') && $tid){
				$sql.= ' and tid in ('.implode(',',$this->classtypedata[$tid]['children']['ids']).') ';
			}else if($tid){
				$sql.=' and tid in('.$tid.') ';
			}
			
			$page = new Page($molds);
			$page->typeurl = 'search';
			$this->currentpage = $this->frparam('page',0,1);
			$data = $page->where($sql)->orderby('id desc')->limit($this->frparam('limit',0,15))->page($this->frparam('page',0,1))->go();
			$pages = $page->pageList(5,'&page=');
			
			$this->pages = $pages;//组合分页
			
			foreach($data as $k=>$v){
				if(isset($v['htmlurl']) && !isset($v['url'])){
					$data[$k]['url'] = gourl($v,$v['htmlurl']);
				}
				
				$data[$k]['title'] = str_replace($word,'<b style="color:#f00">'.$word.'</b>',$v['title']);
			}
			
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
		
			if($this->frparam('ajax') && $this->webconf['isajax']){
				if($this->frparam('ajax_tpl')){
					$this->display($this->template.'/ajax_search_list');
					exit;
				}
				
				$sql = [];
				$sql[] = " molds = '".$molds."' and isshow=1 ";
				$sql[] = " isajax=0 ";//查询出不允许访问的字段，进行限制
				$sql = implode(' and ',$sql);
				$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
				if($fields_list){
					$noallow = [];
					foreach($fields_list as $v){
						$noallow[]=$v['field'];
					}
					$newdata = [];
					foreach($data as $v){
						foreach($v as $kk=>$vv){
							if(in_array($kk,$noallow)){
								unset($v[$kk]);
							}
						}
						$newdata[]=$v;
					}
					
					$data = $newdata;
				}
				
				JsonReturn(['code'=>0,'data'=>$data,'lists'=>$page->listpage,'sum'=>$page->sum,'allpage'=>$page->allpage,'msg'=>'success']);
			}
		
			$this->display($this->template.'/search');
			
			
		}else{
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'data'=>'','msg'=>'搜索超出设定范围！']);
			}
			Error('搜索超出设定范围！');
		}

	}
	//多模块搜索
	function searchAll(){
		$tables = explode('|',$this->webconf['search_table']);
		$molds = $this->frparam('molds',2);//搜索的模块
		$tid = $this->frparam('tid',1);
		if($molds && is_array($molds)){
			$allow_table = [];
			foreach($molds as $v){
				if(in_array($v,$tables)){
					$allow_table[]=strtolower($v);
				}
			}
			if(count($allow_table)==0){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'data'=>'','msg'=>'您的搜索超出设定范围！']);
				}
				Error('您的搜索超出设定范围！');
			}
			
			$word = $this->frparam('word',1);
			if($word==''){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'data'=>'','msg'=>'请输入关键词搜索！']);
				}
				Error('请输入关键词搜索！');
			}
			$this->word = $word;
			if(strpos($tid,',')!==false){
				$tid_arr = explode(',',$tid);
				$tids = [];
				foreach($tid_arr as $v){
					$tids[]=format_param($v,0);
				}
				$tid = implode(',',$tids);
			}else{
				$tid = format_param($tid,0);
			}
			
			$search_words = (isset($this->webconf['search_words'])&& $this->webconf['search_words']) ? explode('|',$this->webconf['search_words']) : ['title'];
			$sql = ' isshow=1 ';
			$sq = [];
			foreach($search_words as $v){
				$sq[]= " ".$v." like '%".$word."%' ";
			}
			if(count($sq)){
				$sql.=" and (".implode(' or ',$sq).") ";
			}
			if($this->frparam('isall') && $tid){
				$sql.= ' and tid in ('.implode(',',$this->classtypedata[$tid]['children']['ids']).') ';
			}else if($tid){
				$sql.=' and tid in('.$tid.') ';
			}
			$sqlx = [];
			$sqln = [];
			foreach($allow_table as $v){
				$list_a = M($v)->findAll($sql);
				$sqlx[] = ' select id,tid,litpic,title,tags,keywords,molds,htmlurl,description,addtime,userid,member_id from '.DB_PREFIX.$v." where ".$sql;
				$sqln[] = ' select id from '.DB_PREFIX.$v." where ".$sql;
			}
			
			$sql = implode(' union all ',$sqlx);
			$sqln = implode(' union all ',$sqln);
			$page = new Page();
			$page->typeurl = 'search';
			$this->currentpage = $this->frpage;
			$data = $page->where($sql)->setPage(['limit'=>$this->frparam('limit',0,15)])->page($this->frpage)->goCount($sqln)->goSql();
			foreach($data as $k=>$v){
				$data[$k]['url'] = gourl($v,$v['htmlurl']);
				$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
				$data[$k]['title'] = str_replace($word,'<span style="color:#f00;">'.$word.'</span>',$v['title']);
			}
			$pages = $page->pageList(5,'&page=');
			$this->pages = $pages;//组合分页
			$this->lists = $data;//列表数据
			$this->sum = $page->sum;//总数据
			$this->pagelist = $page->listpage;//分页数组-自定义分页可用
			$this->listpage = $page->listpage;//分页数组-自定义分页可用
			$this->prevpage = $page->prevpage;//上一页
			$this->nextpage = $page->nextpage;//下一页
			$this->allpage = $page->allpage;//总页数
			if($this->frparam('ajax') && $this->webconf['isajax']){
				if($this->frparam('ajax_tpl')){
					$this->display($this->template.'/ajax_searchall_list');
					exit;
				}
				JsonReturn(['code'=>0,'data'=>$data]);
			}
			$this->display($this->template.'/searchall');
			
			
		}else{
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'data'=>'','msg'=>'请输入关键词搜索！']);
			}
			Error('请输入关键词搜索！');
			
		}

	}
	

	
	//错误页面
	function error($msg){
		$url = substr(REQUEST_URI,1);
		$position = strpos($url, '?');
        $url = $position === false ? $url : substr($url, 0, $position);
		$cache_file = APP_PATH.'cache/data/'.md5(REQUEST_URI);
		$this->cache_file = $cache_file;
		$this->start_cache($cache_file);		
		$r = M('customurl')->find(['url'=>$url]);
		if($r){
			if(isset($this->classtypedata[$r['tid']])){
				$this->type = $this->classtypedata[$r['tid']];
				$this->jizhi_details($r['aid']);
				$this->end_cache($this->cache_file);
			}else if($r['molds']=='tags'){
				$param = [];
				$param['id'] = $r['aid'];
				$tags = new TagsController($param);
				$tags->index();
				$this->end_cache($this->cache_file);
			}
			
			exit;
		}
		header("HTTP/1.0 404");
		$this->display($this->template.'/404');
		$this->end_cache($this->cache_file);
		exit;
	}
	
	//开启检查缓存
	function start_cache($cache_file){
		$cache_file = $cache_file.'_'.$this->template.'.php';
		if($this->webconf['iscachepage']==1){
			if(file_exists($cache_file)){
				
				//获取当前时间戳
				$now_time = time();
				//获取缓存文件时间戳
				$last_time = filemtime($cache_file);
				//如果缓存文件生成超过指定的时间直接删除文件
				if((($now_time - $last_time)/60)>webConf('cache_time')){
					unlink($cache_file);
				}else{
					//有缓存文件直接调用
					$content =  file_get_contents($cache_file);
					echo substr($content,14);
					exit;
				}
				
				
			}
		}
	
		//开启缓存
		ob_start();
	}
	//结束缓存
	function end_cache($cache_file){
		$cache_file = $cache_file.'_'.$this->template.'.php';
		
		//获取缓存
		$content = ob_get_contents();
		if($this->webconf['isautohtml']==1){
			$filepath = substr($_SERVER["REQUEST_URI"],1,strlen($_SERVER["REQUEST_URI"])-1);
			
			$file = APP_PATH.$filepath;
			if(strpos($filepath,'/')!==false){
				$filepath = explode('/',$filepath);
				array_pop($filepath);
				$create_dir = APP_PATH;
				foreach($filepath as $vv){
					$create_dir.=$vv;
					if(!is_dir($create_dir)){
						$r = mkdir($create_dir,0777,true);
						if(!$r){
							echo '系统创建 [ '.str_replace('/','\\',$create_dir).' ] 目录失败!';exit;
						}
						
					}
					$create_dir.='/';
					
				}
				
				
			}
			if(strpos($file,'.html')===false){
				$file.='index.html';
			}
			$fp = fopen($file,'w');
			fwrite($fp,$content);
			fclose($fp);
			
		}
		if($this->webconf['iscachepage']==1){
			//写入到缓存内容到指定的文件夹
			$content ='<?php die();?>'.$content;
			$fp = fopen($cache_file,'w');
			fwrite($fp,$content);
			fclose($fp);
		}
		ob_flush();  
		flush();
		ob_end_clean(); 
		
		
		exit;
	}
	

	//生成sitemap
	function sitemap(){
		//获取缓存文件时间戳
		$last_time = filemtime(APP_PATH.'sitemap.xml');
		$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
		if($last_time>=$start && $last_time<$end){
			//当天
		}else{
			$www = ($this->webconf['domain']=='') ? get_domain() : $this->webconf['domain'];
			$l = '<?xml version="1.0" encoding="UTF-8"?>
			<urlset
				  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
				  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
						http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
						//首页
						$l.='<url>
			  <loc>'.$www.'/</loc>
			  <lastmod>'.date('Y-m-d').'T08:00:00+00:00</lastmod>
			  <changefreq>yearly</changefreq>
			  <priority>1.00</priority>
			</url>';
			$molds = M('molds')->findAll(['sys'=>0,'ismust'=>1]);
			$model = ['classtype','article','product'];
			if($molds){
				foreach($molds as $vv){
					$model[]=$vv['biaoshi'];
				}
			}
			foreach($model as $k=>$v){
				$list = M($v)->findAll(['isshow'=>1]);
				if(count($list)>1000){
					M('sysconfig')->update(['field'=>'isautositemap'],['data'=>0]);
					setCache('webconfig',null);
					$this->jizhi();
					exit;
				}
				//栏目
				if($v=='classtype' && $list){
					foreach($list as $s){
						if($this->classtypedata[$s['id']]['url']){
							$l.='<url>
							  <loc>'.$this->classtypedata[$s['id']]['url'].'</loc>
							  <lastmod>'.date('Y-m-d').'T08:00:00+00:00</lastmod>
							  <changefreq>always</changefreq>
							  <priority>0.80</priority>
							</url>';
						}
						
						if($this->webconf['iswap']==1){
							if($classtypedataMobile[$s['id']]['url']){
								$l.='<url>
								  <loc>'.$classtypedataMobile[$s['id']]['url'].'</loc>
								  <lastmod>'.date('Y-m-d').'T08:00:00+00:00</lastmod>
								  <changefreq>always</changefreq>
								  <priority>0.80</priority>
								</url>';
							}
							
						}	
					}
				}else if($list){
					foreach($list as $s){
						$s['addtime'] = isset($s['addtime']) ? $s['addtime'] : time();
					//文章-商品
						$url = gourl($s,$s['htmlurl']);
						if($url){
							$l.='<url>
							  <loc>'.$url.'</loc>
							  <lastmod>'.date('Y-m-d',$s['addtime']).'T08:00:00+00:00</lastmod>
							   <changefreq>always</changefreq>
								  <priority>0.80</priority>
							</url>';
						}
						
						if($this->webconf['iswap']==1){
							$murl = $this->murl($s,$s['htmlurl']);
							if($murl){
								$l.='<url>
								  <loc>'.$murl.'</loc>
								  <lastmod>'.date('Y-m-d',$s['addtime']).'T08:00:00+00:00</lastmod>
								   <changefreq>always</changefreq>
								  <priority>0.80</priority>
								</url>';
							}
							
						}	
					
					}
				}
				
				
			}
			
			$l.='</urlset>';
			
			$f = @fopen(APP_PATH.'sitemap.xml','w');
			$n = @fwrite($f,$l);
			@fclose($f);
			
			
		}
		
	}
	function murl($id,$htmlurl=null,$molds='article'){
		if(is_array($id)){
			$value = $id;
			if($value['target']){
				return $value['target'];
			}else{
				if($value['ownurl']){
					return get_domain().'/'.$value['ownurl'];
					
				}
			}
			$id = $value['id'];
		}
		if(!$id){Error_msg('缺少ID！');}
		$htmlpath = $this->webconf['iswap']==1 ? $this->webconf['mobile_html'] : $this->webconf['pc_html'];
		$htmlpath = ($htmlpath=='' || $htmlpath=='/') ? '' : '/'.$htmlpath; 
		if($htmlurl!=null){
			return get_domain().$htmlpath.'/'.$htmlurl.'/'.$id.'.html';
		}
		
		$tid = M($molds)->getField(array('id'=>$id),'tid');
		$htmlurl = M('classtype')->getField(array('id'=>$tid),'htmlurl');
		return get_domain().$htmlpath.'/'.$htmlurl.'/'.$id.'.html';
	}
	
	//生成RSS
	function rss(){
		$item = '';
		
		//栏目item
		foreach($this->classtypedata as $v){
			if($v['isshow']==1){
				$item .= '<item>
						<title><![CDATA[ '.$v['classname'].' ]]></title>
						<link>'.$v['url'].'</link>
						<description><![CDATA[ '.$v['description'].' ]]></description>

						<source>'.$this->webconf['web_name'].'</source>

						<pubDate>'.date("D, d M Y H:i:s ", time()) . "GMT".'</pubDate> 
					</item>';
			}
		}
		//文章item
		$article = M('article')->findAll(['isshow'=>1]);
		foreach($article as $v){
			$v['url'] = gourl($v,$v['htmlurl']);
			$item .= '<item>
						<title><![CDATA[ '.$v['title'].' ]]></title>
						<link>'.$v['url'].'</link>
						<description><![CDATA[ '.$v['description'].' ]]></description>

						<source>'.$this->webconf['web_name'].'</source>

						<pubDate>'.date("D, d M Y H:i:s ", time()) . "GMT".'</pubDate> 
					</item>';
		}
		//商品item
		$product = M('product')->findAll(['isshow'=>1]);
		foreach($product as $v){
			$v['url'] = gourl($v,$v['htmlurl']);
			$item .= '<item>
						<title><![CDATA[ '.$v['title'].' ]]></title>
						<link>'.$v['url'].'</link>
						<description><![CDATA[ '.$v['description'].' ]]></description>

						<source>'.$this->webconf['web_name'].'</source>

						<pubDate>'.date("D, d M Y H:i:s ", time()) . "GMT".'</pubDate> 
					</item>';
		}
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>  
				<rss version="2.0">
				<channel>
					<title>'.$this->webconf['web_name'].'</title>
					<description>'.$this->webconf['web_desc'].'</description>
					<link>'.get_domain().'</link>
					<generator>Rss Powered By jizhicms</generator>
					<image>
						<url>'.get_domain().'/'.$this->webconf['web_logo'].'</url>
						<title>'.$this->webconf['web_name'].'</title>
						<link>'.get_domain().'</link>
					</image>
					
					'.$item.'

				</channel>

				</rss>';
		header("Content-type:application/xml");
		echo $xml;
	}
	

}