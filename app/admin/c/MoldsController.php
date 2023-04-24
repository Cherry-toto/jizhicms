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
			$data['biaoshi'] = $this->frparam('biaoshi',1);
			if(!$data['name'] || !$data['biaoshi']){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('模块名和标识不能为空！')));
			}
			//检查是否已存在表
			if(M('molds')->find(['biaoshi'=>strtolower($data['biaoshi'])])){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('模型已添加不能重复添加！')));
			}
			$w = [];
			$w['name'] = $this->frparam('name',1);
			$w['biaoshi'] = $this->frparam('biaoshi',1);
			$w['orders'] = $this->frparam('orders');
			$w['iscontrol'] = $this->frparam('iscontrol');
			$w['ishome'] = $this->frparam('ishome');
			$w['isopen'] = $this->frparam('isopen');
			$w['ismust'] = $this->frparam('ismust');
			$w['ispreview'] = $this->frparam('ispreview',0,1);
			$w['isclasstype'] = $this->frparam('isclasstype');
			$w['isshowclass'] = $this->frparam('isshowclass');
			$w['list_html'] = $this->frparam('list_html',1);
			$w['details_html'] = $this->frparam('details_html',1);
			if($this->frparam('hastable')){
				
				$n = M('molds')->add($w);
				
			}else{
				$sql = "SHOW TABLES";
				$tables = M()->findSql($sql);
				$ttable = array();
				foreach($tables as $value){
					foreach($value as $vv){
						$ttable[] = $vv;
					}
					
				}
				
				if(in_array(DB_PREFIX.strtolower($data['biaoshi']),$ttable)){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('该表已存在！')));
				}
				$n = M('Molds')->add($w);
				
			}
			
			
			if($n){
				$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$data['biaoshi']."` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`tid` int(11) DEFAULT 0 COMMENT '".JZLANG("所属栏目")."',
				`tids` varchar(255) DEFAULT NULL COMMENT '".JZLANG("副栏目")."',
				`title` varchar(255) DEFAULT NULL COMMENT '".JZLANG("标题")."',
				`litpic` varchar(255) DEFAULT NULL COMMENT '".JZLANG("缩略图")."',
				`keywords` varchar(255) DEFAULT NULL COMMENT '".JZLANG("关键词")."',
				`description` varchar(500) DEFAULT NULL COMMENT '".JZLANG("简介")."',
				`body` text DEFAULT NULL COMMENT '".JZLANG("内容")."',
				`molds` varchar(50) DEFAULT '".$data['biaoshi']."' COMMENT '".JZLANG("模型标识")."',
				`userid` int(11) DEFAULT 0 COMMENT '".JZLANG("发布管理员")."',
				`orders` int(11) DEFAULT 0 COMMENT '".JZLANG("排序")."',
				`member_id` int(11) DEFAULT 0 COMMENT '".JZLANG("前台用户")."',
				`comment_num` int(11) DEFAULT 0 COMMENT '".JZLANG("评论数")."',
				`htmlurl` varchar(100) DEFAULT NULL COMMENT '".JZLANG("栏目链接")."',
				`isshow` tinyint(1) DEFAULT 1 COMMENT '".JZLANG("是否显示")."',
				`target` varchar(255) DEFAULT NULL COMMENT '".JZLANG("外链")."',
				`ownurl` varchar(255) DEFAULT NULL COMMENT '".JZLANG("自定义URL")."',
				`jzattr` varchar(50) DEFAULT NULL COMMENT '".JZLANG("推荐属性")."',
				`hits` int(11) DEFAULT 0 COMMENT '".JZLANG("点击量")."',
				`zan` int(11) DEFAULT 0 COMMENT '".JZLANG("点赞数")."',
				`tags` varchar(255) DEFAULT NULL COMMENT 'TAG',
				`addtime` int(11) DEFAULT 0 COMMENT '".JZLANG("发布时间")."',
				PRIMARY 
				KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1";
				
				$x = M()->runSql($sql);
				$w['field'] = 'title';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('标题');
				$w['tips'] = JZLANG('默认为空');
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 1;
				$w['islist'] = 1;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'tid';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('所属栏目');
				$w['tips'] = JZLANG('选择栏目');
				$w['fieldtype'] = 17;
				$w['fieldlong'] = 11;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 1;
				$w['islist'] = 1;
				$w['vdata'] = 0;
				M('fields')->add($w);
				$w['field'] = 'tids';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('副栏目');
				$w['tips'] = JZLANG('绑定后可以在当前模型的其他栏目中显示');
				$w['fieldtype'] = 18;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'keywords';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('关键词');
				$w['tips'] = JZLANG('每个词用英文逗号(,)拼接');
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'tags';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = 'TAG';
				$w['tips'] = JZLANG('每个词用英文逗号(,)拼接');
				$w['fieldtype'] = 19;
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
				$w['fieldname'] = JZLANG('缩略图');
				$w['tips'] = JZLANG('可留空');
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
				$w['fieldname'] = JZLANG('简介');
				$w['tips'] = JZLANG('可留空');
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
				$w['fieldname'] = JZLANG('内容');
				$w['tips'] = JZLANG('可留空');
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
				$w['fieldname'] = JZLANG('发布会员');
				$w['tips'] = JZLANG('前台发布会员ID记录');
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
				$w['field'] = 'userid';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('管理员');
				$w['tips'] = JZLANG('后台发布管理员ID记录');
				$w['fieldtype'] = 13;
				$w['fieldlong'] = 11;
				$w['body'] = '11,name';
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '0';
				M('fields')->add($w);
				$w['field'] = 'target';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('外链URL');
				$w['tips'] = JZLANG('默认为空，系统访问内容则直接跳转到此链接');
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
				$w['fieldname'] = JZLANG('自定义URL');
				$w['tips'] = JZLANG('默认为空，自定义URL');
				$w['fieldtype'] = 1;
				$w['fieldlong'] = 255;
				$w['ismust'] = 0;
				$w['isshow'] = 0;
				$w['isadmin'] = 0;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '';
				M('fields')->add($w);
				$w['field'] = 'hits';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('点击量');
				$w['tips'] = JZLANG('系统自动添加');
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
				$w['field'] = 'comment_num';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('评论数');
				$w['tips'] = JZLANG('系统自带');
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
				$w['field'] = 'zan';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('点赞数');
				$w['tips'] = JZLANG('系统自带');
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
				$w['field'] = 'orders';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('排序');
				$w['tips'] = JZLANG('系统自带');
				$w['fieldtype'] = 4;
				$w['fieldlong'] = 11;
				$w['body'] = '';
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 1;
				$w['vdata'] = '0';
				M('fields')->add($w);
				$w['field'] = 'addtime';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('添加时间');
				$w['tips'] = JZLANG('选择时间');
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
                $id = M('molds')->getField(['biaoshi'=>'attr'],'id');
				$w['field'] = 'jzattr';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('推荐属性');
				$w['tips'] = JZLANG('1置顶2热点3推荐');
				$w['fieldtype'] = 16;
				$w['fieldlong'] = 50;
				$w['format'] = NULL;
				$w['body'] = $id.',name';
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 0;
				$w['islist'] = 0;
				$w['vdata'] = '0';
				M('fields')->add($w);
				$w['field'] = 'isshow';
				$w['molds'] = $data['biaoshi'];
				$w['fieldname'] = JZLANG('是否显示');
				$w['tips'] = JZLANG('显示隐藏');
				$w['fieldtype'] = 7;
				$w['fieldlong'] = 1;
				$w['format'] = NULL;
				$w['body'] = JZLANG('显示=1,未审=0,退回=2');
				$w['ismust'] = 0;
				$w['isshow'] = 1;
				$w['isadmin'] = 1;
				$w['issearch'] = 1;
				$w['islist'] = 1;
				$w['vdata'] = 1;
				M('fields')->add($w);
				
				//添加权限管理
				if(strlen($data['name'])>12){
					$ruler['name'] = $data['name'];
				}else{
					$ruler['name'] = $data['name'].JZLANG('列表');
				}
				
				$ruler['fc'] = 'Extmolds/index/molds/'.$data['biaoshi'];
				$ruler['pid'] = 77;
				$ruler['isdesktop'] = 1;
				$m_id = M('Ruler')->add($ruler);
				$ruler['isdesktop'] = 0;
				$ruler['name'] = JZLANG('新增').$data['name'];
				$ruler['fc'] = 'Extmolds/addmolds/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('修改').$data['name'];
				$ruler['fc'] = 'Extmolds/editmolds/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('复制').$data['name'];
				$ruler['fc'] = 'Extmolds/copymolds/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('删除').$data['name'];
				$ruler['fc'] = 'Extmolds/deletemolds/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('批量删除').$data['name'];
				$ruler['fc'] = 'Extmolds/deleteAll/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('批量修改').$data['name'].JZLANG('栏目');
				$ruler['fc'] = 'Extmolds/changeType/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('批量复制').$data['name'];
				$ruler['fc'] = 'Extmolds/copyAll/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				if(strlen($data['name'])>12){
					$ruler['name'] = JZLANG('批量修改').$data['name'];
				}else{
					$ruler['name'] = JZLANG('批量修改').$data['name'].JZLANG('列表');
				}
				
				$ruler['fc'] = 'Extmolds/editOrders/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
				$ruler['name'] = JZLANG('批量审核').$data['name'];
				$ruler['fc'] = 'Extmolds/checkAll/molds/'.$data['biaoshi'];
				M('Ruler')->add($ruler);
                $ruler['name'] = JZLANG('批量修改推荐属性').$data['name'];
                $ruler['fc'] = 'Extmolds/changeAttribute/molds/'.$data['biaoshi'];
                M('Ruler')->add($ruler);
				
				//写入左侧导航栏
				$dao = M('Layout')->find(array('gid'=>$_SESSION['admin']['gid']));
				if(!$dao){
					$dao = M('Layout')->find(array('isdefault'=>1));
				}
				$left_layout = json_decode($dao['left_layout'],1);
				$left_layout[]=[
					"name" => $data['name'].JZLANG('管理'),
					"icon" => '&amp;#xe6cb;',
					"nav" => array([
						"title"=>$data['name'],
						"icon"=>'',
						"value"=>$m_id,
						
					])
				];
				$left_layout = json_encode($left_layout,JSON_UNESCAPED_UNICODE);
				M('layout')->update(['id'=>$dao['id']],['left_layout'=>$left_layout]);
				if($data['ismust']==1 && $data['isshowclass']==1){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('新增模型成功，快去创建对应的栏目吧！'),'url'=>U('Classtype/addclass',['biaoshi'=>$data['biaoshi']])));
				}else{
					JsonReturn(array('code'=>0,'msg'=>JZLANG('新增模型成功，快去设置表字段吧！'),'url'=>U('Fields/index',['molds'=>$data['biaoshi']])));
				}
				
			
				
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('新增模型失败！')));
				
			}
			
			
			
		}
		
		
		$this->display('molds-add');
	}

	function editMolds(){
		$this->data = M('Molds')->find(array('id'=>$this->frparam('id')));
		if($this->frparam('go',1)==1){
			$data['name'] = $this->frparam('name',1);
			$data['orders'] = $this->frparam('orders');
			$data['iscontrol'] = $this->frparam('iscontrol');
			$data['isopen'] = $this->frparam('isopen');
			$data['ismust'] = $this->frparam('ismust');
			$data['isclasstype'] = $this->frparam('isclasstype');
			$data['isshowclass'] = $this->frparam('isshowclass');
			$data['list_html'] = $this->frparam('list_html',1);
			$data['details_html'] = $this->frparam('details_html',1);
			
			$data['biaoshi'] = $this->frparam('biaoshi',1);
			$data['ispreview'] = $this->frparam('ispreview',0,1);
			$data['ishome'] = $this->frparam('ishome');
			if($this->frparam('id')){
				if(!$data['biaoshi']){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('标识不能为空！')));
					exit;
				}
				$molds = $this->data;
				if($molds['sys']==1 && $data['biaoshi']!=$molds['biaoshi']){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('系统模型标识不允许修改！')));
					exit;
				}
				
				$data['biaoshi'] = strtolower($data['biaoshi']);
				if(M('Molds')->update(array('id'=>$this->frparam('id')),$data)){
					//检测是否表名改变
					if($this->data['biaoshi']!=$data['biaoshi']){
						//修改表名
						$sql = "RENAME TABLE `".DB_PREFIX.$molds['biaoshi']."` TO `".DB_PREFIX.$data['biaoshi']."` ";
						$x = M()->runSql($sql);
						JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！'),'url'=>U('index')));
							
					}
					JsonReturn(array('code'=>0,'msg'=>JZLANG('修改成功！'),'url'=>U('index')));
					
					
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('修改失败！')));
					
				}
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('页面有错误，缺少模块ID！')));
			}
			
			
			
		}
		
		
		$this->display('molds-edit');
		
	}

	function deleteMolds(){
		$id = $this->frparam('id');
		if($id){
			
			//检查表里面是否有数据
			$molds = M('Molds')->find(['id'=>$id]);
			$nums = M($molds['biaoshi'])->getCount();
			if($nums){
				JsonReturn(array('code'=>1,'msg'=>$molds['name'].JZLANG('里面存在数据，请先清空表内数据！')));
			}
			if($molds['sys']==1){
				JsonReturn(array('code'=>1,'msg'=>$molds['name'].JZLANG('是系统模型，不允许删除！')));
			}
			if(M('Molds')->delete('id='.$id)){
				//删除表
				$sql = "DROP TABLE IF EXISTS `".DB_PREFIX.$molds['biaoshi']."`";
				$x = M()->runSql($sql);
				
				//清空字段管理中的字段
				if(M('Fields')->find(array('molds'=>$molds['biaoshi']))){
					$re = M('Fields')->delete(array('molds'=>$molds['biaoshi']));
					if(!$re){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('字段表记录未清除，请手动清除！')));
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
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
	}
	
	function restrucFields(){
		
		$molds = $this->frparam('molds',1);
        //默认数据类型
        $default = [
            'title'=>[
                'field'=>'title',
                'title'=>JZLANG('标题'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>1,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'tid'=>[
                'field'=>'tid',
                'title'=>JZLANG('所属栏目'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>17,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
			'tids'=>[
                'field'=>'tids',
                'title'=>JZLANG('副栏目'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>18,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'body'=>[
                'field'=>'body',
                'title'=>JZLANG('内容'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>3,
                'length'=>0,
				'default'=>null,
				'type'=>'mediumtext',
            ],
            'keywords'=>[
                'field'=>'keywords',
                'title'=>JZLANG('关键词'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>1,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'litpic'=>[
                'field'=>'litpic',
                'title'=>JZLANG('缩略图'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>5,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'description'=>[
                'field'=>'description',
                'title'=>JZLANG('简介'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>2,
                'length'=>0,
				'default'=>null,
				'type'=>'varchar(1000)',
            ],
            'molds'=>[
                'field'=>'molds',
                'title'=>JZLANG('模型'),
                'isshow'=>1,
                'isadmin'=>0,
                'islist'=>0,
                'fieldtype'=>15,
                'length'=>50,
				'default'=>null,
				'type'=>'varchar(50)',
            ],
            'userid'=>[
                'field'=>'userid',
                'title'=>JZLANG('管理员'),
                'isshow'=>1,
                'isadmin'=>0,
                'islist'=>0,
                'fieldtype'=>15,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
            'orders'=>[
                'field'=>'orders',
                'title'=>JZLANG('排序'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>4,
                'length'=>4,
				'default'=>0,
				'type'=>'int(4)',
            ],
            'member_id'=>[
                'field'=>'member_id',
                'title'=>JZLANG('用户'),
                'isshow'=>1,
                'isadmin'=>0,
                'islist'=>0,
                'fieldtype'=>15,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
            'comment_num'=>[
                'field'=>'comment_num',
                'title'=>JZLANG('评论数'),
                'isshow'=>1,
                'isadmin'=>0,
                'islist'=>0,
                'fieldtype'=>4,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
            'htmlurl'=>[
                'field'=>'htmlurl',
                'title'=>JZLANG('栏目链接'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>1,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'isshow'=>[
                'field'=>'isshow',
                'title'=>JZLANG('是否显示'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>7,
                'length'=>1,
				'default'=>0,
				'type'=>'tinyint(1)',
            ],
            'target'=>[
                'field'=>'target',
                'title'=>JZLANG('外链'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>1,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'ownurl'=>[
                'field'=>'ownurl',
                'title'=>JZLANG('自定义链接'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>1,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'hits'=>[
                'field'=>'hits',
                'title'=>JZLANG('点击量'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>4,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
            'zan'=>[
                'field'=>'zan',
                'title'=>JZLANG('点赞数'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>4,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
            'tags'=>[
                'field'=>'tags',
                'title'=>'Tags',
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>0,
                'fieldtype'=>19,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(255)',
            ],
            'jzattr'=>[
                'field'=>'jzattr',
                'title'=>JZLANG('推荐属性'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>16,
                'length'=>255,
				'default'=>null,
				'type'=>'varchar(50)',
            ],
			'addtime'=>[
                'field'=>'addtime',
                'title'=>JZLANG('发布时间'),
                'isshow'=>1,
                'isadmin'=>1,
                'islist'=>1,
                'fieldtype'=>11,
                'length'=>11,
				'default'=>0,
				'type'=>'int(11)',
            ],
        ];
        $default_fields = array_column($default,'field');
        if($_POST){
            $field = $this->frparam('field',2);
            $len = $this->frparam('len',2);
            $type = $this->frparam('fieldtype',2);
            $title = $this->frparam('title',2);
            $fieldtype = $this->frparam('fieldtype',2);
            $isshow = $this->frparam('isshow',2);
            $isadmin = $this->frparam('isadmin',2);
            $islist = $this->frparam('islist',2);
            $old = M('fields')->findAll(['molds'=>$molds]);
            $ids = [];
            if($old){
                foreach ($old as $v){
                    foreach ($field as $k=>$vv){
                        if($vv==$v['field']){
                            $ids[$k] = $v;
                            $len[$k] = $v['fieldlong'];
                        }
                    }
                }
            }
            $newfields = [];
            foreach ($title as $k=>$v){
                if(!$v){
                    JsonReturn(['code'=>1,'msg'=>$field[$k]].JZLANG('字段名称不能为空！'));
                }
                $w = [];
                $w['molds'] = $molds;
                $w['field'] = $field[$k];
                $w['fieldname'] = $v;
                $w['fieldtype'] = $type[$k];
                $w['fieldlong'] = $len[$k];
                $w['isshow'] = $isshow[$k];
                $w['isadmin'] = $isadmin[$k];
                $w['islist'] = $islist[$k];
                if(isset($ids[$k])){
                    M('fields')->update(['id'=>$ids[$k]['id']],$w);
                }else{
                    switch ($w['field']){
                        case 'isshow':
                            $w['body'] = JZLANG('显示=1,未审=0,退回=2');
                            break;
                        case 'islist':
                        case 'isadmin':
                            $w['body'] = JZLANG('显示=1,隐藏=0');
                            break;
                        case 'jzattr':
                            $id = M('molds')->getField(['biaoshi'=>'attr'],'id');
                            if($id){
                                $w['body'] = $id.',name';
                            }
                            break;
                        case 'member_id':
                            $id = M('molds')->getField(['biaoshi'=>'member'],'id');
                            if($id){
                                $w['body'] = $id.','.'username';
                            }
                            break;
                        case 'userid':
                            $id = M('molds')->getField(['biaoshi'=>'level'],'id');
                            if($id){
                                $w['body'] = $id.','.'name';
                            }
                            break;

                    }

                    M('fields')->add($w);
                }

                if(!in_array($field[$k],$default_fields)){
                    $newfields = $default[$field[$k]];
                }

            }
            if(count($newfields)){
                foreach ($newfields as $v){
                    $w = [];
                    $w['molds'] = $molds;
                    $w['field'] = $v['field'];
                    $w['fieldname'] = $v['title'];
                    $w['fieldtype'] = $v['fieldtype'];
                    $w['fieldlong'] = $v['length'];
                    $w['isshow'] = $v['isshow'];
                    $w['isadmin'] = $v['isadmin'];
                    $w['islist'] = $v['islist'];
                    $w['tips'] = $v['title'];
					$w['vdata'] = $v['default'];
                    switch ($w['field']){
                        case 'isshow':
                            $w['body'] = JZLANG('显示=1,隐藏=0,退回=2');
                            break;
                        case 'islist':
                        case 'isadmin':
                            $w['body'] = JZLANG('显示=1,隐藏=0');
                            break;
                        case 'jzattr':
                            $id = M('molds')->getField(['biaoshi'=>'attr'],'id');
                            if($id){
                                $w['body'] = $id.',name';
                            }
                            break;
                        case 'istop':
                            $w['body'] = JZLANG('是=1,否=0');
                            break;
                        case 'member_id':
                            $id = M('molds')->getField(['biaoshi'=>'member'],'id');
                            if($id){
                                $w['body'] = $id.','.'username';
                            }
                            break;
                        case 'userid':
                            $id = M('molds')->getField(['biaoshi'=>'level'],'id');
                            if($id){
                                $w['body'] = $id.','.'name';
                            }
                            break;

                    }
                    M('fields')->add($w);
                }
            }
			$fields = $this->getTableFields($molds);
			
			$nowfields = array_column($fields,'field');
			$res = array_diff($default_fields,$nowfields);
			$sqls = '';
			foreach($res as $vv){
				if($vv){
					
					//添加字段
					$sql="ALTER TABLE ".DB_PREFIX.$molds." ADD ".$default[$vv]['field']." ".$default[$vv]['type']." DEFAULT ";
					if(strpos($default[$vv]['type'],'int')!==false){
						$sql.=" 0;";
					}else{
						$sql.=" NULL; ";
					}
					$sqls.=$sql;
					
				}
				
			}
			if($sqls){
				
				M()->runSql($sqls);
			}
			
			//权限检查
			if(!in_array($molds,['article','product','tags'])){
				if(!M('ruler')->find(['fc'=>'Extmolds/index/molds/'.$molds])){
					//添加权限管理
					$data = M('molds')->find(['biaoshi'=>$molds]);
					$ruler['name'] = $data['name'].JZLANG('列表');
					$ruler['fc'] = 'Extmolds/index/molds/'.$data['biaoshi'];
					$ruler['pid'] = 77;
					$ruler['isdesktop'] = 1;
					$m_id = M('Ruler')->add($ruler);
					$ruler['isdesktop'] = 0;
					$ruler['name'] = JZLANG('新增').$data['name'];
					$ruler['fc'] = 'Extmolds/addmolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('修改').$data['name'];
					$ruler['fc'] = 'Extmolds/editmolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('复制').$data['name'];
					$ruler['fc'] = 'Extmolds/copymolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('删除').$data['name'];
					$ruler['fc'] = 'Extmolds/deletemolds/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('批量删除').$data['name'];
					$ruler['fc'] = 'Extmolds/deleteAll/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('批量修改').$data['name'].JZLANG('栏目');
					$ruler['fc'] = 'Extmolds/changeType/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('批量复制').$data['name'];
					$ruler['fc'] = 'Extmolds/copyAll/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					if(strlen($data['name'])>12){
						$ruler['name'] = JZLANG('批量修改').$data['name'];
					}else{
						$ruler['name'] = JZLANG('批量修改').$data['name'].JZLANG('列表');
					}
					
					$ruler['fc'] = 'Extmolds/editOrders/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
					$ruler['name'] = JZLANG('批量审核').$data['name'];
					$ruler['fc'] = 'Extmolds/checkAll/molds/'.$data['biaoshi'];
					M('Ruler')->add($ruler);
				}
				
			}

            JsonReturn(['code'=>0,'msg'=>JZLANG('重构成功！'),'url'=>U('Fields/index',['molds'=>$molds])]);



        }
        $fields = $this->getTableFields($molds);

		foreach ($fields as &$v){
		    foreach ($default as $kk=>$vv){
		        if($kk==$v['field']){
		            $v = array_merge($v,$vv);
                }
            }
        }
		
		$nowfields = array_column($fields,'field');
		$res = array_diff($default_fields,$nowfields);
		
		foreach($res as $vv){
			$fields[]=$default[$vv];
			
		}
		
        $fieldstype = [
            1=>JZLANG('单行文本'),
            2=>JZLANG('多行文本'),
            3=>JZLANG('文本编辑器'),
            4=>JZLANG('数字'),
            5=>JZLANG('单图片'),
            6=>JZLANG('多图片'),
            7=>JZLANG('单选下拉'),
            8=>JZLANG('多选'),
            9=>JZLANG('单附件'),
            10=>JZLANG('多附件'),
            11=>JZLANG('时间戳'),
            12=>JZLANG('单选按钮'),
            13=>JZLANG('单选关联'),
            14=>JZLANG('小数'),
            15=>JZLANG('多行录入'),
            16=>JZLANG('多选关联'),
            17=>JZLANG('系统栏目'),
            18=>JZLANG('系统副栏目'),
            19=>JZLANG('系统TAG'),
            20=>JZLANG('栏目绑定多选'),
            21=>JZLANG('栏目绑定单选'),
        ];
		$this->molds = $molds;
        $this->fieldstype = $fieldstype;
        $this->fields = $fields;
        $this->display('restrucfields');
		
	}

    private function getTableFields($table){
		$sql="select distinct * from information_schema.columns where table_schema = '".DB_NAME."' and  table_name = '".DB_PREFIX.$table."'";
       // $sql = 'SHOW COLUMNS FROM '.DB_PREFIX.$table;
        $list = M()->findSql($sql);
        $isgo = true;
        $fields = [];
		
        foreach($list as $v){
			$len = 0;
			$s = preg_match('/\((.*)\)/',$v['COLUMN_TYPE'],$math);
			if($s){
				$len = $math[1];
			}
			if($v['COLUMN_NAME']!='id'){
				$fields[]=[
					'field'=>$v['COLUMN_NAME'],
					'type'=>$v['COLUMN_TYPE'],
					'datatype'=>$v['DATA_TYPE'],
					'fieldtype'=>1,
					'comment'=>$v['COLUMN_COMMENT'],
					'title' => $v['COLUMN_COMMENT'] ? $v['COLUMN_COMMENT'] : $v['COLUMN_NAME'],
					'isshow'=>1,
					'isadmin'=>1,
					'islist'=>1,
					'default'=>$v['COLUMN_DEFAULT'],
					'length'=>$len
				 ];
			}
            

        }
        return $fields;

    }










}