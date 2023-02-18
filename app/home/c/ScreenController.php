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


namespace app\home\c;


use frphp\extend\Page;

class ScreenController extends CommonController
{
	function index(){
        $cache_file = APP_PATH.'cache/data/'.md5(REQUEST_URI);
        $this->cache_file = $cache_file;
        if(!$this->frparam('ajax')){
            $this->start_cache($cache_file);
        }
		//检测三个参数是否存在
		if(!$this->frparam('molds',1) || !$this->frparam('tid') || !$this->frparam('jz_screen',1)){
			$this->error(JZLANG('参数错误！'));
		}
		if(!M('molds')->find(['biaoshi'=>$this->frparam('molds',1)])){
			$this->error(JZLANG('非法参数！'));
		}
		if(!isset($_SESSION['screen'])){
			$_SESSION['screen'] = [];
		}
		$session_screen = $_SESSION['screen'];
		//查询扩展字段
		$fields = M('fields')->findAll(['molds'=>$this->frparam('molds',1)]);
		$newfield = [];
		foreach($fields as $k=>$v){
			$newfield[$v['field']] = $v;
		}
		
		$res = M('classtype')->find(array('id'=>$this->frparam('tid')));
		
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
	
		//解析jz_screen
		//检测是否有page分页参数
		$this->frpage = 1;
		if(strpos($this->frparam('jz_screen',1),'page')!==false){
			$jz_screen_arr = explode('-page-',$this->frparam('jz_screen',1));
			$jz_screen = explode('-',$jz_screen_arr[0]);
			$this->frpage = (int)$jz_screen_arr[1];
		}else{
			$jz_screen = explode('-',$this->frparam('jz_screen',1));
		}
		
		if($this->frparam('page')){
			$this->frpage = $this->frparam('page');
		}
		
		$jz_screen_key = [];
		$jz_screen_value = [];
		foreach($jz_screen as $k=>$v){
			if($k%2==0){
				$jz_screen_key[]=$v;
			}else{
				$jz_screen_value[]=$v;
			}

		}
		$screen = array_combine($jz_screen_key,$jz_screen_value);
		foreach($screen as $k=>$v){
			
			if($v==0 || $v==''){
				if(isset($session_screen[$k])){
					unset($session_screen[$k]);
				}
			}else{
				$session_screen[$k] = $v;
			}
			
			
		}
		
		$sql = '1=1 and isshow=1';
		//组合搜索内容
		foreach($session_screen as $k=>$v){
			if(!array_key_exists($k,$newfield)){
				continue;
			}
			if($newfield[$k]['fieldtype']==7 || $newfield[$k]['fieldtype']==12){
				//单选字段
				
				//多选框判断
				if(strpos($v,',')!==false){
					$vv = explode(',',$v);
					$vv_arr = [];
					foreach($vv as $vs){
						$vv_arr[]=" ".$k."='".$vs."' ";
					}
					$sql.=" and (".implode(' or ',$vv_arr).") ";
					$vv = null;
					$vv_arr = null;
				}else{
					$sql.=" and ".$k."='".$v."' ";
				}
				
				
			}else{
				//多选字段
				if(strpos($v,',')!==false){
					$vv = explode(',',$v);
					$vv_arr = [];
					foreach($vv as $vs){
						$vv_arr[]=" ".$k." like '%,".$vs.",%' ";
					}
					$sql.=" and (".implode(' or ',$vv_arr).") ";
					$vv = null;
					$vv_arr = null;
				}else{
					$sql.=" and ".$k." like '%,".$v.",%' ";
				}
				
			}
			
		}
		$this->filters = $session_screen;
		//dump($session_screen);
		$_SESSION['screen'] = $session_screen;
		$molds = $this->frparam('molds',1);
		$sql .= ' and tid in ('.implode(',',$this->classtypedata[$res['id']]['children']['ids']).') ';
		$page = new Page($molds);
		//手动设置分页条数
		$limit = $res['lists_num'];
		if($this->frparam('limit')){
			$limit = $this->frparam('limit');
		}
		//echo $sql;
		//筛选分页的特殊性
		$page->typeurl = 'screen';
		
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
		$this->currentpage = $this->frpage;
		$data = $page->where($sql)->orderby($orders)->limit($limit)->page($this->frpage)->go();
		$pages = $page->pageList(3,'-page-');
		
		$this->pages = $pages;//组合分页
		
		foreach($data as $k=>$v){
			$data[$k]['url'] = gourl($v,$v['htmlurl']);
		}
		$this->type = $res;
		$this->lists = $data;//列表数据
		$this->sum = $page->sum;//总数据
		$this->listpage = $page->listpage;//分页数组-自定义分页可用
		$this->prevpage = $page->prevpage;//上一页
		$this->nextpage = $page->nextpage;//下一页
		$this->allpage = $page->allpage;//总页数
		if($this->frparam('ajax') && $this->webconf['isajax']){
			if($this->frparam('ajax_tpl',1)){
				$this->display($this->template.'/'.$res['molds'].'/screen_list_'.$res['lists_html']);
				exit;
			}
			JsonReturn(['code'=>0,'data'=>$data,'sum'=>$this->sum,'allpage'=>$this->allpage,'listpage'=>$this->listpage]);
			
		}
	
		$this->display($this->template.'/'.$res['molds'].'/'.$res['lists_html']);
        
        if(!$this->frparam('ajax')){
            $this->end_cache($this->cache_file);
        }
		
		
		
		
	}
    
    //开启检查缓存
    function start_cache($cache_file){
        $cache_file = $cache_file.'_'.$this->template.'.php';
        $cache_num = (int)$this->webconf['cachefilenum'];
        if($cache_num){
            $cache_file_list = getCache('cache_list');
            $cache_file_list = $cache_file_list ?: [];
            $n = count($cache_file_list);
            $cache_num = $cache_num<=500 ?: 500;
            if($n && $n>$cache_num ){
                $del = array_slice($cache_file_list,0,$n-$cache_num);
                
                $cache_file_list = array_slice($cache_file_list,$n-$cache_num);
                foreach($del as $v){
                    unlink($v);
                }
                
            }
            $cache_file_list[] = $cache_file;
            setCache('cache_list',$cache_file_list);
        }
        if($this->webconf['iscachepage']==1){
            if(file_exists($cache_file)){
                
                //获取当前时间戳
                $now_time = time();
                //获取缓存文件时间戳
                $last_time = filemtime($cache_file);
                //如果缓存文件生成超过指定的时间直接删除文件
                if((($now_time - $last_time)/60)>$this->webconf['cache_time']){
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
                            echo JZLANG('系统创建').' [ '.str_replace('/','\\',$create_dir).' ] '.JZLANG('目录失败!');exit;
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
	
}