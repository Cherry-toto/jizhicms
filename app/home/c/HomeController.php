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


namespace app\home\c;


use frphp\extend\Page;
use FrPHP\Extend\ArrayPage;

class HomeController extends CommonController
{

	//首页
	function index(){
		//检查缓存
		if(stripos(REQUEST_URI,'.php')!==false && REQUEST_URI!='/index.php'){
			$this->error(JZLANG('链接错误！'));
		}
		$this->ishome = true;
		$this->display($this->template.'/index');

	}
	//栏目
	function jizhi(){
		//接收前台所有的请求
		$request_url = str_replace(APP_URL,'',REQUEST_URI);
		$position = strpos($request_url,'?');
		$url = ($position!==FALSE) ? substr($request_url,0,$position) : $request_url;
		$url = substr($url,1,strlen($url)-1);

		if($url=='' || $url=='/' || $url=='index.php' || $url=='index.html'){
			$this->index();exit;
		}
		$this->ishome = false;
		
		//  news/123.html  news-123.html  news-list-123.html
		$url = str_ireplace('.html','',$url);
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
							$this->error(JZLANG('链接错误！'));exit;
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
							$this->error(JZLANG('链接错误！'));exit;
						}
						$urls[] = implode('-',$html_x);//再次拼接
						$html = implode('/',$urls);
						$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
						
					}else{
						//可能是数字？
						$html = implode('/',$urls);
						$id = (int)$urls_end;
						if($id<0){
							$this->error(JZLANG('链接错误！'));exit;
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
							$this->error(JZLANG('链接错误！'));exit;
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
			if($res['gids']){
				$gids = explode(',',$res['gids']);
				if(!$this->islogin){
					Redirect(U('Login/index'));
				}else{
					if(!in_array($this->member['gid'],$gids)){
						Error(JZLANG('对不起，您没有访问权限！'));
					}
				}
			}
			
			if(isset($id)){
				
				//默认是详情页-非详情页另做处理
				$this->id = $id;
				$this->jizhi_details($this->id);
				
			}
			$children = $this->classtypedata[$res['id']]['children']['ids'];
			$child = [];
			foreach($children as $v){
				if($this->classtypedata[$v]['gids']){
					$gids_n = explode(',',$this->classtypedata[$v]['gids']);
					if($this->islogin && in_array($this->member['gid'],$gids_n)){
						$child[]=$v;
					}
				}else{
					$child[]=$v;
				}
			}
			$sql = ' isshow=1 ';
			$molds = $res['molds'];
            $sql .= " and (tid in (".implode(',',$child).") or tids like '%,".$this->type['id'].",%' )";
            $page = new Page($molds);
            $jzattr = $this->frparam('attr',1);
            if($jzattr){
                $jzattr_arr = explode('-',$jzattr);
                foreach ($jzattr_arr as $s){
                    $sql.=" and jzattr like '%,".$s.",%' ";
                }
                
            }
			
			//手动设置分页条数
			$limit = $res['lists_num'];
			if($this->frparam('limit')){
				$limit = $this->frparam('limit');
			}
			$orders = 'orders desc,addtime desc,id desc';
			$ot = $this->frparam('orders') ? $this->frparam('orders') : $res['orderstype'];
			switch($ot){
				case 1:
					$orders = 'orders desc,addtime desc,id desc';
				break;
				case 2:
					$orders = 'orders desc,id asc';
				break;
				case 3:
					$orders = 'orders asc';
				break;
				case 4:
					$orders = 'addtime desc';
				break;
				case 5:
					$orders = 'id asc';
				break;
				case 6:
					$orders = 'hits desc';
				break;
				case 7:
					$orders = 'addtime asc';
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
				$data[$k]['class_name'] = $this->type['classname'];
				$data[$k]['class_url'] = $this->type['url'];
				$data[$k]['class_litpic'] = $this->type['litpic'];
				$data[$k]['format_addtime'] = $v['addtime'] ? date('Y-m-d H:i:s',$v['addtime']) : '';
				
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
				//$sql[] = " tids like '%,".$res['id'].",%' "; 
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
					if($parent['pid']){
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
			$positions='<a href="'.get_domain().'">'.JZLANG('首页').'</a>';
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

			
		}else{
			
			//进入自定义页面
			/**
				规定自定义页面的文件名跟访问的URL名相同，存放在page文件夹下面
			
			**/
			//html
			$url = ($position!==FALSE) ? substr($request_url,0,$position) : $request_url;
			$url = substr($url,1,strlen($url)-1);
			$html = str_ireplace('.html','',$url);
            if(defined('TPL_PATH')){
                $path = TPL_PATH;
            }else{
                $path = APP_HOME;
            }
            $filepath = HOME_VIEW ? $path.'/'.HOME_VIEW.'/'.TEMPLATE.'/page/'.$html.File_TXT : $path.'/'.TEMPLATE.'/page/'.$html.File_TXT;
            if(file_exists(APP_PATH.$filepath)){
                $this->display($this->template.'/page/'.$html);
                exit;
            }
            if(file_exists(str_replace(File_TXT,'.html',APP_PATH.$filepath))){
                $this->display($this->template.'/page/'.$html);
                exit;
            }
			
			
			//错误页面->404
			$this->error(JZLANG('输入url错误！'));
			exit;
		}
		
		
		
				
	}
	//自由定义链接
	function auto_url(){
		$html = $this->frparam('html',1);
		$molds = $this->frparam('molds',1);
		$id = $this->frparam('id');
		$tid = $this->frparam('tid');
		$isclass = true;
		if($id && $html){
			//详情页+html
			$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
			if(!$res){ $this->error(JZLANG('链接错误！'));}
			$isclass = false;
		}else if($id && $tid){
			//详情页
			$res = M('classtype')->find(array('id'=>$tid,'isclose'=>0));
			if(!$res){ $this->error(JZLANG('链接错误！'));}
			$isclass = false;
		}else if($tid){
			//栏目页
			$res = M('classtype')->find(array('id'=>$tid,'isclose'=>0));
			if(!$res){ $this->error(JZLANG('链接错误！'));}
        }else if($molds && $id){
            $tables = explode('|',$this->webconf['search_table']);
            if(!in_array(strtolower($molds),$tables)){
                $this->error('链接错误！');
            }
            //默认是详情页-非详情页另做处理
            $this->id = $id;
            $tid = M($molds)->getField(['id'=>$id],'tid');
            if(!$tid){
                $this->error('链接错误！');
            }
            $this->type = $this->classtypedata[$tid];
            $this->jizhi_details($this->id);

        }else{
			//html只有栏目页
			$res = M('classtype')->find(array('htmlurl'=>$html,'isclose'=>0));
			if(!$res){ $this->error(JZLANG('链接错误！'));}
			
		}
		
		$res['url'] = $this->classtypedata[$res['id']]['url'];
		$this->type = $res;
		//检查授权
		if($res['gids']){
			$gids = explode(',',$res['gids']);
			if(!$this->islogin){
				Redirect(U('Login/index'));
			}else{
				if(!in_array($this->member['gid'],$gids)){
					Error(JZLANG('对不起，您没有访问权限！'));
				}
			}
		}
		if($isclass){
			
			$children = $this->classtypedata[$res['id']]['children']['ids'];
			$child = [];
			foreach($children as $v){
				if($this->classtypedata[$v]['gids']){
					$gids_n = explode(',',$this->classtypedata[$v]['gids']);
					if($this->islogin && in_array($this->member['gid'],$gids_n)){
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
			
			$data = $page->where($sql)->orderby('orders desc,id desc')->limit($limit)->page($this->frpage)->go();
			
			
			$pages = $page->pageList(5,'-');
			$this->pages = $pages;//组合分页
			foreach($data as $k=>$v){
				if(isset($v['htmlurl']) && !isset($v['url'])){
					$data[$k]['url'] = gourl($v,$v['htmlurl']);
				}
                $data[$k]['class_name'] = $this->type['classname'];
                $data[$k]['class_url'] = $this->type['url'];
                $data[$k]['class_litpic'] = $this->type['litpic'];
                $data[$k]['format_addtime'] = $v['addtime'] ? date('Y-m-d H:i:s',$v['addtime']) : '';
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
				//$sql[] = " tids like '%,".$res['id'].",%' "; 
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
					if($parent['pid']){
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
			$positions='<a href="'.get_domain().'">'.JZLANG('首页').'</a>';
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
			
		}else{
			
			//默认是详情页-非详情页另做处理
			$this->id = $id;
			$this->jizhi_details($this->id);
		}
		
		
		
	}
	
	//详情
	function jizhi_details($id){
		
		if(!$id){
			$this->error(JZLANG('缺少ID！'));
		}
		if(isset($_SESSION['admin']) && $_SESSION['admin']['id']!=0){
			$details = M($this->type['molds'])->find(array('id'=>$id,'tid'=>$this->type['id']));
		}else{
			$details = M($this->type['molds'])->find(array('id'=>$id,'isshow'=>1,'tid'=>$this->type['id']));
		}
		
		if(!$details){
			$this->error(JZLANG('未找到相应内容！'));
			exit;
		}
		if(!isset($details['url'])){
			$details['url'] = gourl($details,$details['htmlurl']);
		}
		if(array_key_exists('body',$details)){
			$con = $details['body'];
			$chains = M('chain')->findAll(['isshow'=>1]);
			if($chains){
				foreach($chains as $v){
					$url = $v['url'];
					$num = $v['num'];
					$name = $v['title'];
					$newname = $v['newtitle']!='' ? $v['newtitle'] : $name;
					if($url!=''){
						$astr = "<a href='".$url."' target='_blank' title='".$newname."'><strong>".$newname."</strong></a>";
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
        
        $details['class_name'] = $this->type['classname'];
        $details['class_url'] = $this->type['url'];
        $details['class_litpic'] = $this->type['litpic'];
        $details['format_addtime'] = $details['addtime'] ? date('Y-m-d H:i:s',$details['addtime']) : '';
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
				if($parent['pid']){
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
		$positions='<a href="'.get_domain().'">'.JZLANG('首页').'</a>';
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
		exit;
	}
	
	
	//搜索--单一模块搜索
	function search(){
		$tables = explode('|',$this->webconf['search_table']);
		$molds = strtolower($this->frparam('molds',1));//搜索的模块
		$tid = $this->frparam('tid',1);
		if(in_array($molds,$tables) && $molds!=''){
			$word = $this->frparam('word',1);
			if($word==''){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'data'=>'','msg'=>JZLANG('请输入关键词搜索！')]);
				}
				Error(JZLANG('请输入关键词搜索！'));
				
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
				
                $data[$k]['class_name'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['classname'] : '';
                $data[$k]['class_url'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['url'] : '';
                $data[$k]['class_litpic'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['litpic'] : '';
                $data[$k]['format_addtime'] = isset($v['addtime']) ? date('Y-m-d H:i:s',$v['addtime']) : '';
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
			if(defined('TPL_PATH')){
				$path = TPL_PATH;
			}else{
				$path = APP_HOME;
			}
			$file = str_replace('//','/',APP_PATH . $path .'/'.HOME_VIEW.'/'.$this->template.'/'.strtolower($molds).'-search'.File_TXT);

            if(file_exists($file)){
                $this->display($this->template.'/'.strtolower($molds).'-search');
                exit;
            }
            if(file_exists(str_replace(File_TXT,'.html',$file))){
                $this->display($this->template.'/'.strtolower($molds).'-search');
                exit;
            }

            $this->display($this->template.'/search');
			
			
			
		}else{
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'data'=>'','msg'=>JZLANG('搜索超出设定范围！')]);
			}
			Error(JZLANG('搜索超出设定范围！'));
		}

	}
	//多模块搜索
	function searchAll(){
		$tables = explode('|',$this->webconf['search_table_muti']);
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
					JsonReturn(['code'=>1,'data'=>'','msg'=>JZLANG('您的搜索超出设定范围！')]);
				}
				Error(JZLANG('您的搜索超出设定范围！'));
			}
			
			$word = $this->frparam('word',1);
			if($word==''){
				if($this->frparam('ajax')){
					JsonReturn(['code'=>1,'data'=>'','msg'=>JZLANG('请输入关键词搜索！')]);
				}
				Error(JZLANG('请输入关键词搜索！'));
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
			
			$search_words = (isset($this->webconf['search_words_muti'])&& $this->webconf['search_words_muti']) ? explode('|',$this->webconf['search_words_muti']) : ['title'];
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
			foreach($allow_table as $v){
				$sqlx[] = ' select '.$this->webconf['search_fields_muti'].' from '.DB_PREFIX.$v." where ".$sql;
			}
			
			$sql = implode(' union all ',$sqlx);
			$page = new Page();
			$page->typeurl = 'search';
			$this->currentpage = $this->frpage;
			$data = $page->where($sql)->setPage(['limit'=>$this->frparam('limit',0,15)])->page($this->frpage)->goSql();
			foreach($data as $k=>$v){
				$data[$k]['url'] = gourl($v,$v['htmlurl']);
                $data[$k]['class_name'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['classname'] : '';
                $data[$k]['class_url'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['url'] : '';
                $data[$k]['class_litpic'] = isset($this->classtypedata[$v['tid']]) ? $this->classtypedata[$v['tid']]['litpic'] : '';
                $data[$k]['format_addtime'] = isset($v['addtime']) ? date('Y-m-d H:i:s',$v['addtime']) : '';
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
				JsonReturn(['code'=>1,'data'=>'','msg'=>JZLANG('请输入关键词搜索！')]);
			}
			Error(JZLANG('请输入关键词搜索！'));
			
		}

	}
	

	
	//错误页面
	function error($msg){
		$url = substr(REQUEST_URI,1);
		$position = strpos($url, '?');
        $url = $position === false ? $url : substr($url, 0, $position);
		$r = M('customurl')->find(['url'=>$url]);
		if($r){
			if(isset($this->classtypedata[$r['tid']])){
				$this->type = $this->classtypedata[$r['tid']];
				$this->jizhi_details($r['aid']);
			}else if($r['molds']=='tags'){
				$param = [];
				$param['id'] = $r['aid'];
				$tags = new TagsController($param);
				$tags->index();
			}
			
			exit;
		}
		header("HTTP/1.0 404");
		$this->display($this->template.'/404');
		exit;
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
			$v['url'] = gourl($v);
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
			$v['url'] = gourl($v);
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