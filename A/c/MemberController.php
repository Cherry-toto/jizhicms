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

class MemberController extends CommonController
{

	function index(){
		$this->username  = $this->frparam('username',1);
		$this->starttime  = $this->frparam('start',1);
		$this->endtime  = $this->frparam('end',1);
		$this->tel  = $this->frparam('tel',1);
		$this->isshow  = $this->frparam('isshow');
		$data = $this->frparam();
		$res = molds_search('member',$data);
		$this->fields_search = $res['fields_search'];
		$this->fields_list = M('Fields')->findAll(array('molds'=>'member','islist'=>1),'orders desc');
		if($this->frparam('ajax')){
			
			$page = new Page('member');
			$sql='1=1';
			if($this->frparam('start',1)){
				$start = strtotime($this->frparam('start',1));
				$sql.=" and regtime >= ".$start;
				
			}
			if($this->frparam('end',1)){
				$end = strtotime($this->frparam('end',1).' 23:59:59');
				$sql.="  and regtime <= ".$end;
			}
			if($this->frparam('tel',1)){
				$sql.=" and tel like '%".$this->frparam('tel',1)."%' ";
			}
			
			if($this->frparam('isshow')){
				$isshow = $this->frparam('isshow')==2 ? 0 : $this->frparam('isshow');
				$sql.=" and isshow=".$isshow;
			}
			
			if($this->frparam('username',1)){
				$sql .=" and username like '%".$this->frparam('username',1)."%' ";
			}
			$get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
			$sql .= $get_sql;
			$lists = $page->where($sql)->orderby('id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			foreach($lists as $k=>$v){
				
				$v['new_gid'] = get_info_table('member_group',['id'=>$v['gid']],'name');
				$v['new_litpic'] = $v['litpic']!='' ? '<a href="'.$v['litpic'].'" target="_blank"><img src="'.$v['litpic'].'" width="100px" /></a>':'无';
				$v['new_isshow'] = $v['isshow']==1 ? '<span class="layui-badge layui-bg-green">显示</span>' : '<span class="layui-badge">不显示</span>';
				$v['new_regtime'] = $v['regtime']!=0 ? date('Y-m-d H:i:s',$v['regtime']) : '-';
				$v['new_logintime'] = $v['logintime']!=0 ? date('Y-m-d H:i:s',$v['logintime']) : '-';
				$v['edit_url'] = U('Member/memberedit',['id'=>$v['id']]);
				
				foreach($this->fields_list as $vv){
					$v[$vv['field']] = format_fields($vv,$v[$vv['field']]);
				}
				$ajaxdata[]=$v;
				
			}
			
			$pages = $page->pageList();
			$this->num = $page->sum;
			$this->lists = $lists;
			$this->pages = $pages;
			JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
			
		}
		
		
		
		$this->display('member-list');
		
		
	}

	function memberadd(){
		$this->fields_biaoshi = 'member';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data = get_fields_data($data,'member');
			$data['username'] = $this->frparam('username',1);
			$data['money'] = $this->frparam('money',3);
			$data['gid'] = $this->frparam('gid');
			$data['jifen'] = $this->frparam('jifen');
			$data['email'] = $this->frparam('email',1);
			$data['litpic'] = $this->frparam('litpic',1);
			$data['address'] = $this->frparam('address',1);
			$data['province'] = $this->frparam('province',1);
			$data['city'] = $this->frparam('city',1);
			$data['signature'] = $this->frparam('signature',1);
			$data['birthday'] = $this->frparam('birthday',1);
			//检查是否邮箱/手机号重复
			if(M('member')->find(['email'=>$data['email']])){
				JsonReturn(array('code'=>1,'msg'=>'邮箱已被注册！'));
			}
			if(M('member')->find(['tel'=>$data['tel']]) && $data['tel']!=''){
				JsonReturn(array('code'=>1,'msg'=>'手机号已被注册！'));
			}
			$data['pass'] = md5(md5($data['pass']).md5($data['pass']));
			$data['regtime'] = time();
			if(M('member')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>'添加成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'添加失败，请重新提交！'));
			}
			
			
			
		}
		
		
		$this->display('member-add');
	}
	
	function memberedit(){
		$this->fields_biaoshi = 'member';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data = get_fields_data($data,'member');
			$data['username'] = $this->frparam('username',1);
			$data['email'] = $this->frparam('email',1);
			$data['money'] = $this->frparam('money',3);
			$data['jifen'] = $this->frparam('jifen');
			$data['gid'] = $this->frparam('gid');
			$data['litpic'] = $this->frparam('litpic',1);
			$data['address'] = $this->frparam('address',1);
			$data['province'] = $this->frparam('province',1);
			$data['city'] = $this->frparam('city',1);
			$data['signature'] = $this->frparam('signature',1);
			$data['birthday'] = $this->frparam('birthday',1);
			if($data['pass']!=''){
				if($data['pass']!=$data['repass']){
					JsonReturn(array('code'=>1,'msg'=>'两次密码不同！'));
				}
				$data['pass']  =  md5(md5($data['pass']).md5($data['pass']));
			}else{
				unset($data['pass']);
			}
			if(M('member')->update(array('id'=>$data['id']),$data)){
				JsonReturn(array('code'=>0,'msg'=>'修改成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'修改失败，请重新提交！'));
			}
			
			
			
		}
		
		$this->data = M('member')->find(['id'=>$this->frparam('id')]);
		if(!$this->data){
			Error('没有找到该用户！');
		}
		
		$this->display('member-edit');
	}

	
	function member_del(){
		
		$id = $this->frparam('id');
		if($id){
			if(M('member')->delete(array('id'=>$id))){
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
			
		}
		
		
	}
	
	//批量删除会员
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			if(M('Member')->delete('id in('.$data.')')){
				JsonReturn(array('code'=>0,'msg'=>'批量删除成功！'));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>'批量操作失败！'));
			}
		}
	}

	public function change_status(){
		$id = $this->frparam('id',1);
		if(!$id){
			JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
		}
		
		$x = M('member')->find('id='.$id);
		if($x['isshow']==1){
			$x['isshow']=0;
		}else{
			$x['isshow']=1;
		}
		M('member')->update(array('id'=>$id),array('isshow'=>$x['isshow']));
	}

	public function membergroup(){
		
		$list = M('member_group')->findAll(null,'orders desc');
		$list = set_class_haschild($list);
		$lists = getTree($list);
		
		
		$this->lists = $lists;
		$this->display('membergroup-list');
	}
	
	function editOrders(){
		$w['orders'] = $this->frparam('orders');
		
		$r = M('membergroup')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>'修改失败！'));
		}
		JsonReturn(array('code'=>0,'info'=>'修改成功！'));
		
	}

	function groupadd(){
		$this->fields_biaoshi = 'member_group';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data['name'] = $this->frparam('name',1);
			$data['description'] = $this->frparam('description',1);
			if($this->frparam('ruler',2)){
				$data['paction'] = (count($this->frparam('ruler',2))>0)?','.implode(',',$this->frparam('ruler',2)).',':'';
			}else{
				$data['paction'] ='';
			}
			
			if(M('member_group')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>'新增成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'新增失败，请重新提交！'));
			}
			
			
			
		}
	
		$rulers = M('power')->findAll(null,'id ASC');
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
		
		
		
		$this->display('membergroup-add');
	}
	function groupedit(){
		$this->fields_biaoshi = 'member_group';
		if($this->frparam('go')==1){
			$data = $this->frparam();
			$data['name'] = $this->frparam('name',1);
			$data['description'] = $this->frparam('description',1);
			if($this->frparam('ruler',2)){
				$data['paction'] = (count($this->frparam('ruler',2))>0)?','.implode(',',$this->frparam('ruler',2)).',':'';
			}else{
				$data['paction'] ='';
			}
			if(M('member_group')->update(array('id'=>$data['id']),$data)){
				JsonReturn(array('code'=>0,'msg'=>'修改成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'修改失败，请重新提交！'));
			}
			
			
			
		}
		
		$this->data = M('member_group')->find(['id'=>$this->frparam('id')]);
		$rulers = M('power')->findAll(null,'id ASC');
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
			Error('没有该角色！');
		}
		
		$this->display('membergroup-edit');
	}

	public function change_group_status(){
		$id = $this->frparam('id',1);
		if(!$id || ($id==1)){
			JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
		}
		
		$x = M('member_group')->find('id='.$id);
		if($x['isagree']==1){
			$x['isagree']=0;
		}else{
			$x['isagree']=1;
		}
		M('member_group')->update(array('id'=>$id),array('isagree'=>$x['isagree']));
	}
	function group_del(){
		
		$id = $this->frparam('id');
		if($id){
			//检查是否有管理员
			if(M('member')->getCount(array('gid'=>$id))>0){
				JsonReturn(array('code'=>1,'msg'=>'该分组下存在用户，请先移除用户再删除！'));
			}
			if(M('member_group')->delete(array('id'=>$id))){
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'删除失败，请重试！'));
			}
		}else{
			JsonReturn(array('code'=>1,'msg'=>'非法操作！'));
		}
		
		
	}
	
	public function power(){
		
		$rulers = M('power')->findAll();
		$rulers = set_class_haschild($rulers);
		$rulers = getTree($rulers);
		
		$this->lists = $rulers;
		$this->display('power-list');
	}
	
	public function addrulers(){
		
		$this->fields_biaoshi = 'power';
		if($this->frparam('go',1)==1){
			
			$data['name'] = $this->frparam('name',1);
			$data['action'] = $this->frparam('action',1);
			$data['pid'] = $this->frparam('pid');
			
			if(M('power')->add($data)){
				
				JsonReturn(array('code'=>0,'msg'=>'添加成功！'));
				exit;
			}else{
				//Error('添加失败！');
				JsonReturn(array('code'=>1,'msg'=>'添加失败！'));
				exit;
			}
			
			
			
		}
		
		$rulers = M('power')->findAll('pid=0');
		//$rulers = getTree($rulers);
		$this->pid = $this->frparam('pid');
		$this->rulers = $rulers;
		
		$this->display('power-add');
	}
	
	public function editrulers(){

		$this->fields_biaoshi = 'power';
		if($this->frparam('go')==1){
			$data['name'] = $this->frparam('name',1);
			$data['action'] = $this->frparam('action',1);
			$data['pid'] = $this->frparam('pid');
			
			$a = M('power')->update(array('id'=>$this->frparam('id')),$data);
			if($a){
				JsonReturn(array('status'=>1));
			}else{
				JsonReturn(array('status'=>1,'info'=>'修改失败！'));
			}
		}
		$this->data = M('power')->find(array('id'=>$this->frparam('id')));
		
		$rulers = M('power')->findAll('pid=0');
		//$rulers = getTree($rulers);
	
		$this->rulers = $rulers;
		
		$this->display('power-edit');
	}
	
	public function deleterulers(){
		$id = $this->frparam('id');
		if($id){
			//判断是否为系统功能，系统功能不能删除
			$ruler = M('power')->find(array('id'=>$id));
			
			$n = M('power')->find(array('pid'=>$id));
			if($n){
				JsonReturn(array('code'=>1,'msg'=>'删除失败，该分类有下级功能，请先删除下级功能！'));
			}else{
				$m = M('power')->delete(array('id'=>$id));
				if($m){
					JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
				}else{
					JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
				}
			}
			
			
			
		}else{
			JsonReturn(array('code'=>1,'msg'=>'未选择删除对象！'));
		}
	}









}