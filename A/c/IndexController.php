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


namespace A\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;
class IndexController extends CommonController
{
	public function index(){
		$this->admin = $_SESSION['admin'];
		$desktop = M('Layout')->find(array('gid'=>$_SESSION['admin']['gid']));
		if(!$desktop){
			$desktop = M('Layout')->find(array('isdefault'=>1));
		}
		
		$this->left_layout = json_decode($desktop['left_layout'],true);
		$this->left_num = count($this->left_layout);
		$this->top_layout = json_decode($desktop['top_layout'],true);
		$rulers = M('Ruler')->findAll(array('isdesktop'=>1));
		$actions = array();
		foreach($rulers as $k=>$v){
			$actions[$v['id']] = $v;
		}
		
		$this->actions = $actions;

		$this->display('index');
	}
	public function details(){
		$this->fields_biaoshi = 'level';
		$id = frdecode($this->frparam('id',1));
		$this->member = M('level')->find('id='.$id);
		if($_SESSION['admin']['isadmin']==1){
			
			$this->isadmin = true;
		}else{
			$this->isadmin = false;
		}
       
        $this->groups = M('level_group')->findAll();
		
		$this->display('admin');
	}
	public function adminedit(){
		
		$this->fields_biaoshi = 'level';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data = get_fields_data($data,'level');
			//防止越权操作
			if(isset($data['gid'])){
				if($this->admin['gid']>$data['gid'] && $this->admin['isadmin']!=1){
					JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
				}
			}
			$data['email'] = $this->frparam('email',1);
			$data['pass'] = $this->frparam('pass',1);
			$data['repass'] = $this->frparam('repass',1);
			
			$data['name'] = $this->frparam('name',1);
			$data['tel'] = $this->frparam('tel',1);
			$data['status'] = $this->frparam('status');
			$data['id'] = $this->admin['id'];
			if($data['id']==0){
				JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
			}
			
			
            
			if($data['pass']!=''){
				if($data['pass']!=$data['repass']){
					JsonReturn(array('code'=>1,'msg'=>'两次密码不同！'));
				}
				$data['pass'] = md5(md5($data['pass']).'YF');
			}else{
				unset($data['pass']);
			}

			
          
           
			
			if($data['tel']!=''){
				if(M('level')->find("tel='".$data['tel']."' and id!=".$data['id'])){
					JsonReturn(array('code'=>1,'msg'=>'手机号已被注册！'));
				}
			}
			
			if(M('level')->find("name='".$data['name']."' and id!=".$data['id'])){
				JsonReturn(array('code'=>1,'msg'=>'昵称已被使用！'));
			}
			if($data['email']!=''){
				if(M('level')->find("email='".$data['email']."' and id!=".$data['id'])){
					JsonReturn(array('code'=>1,'msg'=>'邮箱已被使用！'));
				}
			}
			
			$x = M('level')->update(array('id'=>$data['id']),$data);
			if($x){
				JsonReturn(array('code'=>0,'msg'=>'修改成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
			}
			
		}
	}
	public function welcome(){
		//var_dump($_SERVER);
		
        $this->isadmin = $_SESSION['admin']['isadmin'];
      
		//计算站内信息
		$member_count = M('member')->getCount();
		$this->member_count = $member_count;
		
		$this->article_num = M('article')->getCount();
		$this->product_num = M('product')->getCount();
		$this->message_num = M('message')->getCount();
        
		$this->display('welcome');
	}
	
	function beifen(){
		
		//读取备份数据库
		$dir = APP_PATH.'/backup';
		$fileArray=array();
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&& strpos($file,".php")) {
					$fileArray[$i]=$file;
					if($i==100){
						break;
					}
					$i++;
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		//var_dump($fileArray);
		$this->lists = $fileArray;
		$this->display('beifen');
	}
	
	function backup(){
		
		$pconfig = array(
			'host' =>DB_HOST,
			'port' =>DB_PORT,
			'user' =>DB_USER,
			'password' =>DB_PASS,
			'database' =>DB_NAME
		);
		$database = new \DatabaseTool($pconfig);

		$database->backup();
		//$this->beifen();
	}
	
	function huanyuan(){
		$file = trim($this->frparam('file',1));
		if($file==''){
			Error('非法操作！');
		}
		
		$pconfig = array(
			'host' =>DB_HOST,
			'port' =>DB_PORT,
			'user' =>DB_USER,
			'password' =>DB_PASS,
			'database' =>DB_NAME
		);
		$database = new \DatabaseTool($pconfig);
		$file = APP_PATH.'/backup/'.$file;
		$database->restore($file);
	}
	
	function shanchu(){
		$file = trim($this->frparam('file',1));
		if($file==''){
			Error('非法操作！');
		}
		
		$a = unlink(APP_PATH.'/backup/'.$file);
		if($a){
			Success('删除成功！',U('beifen'));
		}else{
			Error('删除失败！');
		}
		
	}
	
	//桌面设置
	function desktop(){
		$page = new Page('Layout');
		$sql = null;

		$data = $page->where($sql)->orderby('id ASC')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		$this->display('desktop');
	}
	
	function desktop_add(){
		if($this->frparam('go')==1){
			$data['name'] = $this->frparam('name',1);
			
			$left_nav = $this->frparam('left_nav',2);//数组
			$top_nav = $this->frparam('top_nav',2);//数组
			$left_nav_icon = $this->frparam('left_nav_icon',2);//数组
			$top_nav_icon = $this->frparam('top_nav_icon',2);//数组
			$left_num_count = $this->frparam('left_num_count',2);//数组
			$top_num_count = $this->frparam('top_num_count',2);//数组
			
			$left_nav_func = array();
			$top_nav_func = array();
			
			foreach($left_num_count as $v){
				$lf = 'left_nav_func_'.$v;
				$left_nav_func[] = $this->frparam($lf,2);
			}
			foreach($top_num_count as $v){
				$lf = 'top_nav_func_'.$v;
				$top_nav_func[] = $this->frparam($lf,2);
			}
			
			$left_layout = array();
			foreach($left_nav as $k=>$v){
				if(($v!='' && $left_nav_icon[$k]=='') || ($v=='' && $left_nav_icon[$k]!='') && ($v!='' && $left_nav_icon[$k]!='')){
					$left_layout[] = array('name'=>$v,'icon'=>$left_nav_icon[$k],'nav'=>$left_nav_func[$k]);
				}
			}
			$top_layout = array();
			foreach($top_nav as $k=>$v){
				if(($v!='' && $top_nav_icon[$k]=='') || ($v=='' && $top_nav_icon[$k]!='') && ($v!='' && $top_nav_icon[$k]!='')){
					$top_layout[] = array('name'=>$v,'icon'=>$top_nav_icon[$k],'nav'=>$top_nav_func[$k]);
				}
			}
			$data['left_layout'] = json_encode($left_layout,JSON_UNESCAPED_UNICODE);
			$data['top_layout'] = json_encode($top_layout,JSON_UNESCAPED_UNICODE);
			$data['gid'] = $this->frparam('gid');
			$data['isdefault'] = $this->frparam('isdefault');
			
			$data['ext'] = $this->frparam('ext',1);
			$n = M('Layout')->add($data);
			if($n){
				if($data['isdefault']==1){
					M('Layout')->update('id!='.$n,array('isdefault'=>0));
				}
				JsonReturn(array('code'=>0,'msg'=>'新增成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'新增失败！'));
				
			}
			exit;
		}
		$this->lists = M('Ruler')->findAll(array('isdesktop'=>1),'id ASC');
		$this->display('desktop-add');
	}
	
	function desktop_edit(){
		$id = $this->frparam('id');
		if(!$id){
			JsonReturn(array('code'=>1,'msg'=>'请选择桌面配置！'));
		}
		if($this->frparam('go')==1){
			$data['name'] = $this->frparam('name',1);
			
			$left_nav = $this->frparam('left_nav',2);//数组
			$top_nav = $this->frparam('top_nav',2);//数组
			$left_nav_icon = $this->frparam('left_nav_icon',2);//数组
			$top_nav_icon = $this->frparam('top_nav_icon',2);//数组
			$left_num_count = $this->frparam('left_num_count',2);//数组
			$top_num_count = $this->frparam('top_num_count',2);//数组
			
			$left_nav_func = array();
			$top_nav_func = array();
			
			
			foreach($left_num_count as $v){
				$lf = 'left_nav_func_'.$v;
				$left_nav_func[] = $this->frparam($lf,2);
			}
			foreach($top_num_count as $v){
				$lf = 'top_nav_func_'.$v;
				$top_nav_func[] = $this->frparam($lf,2);
			}
			
			
			$left_layout = array();
			foreach($left_nav as $k=>$v){
				if($v!=''){
					$left_layout[] = array('name'=>$v,'icon'=>$left_nav_icon[$k],'nav'=>$left_nav_func[$k]);
				}
			}
			$top_layout = array();
			foreach($top_nav as $k=>$v){
				if($v!=''){
					$top_layout[] = array('name'=>$v,'icon'=>$top_nav_icon[$k],'nav'=>$top_nav_func[$k]);
				}
			}
			$data['left_layout'] = json_encode($left_layout,JSON_UNESCAPED_UNICODE);
			$data['top_layout'] = json_encode($top_layout,JSON_UNESCAPED_UNICODE);
			$data['gid'] = $this->frparam('gid');
			$data['isdefault'] = $this->frparam('isdefault');
			$data['ext'] = $this->frparam('ext',1);
			$n = M('Layout')->update(array('id'=>$id),$data);
			if($n){
				if($data['isdefault']==1){
					M('Layout')->update('id!='.$id,array('isdefault'=>0));
				}
				JsonReturn(array('code'=>0,'msg'=>'修改成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
				
			}
			exit;
		}
		$data = M('Layout')->find(array('id'=>$id));
		$this->data = $data;
		$top_layout = json_decode($data['top_layout'],1);
		$left_layout = json_decode($data['left_layout'],1);
		$this->top_layout = $top_layout;
		$this->left_layout = $left_layout;
		$this->top_num = count($top_layout);
		$this->left_num = count($left_layout);
		$this->lists = M('Ruler')->findAll(array('isdesktop'=>1),'id ASC');
		$this->display('desktop-edit');
	}
	
	function desktop_del(){
		$id = $this->frparam('id');
		if($id){
			//判断是否系统
			$layout = M('Layout')->find(array('id'=>$id));
			if($layout['sys']==1){
				JsonReturn(array('code'=>1,'msg'=>'系统默认不可删除！'));
			}
			if(M('Layout')->delete('id='.$id)){
				
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	function unicode(){
		$this->display('unicode');
	}
	
	 //更新session的过期时间
    function update_session_maxlifetime(){
	  $cache_time = (int)webConf('cache_time');
	  $cache_time = $cache_time==0 ? 600 : $cache_time;
	  setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], time() + $cache_time);
	  
    }
	//清空缓存
	function cleanCache(){
		if($_POST){
			$cache =$this->frparam('cache_data',2);
			foreach($cache as $v){
				switch($v){
					case 'log':
					if(is_dir(APP_PATH.'cache/log')){
						if($handle = opendir(APP_PATH.'cache/log')){
			
						  while (false !== ($file = readdir($handle))){
							 if($file!='.' && $file!='..'){
								
								unlink(APP_PATH.'cache/log/'.$file);
							 }
						  }
						  closedir($handle);
						}
					}
					break;
					case 'tpl':
					if(is_dir(APP_PATH.'cache')){
						if($handle = opendir(APP_PATH.'cache')){
			
						  while (false !== ($file = readdir($handle))){
							 if($file!='.' && $file!='..' && $file!='tmp' && $file!='log' && $file!='data'){
								
								unlink(APP_PATH.'cache/'.$file);
							 }
						  }
						  closedir($handle);
						}
					}
					break;
					case 'login':
					if(is_dir(APP_PATH.'cache/tmp')){
						if($handle = opendir(APP_PATH.'cache/tmp')){
			
						  while (false !== ($file = readdir($handle))){
							 if($file!='.' && $file!='..' && $file!='sess_'.$_COOKIE['PHPSESSID'] && $file!='.htaccess' && $file!='web.config'){
								
								unlink(APP_PATH.'cache/tmp/'.$file);
							 }
						  }
						  closedir($handle);
						}
					}
					break;
					case 'data':
					if(is_dir(APP_PATH.'cache/data')){
						if($handle = opendir(APP_PATH.'cache/data')){
			
						  while (false !== ($file = readdir($handle))){
							 if($file!='.' && $file!='..' ){
								
								unlink(APP_PATH.'cache/data/'.$file);
							 }
						  }
						  closedir($handle);
						}
					}
					break;
					
				}
			}
			
			
			JsonReturn(['code'=>0,'msg'=>'success']);
			
			
		}
		//计算缓存数据大小
		$datacache = 0;
		if(is_dir(APP_PATH.'cache/data')){
			if($handle = opendir(APP_PATH.'cache/data')){
			
			  while (false !== ($file = readdir($handle))){
				 if($file!='.' && $file!='..'){
					
					 $datacache+=round(filesize(APP_PATH.'cache/data/'.$file)/1024,2);
				 }
			  }
			  closedir($handle);
			}
		}
		//登录缓存
		$logincache = 0;
		if(is_dir(APP_PATH.'cache/tmp')){
			if($handle = opendir(APP_PATH.'cache/tmp')){
			
			  while (false !== ($file = readdir($handle))){
				 if($file!='.' && $file!='..' && $file!='.htaccess' && $file!='web.config'){
					 $logincache+=round(filesize(APP_PATH.'cache/tmp/'.$file)/1024,2);
				 }
			  }
			  closedir($handle);
			}
		}
		//日志缓存
		$logcache = 0;
		if(is_dir(APP_PATH.'cache/log')){
			if($handle = opendir(APP_PATH.'cache/log')){
			
			  while (false !== ($file = readdir($handle))){
				 if($file!='.' && $file!='..'){
					 $logcache+=round(filesize(APP_PATH.'cache/log/'.$file)/1024,2);
				 }
			  }
			  closedir($handle);
			}	
		}
		
		//模板缓存
		$tplcache = 0;
		if(is_dir(APP_PATH.'cache')){
			if($handle = opendir(APP_PATH.'cache')){
			
			  while (false !== ($file = readdir($handle))){
				 if($file!='.' && $file!='..' && $file!='tmp' && $file!='log' && $file!='data'){
					 $tplcache+=round(filesize(APP_PATH.'cache/'.$file)/1024,2);
				 }
			  }
			  closedir($handle);
			}
		}
		$this->datacache = $datacache;
		$this->logcache = $logcache;
		$this->tplcache = $tplcache;
		$this->logincache = $logincache;
		$this->display('cache');
		
		
	}

	//模板标签生成
	function showlabel(){
		
		
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->classtypes = $this->classtypetree;
		$this->display('showlabel');
	}
	
	//网站地图XML
	function sitemap(){
		if($_POST){
			$model = $this->frparam('model',2);
			$isshow = $this->frparam('isshow',2);
			$freq = $this->frparam('freq',2);
			$priority = $this->frparam('priority',2);
			$www = get_domain();
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
			
			foreach($model as $k=>$v){
				if($isshow[$k]==1){
					$list = M($v)->findAll(['isshow'=>1]);
				}else{
					$list = M($v)->findAll();
				}
				//栏目
				if($v=='classtype' && $list){
					foreach($list as $s){
						$l.='<url>
							  <loc>'.$www.'/'.$s['htmlurl'].File_TXT.'</loc>
							  <lastmod>'.date('Y-m-d').'T08:00:00+00:00</lastmod>
							  <changefreq>'.$freq[$k].'</changefreq>
							  <priority>'.$priority[$k].'</priority>
							</url>';
					}
				}else if(($v=='article' || $v=='product') && $list){
					foreach($list as $s){
					//文章-商品
					$l.='<url>
						  <loc>'.$www.'/'.$s['htmlurl'].'/'.$s['id'].File_TXT.'</loc>
						  <lastmod>'.date('Y-m-d',$s['addtime']).'T08:00:00+00:00</lastmod>
						  <changefreq>'.$freq[$k].'</changefreq>
						  <priority>'.$priority[$k].'</priority>
						</url>';
					
					}
				}
				
				
			}
			
			$l.='</urlset>';
			
			$n = file_put_contents(APP_PATH.'sitemap.xml',$l);
			if($n){
				JsonReturn(['code'=>0,'msg'=>'网站地图创建成功！']);
				
			}
			JsonReturn(['code'=>1,'msg'=>'网站地图创建失败，请检查根目录权限！']);
			
			
		}
		
		$this->display('sitemap');
	}
	
	//生成静态文件
	function tohtml(){
		if($_POST){
			$type = $this->frparam('type');
			
			
			if($type==1){
				
				$model = $this->frparam('model',1);
				$isshow = $this->frparam('isshow');
				$www = get_domain();
				$sql = $isshow==2?null:'isshow=1';
				
				//单独更新
				$modelname = get_info_table('molds',['biaoshi'=>$model],'name');
				switch($model){
					case 'classtype':
						$this->html_classtype($sql);
						JsonReturn(['code'=>0,'msg'=>'栏目静态HTML更新成功！']);
					
					break;
					//文章商品模块是同样的
					default:
					$this->html_molds($model,$sql);
					JsonReturn(['code'=>0,'msg'=>$modelname.'模块静态HTML更新成功！']);
					
					break;
					
					
				}
				
				
			}else{
				//批量更新
				//以防内容过多，更新不过来
				$model = $this->frparam('model',2);
				$isshow = $this->frparam('isshow',2);
				$www = get_domain();
				set_time_limit(0);
				if($model && $isshow){
					foreach($model as $k=>$v){
						$sql = $isshow[$k]==2?null:'isshow=1';
						if($v=='classtype'){
							$this->html_classtype($sql);
						}else{
							$this->html_molds($v,$sql);
							
						}
						
						
					}
					
					JsonReturn(['code'=>0,'msg'=>'批量HTML更新成功！']);
					
					
					
				}
				
				
				
				
			}
			
			
		}
		
		//是否有静态HTML更新
		$html_cache = getCache('html_cache');
		if($html_cache){
			/*
				type  更新类型
				sql   更新的sql查询
				model   更新模型
				curren_num  当前更新数
				all_num  总数

			*/
			$newcache = [];
			foreach($html_cache as $k=>$v){
				switch($v['type']){
					//栏目数超过100个
					case 'classtype':
					$limit = $v['curren_num'].',100';
					if(($v['curren_num']+100) >=$v['all_num']){
						
					}else{
						$v[$k]['curren_num'] = $v['curren_num']+100;
						$newcache[]=$v;
					}
					$this->html_classtype($v['sql'],$limit);
					
					
					break;
					//分页超过100页
					case 'classtype_page':
					//暂时取消
					break;
					//模块内容超过100个
					case 'molds':
						$limit = $v['curren_num'].',100';
						if(($v['curren_num']+100) >=$v['all_num']){
							
						}else{
							$v[$k]['curren_num'] = $v['curren_num']+100;
							$newcache[]=$v;
						}
						$this->html_molds($v['model'],$v['sql'],$limit);
					
					break;
					
					
					
				}
				
			}
			
			if(empty($newcache)){
				setCache('html_cache',null);
			}else{
				setCache('html_cache',$newcache);
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="refresh" content="3;URL='.U('tohtml').'">';
				echo '系统正在进行下一步生成静态HTML，请稍后~';
				exit;
			}
			
			
		
			
			
		}
		
		
		
		$this->display('tohtml');
	}
	
	
	function html_classtype($sql,$limit=null){
		$www = get_domain();
		//计算栏目个数
		if($limit==null){
			$count = M('classtype')->getCount($sql);
			if($count>100){
				$limit = '1,100';
				$cache_html = getCache('html_cache');
				if($cache_html){
					$cache_html[]=[
						'type' => 'classtype',
						'sql' => $sql,
						'model' => 'classtype',
						'curren_num' => 100,
						'all_num' => $count,
					];
				}else{
					$cache_html = [];
					$cache_html[]=[
						'type' => 'classtype',
						'sql' => $sql,
						'model' => 'classtype',
						'curren_num' => 100,
						'all_num' => $count,
					];
				}
				setCache('html_cache',$cache_html);
				
			}
			
		}
		
		$lists = M('classtype')->findAll($sql,' id asc ',null,$limit);
		$classtypedata = classTypeData();
		foreach($classtypedata as $k=>$v){
			$classtypedata[$k]['children'] = get_children($v,$classtypedata);
		}
		if($lists){
			//更新静态注意事项：
			//1 创建目录文件夹--权限问题
			//2 栏目在根目录中
			//3 从缓存中抓取是最快的
			
			foreach($lists as $v){
				$filename = $v['htmlurl'].File_TXT;
				$url = '/'.$filename;
				$cache_url = APP_PATH.'cache/data/'.md5(frencode($url));
				if(file_exists($cache_url)){
					//栏目在根目录里
					file_put_contents($filename,file_get_contents($cache_url));
				}else{
					file_put_contents($filename,file_get_contents($www.$url));
				}
				//检查分页
				$sql = 'tid in('.implode(",",$classtypedata[$v['id']]["children"]["ids"]).') ';
				$count = M($v['molds'])->getCount($sql);
				$pagenum = ceil($count/$v['lists_num']);
				if($pagenum>1){
					for($i=1;$i<=$pagenum;$i++){
						$filename = $v['htmlurl'].'-'.$i.File_TXT;
						$url = '/'.$filename;
						$cache_url = APP_PATH.'cache/data/'.md5(frencode($url));
						if(file_exists($cache_url)){
							//栏目在根目录里
							file_put_contents($filename,file_get_contents($cache_url));
						}else{
							file_put_contents($filename,file_get_contents($www.$url));
						}
					}
				}
				
				
			}
		}
		
	}
	
	function html_molds($model,$sql=null,$limit=null){
		$modelname = get_info_table('molds',['biaoshi'=>$model],'name');
		if($limit==null){
			$count =  M($model)->getCount($sql);
			if($count>100){
				$limit = '1,100';
				$cache_html = getCache('html_cache');
				if($cache_html){
					$cache_html[]=[
						'type' => 'molds',
						'sql' => $sql,
						'model' => $model,
						'curren_num' => 100,
						'all_num' => $count,
					];
				}else{
					$cache_html = [];
					$cache_html[]=[
						'type' => 'molds',
						'sql' => $sql,
						'model' => $model,
						'curren_num' => 100,
						'all_num' => $count,
					];
				}
				setCache('html_cache',$cache_html);
			}
		}
		
		$lists = M($model)->findAll($sql,' id asc ',null,$limit);
		$www = get_domain();
		if($lists){
			//更新静态注意事项：
			//1 创建目录文件夹--权限问题
			//2 栏目在根目录中
			//3 从缓存中抓取是最快的
			
			foreach($lists as $v){
				
				//检测htmlurl是否为空
				if(trim($v['htmlurl'])==''){
					JsonReturn(['code'=>1,'msg'=>$modelname.'模块未绑定栏目，无法生存HTML！']);
				}
				
				
				//需要检测文件夹是否存在
				if(!is_dir(APP_PATH.$v['htmlurl'])){
					$r = mkdir(APP_PATH.$v['htmlurl'],0777,true);
					if(!$r){
						JsonReturn(['code'=>1,'msg'=>'根目录创建目录失败！']);
					}
				}
				
				$url = '/'.$v['htmlurl'].'/'.$v['id'].File_TXT;
				$filename = APP_PATH.$v['htmlurl'].'/'.$v['id'].File_TXT;
				$cache_url = APP_PATH.'cache/data/'.md5(frencode($url));
				if(file_exists($cache_url)){
					//栏目在根目录里
					file_put_contents($filename,file_get_contents($cache_url));
				}else{
					file_put_contents($filename,file_get_contents($www.$url));
				}
				
			}
		}
	}
	
	
	//内容过多时的静态HTML生成处理
	function html_multi(){
		$echo_html_cache = getCache('echo_html_cache');
		if($echo_html_cache){
			
			
			
		}
		return true;
		
		
	}
	
	
	
}