<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/01-2019/02
// +----------------------------------------------------------------------


namespace A\c;

use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class ClasstypeController extends CommonController
{


	function index(){
		//$sql = null;
		//栏目不需要搜索
		// $data = $this->frparam();
		// $res = molds_search('classtype',$data);
		// $sql = ($res['fields_search_check']!='')?$res['fields_search_check']:null;
		// $this->fields_search = $res['fields_search'];
		$classtype = M('classtype')->findAll(NULL,'orders desc');
		$classtype = set_class_haschild($classtype);
		$classtype = getTree($classtype);
		$this->classtypes = $classtype;
		//$this->classtypes = $this->classtypetree;
		//模块
		$molds = M('Molds')->findAll(['isopen'=>1]);
		$fs = array();
		foreach($molds as $v){
			$fs[$v['biaoshi']] = $v;
		}
		$this->molds = M('molds')->find(['biaoshi'=>'classtype']);
		$this->moldslist = $fs;
		$this->display('classtype-list');
	}
	
	function addclass(){
		$this->fields_biaoshi = 'classtype';
		
		if($this->frparam('go')==1){

			$htmlurl = $this->frparam('htmlurl',1);
			if($htmlurl==''){
				$htmlurl = str_replace(' ','',pinyin($this->frparam('classname',1)));
			}
			if($this->webconf['islevelurl'] && $this->frparam('pid')!=0){
				//层级
				$classtypetree = classTypeData();
				$htmlurl = $classtypetree[$this->frparam('pid')]['htmlurl'].'/'.$htmlurl;
			}
			
			if(in_array(strtolower($htmlurl),array('message','user','comment','home','common','order','tags','wechat','login'))){
				JsonReturn(array('status'=>0,'info'=>'URL链接命名不能是：message,user,comment,home,common,order,tags,wechat,login,jzpay'));
			}
			if(stripos($htmlurl,'.php')!==false){
				JsonReturn(array('status'=>0,'info'=>'非法URL'));
			}

			$w['pid'] = $this->frparam('pid');
			$w['orders'] = $this->frparam('orders');
			$w['classname'] = $this->frparam('classname',1);
			$w['seo_classname'] = $this->frparam('seo_classname',1) ? $this->frparam('seo_classname',1) : $this->frparam('classname',1);
			$w['molds'] = $this->frparam('molds',1);
			$w['description'] = $this->frparam('description',1);
			$w['keywords'] = $this->frparam('keywords',1);
			$w['litpic'] = $this->frparam('litpic',1);
			$w['body'] = $this->frparam('body',4);
			$w['htmlurl'] = $htmlurl;
			$w['iscover'] = $this->frparam('iscover');
			$w['lists_html'] = $this->frparam('lists_html',1);
			$w['details_html'] = $this->frparam('details_html',1);
			$w['gourl'] = $this->frparam('gourl',1);
			$w['lists_num'] = $this->frparam('lists_num');
			
			if($w['pid']){
				$parent = M('classtype')->find(array('id'=>$w['pid']));
				if($parent['iscover']==1){
					$w['lists_html']= $w['lists_html'] ? $w['lists_html'] : ($this->frparam('lists_html_write',1) ? $this->frparam('lists_html_write',1) : $parent['lists_html']);
					$w['details_html']= $w['details_html'] ? $w['details_html'] : ($this->frparam('details_html_write',1) ? $this->frparam('details_html_write',1) : $parent['details_html']);
					$w['lists_num']=$parent['lists_num'];
				}else{
					$w['lists_html']= $w['lists_html'] ? $w['lists_html'] : $this->frparam('lists_html_write',1);
					$w['details_html']= $w['details_html'] ? $w['details_html'] : $this->frparam('details_html_write',1);
				}
			}else{
				$w['lists_html']= $w['lists_html'] ? $w['lists_html'] : $this->frparam('lists_html_write',1);
				$w['details_html']= $w['details_html'] ? $w['details_html'] : $this->frparam('details_html_write',1);
			}
			
			$w['lists_html'] = str_ireplace('.html','',$w['lists_html']);
			$w['details_html'] = str_ireplace('.html','',$w['details_html']);
			
			
			$data = $this->frparam();
			$data = get_fields_data($data,'classtype');
			$w = array_merge($data,$w);
			$a = M('classtype')->add($w);
			if($a){
				$fields=M('fields')->findAll(" tids like '%,".$w['pid'].",%' or (molds='".$w['molds']."' and field='addtime') ");
				foreach ($fields as $v){
					if($v['tids']){
						M('fields')->update(array('id'=>$v['id']),array('tids'=>$v['tids'].$a.','));
					}else{
						M('fields')->update(array('id'=>$v['id']),array('tids'=>','.$a.','));
					}
					
				}
				//这里
				setCache('classtypetree',null);
				setCache('classtype',null);
				setCache('mobileclasstype',null);
				setCache('classtypedatamobile',null);
				setCache('classtypedatapc',null);
				JsonReturn(array('status'=>1,'info'=>'添加栏目成功，继续添加~','url'=>U('addclass',array('pid'=>$w['pid'],'biaoshi'=>$w['molds']))));
			}else{
				JsonReturn(array('status'=>0,'info'=>'新增失败！'));
			}
		}
		//模块
		$this->molds = M('Molds')->findAll(['isopen'=>1]);
		
		$this->pid = $this->frparam('pid');
		$this->biaoshi = $this->frparam('biaoshi',1);
		$this->classtypes = $this->classtypetree;
		$this->display('classtype-add');
		
	}
	function editclass(){
		$this->data = M('classtype')->find(array('id'=>$this->frparam('id')));
		$this->fields_biaoshi = 'classtype';
		if($this->frparam('go')==1){
			$htmlurl = $this->frparam('htmlurl',1);
			if($htmlurl==''){
				$htmlurl = str_replace(' ','',pinyin($this->frparam('classname',1)));
			}
			
			if(in_array(strtolower($htmlurl),array('message','user','comment','home','common','order','tags','wechat','login'))){
				JsonReturn(array('status'=>0,'info'=>'URL链接命名不能是：message,user,comment,home,common,order,tags,wechat,login,jzpay'));
			}
			if(stripos($htmlurl,'.php')!==false){
				JsonReturn(array('status'=>0,'info'=>'非法URL'));
			}
			$w['pid'] = $this->frparam('pid');
			$w['orders'] = $this->frparam('orders');
			$w['classname'] = $this->frparam('classname',1);
			$w['molds'] = $this->frparam('molds',1);
			$w['description'] = $this->frparam('description',1);
			$w['keywords'] = $this->frparam('keywords',1);
			$w['id'] = $this->frparam('id');
			$w['litpic'] = $this->frparam('litpic',1);
			$w['body'] = $this->frparam('body',4);
			$w['htmlurl'] = $htmlurl;
			$w['iscover'] = $this->frparam('iscover');
			$w['lists_html'] = $this->frparam('lists_html',1) ? $this->frparam('lists_html',1) : $this->frparam('lists_html_write',1);
			$w['details_html'] = $this->frparam('details_html',1) ? $this->frparam('details_html',1) : $this->frparam('details_html_write',1);
			$w['lists_num'] = $this->frparam('lists_num');
			$w['gourl'] = $this->frparam('gourl',1);
			$w['lists_html'] = str_ireplace('.html','',$w['lists_html']);
			$w['details_html'] = str_ireplace('.html','',$w['details_html']);
			
			
			$data = $this->frparam();
			$data = get_fields_data($data,'classtype');
			$w = array_merge($data,$w);
			
			//检测pid是否为该栏目下级
			if(checkClass($w['pid'],$this->data['id']) || ($w['pid']==$this->data['id'])){
				JsonReturn(array('status'=>0,'info'=>'不能选择当前栏目及下级为顶级栏目'));
			}
			
			
			$a = M('classtype')->update(array('id'=>$w['id']),$w);
			if($a){
				if($w['iscover']==1){
					$children = M('classtype')->update(array('pid'=>$w['id']),array('lists_html'=>$w['lists_html'],'details_html'=>$w['details_html'],'lists_num'=>$w['lists_num']));
				}
				//批量修改栏目对应的模块内容htmlurl
				if($this->data['htmlurl']!=$data['htmlurl']){
					M($data['molds'])->update(array('tid'=>$data['id']),array('htmlurl'=>$data['htmlurl']));
			
				}
				//批量修改栏目url
				if($this->webconf['islevelurl']==1){
					if( ($this->data['htmlurl']!=$data['htmlurl']) || ($this->data['pid']!=$w['pid'])){
						
						//层级
						$classtypetree = classTypeData();
						$children = get_children($classtypetree[$w['id']],$classtypetree,5);
						//计算当前url
						//以前的url替换成当前的url
						$old_htmlurl = $this->data['htmlurl'];
						if(strpos($w['htmlurl'],'/')!==false){
							//获取最后一个
							$htl = explode('/',$w['htmlurl']);
							$htl_new = end($htl);//最后一个名字
							
						}else{
							$htl_new = $w['htmlurl'];
						}
						
						if($w['pid']!=0){
							$p_html = $classtypetree[$w['pid']]['htmlurl'];
							$new_htmlurl = $p_html.'/'.$htl_new;
						}else{
							$new_htmlurl = $htl_new;
						}
						//更新栏目及其内容HTML
						M('classtype')->update(['id'=>$data['id']],['htmlurl'=>$new_htmlurl]);
						M($data['molds'])->update(array('tid'=>$data['id']),array('htmlurl'=>$new_htmlurl));
						
						foreach($children as $v){
							$html = substr($v['htmlurl'],strlen($old_htmlurl));
							$htmlurl_s = $new_htmlurl.$html;
							M('classtype')->update(['id'=>$v['id']],['htmlurl'=>$htmlurl_s]);
							M($v['molds'])->update(['tid'=>$v['id']],['htmlurl'=>$htmlurl_s]);
						}

					}


				}
				
				setCache('classtypetree',null);
				setCache('classtype',null);
				setCache('mobileclasstype',null);
				setCache('classtypedatamobile',null);
				setCache('classtypedatapc',null);
				JsonReturn(array('status'=>1));
			}else{
				JsonReturn(array('status'=>0,'info'=>'您未做任何修改，不能提交！'));
			}
		}
		
		//模块
		$this->molds = M('Molds')->findAll(['isopen'=>1]);
		$this->classtypes = $this->classtypetree;
		$this->display('classtype-edit');
		
	}
	
	function editClassOrders(){
		$w['orders'] = $this->frparam('orders');
		
		$r = M('classtype')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>'修改失败！'));
		}
		setCache('classtypetree',null);
		setCache('classtype',null);
		setCache('mobileclasstype',null);
		setCache('classtypedatamobile',null);
		setCache('classtypedatapc',null);
		JsonReturn(array('code'=>0,'info'=>'修改成功！'));
		
	}
	function deleteclass(){
		$id = $this->frparam('id');
		if($id){
			//检测栏目是否有下级
			if(M('classtype')->find(['pid'=>$id])){
				JsonReturn(array('status'=>0,'info'=>'该栏目有子栏目，请先删除子栏目！'));
			}
			
			$a = M('classtype')->delete(array('id'=>$id));
			if($a){
				setCache('classtypetree',null);
				setCache('classtype',null);
				setCache('mobileclasstype',null);
				setCache('classtypedatamobile',null);
				setCache('classtypedatapc',null);
				JsonReturn(array('status'=>1));
			}else{
				JsonReturn(array('status'=>0,'info'=>'删除失败！'));
			}
		}
		
	}
	function change_status(){
		$id = $this->frparam('id',1);
		if(!$id){
			JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
		}
		
		$x = M('Classtype')->find('id='.$id);
		if($x['isshow']==1){
			$x['isshow']=0;
		}else{
			$x['isshow']=1;
		}
		M('Classtype')->update(array('id'=>$id),array('isshow'=>$x['isshow']));
		setCache('classtypetree',null);
		setCache('classtype',null);
		setCache('mobileclasstype',null);
		setCache('classtypedatamobile',null);
		setCache('classtypedatapc',null);
	}
	
	function get_pinyin(){
		
		$classname = $this->frparam('classname',1);
		if($classname){
			$data = pinyin($classname,'first');
			JsonReturn(['code'=>0,'data'=>$data]);
		}
		
	}
	
	function addmany(){
		if($_POST){
			
			$molds = $this->frparam('molds',1);
			$pid = $this->frparam('pid',0,0);
			$classname = $this->frparam('classname',1);
			if(!trim($classname)){
				JsonReturn(['code'=>1,'msg'=>'栏目不能为空！']);
			}
			$classname = explode("\n",trim($classname));
			$classtypetree = classTypeData();
			foreach($classname as $k=>$v){
				if($v){
					if(strpos($v,'|')!==false){
						$d = explode('|',$v);
					}else{
						$d = [$v,pinyin($v,'first')];
					}
					$w['molds'] = $molds;
					$w['classname'] = $d[0];
					$w['seo_classname'] = $d[0];
					$w['pid'] = $pid;
					$d[1] = str_replace(' ','-',$d[1]);
					if($this->webconf['islevelurl'] && $w['pid']!=0){
						//层级
						$html = $classtypetree[$w['pid']]['htmlurl'].'/'.$d[1];
					}else{
						$html = $d[1];
					}
					if(stripos($html,'.php')!==false){
						JsonReturn(array('code'=>1,'info'=>'非法URL'));
					}
					$w['htmlurl'] = str_replace(' ','-',$html);
					$w['lists_num'] = $this->frparam('lists_num',0,10);
					$w['lists_html'] = $this->frparam('lists_html',1);
					$w['details_html'] = $this->frparam('details_html',1);
					$w['isshow'] =$this->frparam('isshow',0,1);
					$w['ishome'] =$this->frparam('ishome',0,1);
					M('classtype')->add($w);
					$w = [];
				}
				
				
			}
			setCache('classtypetree',null);
			setCache('classtype',null);
			setCache('mobileclasstype',null);
			setCache('classtypedatamobile',null);
			setCache('classtypedatapc',null);
			JsonReturn(['code'=>0,'msg'=>'success']);
		}
		$this->molds = M('molds')->find(['biaoshi'=>'classtype']);
		$this->moldslist = M('molds')->findAll(['isopen'=>1]);
		$this->classtypes = $this->classtypetree;;
		
		$this->display('classtype-addmany');
		
		
	}
	
	public function get_html(){
		$molds = $this->frparam('molds',1,'article');
		//获取前台template
		$indexdata = file_get_contents(APP_PATH.'index.php');
		$r = preg_match("/define\('HOME_VIEW','([^']+)'\)/",$indexdata,$matches);
		if($r){
			$template = $matches[1];
		}else{
			$template = 'template';
		}
		$rr = preg_match("/define\('TPL_PATH',[\'|\"](.*?)[\'|\"]\)/",$indexdata,$matches2);
		if($rr){
			$tplpath = $matches2[1];
		}else{
			$tplpath = 'Home';
		}
		$dir = APP_PATH.$tplpath.'/'.$template.'/'.$this->webconf['pc_template'].'/'.strtolower($molds);
		$fileArray=array();
		if(is_dir($dir)){

			if (false != ($handle = opendir ( $dir ))) {
				$i=0;
				while ( false !== ($file = readdir ( $handle )) ) {
					//去掉"“.”、“..”以及带“.xxx”后缀的文件
					if ($file != "." && $file != ".."&& strpos($file,".html")!==false) {
						$fileArray[$i]=['html'=>$file,'value'=>str_replace('.html','',$file)];
						
						$i++;
					}
				}
				//关闭句柄
				closedir ( $handle );
			}
		}
		$m = M('molds')->find(['biaoshi'=>$molds]);
		if(!count($fileArray)){
			$fileArray[] = ['html'=>$m['list_html'],'value'=>str_replace('.html','',$m['list_html'])];
			$fileArray[] = ['html'=>$m['details_html'],'value'=>str_replace('.html','',$m['details_html'])];
			
		}
		
		
		
		
		JsonReturn(['code'=>0,'data'=>$fileArray,'path'=>$dir,'lists_html'=>str_replace('.html','',$m['list_html']),'details_html'=>str_replace('.html','',$m['details_html'])]);

	}
	
		
	function changeClass(){
		$pid = $this->frparam('pid',0,0);
		$tids = $this->frparam('tids',1);
		$tids_arr = explode(',',$tids);
		
		foreach($tids_arr as $v){
			//检测pid是否为该栏目下级
			if(checkClass($pid,$v) || ($pid==$v)){
				JsonReturn(array('code'=>1,'msg'=>'不能选择当前栏目及下级为顶级栏目'));
			}
		}
		
		//批量修改栏目url
		if($this->webconf['islevelurl']==1){
				//层级
				$classtypetree = classTypeData();
			foreach($tids_arr as $v){	
				$children = get_children($classtypetree[$v],$classtypetree,5);
				//计算当前url
				//以前的url替换成当前的url
				$old_htmlurl = $classtypetree[$v]['htmlurl'];
				if(strpos($old_htmlurl,'/')!==false){
					//获取最后一个
					$htl = explode('/',$old_htmlurl);
					$htl_new = end($htl);//最后一个名字
					
				}else{
					$htl_new = $old_htmlurl;
				}
				
				if($pid!=0){
					$p_html = $classtypetree[$pid]['htmlurl'];
					$new_htmlurl = $p_html.'/'.$htl_new;
				}else{
					$new_htmlurl = $htl_new;
				}
				//更新栏目及其内容HTML
				M('classtype')->update(['id'=>$v],['htmlurl'=>$new_htmlurl,'pid'=>$pid]);
				M($classtypetree[$v]['molds'])->update(array('tid'=>$v),array('htmlurl'=>$new_htmlurl,'tid'=>$pid));
				
				foreach($children as $vv){
					$html = substr($vv['htmlurl'],strlen($old_htmlurl));
					$htmlurl_s = $new_htmlurl.$html;
					M('classtype')->update(['id'=>$vv['id']],['htmlurl'=>$htmlurl_s]);
					M($vv['molds'])->update(['tid'=>$vv['id']],['htmlurl'=>$htmlurl_s]);
				}

			}


		}else{
			M('classtype')->update(' id in('.$tids.')',['pid'=>$pid]);
			
		}
		setCache('classtypetree',null);
		setCache('classtype',null);
		setCache('mobileclasstype',null);
		setCache('classtypedatamobile',null);
		setCache('classtypedatapc',null);
		JsonReturn(array('code'=>0,'msg'=>'操作成功！'));
	}
	
	
}
	