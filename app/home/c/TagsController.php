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
        $cache_file = APP_PATH.'cache/data/'.md5(REQUEST_URI);
        $this->cache_file = $cache_file;
        if(!$this->frparam('ajax')){
            $this->start_cache($cache_file);
        }
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
			$sqln = [];
			foreach($tables as $v){
				$sqlx[] = " select id,tid,litpic,title,hits,tags,keywords,molds,htmlurl,ownurl,description,addtime,userid,member_id from ".DB_PREFIX.$v." where tags like '%,".$keywords.",%' and isshow=1 ";
				$sqln[] = " select id from ".DB_PREFIX.$v." where tags like '%,".$keywords.",%' and isshow=1 ";
			}
			$sql = implode(' union all ',$sqlx);
			$sqln = implode(' union all ',$sqln);
			$page = new Page();
			$this->currentpage = $this->frpage;
			$data = $page->where($sql)->limit($this->frparam('limit',0,15))->page($this->frpage)->goCount($sqln)->goSql();
			foreach($data as $k=>$v){
				$data[$k]['url'] = gourl($v,$v['htmlurl']);
				$data[$k]['classname'] = $this->classtypedata[$v['tid']]['classname'];
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
            if(!$this->frparam('ajax')){
                $this->end_cache($this->cache_file);
            }
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
            if(!$this->frparam('ajax')){
                $this->end_cache($this->cache_file);
            }
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