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

            $get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
            $sql .= $get_sql;
            $lists = $page->where($sql)->orderby('id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
            $ajaxdata = [];
            foreach($lists as $k=>$v){


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

            //检查是否邮箱/手机号重复
            if(M('member')->find(['email'=>$data['email']])){
                JsonReturn(array('code'=>1,'msg'=>JZLANG('邮箱已被注册！')));
            }
            if(M('member')->find(['tel'=>$data['tel']]) && $data['tel']!=''){
                JsonReturn(array('code'=>1,'msg'=>JZLANG('手机号已被注册！')));
            }
            $data['pass'] = md5(md5($this->frparam('pass',1)).md5($this->frparam('pass',1)));
            if(M('member')->add($data)){
                JsonReturn(array('code'=>0,'msg'=>JZLANG('添加成功！')));
            }else{
                JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败，请重新提交！')));
            }



        }


        $this->display('member-add');
    }

    function memberedit(){
        $this->fields_biaoshi = 'member';
        if($this->frparam('go')==1){
            $data = $this->frparam();
            $data = get_fields_data($data,'member');

            if($this->frparam('pass',1)){
                if($this->frparam('pass',1)!=$this->frparam('repass',1)){
                    JsonReturn(array('code'=>1,'msg'=>JZLANG('两次密码不同！')));
                }
                $data['pass']  =  md5(md5($this->frparam('pass',1)).md5($this->frparam('pass',1)));
            }else{
                unset($data['pass']);
            }

            //检查是否邮箱/手机号重复
            if($data['email']){
                if(M('member')->find("email='".$data['email']."' and id!=".$data['id'])){
                    JsonReturn(array('code'=>1,'msg'=>JZLANG('邮箱已被注册！')));
                }
            }
            if($data['tel']){
                if(M('member')->find("tel='".$data['tel']."' and id!=".$data['id'])){
                    JsonReturn(array('code'=>1,'msg'=>JZLANG('手机号已被注册！')));
                }
            }


            if(M('member')->update(array('id'=>$data['id']),$data)){
                JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
            }else{
                JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败，请重新提交！')));
            }



        }

        $this->data = M('member')->find(['id'=>$this->frparam('id')]);
        if(!$this->data){
            Error(JZLANG('没有找到该用户！'));
        }

        $this->display('member-edit');
    }

    function member_del(){
		
		$id = $this->frparam('id');
		if($id){
			$data = M('member')->find(array('id'=>$id));
			if(M('member')->delete(array('id'=>$id))){
				$w['molds'] = 'member';
				$w['data'] = serialize($data);
				$w['title'] = '['.$data['id'].']'.$data['username'];
				$w['addtime'] = time();
				M('recycle')->add($w);
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
			
		}
		
		
	}
	
	//批量删除会员
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$all = M('Member')->findAll('id in('.$data.')');
			if(M('Member')->delete('id in('.$data.')')){
				foreach($all as $v){
					$w['molds'] = 'member';
					$w['data'] = serialize($v);
                    $w['title'] = '['.$v['id'].']'.$v['username'];
					$w['addtime'] = time();
					M('recycle')->add($w);
				}
				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
			}
		}
	}

	public function change_status(){
		$id = $this->frparam('id',1);
		if(!$id){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
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
        $this->fields_list = M('Fields')->findAll(array('molds'=>'member_group','islist'=>1),'orders desc');
		$this->lists = $lists;
		$this->display('membergroup-list');
	}
	
	function editOrders(){
		$w['orders'] = $this->frparam('orders');
		
		$r = M('member_group')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));
		
	}

	function groupadd(){
		$this->fields_biaoshi = 'member_group';
		if($this->frparam('go')==1){
			$data = $this->frparam();
            $data = get_fields_data($data,'member_group');
			$data['name'] = $this->frparam('name',1);
			$data['description'] = $this->frparam('description',1);
			if($this->frparam('ruler',2)){
				$data['paction'] = (count($this->frparam('ruler',2))>0)?','.implode(',',$this->frparam('ruler',2)).',':'';
			}else{
				$data['paction'] ='';
			}
			
			if(M('member_group')->add($data)){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('新增成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('新增失败，请重新提交！')));
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
            $data = get_fields_data($data,'member_group');
			$data['name'] = $this->frparam('name',1);
			$data['description'] = $this->frparam('description',1);
			if($this->frparam('ruler',2)){
				$data['paction'] = (count($this->frparam('ruler',2))>0)?','.implode(',',$this->frparam('ruler',2)).',':'';
			}else{
				$data['paction'] ='';
			}
			if(M('member_group')->update(array('id'=>$data['id']),$data)){
				JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败，请重新提交！')));
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
			Error(JZLANG('没有该角色！'));
		}
		
		$this->display('membergroup-edit');
	}

	public function change_group_status(){
		$id = $this->frparam('id',1);
		if(!$id || ($id==1)){
			JsonReturn(array('code'=>1,'msg'=>JZLANG('非法操作！')));
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
				JsonReturn(array('code'=>1,'msg'=>JZLANG('该分组下存在用户，请先移除用户再删除！')));
			}
			$data = M('member_group')->find(array('id'=>$id));
			if(M('member_group')->delete(array('id'=>$id))){
				$w['molds'] = 'member_group';
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
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('添加成功！')));
				exit;
			}else{
				//Error('添加失败！');
				JsonReturn(array('code'=>1,'msg'=>JZLANG('添加失败！')));
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
				JsonReturn(array('status'=>1,'info'=>JZLANG('修改失败！')));
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
			$ruler = M('power')->find(array('id'=>$id));
			$n = M('power')->find(array('pid'=>$id));
			if($n){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败，该分类有下级功能，请先删除下级功能！')));
			}else{
				$m = M('power')->delete(array('id'=>$id));
				if($m){
                    $w['molds'] = 'power';
                    $w['data'] = serialize($ruler);
                    $w['title'] = '['.$ruler['id'].']'.$ruler['name'];
                    $w['addtime'] = time();
                    M('recycle')->add($w);
					JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
				}
			}
			
			
			
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('未选择删除对象！')));
		}
	}









}