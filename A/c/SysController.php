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

class SysController extends CommonController
{


	
   public function index(){
   
		$web_config = $this->webconf;
		$custom = M('sysconfig')->findAll('type!=0');
		if($_POST){
			$data = $this->frparam();
			foreach($web_config as $k=>$v){
				if (array_key_exists($k,$data)) {
					if($k=='web_js'){
						$value = $_POST['web_js'];
						$value=htmlspecialchars(trim($value), ENT_QUOTES);
						if(version_compare(PHP_VERSION,'7.4','>=')){
							$value = addslashes($value);
						}else{
							if(!get_magic_quotes_gpc())$value = addslashes($value);
						} 
						M('sysconfig')->update(['field'=>'web_js','type'=>0],['data'=>$value]);
					}else{
						M('sysconfig')->update(['field'=>$k,'type'=>0],['data'=>$this->frparam($k,1)]);
					}
					
				}

			}
		   
		   
		   //检测扩展参数
		   $new_custom = array();
		   $data = $this->frparam();
		   $first_key = '';
		   if($custom){
			   
			foreach($custom as $v){
			   if(array_key_exists($v['field'],$data)){
					if($v['type']==4){
						M('sysconfig')->update("type!=0 and field='".$v['field']."'",['data'=>$this->frparam($v['field'],4)]);
					}else{
						M('sysconfig')->update("type!=0 and field='".$v['field']."'",['data'=>$this->frparam($v['field'],1)]);
					}
					
			   }
			  
			}
		
		      
		   }
		  
		   //检测是否有新增
		   if($this->frparam('custom_title',1)!='' && $this->frparam('custom_type')!=0){
			   $new['title'] = $this->frparam('custom_title',1);
			   $new['data'] = '';
			   $new['type'] = $this->frparam('custom_type');
			   if($this->frparam('custom_fields',1)){
				   $new['field'] = $this->frparam('custom_fields',1);
				   $n = M('sysconfig')->add($new);
			   }else{
				   $new['field'] = 'jizhi_demo';
				   $n = M('sysconfig')->add($new);
				   if($n){
					   M('sysconfig')->update(['id'=>$n],['field'=>'jz_'.$n]);
				   }
			   } 
		   }
		   $config = include(APP_PATH.'Conf/config.php');
		   if(checkAction('Sys/high-level')){
			   if($this->frparam('isdebug')){
					$config['APP_DEBUG'] = true;
			   }else{
					$config['APP_DEBUG'] = false;
			   }
			   if($this->frparam('hideclasspath')){
					$config['File_TXT_HIDE'] = true;
			   }else{
					$config['File_TXT_HIDE'] = false;
			   }
		   }
		   
		   
		   $ress = file_put_contents(APP_PATH.'Conf/config.php', '<?php return ' . var_export($config, true) . '; ?>');

		   if($this->webconf['pc_html']!=$this->frparam('pc_html',1) || $this->webconf['mobile_html']!=$this->frparam('mobile_html',1)){
			   setCache('classtype',null);
			   setCache('mobileclasstype',null);
			   setCache('classtypedatamobile',null);
			   setCache('classtypedatapc',null);
		   }
		   setCache('webconfig',null);
		   setCache('customconfig',null);
		   
		   JsonReturn(['code'=>0,'msg'=>'提交成功！']);
		   
		}
		//获取前台template
		$indexdata = file_get_contents(APP_PATH.'index.php');
		$r = preg_match("/define\('HOME_VIEW',[\'|\"](.*?)[\'|\"]\)/",$indexdata,$matches);
		if($r){
			$template = $matches[1];
		}else{
			$template = 'template';
		}
		$rr = preg_match("/define\('TPL_PATH',[\'|\"](.*?)[\'|\"]\)/",$indexdata,$matches);
		if($rr){
			$tplpath = $matches[1];
		}else{
			$tplpath = 'Home';
		}
		$dir = APP_PATH.$tplpath.'/'.$template;
		$fileArray=array();
		if(is_dir($dir)){

			if (false != ($handle = opendir ( $dir ))) {
				$i=0;
				while ( false !== ($file = readdir ( $handle )) ) {
					//去掉"“.”、“..”以及带“.xxx”后缀的文件
					if ($file != "." && $file != ".."&& strpos($file,".html")===false) {
						$fileArray[]=$file;
						$i++;
					}
				}
				//关闭句柄
				closedir ( $handle );
			}
		}
		$this->templatelist = $fileArray;
		$this->config = $web_config;
		$this->custom =	$custom;
		$this->admin = $_SESSION['admin'];
		$this->display('sys');
   
   }
   
   function custom_del(){
	   $field = $this->frparam('field',1);
	   if($field){
		   M('sysconfig')->delete(" type!=0 and field='".$field."'");
		   setCache('customconfig',null);
		   JsonReturn(['code'=>0,'msg'=>'删除成功！']);
	   }
	    JsonReturn(['code'=>1,'msg'=>'删除失败！']);
	   
   }

	//登录日志
	function loginlog(){
		
		if($this->frparam('ajax')){
			$res = show_log('login');
			if(!$res){
				JsonReturn(['code'=>0,'data'=>array(),'count'=>0]);
			}
			if($this->admin['isadmin']!=1){
				$admins = M('level')->findAll(['gid'=>1]);
				$ids = [];
				foreach($admins as $v){
					$ids[]=$v['id'];
				}
				$new = [];
				foreach($res as $v){
					if(!in_array($v['data']['id'],$ids)){
						$new[]=$v;
					}
				}
				$res = $new;
			}
			
			rsort($res);
			$page = new \ArrayPage($res);
			
			if(isset($_GET['limit'])){
				$_SESSION['limit'] = $_GET['limit'];
			}

			if(isset($_SESSION['limit'])){
				$limit = $_SESSION['limit'];
			}else{
				$limit = 10;
			}
			$count = count($res);
			
			
			$lists = $page->query(['page'=>$this->frparam('page',0,1)])->setPage(['limit'=>$limit])->go();
			foreach($lists as $k=>$v){
				$lists[$k]['id'] = $v['data']['id'];
				$lists[$k]['username'] = $v['data']['name'];
			}
			
			
			JsonReturn(['code'=>0,'data'=>$lists,'count'=>$count]);
		}
		
		$this->display('loginlog');
		
	}
	
	//图片库
	function pictures(){
		$page = new Page('pictures');
		$sql = ' 1=1 ';
		if($this->frparam('molds',1)){
			if($this->frparam('molds',1)=='other'){
				$sql .= " and (molds='' || molds is null) ";
			}else{
				$sql .= " and molds='".$this->frparam('molds',1)."' ";
			}
			
		}
		if($this->frparam('path',1)){
			$sql .= " and path='".$this->frparam('path',1)."' ";
		}
		if($this->frparam('tid')){
			$sql .= ' and tid='.$this->frparam('tid');
		}
		$this->tid = $this->frparam('tid');
		$this->path = $this->frparam('path',1);
		$this->molds = $this->frparam('molds',1);
		
		$data = $page->where($sql)->orderby('addtime desc,id desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		
		$this->tid=  $this->frparam('tid');
		//$classtype = M('classtype')->findAll(null,'orders desc');
		//$classtype = getTree($classtype);
		$this->classtypes = $this->classtypetree;
		$this->display('pictures');
		
	}
	
	//删除图片
	function deletePic(){
		$id = $this->frparam('id');
		if($id){
			
			$pic = M('pictures')->find(['id'=>$id]);
			if(strpos($pic['litpic'],'http')===false){
				if(file_exists('.'.$pic['litpic'])){
					unlink('.'.$pic['litpic']);
					$r = M('pictures')->delete(['id'=>$id]);
					JsonReturn(['code'=>0,'msg'=>'删除成功！']);
				}else{
					JsonReturn(['code'=>1,'msg'=>'图片不存在，删除失败！']);
				}
				
			}else{
				JsonReturn(['code'=>1,'msg'=>'远程存储图片无法删除！']);
			}
			
			
			
		}else{
			JsonReturn(['code'=>1,'msg'=>'图片ID错误！']);
		}
		
	}
	//批量删除
	function deletePicAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			
			$pictures = M('pictures')->findAll('id in('.$data.')');
			$isall = true;
			foreach($pictures as $v){
				if(strpos($v['litpic'],'http')===false){
					if(file_exists('.'.$v['litpic'])){
						unlink('.'.$v['litpic']);
					}
					
				}else{
					$isall = false;
				}
				M('pictures')->delete(['id'=>$v['id']]);
			}
			
			if($isall){
				JsonReturn(array('code'=>0,'msg'=>'批量删除成功！'));
			}else{
				JsonReturn(array('code'=>0,'msg'=>'部分删除成功，存在远程链接无法删除！'));
			}
			
			
		}
		
	}
	
	//上传证书
	function uploadcert(){
		if ($_FILES["file"]["error"] > 0){
		  $data['error'] =  "Error: " . $_FILES["file"]["error"];
		  $data['code'] = 1000;
		}else{
		  // echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		  // echo "Type: " . $_FILES["file"]["type"] . "<br />";
		  // echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		  // echo "Stored in: " . $_FILES["file"]["tmp_name"];
			$pix = explode('.',$_FILES['file']['name']);
			$pix = end($pix);
			
			$fileType = webConf('fileType');
			if(strpos($fileType,strtolower($pix))===false  || stripos($pix,'php')!==false){
				$data['error'] =  "Error: 文件类型不允许上传！";
				$data['code'] = 1002;
				JsonReturn($data);
			}
			$fileSize = (int)webConf('fileSize');
			if($fileSize!=0 && $_FILES["file"]["size"]/(1024*1024)>$fileSize){
				$data['error'] =  "Error: 文件大小超过网站内部限制！";
				$data['code'] = 1003;
				JsonReturn($data);
			}
			if(!is_dir('static/upload')){
				mkdir('static/upload',0777);
			}
			if(!is_dir('static/upload/cert')){
				mkdir('static/upload/cert',0777);
			}
		    $filename =  'static/upload/cert/'.date('Ymd').rand(1000,9999).'.'.$pix;
		  
			if(move_uploaded_file($_FILES["file"]['tmp_name'],$filename)){
				$data['url'] = $filename;
				$data['code'] = 0;
				$filesize = round(filesize(APP_PATH.$filename)/1024,2);
				M('pictures')->add(['litpic'=>'/'.$filename,'addtime'=>time(),'userid'=>$_SESSION['admin']['id'],'size'=>$filesize,'filetype'=>strtolower($pix),'tid'=>$this->frparam('tid',0,0),'molds'=>$this->frparam('molds',1,null)]);
			}else{
				$data['error'] =  "Error: 请检查目录[static/upload/cert]写入权限";
				$data['code'] = 1001;
				  
			} 

			  
		  
		}

		JsonReturn($data);
		  
	}

	public function datacache(){
		$this->lists = M('cachedata')->findAll();
		$this->display('datacache');
	}
	
	public function addcache(){
		if($this->frparam('go',1)==1){
			$data = $this->frparam();
			$data['title'] = $this->frparam("title",1);
			$data['limits'] = $this->frparam("limits");
			$data['orders'] = $this->frparam("orders",1);
			$data['tid'] = $this->frparam('tid');
			$data['isall'] = $this->frparam('isall');
			$data['sqls'] = $this->frparam('sqls',1);
			$data['field'] = $this->frparam('field',1);
			$data['times'] = $this->frparam('times',0,10);
			$data['molds'] = $this->frparam('molds',1);
			
			if(stripos($data['sqls'],'update')!==false || stripos($data['sqls'],'delete')!==false || stripos($data['sqls'],'insert')!==false || stripos($data['sqls'],'drop')!==false || stripos($data['sqls'],'truncate')!==false){
				JsonReturn(array('code'=>1,'msg'=>'非法操作'));
			}
			$tid = $data['tid'] ? ($data['isall']==1 ? ' and tid in ('.implode(',',$this->classtypedata[$data['tid']]['children']['ids']).') ' : ' and tid='.$data['tid']) : '';
			$sqls = $data['sqls'] ? ' and '.$data['sqls'] : '';
			$orderby = $data['orders'] ? ' order by '.$data['orders'] : '';
			$limit = $data['limits'] ? ' limit '.$data['limits'] : '';
			if($tid || $sqls){
				$where = ' where 1=1 '.$tid.htmlspecialchars_decode($sqls,ENT_QUOTES);
			}else{
				$where = '';
			}
			$sql = "select * from ".DB_PREFIX.$data['molds'].$where.$orderby.$limit;
			$result = M()->findSql($sql);
			if($result){
				foreach($result as $k=>$v){
					if(isset($v['htmlurl'])){
						$result[$k]['url'] = gourl($v,$v['htmlurl']);
					}
				}
			}
			
			if(M('cachedata')->add($data)){
				
				$time = $data['times']*60;
				setCache('jzcache_'.$data['field'],$result,$time);
				
				JsonReturn(array('code'=>0,'msg'=>'添加成功！继续添加~','url'=>U('sys/addcache')));
				exit;
			}else{
				JsonReturn(array('code'=>1,'msg'=>'添加失败！'));
				exit;
			}
			
			
		}

		$this->display('addcache');
	}
	
	public function editcache(){
		$id = $this->frparam('id');
		$res = M('cachedata')->find(['id'=>$id]);
		if(!$id || !$res){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'缺少ID']);
			}
			Error('链接错误！');
		}
		if($this->frparam('go',1)==1){
			$data = $this->frparam();
			$data['title'] = $this->frparam("title",1);
			$data['limits'] = $this->frparam("limits");
			$data['orders'] = $this->frparam("orders",1);
			$data['tid'] = $this->frparam('tid');
			$data['isall'] = $this->frparam('isall');
			$data['sqls'] = $this->frparam('sqls',1);
			$data['field'] = $this->frparam('field',1);
			$data['times'] = $this->frparam('times',0,10);
			$data['molds'] = $this->frparam('molds',1);
			if(stripos($data['sqls'],'update')!==false || stripos($data['sqls'],'delete')!==false || stripos($data['sqls'],'insert')!==false || stripos($data['sqls'],'drop')!==false || stripos($data['sqls'],'truncate')!==false){
				JsonReturn(array('code'=>1,'msg'=>'非法操作'));
			}
			$tid = $data['tid'] ? ($data['isall']==1 ? ' and tid in ('.implode(',',$this->classtypedata[$data['tid']]['children']['ids']).') ' : ' and tid='.$data['tid']) : '';
			$sqls = $data['sqls'] ? ' and '.$data['sqls'] : '';
			$orderby = $data['orders'] ? ' order by '.$data['orders'] : '';
			$limit = $data['limits'] ? ' limit '.$data['limits'] : '';
			if($tid || $sqls){
				$where = ' where 1=1 '.$tid.htmlspecialchars_decode($sqls,ENT_QUOTES);
			}else{
				$where = '';
			}
			$sql = "select * from ".DB_PREFIX.$data['molds'].$where.$orderby.$limit;
			$result = M()->findSql($sql);
			if($result){
				foreach($result as $k=>$v){
					if(isset($v['htmlurl'])){
						$result[$k]['url'] = gourl($v,$v['htmlurl']);
					}
				}
			}
			if(M('cachedata')->update(['id'=>$id],$data)){
				
				$time = $data['times']*60;
				setCache('jzcache_'.$data['field'],$result,$time);
				JsonReturn(array('code'=>0,'msg'=>'修改成功！','url'=>U('sys/datacache')));
				exit;
			}else{
				JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
				exit;
			}
			
			
		}
		
		$this->data = $res;
		
		$this->display('editcache');
	}
	
	public function delcache(){
		$id = $this->frparam('id');
		if($id){
			if(M('cachedata')->delete('id='.$id)){
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	
	public function viewcache(){
		$id = $this->frparam('id');
		$res = M('cachedata')->find(['id'=>$id]);
		if(!$id || !$res){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'缺少ID']);
			}
			Error('链接错误！');
		}
		
		
		$tid = $res['tid'] ? ($res['isall']==1 ? ' and tid in ('.implode(',',$this->classtypedata[$res['tid']]['children']['ids']).') ' : ' and tid='.$res['tid']) : '';
		$sqls = $res['sqls'] ? ' and '.$res['sqls'] : '';
		$orderby = $res['orders'] ? ' order by '.$res['orders'] : '';
		$limit = $res['limits'] ? ' limit '.$res['limits'] : '';
		if($tid || $sqls){
			$where = ' where 1=1 '.$tid.htmlspecialchars_decode($sqls,ENT_QUOTES);
		}else{
			$where = '';
		}
		$sql = "select * from ".DB_PREFIX.$res['molds'].$where.$orderby.$limit;
		echo 'SQL:'.$sql.'<br>';
		
		
	}



}