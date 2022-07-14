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

class AdminController extends CommonController
{

	
	
	public function group(){
		$page = new Page('Level_group');
		$sql = ' 1=1 ';
		if($this->admin['gid']!=1){
			$sql.=" and id!=1 ";
		}
		$data = $page->where($sql)->orderby('id desc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		$this->display('group-list');
	}
	function group_del(){
		
		$id = $this->frparam('id');
		if($id){
			//检查是否有管理员
			if(M('level')->getCount(array('gid'=>$id))>0){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('该角色下存在用户，请先移除用户再删除！')));
			}
			if($id==1){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败，该分组不允许删除！')));
			}
			$data = M('level_group')->find(array('id'=>$id));
			if(M('level_group')->delete(array('id'=>$id))){
				$w['molds'] = 'level_group';
				$w['data'] = serialize($data);
				$w['title'] = '['.$data['id'].']'.$data['name'];
				$w['addtime'] = time();
				M('recycle')->add($w);
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败，请重试！')));
			}
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
		}
		
		
	}
	
	function groupedit(){
		$this->fields_biaoshi = 'level_group';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			if($this->admin['gid']!=1 && $this->frparam('isadmin')==1){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败，您的权限不足！')));
			}
			$data['name'] = $this->frparam('name',1);
			$data['ischeck'] = $this->frparam('ischeck');
			$data['description'] = $this->frparam('description',1);
			$data['paction'] = (count($this->frparam('ruler',2))>0)?','.implode(',',$this->frparam('ruler',2)).',':'';
			$data['tids'] = (count($this->frparam('tids',2))>0)?','.implode(',',$this->frparam('tids',2)).',':'';
			if(M('level_group')->update(array('id'=>$data['id']),$data)){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败，请重新提交！')));
			}
			
			
			
		}
		
		$this->data = M('level_group')->find(['id'=>$this->frparam('id')]);
		$rulers = M('ruler')->findAll(null,'id ASC');
		$ruler_top = array();
		$ruler_children = array();
		foreach($rulers as $v){
			if($v['pid']==0){
				$ruler_top[]=$v;
			}else{
				$ruler_children[$v['pid']][]=$v;
			}
		}
		$this->ruler_top = $ruler_top;
		$this->ruler_children = $ruler_children;
		
		if(!$this->data){
			Error(JZLANG('没有该角色！'));
		}
		
		$this->display('group-edit');
	}
	
	function groupadd(){
		$this->fields_biaoshi = 'level_group';
		if($this->frparam('go')==1){
			$data = $this->frparam();
            if($this->admin['gid']!=1 && $this->frparam('isadmin')==1){
                JsonReturn(array('code'=>1,'msg'=>JZLANG('您的权限不足！')));
            }
			$data['name'] = $this->frparam('name',1);
			$data['ischeck'] = $this->frparam('ischeck');
			$data['description'] = $this->frparam('description',1);
			$data['paction'] = (count($this->frparam('ruler',2))>0)?','.implode(',',$this->frparam('ruler',2)).',':'';
			$data['tids'] = (count($this->frparam('tids',2))>0)?','.implode(',',$this->frparam('tids',2)).',':'';
			if(M('level_group')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('新增成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('新增失败，请重新提交！')));
			}
			
			
			
		}
		
		
		$rulers = M('ruler')->findAll(null,'id ASC');
		$ruler_top = array();
		$ruler_children = array();
		foreach($rulers as $v){
			if($v['pid']==0){
				$ruler_top[]=$v;
			}else{
				$ruler_children[$v['pid']][]=$v;
			}
		}
		$this->ruler_top = $ruler_top;
		$this->ruler_children = $ruler_children;
		
		
		
		$this->display('group-add');
	}
	public function change_group_status(){
		$id = $this->frparam('id',1);
		if(!$id){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
		}
		if($id==1){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败，该分组不允许修改！')));
		}
		
		$x = M('Level_group')->find('id='.$id);
		if($x['isagree']==1){
			$x['isagree']=0;
		}else{
			$x['isagree']=1;
		}
		M('Level_group')->update(array('id'=>$id),array('isagree'=>$x['isagree']));
	}
	
	public function adminlist(){
		
		$data = $this->frparam();
		$res = molds_search('level',$data);
		$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>'level','islist'=>1),'orders desc');
		$this->username = $this->frparam('username',1);
		$this->endtime = $this->frparam('end',1);
		$this->status = $this->frparam('status');
		$this->starttime = $this->frparam('start',1);
		if($this->frparam('ajax')){
			 $admin = adminInfo($_SESSION['admin']['id']);
			$page = new Page('level');
			$sql = ' 1=1 ';
			if($this->frparam('status')){
				$status = $this->frparam('status')==1 ? 1 : 0;
				$sql .= ' and status='.$status;
			}
			
			if($this->frparam('username',1)){
				$sql .= " and name like '%".$this->frparam('username',1)."%' ";
			}
			
			//只有超级管理员有权限看到整个列表
			if($this->admin['gid']!=1){
				$sql.= " and gid!=1 ";
			}
			
		   
			if($this->frparam('start',1)){
				$time = strtotime($this->frparam('start',1));
				
				$sql .= " and regtime >= ".$time;
				
			}
			if($this->frparam('end',1)){
				$end = strtotime($this->frparam('end',1).' 23:59:59');
				$sql .= " and regtime <= ".$end;
			}
			
			
			$sql .= $get_sql;
			
			
			$lists = $page->where($sql)->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$pages = $page->pageList();
			
			$ajaxdata = [];
			foreach($lists as $k=>$v){
				$v['group'] = get_info_table('level_group',['id'=>$v['gid']],'name');
				$v['new_logintime'] = $v['logintime']!=0 ? date('Y-m-d H:i:s',$v['logintime']) : '-';
				$v['new_regtime'] = $v['regtime']!=0 ? date('Y-m-d H:i:s',$v['regtime']) : '-';
				$v['edit_url'] = U('Admin/adminedit',array('id'=>$v['id']));
				foreach($this->fields_list as $vv){
					$v[$vv['field']] = format_fields($vv,$v[$vv['field']]);
				}
				$ajaxdata[]=$v;
				
			}
			
			$this->lists = $lists;
			$this->page = $pages;
			$this->sum = $page->sum;
			JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
		}
		
       
		$this->display('admin-list');
	}
	
	public function adminedit(){
		$this->fields_biaoshi = 'level';
		$id = $this->frparam('id',1);
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data = get_fields_data($data,'level');
			$data['gid'] = $this->frparam('gid',0,$this->admin['gid']);
			//防止越权操作
			$change_admin = M('level')->find(['id'=>$id]);
			if($this->admin['gid']!=1 && $change_admin['gid']==1){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('您没有权限操作！')));
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
			$data['id'] = $id;
			if($data['id']==0){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
			}
			
			
            
			if($data['pass']){
				if($data['pass']!=$data['repass']){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('两次密码不同！')));
				}
				$data['pass'] = md5(md5($data['pass']).'YF');
			}else{
				unset($data['pass']);
			}

			
          
           
			
			if($data['tel']){
				if(M('level')->find("tel='".$data['tel']."' and id!=".$data['id'])){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('手机号已被注册！')));
				}	
			}
			
			if(M('level')->find("name='".$data['name']."' and id!=".$data['id'])){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('昵称已被使用！')));
			}
			
			if($data['email']){
				if(M('level')->find("email='".$data['email']."' and id!=".$data['id'])){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('邮箱已被使用！')));
				}
			}
			
			$x = M('level')->update(array('id'=>$data['id']),$data);
			if($x){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败！')));
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
		setCache('admin_'.$this->admin['id'].'_token',$token);
		$this->token = $token;
		$this->display('admin-edit');
	}
	
	public function adminadd(){
		
		$this->fields_biaoshi = 'level';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data = get_fields_data($data,'level');
			$data['gid'] = $this->frparam('gid',0,$this->admin['gid']);
			//防止越权操作
			if($this->admin['gid']!=1 && $data['gid']==1){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('您没有权限操作！')));
			}
			//检查token
			$token = getCache('admin_'.$this->admin['id'].'_token');
			if(!isset($_SESSION['token']) || !$token || $token!=$_SESSION['token']){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
			}
			
			
			$data['email'] = $this->frparam('email',1);
			$data['pass'] = $this->frparam('pass',1);
			$data['repass'] = $this->frparam('repass',1);
			
			$data['name'] = $this->frparam('name',1);
			$data['tel'] = $this->frparam('tel',1);
			$data['status'] = $this->frparam('status');
			
			$data['regtime'] = time();
			$data['logintime'] = time();
			
            
			if($data['pass']!=$data['repass']){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('两次密码不同！')));
			}
			$data['pass'] = md5(md5($data['pass']).'YF');
			if($data['tel']){
				if(M('level')->find("tel='".$data['tel']."'")){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('手机号已被注册！')));
				}
			}
			
			if(M('level')->find("name='".$data['name']."'")){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('昵称已被使用！')));
			}
			if($data['email']){
				if(M('level')->find("email='".$data['email']."' ")){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('邮箱已被使用！')));
				}
			}
			$x = M('level')->add($data);
			if($x){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('新增成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('新增失败！')));
			}
			
		}
        $this->admin = $_SESSION['admin'];
        $this->groups = M('level_group')->findAll();
		if($_SESSION['admin']['isadmin']==1){
			
			$this->isadmin = true;
		}else{
			$this->isadmin = false;
		}
		
		$token = getRandChar(10);
		$_SESSION['token'] = $token;
		setCache('admin_'.$this->admin['id'].'_token',$token);
		$this->token = $token;
		$this->display('admin-add');
	
	}
	
	public function change_status(){
		$id = $this->frparam('id',1);
		if(!$id || $id==1){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
		}
		
		$x = M('level')->find('id='.$id);
		
		
		if($x['status']==1){
			$x['status']=0;
		}else{
			$x['status']=1;
		}
		M('level')->update(array('id'=>$id),array('status'=>$x['status']));
	}
	public function admindelete(){
    	$id = $this->frparam('id',1);
        if($id==''){
        	JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
        }
		
		if($id==1){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('系统管理员不能删除！')));
		}
		
        $data = M('level')->find(array('id'=>$id));
        $x = M('level')->delete(array('id'=>$id));
		  if($x){
			$w['molds'] = 'level';
			$w['data'] = serialize($data);
			$w['title'] = '['.$data['id'].']'.$data['name'];
			$w['addtime'] = time();
			M('recycle')->add($w);
			JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
		  }else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
		  }
    }

	
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if($this->admin['gid']!=1){
				$lists = M('level')->findAll('id in('.$data.')');
				foreach($lists as $v){
					if($v['gid']==1){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
					}
				}
			}
			$all = M('level')->findAll('id in('.$data.')');
			if(M('level')->delete('id in('.$data.')')){
				foreach($all as $v){
					$w['molds'] = 'level';
					$w['data'] = serialize($v);
					$w['title'] = '['.$v['id'].']'.$v['name'];
					$w['addtime'] = time();
					M('recycle')->add($w);
				}
				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	}