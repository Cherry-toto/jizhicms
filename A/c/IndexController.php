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
		$id = $this->admin['id'];
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data = get_fields_data($data,'level');
			
			$data['gid'] = $this->frparam('gid',0,$this->admin['gid']);
			//防止越权操作
			if($this->admin['gid']!=1 && $data['gid']==1){
				JsonReturn(array('code'=>1,'msg'=>'您没有权限操作！'));	
			}
			
			//检查token
			$token = getCache('admin_'.$this->admin['id'].'_token');
			if(!isset($_SESSION['token']) || !$token || $token!=$_SESSION['token']){
				JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
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
		
		$this->member = M('level')->find('id='.$id);
		if($_SESSION['admin']['isadmin']==1){
			
			$this->isadmin = true;
		}else{
			$this->isadmin = false;
		}
       
        $this->groups = M('level_group')->findAll();
		$token = getRandChar(10);
		$_SESSION['token'] = $token;
		setCache('admin_'.$this->admin['id'].'_token',$token,60);
		$this->token = $token;
		$this->display('admin');
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
        $classtypedata = (isMobile() && $this->webconf['iswap']==1)?classTypeDataMobile():classTypeData();
        $this->classtypedata = $classtypedata;
		$this->display('welcome');
	}
	
	function beifen(){
		
		//读取备份数据库
		$dir = APP_PATH.'backup';
		$fileArray=array();
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".." && strpos($file,".php")!==false && strpos($file,"_v")===false) {
					$fileArray[$i]=$file;
					
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
		$file = APP_PATH.'backup/'.$file;
		$database->restore($file);
	}
	
	function shanchu(){
		$file = trim($this->frparam('file',1));
		if($file==''){
			Error('非法操作！');
		}
		$f = explode('.php',$file);
		$filename = $f[0];
		if($filename==''){
			Error('非法操作！');
		}
		//读取备份数据库
		$dir = APP_PATH.'backup';
		$fileArray=array();
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&& strpos($file,$filename)!==false) {
					unlink(APP_PATH.'backup/'.$file);
					$i++;
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		
		Success('删除成功！',U('beifen'));
		
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
			$type = $this->frparam('type',1,'edit');
			if($type=='copy'){
				unset($data['id']);
				$n = M('Layout')->add($data);
				if($n){
					if($data['isdefault']==1){
						M('Layout')->update('id!='.$n,array('isdefault'=>0));
					}
					JsonReturn(array('code'=>0,'msg'=>'新增成功！'));
					
				}else{
					JsonReturn(array('code'=>1,'msg'=>'新增失败！'));
					
				}
			}else{
				$n = M('Layout')->update(array('id'=>$id),$data);
				if($n){
					if($data['isdefault']==1){
						M('Layout')->update('id!='.$id,array('isdefault'=>0));
					}
					JsonReturn(array('code'=>0,'msg'=>'修改成功！'));
					
				}else{
					JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
					
				}
			}
			
			
		}
		$data = M('Layout')->find(array('id'=>$id));
		$this->data = $data;
		$this->type = $this->frparam('type',1,'edit');
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
			
			$_SESSION['terminal'] = null;
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
					case 'pc_html':
					
					
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
			$pc_html = (trim($this->webconf['pc_html'])=='' || $this->webconf['pc_html']=='/') ? '/' : '/'.$this->webconf['pc_html'].'/';
			$pc_html = trim($pc_html);
			$mobile_html = (trim($this->webconf['mobile_html'])=='' || $this->webconf['mobile_html']=='/') ? '/' : '/'.$this->webconf['mobile_html'].'/';;
			$mobile_html = trim($mobile_html);
			
			 $classtypedataMobile = classTypeDataMobile();
			 foreach($classtypedataMobile as $k=>$v){
				$classtypedataMobile[$k]['children'] = get_children($v,$classtypedataMobile);
			 }
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
							  <loc>'.$this->classtypedata[$s['id']]['url'].'</loc>
							  <lastmod>'.date('Y-m-d').'T08:00:00+00:00</lastmod>
							  <changefreq>'.$freq[$k].'</changefreq>
							  <priority>'.$priority[$k].'</priority>
							</url>';
						if($this->webconf['iswap']==1){
							$l.='<url>
							  <loc>'.$classtypedataMobile[$s['id']]['url'].'</loc>
							  <lastmod>'.date('Y-m-d').'T08:00:00+00:00</lastmod>
							  <changefreq>'.$freq[$k].'</changefreq>
							  <priority>'.$priority[$k].'</priority>
							</url>';
						}	
					}
				}else if($list){
					foreach($list as $s){
						$s['addtime'] = isset($s['addtime']) ? $s['addtime'] : time();
					//文章-商品
						$l.='<url>
						  <loc>'.gourl($s,$s['htmlurl']).'</loc>
						  <lastmod>'.date('Y-m-d',$s['addtime']).'T08:00:00+00:00</lastmod>
						  <changefreq>'.$freq[$k].'</changefreq>
						  <priority>'.$priority[$k].'</priority>
						</url>';
						if($this->webconf['iswap']==1){
							$l.='<url>
							  <loc>'.gourl($s,$s['htmlurl']).'</loc>
							  <lastmod>'.date('Y-m-d',$s['addtime']).'T08:00:00+00:00</lastmod>
							  <changefreq>'.$freq[$k].'</changefreq>
							  <priority>'.$priority[$k].'</priority>
							</url>';
						}	
					
					}
				}
				
				
			}
			
			$l.='</urlset>';
			
			//$f = @fopen(APP_PATH.'sitemap.xml','w');
			//$n = @fwrite($f,$l);
			//@fclose($f);
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
		
		$maxlimit = 500;
		$sleep = 2;//最小填0，立即跳转。

		if($_POST){
			$_SESSION['terminal'] = $this->frparam('terminal',1,'pc');
			$terminal_path = $_SESSION['terminal']=='pc' ? $this->webconf['pc_html'] : $this->webconf['mobile_html'];
			$terminal_path = ($terminal_path=='' || $terminal_path=='/') ? '' : $terminal_path.'/';
			
			$type = $this->frparam('type');
			setCache('tohtmlurl',null);
			if($this->frparam('clearhtml')){
				setCache('clearhtml',1);
			}
			
			if(isset($_SESSION['terminal'])){
				$classtypedata = $_SESSION['terminal']=='mobile' ? classTypeDataMobile() : classTypeData();
			}else{
				$classtypedata = (isMobile() && $webconf['iswap']==1)?classTypeDataMobile():classTypeData();
			}
		
			foreach($classtypedata as $k=>$v){
				$classtypedata[$k]['children'] = get_children($v,$classtypedata);
			}
			//echo '提交成功！';
			if($type==1){
				//有选择的更新HTML
				$model = $this->frparam('model',1);
				$isshow = $this->frparam('isshow');
				$tid = $this->frparam('tid');
				$www = get_domain();
				$sql = ' 1=1 ';
				if($isshow!=2){
					$sql.=' and isshow=1 ';
				}
				
				//单独更新
				$modelname = get_info_table('molds',['biaoshi'=>$model],'name');
				$urls = [];
				switch($model){
					case 'classtype':
						if($tid){
							$sql.=' and id in('.implode(",",$classtypedata[$tid]["children"]["ids"]).') ';
						}
						
						$urls = $this->html_classtype($sql);//获取所有的更新静态链接
						$urls[]= ['url'=>$www,'html'=>APP_PATH.$terminal_path.'index.html'];
						
						setCache('tohtmlurl',$urls,86400);
						//$_SESSION['terminal'] = null;
						
						JsonReturn(['code'=>0,'msg'=>'success']);
						exit;
					break;
					//文章商品模块是同样的
					default:
						if($tid){
							$sql.=' and tid in('.implode(",",$classtypedata[$tid]["children"]["ids"]).') ' ;
						}
						
						$urls = $this->html_molds($model,$sql);
						$urls[]= ['url'=>$www,'html'=>APP_PATH.$terminal_path.'index.html'];
						setCache('tohtmlurl',$urls,86400);
						//$_SESSION['terminal'] = null;
						JsonReturn(['code'=>0,'msg'=>'success']);
						exit;
					break;
					
					
				}
				
				
			}else{
				//批量更新
				//以防内容过多，更新不过来
				$model = $this->frparam('model',2);
				$isshow = $this->frparam('isshow',2);
				$tid = $this->frparam('tid',2);
				$www = get_domain();
				set_time_limit(0);
				if($model && $isshow){
					$urls = [];
					foreach($model as $k=>$v){
						
						$sql = ' 1=1 ';
						if($isshow[$k]!=2){
							$sql.=' and isshow=1 ';
						}
								
						if($v=='classtype'){
							if($tid[$k]){
								$sql.=' and id in('.implode(",",$classtypedata[$tid[$k]]["children"]["ids"]).') ';
							}
							
							$urls1 = $this->html_classtype($sql);
							$urls = count($urls1)>0 ? array_merge($urls,$urls1) : $urls;
						}else{
							if($tid[$k]){
								$sql.=' and tid in('.implode(",",$classtypedata[$tid[$k]]["children"]["ids"]).') ';
							}
							
							$urls2 = $this->html_molds($v,$sql);
							$urls = count($urls2)>0 ? array_merge($urls,$urls2) : $urls;
							
						}
						
						
					}
					$urls[]= ['url'=>$www,'html'=>APP_PATH.$terminal_path.'index.html'];
					setCache('tohtmlurl',$urls,86400);
					//$_SESSION['terminal'] = null;
					JsonReturn(['code'=>0,'msg'=>'success','urls'=>$urls]);
					exit;
					
					
				}
				
				
				
				
			}
			
			
		}


		$tohtmlurl = getCache('tohtmlurl');

		if($tohtmlurl){

			$clearhtml = getCache('clearhtml');

			$opts = array(
			  'http'=>array(
				'method'=>"GET",
				'header'=>"Cookie: PHPSESSID=".$_COOKIE['PHPSESSID']."\r\n"
			  )
			);

			$context = stream_context_create($opts);
			$max = count($tohtmlurl);
			$start_time = getCache('start_time');
			if(!$start_time){
				$start_time = time();
				setCache('start_time',$start_time,86400);
				setCache('allpage',$max);
			}

			$count = 0;
			foreach ($tohtmlurl as $key => $value) {
				if($key<$maxlimit){
					if($clearhtml){

						//清理HTML
						if(file_exists($value['html'])){
							$r = unlink($value['html']);
							if(!$r){
								echo $value['html'].'清除失败！<br/>';
							}else{
								echo $value['html'].'清除成功！<br/>';
							}
						}else{
							echo $value['html'].'清除成功！<br/>';
						}
							


					}else{
						$data = curl_http($value['url']);
						$f = @fopen($value['html'],'w');
						//$r = @fwrite($f,file_get_contents($value['url'],false,$context));
						$r = @fwrite($f,$data);
						@fclose($f);
						//$r = file_put_contents($value['html'],file_get_contents($value['url'],false,$context));
						if(!$r){
							echo $value['html'].'生成失败！<br/>';
						}else{
							echo $value['html'].'生成成功！<br/>';
						}
					}

					
					$count++;
				}else{
					$tohtmlurl = array_slice($tohtmlurl,$maxlimit);
					setCache('tohtmlurl',$tohtmlurl,86400);
					if($clearhtml){
						echo '已清理一部分页面，请不要关闭当前页面，还需要继续清理HTML~';
					}else{
						echo '已生成一部分页面，请不要关闭当前页面，还需要继续生成HTML~';
					}
					
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="refresh" content="'.$sleep.';URL='.U('tohtml').'">';
					exit;
				}
				
			}
			if($count>=$max){
				setCache('tohtmlurl',false);
				if($clearhtml){
					echo '静态HTML页面已全部清理完毕！<br/>';
					$end_time = time();
					$start_time = getCache('start_time');
					$allpage = getCache('allpage');
					echo '总共清理页面数：'.$allpage.' 每次清理页面数：'.$maxlimit.',停顿时间：'.$sleep.'秒,开始时间：'.date('Y-m-d H:i:s',$start_time).' ,结束时间：'.date('Y-m-d H:i:s',$end_time).', 总共花费时间：'.($end_time-$start_time).'秒';
					setCache('clearhtml',false);
				}else{
					echo '页面已全部生成完毕！<br/>';
					$end_time = time();
					$start_time = getCache('start_time');
					$allpage = getCache('allpage');
					echo '总共生成页面数：'.$allpage.' 每次生成页面数：'.$maxlimit.',停顿时间：'.$sleep.'秒,开始时间：'.date('Y-m-d H:i:s',$start_time).' ,结束时间：'.date('Y-m-d H:i:s',$end_time).', 总共花费时间：'.($end_time-$start_time).'秒';
				}
				
				setCache('start_time',false);
				setCache('allpage',false);
				$_SESSION['terminal'] = null;
				//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="refresh" content="2;URL='.U('tohtml').'">';
				exit;
			}else{

			}
			

		}
		
		
		
		
		
		$this->display('tohtml');
	}
	
	
	function html_classtype($sql,$limit=null){
		$terminal_path = $_SESSION['terminal']=='pc' ? $this->webconf['pc_html'] : $this->webconf['mobile_html'];
		$terminal_path = ($terminal_path=='' || $terminal_path=='/') ? '' : $terminal_path.'/';
		
		$www = get_domain();
		
		
		$lists = M('classtype')->findAll($sql,' id asc ',null,$limit);
		if(isset($_SESSION['terminal'])){
			$classtypedata = $_SESSION['terminal']=='mobile' ? classTypeDataMobile() : classTypeData();
		}else{
			$classtypedata = (isMobile() && $webconf['iswap']==1)?classTypeDataMobile():classTypeData();
		}
		foreach($classtypedata as $k=>$v){
			$classtypedata[$k]['children'] = get_children($v,$classtypedata);
		}
		$urls = [];
		if($lists){
			//更新静态注意事项：
			//1 创建目录文件夹--权限问题
			//2 栏目在根目录中
			//3 从缓存中抓取是最快的
			
			foreach($lists as $v){
				
				if($v['gourl']){
					continue;
				}
				$filename = $v['htmlurl'];
				//创建文件夹
				if(!is_dir(APP_PATH.$terminal_path)){
					$r = mkdir(APP_PATH.$terminal_path,0777,true);
					if(!$r){
						JsonReturn(['code'=>1,'msg'=>'系统创建 [ '.str_replace('/','\\',APP_PATH.$terminal_path).' ] 目录失败!']);
					}
				}
				if(strpos($filename,'/')!==false){
					$filepath = explode('/',$filename);
					array_pop($filepath);
					$dir = APP_PATH.$terminal_path.implode('/',$filepath);
					$create_dir = APP_PATH.$terminal_path;
					foreach($filepath as $vv){
						$create_dir.=$vv;
						if(!is_dir($create_dir)){
							$r = mkdir($create_dir,0777,true);
							if(!$r){
							
								JsonReturn(['code'=>1,'msg'=>'系统创建['.str_replace('/','\\',$create_dir).']目录失败!']);
							}
						}
						$create_dir.='/';
						
					}
				}
				
				if(File_TXT_HIDE){
					if(!file_exists(APP_PATH.$terminal_path.$filename)){
						$r = mkdir(APP_PATH.$terminal_path.$filename,0777);
						if(!$r){
							JsonReturn(['code'=>1,'msg'=>'系统创建['.str_replace('/','\\',APP_PATH.$terminal_path.$filename).']目录失败!']);
						}
					}
					$url = APP_PATH.$terminal_path.$filename.'/index.html';
					
				}else{
					$url = APP_PATH.$terminal_path.$filename.'.html';
				}
				
				
				
				$urls[] = ['url'=>$www.'/'.$terminal_path.$filename.'.html','html'=>$url];


				//检查分页
				$sql = 'tid in('.implode(",",$classtypedata[$v['id']]["children"]["ids"]).') ';
				$count = M($v['molds'])->getCount($sql);
				$pagenum = ceil($count/$v['lists_num']);
				if($pagenum>1){
					for($i=1;$i<=$pagenum;$i++){
						$filename = $v['htmlurl'].'-'.$i;
						if(File_TXT_HIDE){
							$url = APP_PATH.$terminal_path.$filename.'/index.html';
						}else{
							$url = APP_PATH.$terminal_path.$filename.'.html';
						}
						if(File_TXT_HIDE){
							if(!file_exists(APP_PATH.$terminal_path.$filename)){
								mkdir(APP_PATH.$terminal_path.$filename,0777);
							}
						}
						$urls[] = ['url'=>$www.'/'.$terminal_path.$filename.'.html','html'=>$url];
					}
					
				}
					
				
			}
		}
		return $urls;
		
	}
	
	function html_molds($model,$sql=null,$limit=null){
		$terminal_path = $_SESSION['terminal']=='pc' ? $this->webconf['pc_html'] : $this->webconf['mobile_html'];
		$terminal_path = ($terminal_path=='' || $terminal_path=='/') ? '' : $terminal_path.'/';
		$modelname = get_info_table('molds',['biaoshi'=>$model],'name');
		
		
		$lists = M($model)->findAll($sql,' id asc ',null,$limit);
		$www = get_domain();
		$urls=[];//存储更新url链接
		if($lists && is_array($lists)){
			//更新静态注意事项：
			//1 创建目录文件夹--权限问题
			//2 栏目在根目录中
			//3 从缓存中抓取是最快的
			
			foreach($lists as $v){
				if($v['target']){
					continue;
				}
				if($v['ownurl']){
					continue;
				}
				//检测htmlurl是否为空
				if(trim($v['htmlurl'])==''){
					//JsonReturn(['code'=>1,'msg'=>$modelname.'模块未绑定栏目，无法生存HTML！']);
					//echo $modelname.'模块未绑定栏目，无法生存HTML！';exit;
					JsonReturn(['code'=>1,'msg'=>$modelname.'模块未绑定栏目，无法生存HTML！']);
				}
				
				
				//需要检测文件夹是否存在
				//创建文件夹
				if(!is_dir(APP_PATH.$terminal_path)){
					$r = mkdir(APP_PATH.$terminal_path,0777,true);
					if(!$r){
						//JsonReturn(['code'=>1,'msg'=>'根目录创建目录失败！']);
						//echo '系统创建 [ '.str_replace('/','\\',APP_PATH.$terminal_path).' ] 目录失败!';exit;
						JsonReturn(['code'=>1,'msg'=>'系统创建 [ '.str_replace('/','\\',APP_PATH.$terminal_path).' ] 目录失败!']);
					}
					
				}
				
				if(strpos($v['htmlurl'],'/')!==false){
					$filepath = explode('/',$v['htmlurl']);
					//array_pop($filepath);
					$dir = APP_PATH.$terminal_path.implode('/',$filepath);
					$create_dir = APP_PATH.$terminal_path;
					foreach($filepath as $vv){
						$create_dir.=$vv;
						if(!is_dir($create_dir)){
							
							$r = mkdir($create_dir,0777,true);
							if(!$r){
								//JsonReturn(['code'=>1,'msg'=>'根目录创建目录失败！']);
								//echo '系统创建 [ '.str_replace('/','\\',$create_dir).' ] 目录失败!';exit;
								JsonReturn(['code'=>1,'msg'=>'系统创建 [ '.str_replace('/','\\',$create_dir).' ] 目录失败!']);
							}
							
						}
						$create_dir.='/';
						
					}
				}else{
					if(!is_dir(APP_PATH.$terminal_path.$v['htmlurl'])){
						$r = mkdir(APP_PATH.$terminal_path.$v['htmlurl'],0777,true);
						if(!$r){
							//JsonReturn(['code'=>1,'msg'=>'根目录创建目录失败！']);
							//echo '系统创建 [ '.str_replace('/','\\',APP_PATH.$terminal_path.$v['htmlurl']).' ] 目录失败！';exit;
							JsonReturn(['code'=>1,'msg'=>'系统创建 [ '.str_replace('/','\\',APP_PATH.$terminal_path.$v['htmlurl']).' ] 目录失败！']);
						}
					}
				}
				
				
				
				$url = APP_PATH.$terminal_path.$v['htmlurl'].'/'.$v['id'].'.html';
				$filename = APP_PATH.$terminal_path.$v['htmlurl'].'/'.$v['id'].'.html';

				$urls[] = ['url'=>$www.'/'.$v['htmlurl'].'/'.$v['id'].'.html','html'=>$filename];

				
				
			}
		}
		return $urls;
		
	}
	
	
	//内容过多时的静态HTML生成处理
	function html_multi(){
		$echo_html_cache = getCache('echo_html_cache');
		if($echo_html_cache){
			
			
			
		}
		return true;
		
		
	}
	
	
	
}