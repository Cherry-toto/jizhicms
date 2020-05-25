<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2018/02
// +----------------------------------------------------------------------



/*****************
 * 项目公共函数 *
 *****************/

//网站配置

function webConf($str=null){
	//v1.3 取消文件存储
	//$web_config = include(APP_PATH.'Conf/webconf.php');
	$webconfig = getCache('webconfig');
	if(!$webconfig){
		$wcf = M('sysconfig')->findAll();
		$webconfig = array();
		foreach($wcf as $k=>$v){
			if($v['field']=='web_js' || $v['field']=='ueditor_config'){
				$v['data'] = html_decode($v['data']);
			}
			$webconfig[$v['field']] = $v['data'];
		}
		setCache('webconfig',$webconfig);
	}
	
	
	if($str!=null){
		if(!array_key_exists($str,$webconfig)){
			return false;
		}
		return $webconfig[$str];
	}else{
		return $webconfig;
	}
}
 
function get_custom($str=null){
	//v1.3 取消文件存储
	//$custom = include(APP_PATH.'Conf/custom.php');
	$customconfig = getCache('customconfig');
	if(!$customconfig){
		$ccf = M('sysconfig')->findAll('type!=0');
		$customconfig = array();
		foreach($ccf as $k=>$v){
			$customconfig[$v['field']] = $v['data'];
		}
		setCache('customconfig',$customconfig);
	}
	
	
	if($str!=null){
		if(!array_key_exists($str,$customconfig)){
			return false;
		}
		return $customconfig[$str];
	}else{
		return $customconfig;
	}
}

function get_template(){
	$webconf = webConf();
	$isgo = true;
	
	if($webconf['isopenwebsite']){
	
		//检测是否安装插件
		$res = M('plugins')->find(['filepath'=>'website','isopen'=>1]);
		if($res  && $res['config']){ 
			$website = $_SERVER['HTTP_HOST'];
			$config = json_decode($res['config'],1);
			$pc = $webconf['pc_template'];
			$wap = $webconf['wap_template'];
			$wechat = $webconf['weixin_template'];
			foreach($config as $v){
				if($v['website']==$website){
					$isgo = false;
					$v['model'] = (int)$v['model'];
					switch($v['model']){
						case 0:
						$pc=$wap=$wechat=$v['tpl'];
						break;
						case 1:
						$pc=$v['tpl'];
						break;
						case 2:
						$wap=$v['tpl'];
						break;
						case 3:
						$wechat=$v['tpl'];
						break;
					}
				}
			}
			if(isset($_SESSION['terminal'])){
				$template = ($_SESSION['terminal']=='mobile' && $webconf['iswap']==1) ? (isWeixin() ? $wechat : $wap) : $pc;
			}else{
				
				//当前端口检测
				if($webconf['iswap']==1 && isMobile()){
					$template = $wap;
					//wap
					if(isWeixin()){
						//wechat
						$template = $wechat;
					}
					
					
				}else{
					//pc
					$template = $pc;
				}
			}
			
			
			if($template==''){
				//全局
				$isgo = true;//直接跳转下面进行默认设置
			}
			
		}
		
		
		
		
	}
	if($isgo){
		if(isset($_SESSION['terminal'])){
			$wechat = ($webconf['weixin_template']!='')?$webconf['weixin_template']:$webconf['wap_template'];
			$template = ($_SESSION['terminal']=='mobile' && $webconf['iswap']==1) ? (isWeixin() ? $wechat : $webconf['wap_template']) : $webconf['pc_template'];
		}else{
			if($webconf['iswap']==1 && isMobile()){
				if(isWeixin()){
					$template = ($webconf['weixin_template']!='')?$webconf['weixin_template']:$webconf['wap_template'];
				}else{
					$template = $webconf['wap_template'];
				}
				
			}else{
				$template = $webconf['pc_template'];
			}
			
		}
		
	}
	
	
	
	return $template;
}

function curl_http($url,$data=null,$method='GET'){
	if(is_array($data)){
		$data = http_build_query($data);
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	if($method!='GET'){
		curl_setopt($ch, CURLOPT_POST, 1);
	}
	if($data!=null){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  //结果是否显示出来，1不显示，0显示    
	//判断是否https
	if(strpos($url,'https://')!==false){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
	}
	
	
	$data = curl_exec($ch);
	curl_close($ch);
	if($data === FALSE) 
	{ 
	  $data = "curl Error:".curl_error($ch);
	} 
	return $data;
}
 

 
function get_all_page($url,$start=5,$end=0,$match='{$}'){
	 $urls = array();
	 if($end==0){
		 for($i=1;$i<=$start;$i++){
			 $urls[] = str_ireplace($match,$i,$url);
		 }
	 }else{
		
		 for($i=$start;$i<=$end;$i++){
			 $urls[] = str_ireplace($match,$i,$url);
		 }
	 }
	
	 return $urls;
	 
 }
 

function adminInfo($id,$str=null){
	$user = M('level')->find('id='.$id);
  if($str!=null){
  	return $user[$str];
  }
  return $user;

}

 
//检测是否开启权限

function checkAction($action){
	if(!isset($_SESSION['admin'])){
    	Error('登录超时,请重新登录！');
    }
	$action = ucfirst($action);
    $paction = $_SESSION['admin']['paction'];
	if($_SESSION['admin']['isadmin']!=1){
	  
		if(strpos($action,'/')!==false){
			 if(strpos($paction,','.$action.',')!==false){
				return true;
			 }else{
				 $d = explode('/',$action);
				 if(strpos($paction,','.$d[0].',')!==false){
					return true;
				 }else{
					return false;
				 }
				
			 }
		}else{
			 if(strpos($paction,','.$action.',')!==false){
				return true;
			 }else{
				return false;
			 }
		}
	   
     
   }else{
   		return true;
   }

   

}


/**
 * 递归实现无限极分类
 * @param $array 分类数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */

function getTree($array, $pid =0, $level = 0){

	//声明静态数组,避免递归调用时,多次声明导致数组覆盖
	static $list = [];
	if($level==0){
		$list=[];
	}
	foreach ($array as $key => $value){
		
		//判断是否有下级---存在多次处理的bug v1.3已解决
		//$value['haschild'] = haschild($array,$value['id']);
		//第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
		if ($value['pid'] == $pid){
			//父节点为根节点的节点,级别为0，也就是第一级
			$value['level'] = $level;
			//把数组放到list中
			$list[] = $value;
			//把这个节点从数组中移除,减少后续递归消耗
			unset($array[$key]);
			//开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
			getTree($array, $value['id'], $level+1);

		}
	}
	return $list;
}
//判断是否有下级
function haschild($array,$pid){
	$n = false;
	foreach($array as $v){
		if($v['pid']==$pid){
			$n=true;
			break;
		}
	}
	return $n;
}

function show_tree($array){
	foreach($array as $value){
	   echo str_repeat('--', $value['level']), $value['classname'].'<br />';
	}
}
//$classtype 查询数据库中classtype表的所有内容
function set_class_haschild($classtype = null){
	
	$newarray = [];//组建新栏目数组
	foreach($classtype as $k=>$v){
		
		$v['haschild'] = false;//默认所有都没有下级
		$newarray[$v['id']] = $v;
		
		
	}
	foreach($newarray as $k=>$v){
		if($v['pid']!=0){
			//找到有上级的栏目，那么上级栏目就有下级了。。。。
			$newarray[$v['pid']]['haschild'] = true;
		}

	}
	return $newarray;
	
}

//获取格式化栏目类
function get_classtype_tree(){
	
	$classtypetree = getCache('classtypetree');
	if(!$classtypetree){
		
		$classtype = M('classtype')->findAll(null,'orders desc');
		$classtype = set_class_haschild($classtype);
		$classtypetree = getTree($classtype);
		setCache('classtypetree',$classtypetree);	
	}
	
	return $classtypetree;
	
}
//前台栏目输出
function classTypeData(){
	$res = getCache('classtype');
	$cache_time = (int)webConf('cache_time');
	if(!$res || !$cache_time){
		$classtypedata = get_classtype_tree();
		$d = array();
		
		$htmlpath = webConf('pc_html');
		$htmlpath = ($htmlpath=='' || $htmlpath=='/') ? '' : '/'.$htmlpath; 
		foreach($classtypedata as $k=>$v){
			$d[$v['id']] = $v;
			if($v['gourl']!=''){
				$d[$v['id']]['url'] = $v['gourl'];
			}else{
				$file_txt = File_TXT_HIDE ? '' : File_TXT;
				if($file_txt==''){
					$file_txt = CLASS_HIDE_SLASH ? $file_txt : $file_txt.'/';
				}
				$d[$v['id']]['url'] = get_domain().$htmlpath.'/'.$v['htmlurl'].$file_txt;
			}
			
		}
		
		setCache('classtype',$d,$cache_time);
		return $d;
	}
	return $res;
	
	
}

//手机端栏目缓存
function classTypeDataMobile(){
	$res = getCache('mobileclasstype');
	$cache_time = (int)webConf('cache_time');
	if(!$res || !$cache_time){
		$classtypedata = get_classtype_tree();
		$d = array();
		
		$htmlpath = webConf('mobile_html');
		$htmlpath = ($htmlpath=='' || $htmlpath=='/') ? '' : '/'.$htmlpath; 
		foreach($classtypedata as $k=>$v){
			$d[$v['id']] = $v;
			if($v['gourl']!=''){
				$d[$v['id']]['url'] = $v['gourl'];
			}else{
				$file_txt = File_TXT_HIDE ? '' : File_TXT;
				if($file_txt==''){
					$file_txt = CLASS_HIDE_SLASH ? $file_txt : $file_txt.'/';
				}
				$d[$v['id']]['url'] = get_domain().$htmlpath.'/'.$v['htmlurl'].$file_txt;
			}
			
		}
		
		setCache('mobileclasstype',$d,$cache_time);
		return $d;
	}
	return $res;
	
	
}

 //检测栏目是否该栏目下级
 function checkClass($pid,$tid){
	
	 $class = M('classtype')->find(array('id'=>$pid));
	 
	 if($class['pid']==$tid){
		 return true;
	 }
	 
	
	 if($class['pid']==0){
		 return false;
	 }else{
		 checkClass($class['pid'],$tid);
	 }
 }

 //获取栏目的所有下级
 /*
 	@param type				当前栏目数组
	@param classtype  已被getTree格式化数组
	@param code       获取内容类型
		1输出所有数组
		2输出直系子类id
		3输出全系子类ids
		4输出直系子类数组children
		5输出全系子类数组childrens
 */
 function get_children($type,$classtype=null,$code=1){
		if($type==null || $classtype==null){
				Error_msg('参数错误！');
		}
		
		$children=array();
		$childrens=array();
		$alldata = array();
		$go = false;
		$children_id = [];
		$children_ids[] = $type['id'];
		
		foreach($classtype as $v){
				if($v['id']==$type['id']){
					$go=true;
					continue;
				}
				if($v['level']==$type['level']){
					$go=false;
					continue;
				}
				//所有下级
				if($v['level']>=$type['level'] && $go){
					$childrens[]=$v;
					$children_ids[]=$v['id'];
						
				}
				//直系下级
				if($v['pid']==$type['id']){
					$children[]=$v;
					$children_id[]=$v['id'];
				}
				
				
		}
		//直系属性
		$alldata['id']=$children_id;
		$alldata['list']=$children;
		//全系属性
		$alldata['ids']=$children_ids;
		$alldata['lists']=$childrens;
		switch($code){
			case 1:
				return $alldata;
			break;
			case 2:
				return $children_id;
			break;
			case 3:
				return $children_ids;
			break;
			case 4:
				return $children;
			break;
			case 5:
				return $childrens;
			break;
		}
		
	
	

 }

 
  //获取单条表数据信息
 function get_info_table($table,$where=null,$str=null){

		$data = M($table)->find($where,null,$str);
		if($str!=null){
			return $data[$str];
		}
		return $data;
	 
 }
 //获取指定表中所有内容
 function get_all_info_table($table,$where=null,$order=null,$limit=null,$field=null){
	 $data = M($table)->findAll($where,$order,$field,$limit);
	 return $data;
 }
 
 
 //后台方法-获取表单提交的扩展字段的内容
 /**
	@param data   表单提交的内容
	@param molds  模块标识
	@param isadmin是否后台
 **/
 function get_fields_data($data,$molds,$isadmin=1){
	 if($isadmin){
		 $fields = M('fields')->findAll(['molds'=>$molds,'isadmin'=>1],'orders desc,id asc');
	 }else{
		 //前台需要判断是否前台显示
		 $fields = M('fields')->findAll(['molds'=>$molds,'isshow'=>1],'orders desc,id asc');
	 }
	 foreach($fields as $v){
		 if(array_key_exists($v['field'],$data)){
			 switch($v['fieldtype']){
				 case 1:
				 case 2:
				 case 5:
				 case 7:
				 case 9:
				 case 12:
				 $data[$v['field']] = format_param($data[$v['field']],1);
				 break;
				 case 11:
				 $data[$v['field']] = strtotime(format_param($data[$v['field']],1));
				 break;
				 case 3:
				 $data[$v['field']] = format_param($data[$v['field']],4);
				 break;
				 case 4:
				 case 13:
				 $data[$v['field']] = format_param($data[$v['field']]);
				 break;
				 case 14:
				 $data[$v['field']] = format_param($data[$v['field']],3);
				 break;
				 case 8:
				 $r = implode(',',format_param($data[$v['field']],2));
				 if($r!=''){
					 $r = ','.$r.',';
				 } 
			 
				 $data[$v['field']] = $r;
				 break;
				
			 }
		 }else if(array_key_exists($v['field'].'_urls',$data)){
		     switch($v['fieldtype']){
		         case 6:
				 case 10:
				 if(array_key_exists($v['field'].'_des',$data)){
					 $pics = format_param($data[$v['field'].'_urls'],2);
					 $pics_des = format_param($data[$v['field'].'_des'],2);
					 foreach($pics as $k=>$vv){
						 if($pics_des[$k]){
							 $pics[$k] = $vv.'|'.$pics_des[$k];
						 }
					 }
					$data[$v['field']] = implode('||',$pics);
					
				 }else{
					$data[$v['field']] = implode('||',format_param($data[$v['field'].'_urls'],2)); 
				 }
				 
				 break;
		     }
		 }else{
		     
			$data[$v['field']] = '';      
		     
		 }
		 
	 }
	 return $data;
	 
 }
 
 
 
 //新增字段-后台列表搜索获取
 function molds_search($molds=null,$data){
	 if($molds==null){
		 Error('缺少模块标识！');
	 }
	 $lists = M('Fields')->findAll(array('molds'=>$molds,'issearch'=>1),'orders desc,id asc');
	 $fields_search = '';
	 $fields_search_check = array();
	 foreach($lists as $v){
		 $data[$v['field']] = array_key_exists($v['field'],$data) ? $data[$v['field']] : '';
		 switch($v['fieldtype']){
			 case 1:
			 case 2:
			 case 3:
			 case 5:
			 case 6:
			 case 9:
			 case 10:
			 case 14:
			 $fields_search .= '<input type="text" name="'.$v['field'].'" value="'.format_param($data[$v['field']],1).'" placeholder="请输入'.$v['fieldname'].'" autocomplete="off" class="layui-input">';
			 if(array_key_exists($v['field'],$data)){
				 if(format_param($data[$v['field']],1)!=''){
					 $fields_search_check[] ="  ".$v['field']." like '%".format_param($data[$v['field']],1)."%'";
				 }
				
			 }
			 break;
			 case 4:
			 $fields_search .= '<input type="number" name="'.$v['field'].'" value="'.format_param($data[$v['field']]).'" placeholder="请输入'.$v['fieldname'].'" autocomplete="off" class="layui-input">';
			 if(array_key_exists($v['field'],$data)){
				 if(format_param($data[$v['field']],1)!=''){
					 $fields_search_check[] ="  ".$v['field']." = '".format_param($data[$v['field']],1)."'";
				 }
				
			 }
			 break;
			 case 7:
			 case 12:
			 $fields_search .= '<div class="layui-input-inline">
			  <select name="'.$v['field'].'" lay-search="" class="layui-inline">
			  <option value="">请选择'.$v['fieldname'].'</option>';
			 foreach(explode(',',$v['body']) as $vv){
			   $s = explode('=',$vv);
			   $fields_search .= '<option ';
			   if(array_key_exists($v['field'],$data)){
				   if(format_param($data[$v['field']],1)==$s[1]){
					  $fields_search .= 'selected="selected"'; 
				   }
			   }
			   $fields_search .= 'value="'.$s[1].'">'.$s[0].'</option>';
			 }
			
			 $fields_search .=  '</select>
			 </div>';
			 if(array_key_exists($v['field'],$data)){
				 if(format_param($data[$v['field']],1)!=''){
					 $fields_search_check[] ="  ".$v['field']." = '".format_param($data[$v['field']],1)."'";
				 }
				
			 }
			 break;
			 case 8:
			 $fields_search .= '<div class="layui-input-inline">
			  <select name="'.$v['field'].'" lay-search="" class="layui-inline">
			  <option value="">请选择'.$v['fieldname'].'</option>';
			 foreach(explode(',',$v['body']) as $vv){
			   $s = explode('=',$vv);
			   $fields_search .= '<option ';
			   if(array_key_exists($v['field'],$data)){
				   if(format_param($data[$v['field']],1)==$s[1]){
					  $fields_search .= 'selected="selected"'; 
				   }
			   }
			   $fields_search .= 'value="'.$s[1].'">'.$s[0].'</option>';
			 }
			
			 $fields_search .=  '</select>
			 </div>';
			 if(array_key_exists($v['field'],$data)){
				 if(format_param($data[$v['field']],1)!=''){
					$fields_search_check[] =" ".$v['field']." like '%,".format_param($data[$v['field']],1).",%'";
				 }
				
			 }
			 
			 break;
			 case 11:
			 $laydate = ($data[$v['field']]=='' || $data[$v['field']]==0)?'':date('Y-m-d',strtotime($data[$v['field']]));
			 $laytime = ($data[$v['field']]=='' || $data[$v['field']]==0)?0:strtotime($laydate);
			 $fields_search .= '<input name="'.$v['field'].'" value="'.$laydate.'" placeholder="请选择'.$v['fieldname'].'" id="laydate_'.$v['field'].'" autocomplete="off" class="layui-input"><script>
layui.use("laydate", function(){
  var laydate = layui.laydate;
  laydate.render({elem: "#laydate_'.$v['field'].'" });});</script>';
			 if(array_key_exists($v['field'],$data)){
				 if(format_param($data[$v['field']])!=0){
					$fields_search_check[] ="  (".$v['field']." >= ".$laytime." and ".$v['field']." < ".($laytime+86400).") ";
				 }
				
			 }
			 break;
			 case 13:
			  $body = explode(',',$v['body']);
			  $moldsdata = M('molds')->find(['id'=>$body[0]],'');
			  $datalist = M($moldsdata['biaoshi'])->findAll();
			 $fields_search .= '<div class="layui-input-inline">
			  <select name="'.$v['field'].'" lay-search="" class="layui-inline">
			  <option value="">请选择关联'.$moldsdata['name'].'</option>';
			 foreach($datalist as $vv){
			   $fields_search .= '<option ';
			   if(array_key_exists($v['field'],$data)){
				   if(format_param($data[$v['field']])==$vv['id']){
					  $fields_search .= 'selected="selected"'; 
				   }
			   }
			   $fields_search .= 'value="'.$vv['id'].'">'.$vv[$body[1]].'</option>';
			 }
			
			 $fields_search .=  '</select>
			 </div>';
			 if(array_key_exists($v['field'],$data)){
				 if(format_param($data[$v['field']],1)!=''){
					$fields_search_check[] =" ".$v['field']." =".format_param($data[$v['field']])." ";
				 }
				
			 }
			 
			 break;
			
			 
			 
		 }
		 
		 
	 }
	 if(count($fields_search_check)>0){
		 $fields_search_check = implode(' and ',$fields_search_check);
	 }else{
		 $fields_search_check = '';
	 }
	 return array('fields_search'=>$fields_search,'fields_search_check'=>$fields_search_check);
 }
 
 /**
	后台格式化类型显示
 
 **/
 function format_fields($fields=null,$data=null){
	 
	 if($fields==null){
		 $list = array(
		
			'string_10' => '截取10个字',
			'string_15' => '截取15个字',
			'date_1' => '日期(Y-m-d)',
			'date_2' => '日期(Y-m-d H:i:s)',
		 );
		 return $list;
	 }else{
		 switch($fields['format']){
			  case 'string_10':
			  return newstr($data,10);
			 break;
			  case 'string_15':
			  return newstr($data,15);
			 break;
			  case 'date_1':
			  return date('Y-m-d',$data);
			 break;
			  case 'date_2':
			  return date('Y-m-d H:i:s',$data);
			 break;
			 default:
			 if($fields['fieldtype']==7 || $fields['fieldtype']==12){
						$r = explode(',',$fields['body']);
						foreach($r as $v){
							$d = explode('=',$v);
							if($d[1]==$data){
								return $d[0];exit;
							}
						}
			 }else if($fields['fieldtype']==8){
					$r = explode(',',$fields['body']);
					$rr = array();
					foreach($r as $v){
							$d = explode('=',$v);
							if(strpos($data,','.$d[1].',')!==false){
								$rr[]=$d[0];
							}
					}
					return implode(',',$rr);
			 }else if($fields['fieldtype']==5){
				 $vdata = $data!='' ? '<a href="'.$data.'" target="_blank"><img src="'.$data.'" width="100px"  /></a>' : '';
				 return $vdata;
			 }else if($fields['fieldtype']==6){
				 //图集
				 if($data!=''){
					 $vdata = explode('||',$data);
					 $res = '';
					 foreach($data as $s){
						 if($s!=''){
							 $res.='<a href="'.$s.'" target="_blank"><img src="'.$s.'" width="50px" /></a>';
						 }
					 }
					 return $res;
				 }else{
					 return '';
				 }
			 }else if($fields['fieldtype']==9){	
				 $vdata = $data!='' ? '<a href="'.$data.'" target="_blank">[查看]</a>' : '';
				 return $vdata;
			 }else if($fields['fieldtype']==10){
				 if($data!=''){
					 $vdata = explode('||',$data);
					 $res = '';
					 foreach($data as $s){
						 if($s!=''){
							 $res.='<a href="'.$s.'" target="_blank">[查看]</a>';
						 }
					 }
					 return $res;
				 }else{
					 return '';
				 }
			 }else if($fields['fieldtype']==11){
				 $vdata = $data==0?'':date('Y-m-d H:i:s',$data);
				 return $vdata;
			 }else if($fields['fieldtype']==13){
				    $body = explode(',',$fields['body']);
					$biaoshi = M('molds')->getField(['id'=>$body[0]],'biaoshi');
					$res = M($biaoshi)->getField(['id'=>$data],$body[1]);
					if(!$res){
						return '[ 空 ]';
					}
					return $res;
					
					
			 }
			 return $data;
			 break;
		 }
	 }
	 
 }


 

 
function frvercode($num=4,$str='frcode'){
	//创建随机码
	$_nmsg = '';
	for($i=0;$i<$num;$i++){
		$_nmsg .= dechex(mt_rand(0, 15));
	}
	//保存在session里
	$_SESSION[$str] = md5(md5($_nmsg));
	//长和高
	$_width = 75;
	$_height = 25;
	//创建图像
	$_img = imagecreatetruecolor($_width, $_height);
	$_white = imagecolorallocate($_img, 255, 255, 255);
	imagefill($_img, 0, 0, $_white);
	/*
	//创建黑色边框
	$_black = imagecolorallocate($_img, 100, 100, 100);
	imagerectangle($_img, 0, 0, $_width-1, $_height-1, $_black);
	//随机划线条
	for ($i=0;$i<6;$i++) {
	$_rnd_color= imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255)
	,mt_rand(0,255));
	imageline($_img,mt_rand(0,75),mt_rand(0,25),mt_rand(0,75),mt_rand(0,25)
	,$_rnd_color);
	}
	//随机打雪花
	for ($i=1;$i<100;$i++) {
	imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),"*",
	imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255)));
	}
	*/
	//输出验证码
	for ($i=0;$i<strlen($_nmsg);$i++){
	imagestring($_img,mt_rand(3,5),$i*$_width/$num+mt_rand(1,10),
	mt_rand(1,$_height/2),$_nmsg[$i],
	imagecolorallocate($_img,mt_rand(0,150),mt_rand(0,100),mt_rand(0,150)));
	}
	//输出图像
	//ob_clean();
	header('Content-Type:image/png');
	imagepng($_img);
	//销毁
	imagedestroy($_img);

}
 
//检测手机端
function aCheckSubstrs($substrs,$text){    
        foreach($substrs as $substr)    
            if(false!==strpos($text,$substr)){    
                return true;    
            }    
            return false;    
    } 
function isMobile(){   
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';    
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';      
 
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');  
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');    
                
    $found_mobile=aCheckSubstrs($mobile_os_list,$useragent_commentsblock) ||    
              aCheckSubstrs($mobile_token_list,$useragent);    
                
    if ($found_mobile){    
        return true;    
    }else{    
        return false;    
    }    
}
//检测微信端
function isWeixin(){  
    if ( isset($_SERVER['HTTP_USER_AGENT']) &&  strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {  
            return true;  
    }    
    return false;  
} 



//内容url
function gourl($id,$htmlurl=null,$molds='article'){
		if(is_array($id)){
			/**
			ownurl target id 
			**/
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
		if(isset($_SESSION['terminal'])){
			$htmlpath = $_SESSION['terminal']=='mobile' ? webConf('mobile_html') : webConf('pc_html');
		}else{
			$htmlpath = (isMobile() && webConf('iswap')==1)?webConf('mobile_html'):webConf('pc_html');
		}
		$htmlpath = ($htmlpath=='' || $htmlpath=='/') ? '' : '/'.$htmlpath; 
		if($htmlurl!=null){
			return get_domain().$htmlpath.'/'.$htmlurl.'/'.$id.'.html';
		}
		
		$tid = M($molds)->getField(array('id'=>$id),'tid');
		$htmlurl = M('classtype')->getField(array('id'=>$tid),'htmlurl');
		return get_domain().$htmlpath.'/'.$htmlurl.'/'.$id.'.html';
}

//输出任何模块的内容URL
function all_url($id,$molds='article',$htmlurl=null){
	if(is_array($id)){
		/**
		ownurl target id 
		**/
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
		if(isset($_SESSION['terminal'])){
			$htmlpath = $_SESSION['terminal']=='mobile' ? webConf('mobile_html') : webConf('pc_html');
		}else{
			$htmlpath = isMobile() && webConf('isopen')?webConf('mobile_html'):webConf('pc_html');
		}
		$htmlpath = ($htmlpath=='' || $htmlpath=='/') ? '' : '/'.$htmlpath; 
		if($htmlurl!=null){
			$file_txt = File_TXT_HIDE ? '' : File_TXT;
			return get_domain().$htmlpath.'/'.$htmlurl.'/'.$id.$file_txt;
		}
		$tid = M($molds)->getField(array('id'=>$id),'tid');
		$htmlurl = M('classtype')->getField(array('id'=>$tid),'htmlurl');
		$file_txt = File_TXT_HIDE ? '' : File_TXT;
		return get_domain().$htmlpath.'/'.$htmlurl.'/'.$id.$file_txt;
}

//递增
function incrData($table=null,$id=0,$field='hits',$num=1){
	
	if(!format_param($table,1)){
		Error_msg($table.'表不存在！');
	}
	if(!format_param($id)){
		Error_msg('缺少ID！');
	}
	if(!format_param($field,1)){
		Error_msg('递增字段缺少！');
	}
	if(!format_param($num)){
		Error_msg('递增数据格式错误！');
	}
	
	$r = M($table)->goInc(array('id'=>$id),$field,$num);
	if(!$r){
		return '递增失败！';
	}
	return M($table)->getField(array('id'=>$id),$field);
}

//自定义字段单项/多项选择获取
function get_key_field_select($key=0,$molds=null,$field=null){
	if($molds==null || $field==null){
		echo '参数molds或field缺少';exit;
	}
	$res =	M('Fields')->find(array('molds'=>$molds,'field'=>$field),null,'body,fieldtype');
	if($res){
		$value = explode(',',$res['body']);
		if($res['fieldtype']==7 || $res['fieldtype']==12){
			//单选
			foreach($value as $v){
				$d = explode('=',$v);
				if($d[1]==$key){
					return $d[0];
				}
			}
			return false;
		}else if($res['fieldtype']==13) {
			$biaoshi = M('molds')->getField(['id'=>$value[0]],'biaoshi');
			$data = M($biaoshi)->getField(['id'=>$key],$value[1]);
			return $data;
		}else{
			$s = array();
			foreach($value as $v){
				$d = explode('=',$v);
				if(strpos($key,','.$d[1].',')!==false){
					$s[] = $d[0];
				}
			}
			return $s;
		}
		
		
	}else{
		return '没有查询到该字段内容！';
	}
		
}
//根据模型[$molds]、字段[$field]获取并输出内容选项

function get_field_select($molds=null,$field=null){
	if($molds==null || $field==null){
		echo '参数molds或field缺少';exit;
	}
	$res =	M('Fields')->find(array('molds'=>$molds,'field'=>$field),null,'body,fieldtype');
	if($res){
		$value = explode(',',$res['body']);
		$s = array();
		foreach($value as $v){
			$s[] = explode('=',$v);
			
		}
		return $s;
		
	}else{
		return '没有查询到该字段内容！';
	}
		
}

//获取文件大小
function get_file_byte($file)

{
	$byte = filesize($file);

    $KB = 1024;

    $MB = 1024 * $KB;

    $GB = 1024 * $MB;

    $TB = 1024 * $GB;

    if ($byte < $KB) {

        return $byte . "B";

    } elseif ($byte < $MB) {

        return round($byte / $KB, 2) . "KB";

    } elseif ($byte < $GB) {

        return round($byte / $MB, 2) . "MB";

    } elseif ($byte < $TB) {

        return round($byte / $GB, 2) . "GB";

    } else {

        return round($byte / $TB, 2) . "TB";

    }

}


//获取文章评论
function show_comment($tid=0,$id=0,$str=null){
	if($tid==0||$id==0){
		return false;
	}
	
	$lists = M('comment')->findAll(['tid'=>$tid,'aid'=>$id,'isshow'=>1],'addtime asc');
	$star_num = 0;
	$count = 0;
	if($lists){
		foreach($lists as $k=>$v){
			$star_num += $v['likes'];
			$lists[$k]['userinfo'] = M('member')->find(['id'=>$v['userid']]);
			if($v['likes']>0){
			    $count+=1;
			}
		}
		$lists = set_class_haschild($lists);
		$lists = getTree($lists);
	}
	if($count!=0){
		$average = round($star_num/$count,1);
	}else{
		$average = 0;
	}
	$res = array('data'=>$lists,'star'=>$star_num,'count'=>$count,'average'=>$average);
	if($str!=null){
		return $res[$str];
	}
	return $res;
}
//获取指定评论用户姓名
function get_comment_user($id){
	$userid = M('comment')->getField(['id'=>$id],'userid');
	if(!$userid){
		return '';
	}else{
		return M('member')->getField(['id'=>$userid],'username');
	}
}

//计算评论数量---或者直接comment_num显示
function get_comment_num($tid,$id=0){
	if($id==0){ return '缺少ID！';}
	$count = M('comment')->getCount(['aid'=>$id,'tid'=>$tid]);
	return $count;
}

//处理数组拼接--Screen筛选功能有使用
function change_parse_url($arr,$str){
	if(count($arr)==0){
		return '';
	}
	unset($arr[$str]);
	if(count($arr)==0){
		return '';
	}
	$url = str_replace('=','-',http_build_query($arr,false,'-'));
	return '-'.$url;
}

//获取扩展字段内容输出
function get_fields_show($tid,$molds){
	$sql = array();
	if($tid!=0){
		$sql[] = " tids like '%,".$tid.",%' "; 
	}
	
	$sql[] = " molds = '".$molds."' and isshow=1 ";
	$sql = implode(' and ',$sql);
	$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
	return $fields_list;
}

//发送邮件处理
function send_mail($send_mail,$password,$send_name,$to_mail,$title,$body,$email_ext=''){
	
	require APP_PATH.'FrPHP/Extend/PHPMailer/PHPMailerAutoload.php';
	require_once(APP_PATH.'FrPHP/Extend/PHPMailer/class.phpmailer.php');
	require_once(APP_PATH."FrPHP/Extend/PHPMailer/class.smtp.php");
	
	    $mail = new PHPMailer();

		$host = webConf('email_server');
		$port = (int)webConf('email_port');
		if(!$host || !$port){
			exit('邮件服务器未配置完成');
		}

		if(strpos($host,'qq')!==false){
			$mail->isSMTP();
		    $mail->CharSet = "UTF-8";
		    $mail->Host = $host;
		    $mail->SMTPAuth = true;
		    $mail->Username = $send_mail;
		    $mail->Password = $password;
		    $mail->SMTPSecure = 'tls';
		    $mail->Port = $port;
		    $mail->SetFrom($send_mail, $send_name);
		    $address = $to_mail;
		    if($email_ext!=''){
				 $mail->AddAddress($email_ext, $send_name);
			}
	        $mail->AddAddress($address, $send_name);
		    $mail->isHTML(true);
		    $mail->Subject = $title;
		    $mail->Body    = $body;
		}else{
			$mail->IsSMTP(); // telling the class to use SMTP

	        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)

	        $mail->SMTPAuth = true; // enable SMTP authentication

	        $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
	       
	     	//$mail->SMTPSecure = false; // sets the prefix to the servier

	        $mail->Host = $host; // sets GMAIL as the SMTP server

	        $mail->Port = $port; // set the SMTP port for the GMAIL server

	        $mail->Username = $send_mail; // GMAIL username

	        $mail->Password = $password; // GMAIL password

	        $mail->SetFrom($send_mail, $send_name);

	        //$mail->AddReplyTo("xxx@xxx.com","First Last");

	        $mail->Subject = $title;

	        $mail->AltBody = $title; // optional, comment out and test

	        $mail->MsgHTML($body);

	        $mail->CharSet = "utf-8"; // 这里指定字符集！

	        $address = $to_mail;
			if($email_ext!=''){
				
				 $mail->AddAddress($email_ext, $send_name);
			}
	        $mail->AddAddress($address, $send_name);

		}

        

        if(!$mail->Send()) {

           // echo "Mailer Error: " . $mail->ErrorInfo;
		   return false;

        } else {

            //echo "Message sent!";
			return true;

        }

}

//检测是否收藏
function checkCollect($tid=0,$id=0){
	if($tid && $id && isset($_SESSION['member'])){
		if(strpos($_SESSION['member']['collection'],'||'.$tid.'-'.$id.'||')!==false){
			return true;
		}else{
			return false;
		}
		
		
	}else{
		return false;
	}
	
}
//检测是否点赞
function checkLikes($tid=0,$id=0){
	if($tid && $id && isset($_SESSION['member'])){
		if(strpos($_SESSION['member']['likes'],'||'.$tid.'-'.$id.'||')!==false){
			return true;
		}else{
			return false;
		}
		
		
	}else{
		return false;
	}
	
}

//检查多少未读评论
function has_no_read_comment(){
    if(!isset($_SESSION['member'])){
        return 0;
    }
    $sql = 'userid='.$_SESSION['member']['id']." and isshow=1 and (type = 'comment' or type = 'reply') and isread=0 ";
    $count = M('task')->getCount($sql);
    return $count;
}
//检查多少未读消息
function has_no_read_msg(){
	if(!isset($_SESSION['member'])){
        return 0;
    }
    //增对个人用户是否关闭提醒
    $sql = 'userid='.$_SESSION['member']['id']." and isshow=1  and isread=0 ";
    if(!$_SESSION['member']['ismsg']){
    	$sql.=" and type = '0' ";//只接收交易提醒
    }
    if(!$_SESSION['member']['iscomment']){
    	$sql.=" and type != 'comment' and type != 'reply'  ";
    }
    if(!$_SESSION['member']['iscollect']){
    	$sql.=" and type != 'collect'  ";
    }
    if(!$_SESSION['member']['islikes']){
    	$sql.=" and type != 'likes'  ";
    }
    if(!$_SESSION['member']['isat']){
    	$sql.=" and type != 'at'  ";
    }
	if(!$_SESSION['member']['isrechange']){
    	$sql.=" and type != 'rechange'  ";
    }
    
    $count = M('task')->getCount($sql);
    return $count;
}


//数据库html反转义
function html_decode($data=null){
	$data = str_replace('&#039;',"'",htmlspecialchars_decode($data));
	return $data;
	
}

//字符串替换
function str_replace_limit($search, $replace, $subject, $limit=-1) { 
    if (is_array($search)) { 
        foreach ($search as $k=>$v) { 
            $search[$k] = '`' . preg_quote($search[$k],'`') . '`'; 
        }
    }else { 
        $search = '`' . preg_quote($search,'`') . '`'; 
    } 
    return preg_replace($search, $replace, $subject, $limit); 
}

//人性化时间显示
function formatTime($sTime, $formt = 'Y-m-d') {
 
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime = time();
    $dTime = $cTime - $sTime;
    $dDay = intval($dTime/86400);
    $dYear = intval(date('Y',$cTime)) - intval(date('Y',$sTime));
 
    //n秒前，n分钟前，n小时前，日期
    if ($dTime < 60 ) {
        if ($dTime < 10) {
			
			if($dTime<0){
				return date($formt, $sTime);
			}else{
				return '刚刚';
			}
        } else {
            return intval(floor($dTime / 10) * 10).'秒前';
        }
    } else if ($dTime < 3600 ) {
        return intval($dTime/60).'分钟前';
    } else if( $dTime >= 3600 && $dDay == 0){
        return intval($dTime/3600).'小时前';
    } else if( $dDay > 0 && $dDay<=7){
        return intval($dDay).'天前';
    } else if( $dDay > 7 &&  $dDay <= 30){
        return intval($dDay/7).'周前';
    } else if( $dDay > 30  && $dDay < 365){
        return intval($dDay/30).'个月前';
	} else if($dDay >= 365 && $dDay < 3650){
		return intval($dDay/365).'年前';
    } else {
        return date($formt, $sTime);
    }
}

//过滤HTML代码函数
function htmldecode($data){
	$data = strip_tags($data);
	$data = str_replace('&nbsp;','',$data);
	return $data;
}

//计算点赞数
function jz_zan($tid,$id){
	
	$sql = " likes like '%||".$tid.'-'.$id."||%' ";
	$count = M('member')->getCount($sql);
	return $count;
	
}
//计算收藏数
function jz_collect($tid,$id){
	
	$sql = " collection like '%||".$tid.'-'.$id."||%' ";
	$count = M('member')->getCount($sql);
	return $count;
	
}
//用户详情
function memberInfo($id,$str=null){
	$user = M('member')->find('id='.$id);
  if($str!=null){
  	return $user[$str];
  }
  return $user;

}

//图片水印
function watermark($img,$water,$pos=9,$tm=100){

	 $info=getImageInfo($img);

	 $logo=getImageInfo($water);

	 $dst=openImg($img,$info['type']);
	 $src=openImg($water,$logo['type']);


	 switch($pos){
	 case 1:
		 $x=0;
		 $y=0;
	 break;
	 case 2:
		 $x=ceil(($info['width']-$logo['width'])/2);
		 $y=0;
	 break;
	 case 3:
		 $x=$info['width']-$logo['width'];
		 $y=0;
	 break;
	 case 4:
		 $x=0;
		 $y=ceil(($info['height']-$logo['height'])/2);
	 break;
	 case 5:
		 $x=ceil(($info['width']-$logo['width'])/2);
		 $y=ceil(($info['height']-$logo['height'])/2);
	 break;
	 case 6:
		 $x=$info['width']-$logo['width'];
		 $y=ceil(($info['height']-$logo['height'])/2);
	 break;

	 case 7:
		 $x=0;
		 $y=$info['height']-$logo['height'];
	 break;
	 case 8:
		 $x=ceil(($info['width']-$logo['width'])/2);
		 $y=$info['height']-$logo['height'];
	 break;
	 case 9:
		 $x=$info['width']-$logo['width'];
		 $y=$info['height']-$logo['height'];
	 break;
	 case 0:
	 default:
		 $x=mt_rand(0,$info['width']-$logo['width']);
		 $y=mt_rand(0,$y=$info['height']-$logo['height']);
	 break;

	 }
		 imagecopymerge($dst,$src,$x,$y,0,0,$logo['width'],$logo['height'],$tm);


		 imagejpeg($dst,$img);

		 imagedestroy($dst);
		 imagedestroy($src);
		 return $img;

}

function getImageInfo($path) {
    $info = getimagesize($path);
    $data['width'] = $info[0];
    $data['height'] = $info[1];
    $data['type'] = $info['mime'];
    return $data;
} 
//打开图片
 function openImg($path,$type){
	 switch($type){
		 case 'image/jpeg':
		 case 'image/jpg':
		 case 'image/pjpeg':
		 $img=imagecreatefromjpeg($path);
	 break;
		 case 'image/png':
		 case 'image/x-png':
		 $img=imagecreatefrompng($path);
	 break;
	 case 'image/gif':
	 	$img=imagecreatefromgif($path);
	 break;
	 case 'image/wbmp':
		 $img=imagecreatefromwbmp($path);
	 break;
	 default:
		 exit('图片类型不支持');


	 }
	 return $img;
 }
//检查是否关注
function isfollow($id,$other){
	$follow = M('member')->getField(['id'=>$id],'follow');
	if(strpos($follow,','.$other.',')!==false){
		return true;
	}else{
		return false;
	}
}
//计算粉丝数
function jz_fans($id=0){
	if($id){
		$user_num = M('member')->getCount(" follow like '%,".$id.",%'");
		return $user_num;
	}else{
		return 0;
	}
}
//计算关注数
function jz_follow($id=0){
	if($id){
		//,1,2,2,4,
		$follow = M('member')->getField(['id'=>$id],'follow');
		if($follow!=''){
			$follow = trim($follow,',');
			$num = substr_count($follow,',');
			return $num;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}
	
//删除文件目录
function deldir($dir) {
	//先删除目录下的文件：
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}
	closedir($dh);
	//删除当前文件夹：
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}

//引入扩展方法文件
include(APP_PATH.'Conf/FunctionsExt.php');