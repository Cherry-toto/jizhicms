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

class MessageController extends CommonController
{

	//批量删除留言
	function deleteAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$all = M('message')->findAll('id in('.$data.')');
			if(M('message')->delete('id in('.$data.')')){
				foreach($all as $v){
					$w['molds'] = 'message';
					$w['data'] = serialize($v);
					$w['title'] = '['.$v['id'].']'.$v['title'];
					$w['addtime'] = time();
					M('recycle')->add($w);
				}
				JsonReturn(array('code'=>0,'msg'=>JZLANG('批量删除成功！')));
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('批量操作失败！')));
			}
		}
	}
	
	//留言管理
    function messagelist(){
        $this->tid=  $this->frparam('tid');
        $this->aid = $this->frparam('aid');
        $this->classtypes = $this->classtypetree;
        $this->isshow = $this->frparam('isshow');
        $data = $this->frparam();
        $res = molds_search('message',$data);
        $this->fields_search = $res['fields_search'];
        $this->fields_list = M('Fields')->findAll(array('molds'=>'message','islist'=>1),'orders desc');
        $this->molds = M('molds')->find(['biaoshi'=>'message']);
        if($this->frparam('ajax')){
            $page = new Page('Message');
            $sql = ' 1=1 ';
            if($this->admin['classcontrol']==1 && $this->admin['isadmin']!=1 && $this->molds['iscontrol']!=0 && $this->molds['isclasstype']==1){
                $a1 = explode(',',$this->tids);
                $a2 = array_filter($a1);
                $tids = implode(',',$a2);
                $sql.=' and tid in('.$tids.') ';


            }

            $get_sql = ($res['fields_search_check']!='') ? (' and '.$res['fields_search_check']) : '';
            $sql .= $get_sql;
            $data = $page->where($sql)->orderby('id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
            $ajaxdata = [];
            foreach($data as $k=>$v){

                $v['edit_url'] = U('Message/editmessage',array('id'=>$v['id']));
                foreach($this->fields_list as $vv){
                    $v[$vv['field']] = format_fields($vv,$v[$vv['field']]);
                }
                $ajaxdata[]=$v;

            }

            $pages = $page->pageList();
            $this->pages = $pages;
            $this->lists = $data;
            $this->sum = $page->sum;
            JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
        }

        $this->display('message-list');


    }

    function editmessage(){
        $this->fields_biaoshi = 'message';
        if($this->frparam('go',1)==1){
            $data = $this->frparam();
            $data = get_fields_data($data,'message');
            check_field_must($data,'message');
            if($this->frparam('id')){
                if(M('Message')->update(array('id'=>$this->frparam('id')),$data)){
                    JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！')));
                }else{
                    JsonReturn(array('code'=>1,'msg'=>JZLANG('您未做任何修改，不能提交！')));
                }
            }

        }
        if($this->frparam('id')){
            $this->data = M('Message')->find(array('id'=>$this->frparam('id')));
        }
        $this->molds = M('molds')->find(['biaoshi'=>'message']);
        $this->classtypes = $this->classtypetree;
        $this->display('message-details');

    }

    function deletemessage(){
		$id = $this->frparam('id');
		if($id){
			$data = M('Message')->find(['id'=>$id]);
			if(M('Message')->delete(['id'=>$id])){
				$w['molds'] = 'message';
				$w['data'] = serialize($data);
				$w['title'] = '['.$data['id'].']'.$data['title'];
				$w['addtime'] = time();
				M('recycle')->add($w);
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				//Error('删除失败！');
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
	}
	
	//批量审核
	function checkAll(){
		$data = $this->frparam('data',1);
		if($data!=''){
			$isshow = $this->frparam('isshow')==1 ? 1 : 0;
			M('message')->update('id in('.$data.')',['isshow'=>$isshow]);
			JsonReturn(array('code'=>0,'msg'=>JZLANG('批量审核成功！')));
		}else{
			JsonReturn(array('code'=>1,'msg'=>JZLANG('批量审核失败！')));
		}
	}
	
	
	
	
	
	
	
	
	
	
}