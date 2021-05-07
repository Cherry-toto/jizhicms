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

class MoldsController extends CommonController
{
	
	function index(){
		$page = new Page('Molds');
		$sql = ' 1=1 ';

		$data = $page->where($sql)->orderby('id asc')->page($this->frparam('page',0,1))->go();
		$pages = $page->pageList();
		$this->pages = $pages;
		$this->lists = $data;
		$this->sum = $page->sum;
		
		$this->display('molds-list');
		
		
	}

	function addMolds(){
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			if($data['name']=='' || $data['biaoshi']==''){
				JsonReturn(array('code'=>1,'msg'=>'模块名和标识不能为空！'));
			}
			
			$sql = "SHOW TABLES";
			$tables = M()->findSql($sql);
			$ttable = array();
			foreach($tables as $value){
				foreach($value as $vv){
					$ttable[] = $vv;
				}
				
			}
			$data['biaoshi'] = strtolower($this->frparam('biaoshi',1));
			//JsonReturn(array('code'=>1,'msg'=>$ttable));
			if(in_array(DB_PREFIX.$data['biaoshi'],$ttable)){
				JsonReturn(array('code'=>1,'msg'=>'该表已存在！'));
			}
			//检测是否存在该模块
			if(M('Molds')->find(array('biaoshi'=>$data['biaoshi']))){
				
				JsonReturn(array('code'=>1,'msg'=>'标识已存在！'));
			}
			
			$n = M('Molds')->add($data);
			if($n){
				$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$data['biaoshi']."` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`tid` int(11) DEFAULT 0,
				`title` varchar(255) DEFAULT NULL,
				`litpic` varchar(255) DEFAULT NULL,
				`keywords` varchar(255) DEFAULT NULL,
				`description` varchar(500) DEFAULT NULL,
				`body` text DEFAULT NULL,
				`molds` varchar(50) DEFAULT '".$data['biaoshi']."',
				`userid` int(11) DEFAULT 0,
				`orders` int(11) DEFAULT 0,
				`member_id` int(11) DEFAULT 0,
				`comment_num` int(11) DEFAULT 0,
				`htmlurl` varchar(100) DEFAULT NULL,
				`isshow` tinyint(1) DEFAULT 1,
				`target` varchar(255) DEFAULT NULL,
				`ownurl` varchar(255) DEFAULT NULL,
				`istop` tinyint(1) DEFAULT 0,
				`hits` int(11) DEFAULT 0,
				`zan` int(11) DEFAULT 0,
				`tags` varchar(255) DEFAULT NULL,
				`addtime` int(11) DEFAULT 0,
				PRIMARY 
				KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				
				$x = M()->runSql($sql);
				$w['field'] = 'title';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '标题';
				$w['tips'] = '默认为空';
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 1;
				$w['islist'] = 1;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'keywords';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '关键词';
				$w['tips'] = '每个词用英文逗号(,)拼接';
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'litpic';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '缩略图';
				$w['tips'] = '可留空';
				$w['fieldtype'] = 5;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 1;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'description';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '简介';
				$w['tips'] = '可留空';
				$w['fieldtype'] = 2;
				$w['fieldlong'] = 500;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'body';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '内容';
				$w['tips'] = '可留空';
				$w['fieldtype'] = 3;
				$w['fieldlong'] = 500;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'member_id';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '发布会员';
				$w['tips'] = '前台发布会员ID记录';
				$w['fieldtype'] = 13;
				$w['fieldlong'] = 11;
				$w['body'] = '3,username';
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '0';
				M('fields')->add($w);
				$w['field'] = 'target';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '外链URL';
				$w['tips'] = '默认为空，系统访问内容则直接跳转到此链接';
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'ownurl';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '自定义URL';
				$w['tips'] = '默认为空，自定义URL';
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'istop';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '是否置顶';
				$w['tips'] = '置顶';
				$w['fieldtype'] = 12;
				$w['fieldlong'] = 1;
				$w['body'] = '否=0,是=1';
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '0';
				M('fields')->add($w);
				$w['field'] = 'hits';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '点击量';
				$w['tips'] = '系统自动添加';
				$w['fieldtype'] = 4;
				$w['fieldlong'] = 11;
				$w['body'] = '';
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '0';
				M('fields')->add($w);
				$w['field'] = 'addtime';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = '添加时间';
				$w['tips'] = '选择时间';
				$w['fieldtype'] = 11;
				$w['fieldlong'] = 11;
				$w['format'] = 'date_2';
				$w['body'] = '';
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 1;
				$w['vdata'] = '0';
				M('fields')->add($w);
				
				//if($x){
					//添加权限管理
					if(strlen($data['name'])>12){
						$ruler['name'] = $data['name'];
					}else{
						$ruler['name'] = $data['name'].'列表';
					}
					
					$ruler['fc'] = 'Extmolds/index/molds/'.$data['biaoshi'];
					$ruler['pid'] = 77;
					$ruler['isdesktop'] = 1;
					$m_id = M('Ruler')->add($ruler);
					$ruler['isdesktop'] = 0;
					$ruler['name'] = '新增'.$data['name'];
					$ruler['fc'] = 'Extmolds/addmolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '修改'.$data['name'];
					$ruler['fc'] = 'Extmolds/editmolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '复制'.$data['name'];
					$ruler['fc'] = 'Extmolds/copymolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '删除'.$data['name'];
					$ruler['fc'] = 'Extmolds/deletemolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '批量删除'.$data['name'];
					$ruler['fc'] = 'Extmolds/deleteAll/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '批量修改'.$data['name'].'栏目';
					$ruler['fc'] = 'Extmolds/changeType/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '批量复制'.$data['name'];
					$ruler['fc'] = 'Extmolds/copyAll/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					if(strlen($data['name'])>12){
						$ruler['name'] = '批量修改'.$data['name'];
					}else{
						$ruler['name'] = '批量修改'.$data['name'].'列表';
					}
					
					$ruler['fc'] = 'Extmolds/editOrders/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = '批量审核'.$data['name'];
					$ruler['fc'] = 'Extmolds/checkAll/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					
					//写入左侧导航栏
					$dao = M('Layout')->find(array('gid'=>$_SESSION['admin']['gid']));
					if(!$dao){
						$dao = M('Layout')->find(array('isdefault'=>1));
					}
					$left_layout = json_decode($dao['left_layout'],1);
					$left_layout[]=[
						"name" => $data['name'].'模型',
						"icon" => '&amp;#xe6cb;',
						"nav" => array($m_id)
					];
					$left_layout = json_encode($left_layout,JSON_UNESCAPED_UNICODE);
					M('layout')->update(['id'=>$dao['id']],['left_layout'=>$left_layout]);
					if($data['ismust']==1 && $data['isshowclass']==1){
						JsonReturn(array('code'=>0,'msg'=>'新增模型成功，快去创建对应的栏目吧！','url'=>U('Classtype/addclass',['biaoshi'=>$data['biaoshi']])));
					}else{
						JsonReturn(array('code'=>0,'msg'=>'新增模型成功，快去设置表字段吧！','url'=>U('Fields/index',['molds'=>$data['biaoshi']])));
					}
					
				
				// }else{
					// JsonReturn(array('code'=>1,'msg'=>'新增表失败！请检查数据库配置！'));
				// }
				
			}else{
				//Error('添加失败！');
				JsonReturn(array('code'=>1,'msg'=>'新增模型失败！'));
				
			}
			
			
			
		}
		
		
		$this->display('molds-add');
	}
	

	
	
	public function editMolds(){
		$this->data = M('Molds')->find(array('id'=>$this->frparam('id')));
		if($this->frparam('go',1)==1){
			
			$data = $this->frparam();
			if($this->frparam('id')){
				if($this->frparam('biaoshi',1)==''){
					JsonReturn(array('code'=>1,'msg'=>'标识不能为空！'));
					exit;
				}
				$molds = M('Molds')->find(array('id'=>$this->frparam('id')));
				if($molds['sys']==1 && $data['biaoshi']!=$molds['biaoshi']){
					JsonReturn(array('code'=>1,'msg'=>'系统模型标识不允许修改！'));
					exit;
				}
				
				$data['biaoshi'] = strtolower($this->frparam('biaoshi',1));
				if(M('Molds')->update(array('id'=>$this->frparam('id')),$data)){
					//检测是否表名改变
					if($this->data['biaoshi']!=$data['biaoshi']){
						//修改表名
						$sql = "RENAME TABLE `".DB_PREFIX.$molds['biaoshi']."` TO `".DB_PREFIX.$data['biaoshi']."` ";
						$x = M()->runSql($sql);
						JsonReturn(array('code'=>0,'msg'=>'修改成功！','url'=>U('index')));
							
					}
					JsonReturn(array('code'=>0,'msg'=>'修改成功！','url'=>U('index')));
					
					
				}else{
					JsonReturn(array('code'=>1,'msg'=>'修改失败！'));
					
				}
			}else{
				JsonReturn(array('code'=>1,'msg'=>'页面有错误，缺少模块ID！'));
			}
			
			
			
		}
		
		
		$this->display('molds-edit');
		
	}
	function deleteMolds(){
		$id = $this->frparam('id');
		if($id){
			
			//检查表里面是否有数据
			$molds = M('Molds')->find('id='.$id);
			$nums = M($molds['biaoshi'])->getCount();
			if($nums){
				JsonReturn(array('code'=>1,'msg'=>$molds['name'].'里面存在数据，请先清空表内数据！'));
			}
			if($molds['sys']==1){
				JsonReturn(array('code'=>1,'msg'=>$molds['name'].'是系统模型，不允许删除！'));
			}
			if(M('Molds')->delete('id='.$id)){
				//删除表
				$sql = "DROP TABLE IF EXISTS `".DB_PREFIX.$molds['biaoshi']."`";
				$x = M()->runSql($sql);
				// if(!$x){
					// JsonReturn(array('code'=>1,'msg'=>'数据库表删除失败！请检查数据库用户权限！'));
				// }
				//清空字段管理中的字段
				if(M('Fields')->find(array('molds'=>$molds['biaoshi']))){
					$re = M('Fields')->delete(array('molds'=>$molds['biaoshi']));
					if(!$re){
						JsonReturn(array('code'=>1,'msg'=>'字段表记录未清除，请手动清除！'));
					}
				}
				
				
				//删除权限管理
				$ruler['fc'] = 'Extmolds/index/molds/'.$molds['biaoshi'];
				//$ruler['pid'] = 77;
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/addmolds/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/editmolds/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/copymolds/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/deletemolds/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/deleteAll/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/editOrders/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/changeType/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/copyAll/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				$ruler['fc'] = 'Extmolds/checkAll/molds/'.$molds['biaoshi'];
				M('Ruler')->delete($ruler);
				
				JsonReturn(array('code'=>0,'msg'=>'删除成功！'));
			}else{
				JsonReturn(array('code'=>1,'msg'=>'删除失败！'));
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
}