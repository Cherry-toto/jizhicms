<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/01-2019/10
// +----------------------------------------------------------------------


namespace app\admin\c;


use frphp\extend\Page;

class FieldsController extends CommonController
{
	
	function index(){
		if($this->frparam('molds',1)==''){
			Error(JZLANG('请选择模块！'));
		}
		if($this->frparam('ajax')){

			$data = M('fields')->findAll(array('molds'=>$this->frparam('molds',1)),'orders desc');
			foreach($data as &$v){
				$v['isadmin'] = $v['isadmin']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['isshow'] = $v['isshow']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['ishome'] = $v['ishome']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['islist'] = $v['islist']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['issearch'] = $v['issearch']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['ismust'] = $v['ismust']==1 ? JZLANG('是') : JZLANG('否');
				$v['isext'] = $v['isext']==1 ? JZLANG('是') : JZLANG('否');

				switch($v['fieldtype']){
					case 1:
					$v['fieldtypename'] = JZLANG('单行文本');
					break;
					case 2:
					$v['fieldtypename'] = JZLANG('多行文本');
					break;
					case 3:
					$v['fieldtypename'] = JZLANG('文本编辑器');
					break;
					case 4:
					$v['fieldtypename'] = JZLANG('数字');
					break;
					case 5:
					$v['fieldtypename'] = JZLANG('单图片');
					break;
					case 6:
					$v['fieldtypename'] = JZLANG('多图片');
					break;
					case 7:
					$v['fieldtypename'] = JZLANG('单选下拉');
					break;
					case 8:
					$v['fieldtypename'] = JZLANG('多选');
					break;
					case 9:
					$v['fieldtypename'] = JZLANG('单附件');
					break;
					case 10:
					$v['fieldtypename'] = JZLANG('多附件');
					break;
					case 11:
					$v['fieldtypename'] = JZLANG('时间戳');
					break;
					case 12:
					$v['fieldtypename'] = JZLANG('单选按钮');
					break;
					case 13:
					$v['fieldtypename'] = JZLANG('单选关联');
					break;
					case 14:
					$v['fieldtypename'] = JZLANG('小数');
					break;
					case 15:
					$v['fieldtypename'] = JZLANG('多行录入');
					break;
					case 16:
					$v['fieldtypename'] = JZLANG('多选关联');
					break;
                    case 17:
                    $v['fieldtypename'] = JZLANG('栏目');
                    break;
                    case 18:
                    $v['fieldtypename'] = JZLANG('副栏目');
                    break;
					case 19:
                    $v['fieldtypename'] = JZLANG('系统TAG');
                    break;
                    case 20:
                        $v['fieldtypename'] = JZLANG('绑定栏目单选');
                        break;
                    case 21:
                        $v['fieldtypename'] = JZLANG('绑定栏目多选');
                        break;
				}
				$v['edit_url'] = U('editFields',['id'=>$v['id']]);

			}
			JsonReturn(['code'=>0,'data'=>$data,'count'=>count($data)]);
			
			
		}
		$this->molds = M('Molds')->find(array('biaoshi'=>$this->frparam('molds',1)));
		
		$this->display('fields-list');
		
		
	}

	function addFields(){
		
		if($this->frparam('go',1)==1){
			
			$data['field'] = strtolower($this->frparam('field',1));
			$data['molds'] = strtolower($this->frparam('molds',1));
			$data['fieldname'] = $this->frparam('fieldname',1);
			$data['tips'] = $this->frparam('tips',1);
			$data['fieldtype'] = $this->frparam('fieldtype');
			$data['tids'] = $this->frparam('tids',1);
			$data['fieldlong'] = $this->frparam('fieldlong',1);
			$data['body'] = $this->frparam('body',1);
			$data['orders'] = $this->frparam('orders');
			$data['ismust'] = $this->frparam('ismust');
			$data['isshow'] = $this->frparam('isshow');
			$data['ishome'] = $this->frparam('ishome');
			$data['isadmin'] = $this->frparam('isadmin');
			$data['issearch'] = $this->frparam('issearch');
			$data['islist'] = $this->frparam('islist');
			$data['format'] = $this->frparam('format',1);
			$data['vdata'] = $this->frparam('vdata',1);
			$data['isajax'] = $this->frparam('isajax');
			$data['listorders'] = $this->frparam('listorders');
			$data['isext'] = $this->frparam('isext');
			if($data['fieldname']=='' || $data['field']==''){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('字段名和字段标识不能为空！')));
			}
			
			//检测是否存在该模块
			if(M('Fields')->find(array('field'=>$data['field'],'molds'=>$data['molds']))){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('字段标识已存在！')));
			}
			//取消保护字段，可以继续创建
			if(in_array($data['field'],['sql','jzcache','jzcachetime','table','orderby','limit','ispage','notin','in','empty','notempty','fields','like','day','as','file'])){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('系统保护字段，不允许创建！')));
			}
			// $sql = "select count(*) as n from information_schema.columns where table_name = '".DB_PREFIX.$data['molds']."' and TABLE_SCHEMA='".DB_PREFIX.$data['molds']."' and column_name = '".$data['field']."'";
			// $check = M()->findSql($sql);
			// if($check[0]['n']){
				// JsonReturn(array('code'=>1,'msg'=>'字段标识已存在！'));
			// }
			
			$sql = 'SHOW COLUMNS FROM '.DB_PREFIX.$data['molds'];
			$list = M()->findSql($sql);
			$isgo = true;
			//不管存不存在，都可以创建
			foreach($list as $v){
				if($v['Field']==$data['field']){
					$isgo = false;
					//JsonReturn(array('code'=>1,'msg'=>'字段标识已存在！'));
				}
			}
			
			
			$data['tids'] = ($data['tids']!='')?(','.$data['tids'].','):$data['tids'];
			$sql = "ALTER TABLE ".DB_PREFIX.$data['molds']." ADD ".$data['field']." ";
			$data['fieldlong'] = $this->frparam('fieldlong_'.$data['fieldtype'],1);
			switch($data['fieldtype']){
				case 1:
				case 2:
				case 18:
				case 19:
				$sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
				if($data['vdata'] || $data['vdata']==0){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= ' NULL ';
				}
				break;
				case 3:
				case 15:
				case 6:
				case 10:
				$sql .= "TEXT CHARACTER SET utf8 default ";
				$sql .= ' NULL ';
				
				break;
				case 4:
				case 17:
				if($data['fieldlong']>11 || $data['fieldlong']<=0){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
				}
				$sql .= "INT(".$data['fieldlong'].") DEFAULT ";
				if($data['vdata']){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= " '0' NOT NULL ";
				}
				break;
				case 11:
				if($data['fieldlong']!=11){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对,时间属性必须长度为11')));
				}
				$sql .= "INT(".$data['fieldlong'].") DEFAULT ";
				if($data['vdata']){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= " '0' NOT NULL ";
				}
				break;
				case 5:
				case 9:
				$sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
				if($data['vdata'] || $data['vdata']==0){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= " NULL ";
				}
				break;
			
				case 7:
				case 8:
				case 12:
				$data['body'] = $this->frparam('body_'.$data['fieldtype'],1);
				$sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
				if($data['vdata'] || $data['vdata']==0){
					$sql .= "'".$data['vdata']."'";
				}else{
					$sql .= " NULL ";
				}
				break;
				case 13:
				if($data['fieldlong']>11 || $data['fieldlong']<=0){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
				}
				$sql .= "INT(".$data['fieldlong'].") DEFAULT ";
				if($data['vdata']){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= " '0' NOT NULL ";
				}
				$data['body'] = $this->frparam('molds_select',1).','.$this->frparam('molds_list_field',1);
				break;
				case 14:
				if(strpos($data['fieldlong'],',')===false){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对,decimal字段长度格式[整数位数,小数位数]')));
				}
				$sql .= "DECIMAL(".$data['fieldlong'].") DEFAULT ";
				if($data['vdata']){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= " '".$this->frparam('body_14',1)."' NOT NULL ";
				}
				break;
				case 16:
				$sql .= "VARCHAR(".$data['fieldlong'].") DEFAULT ";
				if($data['vdata'] || $data['vdata']==0){
					$sql .=  "'".$data['vdata']."'";
				}else{
					$sql .= " NULL ";
				}
				$data['body'] = $this->frparam('molds_select_muti',1).','.$this->frparam('molds_list_field_muti',1);
				break;
                case 20:
                    $sql .= "VARCHAR(".$data['fieldlong'].") DEFAULT ";
                    if($data['vdata'] || $data['vdata']==0){
                        $sql .=  "'".$data['vdata']."'";
                    }else{
                        $sql .= " NULL ";
                    }
                    $data['body'] = $this->frparam('molds_select_tid_muti',1).','.$this->frparam('molds_list_field_tid_muti',1);
                    break;
                case 21:
                    if($data['fieldlong']>11 || $data['fieldlong']<=0){
                        JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
                    }
                    $sql .= "INT(".$data['fieldlong'].") DEFAULT ";
                    if($data['vdata']){
                        $sql .=  "'".$data['vdata']."'";
                    }else{
                        $sql .= " '0' NOT NULL ";
                    }
                    $data['body'] = $this->frparam('molds_select_tid',1).','.$this->frparam('molds_list_field_tid',1);
                    break;
				
			}
			//由于已经存在，所以不需要再执行一遍SQL
			if($isgo){
				$x = M()->runSql($sql);
			}
			
			
			$n = M('Fields')->add($data);
			if(!$n){
				//新增字段记录失败，删除新增字段--不需要删除
				//$delsql = "ALTER TABLE ".DB_PREFIX.$data['molds']." DROP COLUMN ".$data['field'];
				//M()->runSql($delsql);
				JsonReturn(array('code'=>1,'msg'=>JZLANG('字段创建成功，但是字段表记录失败，请反馈官方解决！')));
			}
			JsonReturn(array('code'=>0,'msg'=>JZLANG('字段创建成功！')));
			
			
		}
		
		
		$this->classtypes = $this->classtypetree;
		$this->molds = $this->frparam('molds',1);
		$this->display('fields-add');
	}
	
	function editFields(){
		
		if($this->frparam('go',1)==1){

			if($this->frparam('id')){
				$data['field'] = strtolower($this->frparam('field',1));
				$data['molds'] = strtolower($this->frparam('molds',1));
				$data['fieldname'] = $this->frparam('fieldname',1);
				$data['tips'] = $this->frparam('tips',1);
				$data['fieldtype'] = $this->frparam('fieldtype');
				$data['tids'] = $this->frparam('tids',1);
				$data['fieldlong'] = $this->frparam('fieldlong',1);
				$data['body'] = $this->frparam('body',1);
				$data['orders'] = $this->frparam('orders');
				$data['ismust'] = $this->frparam('ismust');
				$data['isshow'] = $this->frparam('isshow');
				$data['ishome'] = $this->frparam('ishome');
				$data['isadmin'] = $this->frparam('isadmin');
				$data['issearch'] = $this->frparam('issearch');
				$data['islist'] = $this->frparam('islist');
				$data['format'] = $this->frparam('format',1);
				$data['vdata'] = $this->frparam('vdata',1);
				$data['isajax'] = $this->frparam('isajax');
				$data['listorders'] = $this->frparam('listorders');
				$data['isext'] = $this->frparam('isext');
				if($data['fieldname']=='' || $data['field']==''){
					JsonReturn(array('code'=>1,'msg'=>JZLANG('字段名和字段标识不能为空！')));
				}
				
				$data['tids'] = ($data['tids']!='')?(','.$data['tids'].','):$data['tids'];
				$old = M('Fields')->find(array('id'=>$this->frparam('id')));
				$data['fieldlong'] = $this->frparam('fieldlong_'.$data['fieldtype'],1);
				//只是更改样式，不更改字段属性
				if($old['field']==$data['field']){
					
					//判断长度是否不同
					if($data['fieldlong']!=$old['fieldlong'] || $data['vdata']!=$old['vdata']){
						$sql =  "ALTER TABLE ".DB_PREFIX.$old['molds']." modify column ".$old['field']."  ";
						switch($data['fieldtype']){
							case 1:
							case 2:
							case 5:
							case 7:
							case 8:
							case 9:
							case 12:
							case 16:
							case 18:
							case 19:
							case 20:
							$sql.=" varchar(".$data['fieldlong'].") default";
							if($data['vdata'] || $data['vdata']==0){
								$sql .=  "'".$data['vdata']."'";
							}else{
								$sql .= ' NULL ';
							}
							break;
							case 3:
							case 15:
							case 6:
							case 10:
							$sql .= "TEXT CHARACTER SET utf8 default ";
							$sql .= ' NULL ';
							break;
							case 4:
							case 11:
							case 13:
							case 17:
							case 21:
							$sql.=" int(".$data['fieldlong'].") default ";
							if($data['vdata']){
								$sql .=  "'".$data['vdata']."'";
							}else{
								$sql .= ' 0 ';
							}
							break;
							case 14:
							$sql.=" decimal(".$data['fieldlong'].") default ";
							if($data['vdata']){
								$sql .=  "'".$data['vdata']."'";
							}else{
								$sql .= " '".$this->frparam('body_14',1)."' NOT NULL ";
							}
							break;
							
						}
						$x = M()->runSql($sql);
						
					}
					if($data['fieldtype']==7 || $data['fieldtype']==8 || $data['fieldtype']==12 || $data['fieldtype']==14){
                    	$data['body'] = $this->frparam('body_'.$data['fieldtype'],1);
                    }
					if($data['fieldtype']==13){
						$data['body'] = $this->frparam('molds_select',1).','.$this->frparam('molds_list_field',1);
					}
					if($data['fieldtype']==16){
						$data['body'] = $this->frparam('molds_select_muti',1).','.$this->frparam('molds_list_field_muti',1);
					}
                    if($data['fieldtype']==20){
                        $data['body'] = $this->frparam('molds_select_tid_muti',1).','.$this->frparam('molds_list_field_tid_muti',1);
                    }
                    if($data['fieldtype']==21){
                        $data['body'] = $this->frparam('molds_select_tid',1).','.$this->frparam('molds_list_field_tid',1);
                    }
					if(M('Fields')->update(array('id'=>$this->frparam('id')),$data)){
						JsonReturn(array('code'=>0,'msg'=>JZLANG('字段修改成功！')));
					}else{
						JsonReturn(array('code'=>1,'msg'=>JZLANG('字段修改失败！')));
					}
					
				}else{
					if(in_array($data['field'],['id','sql','jzcache','jzcachetime','table','orderby','limit','ispage','notin','in','empty','notempty','fields','like','tids','day','as','istop','istuijian','ishot','isall','file'])){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('系统保护字段，不允许创建！')));
					}
				}
				
				$sql = "ALTER TABLE ".DB_PREFIX.$old['molds']." change ".$old['field']." ".$data['field']." ";
				
				switch($data['fieldtype']){
					case 1:
					case 2:
                    case 5:
                    case 9:
                    case 18:
                    case 19:

					$sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
					if($data['vdata'] || $data['vdata']==0){
						$sql .=  "'".$data['vdata']."'";
					}else{
						$sql .= ' NULL ';
					}
					break;
					case 3:
					case 15:
					case 6:
					case 10:
					$sql .= "TEXT CHARACTER SET utf8 default ";
					$sql .= ' NULL ';
					
					break;
					case 4:
					case 17:
					if($data['fieldlong']>11 || $data['fieldlong']<=0){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
					}
					$sql .= "INT(".$data['fieldlong'].") DEFAULT ";
					if($data['vdata']){
						$sql .=  "'".$data['vdata']."'";
					}else{
						$sql .= " '0' NOT NULL ";
					}
					break;
					case 11:
					if($data['fieldlong']!=11){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对,时间属性必须长度为11')));
					}
					$sql .= "INT(".$data['fieldlong'].") DEFAULT ";
					if($data['vdata']){
						$sql .=  "'".$data['vdata']."'";
					}else{
						$sql .= " '0' NOT NULL ";
					}
					break;
					case 14:
					if(strpos($data['fieldlong'],',')===false){
						JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对,decimal字段长度格式[整数位数,小数位数]')));
					}
					$sql .= "DECIMAL(".$data['fieldlong'].") DEFAULT ";
					if($data['vdata']){
						$sql .=  "'".$data['vdata']."'";
					}else{
						$sql .= " '".$this->frparam('body_14',1)."' NOT NULL ";
					}
					break;

					
					case 7:
					case 8:
					case 12:
					$sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
					if($data['vdata'] || $data['vdata']==0){
						$sql .=  "'".$data['vdata']."'";
					}else{
						$sql .= ' NULL ';
					}
					$data['body'] = $this->frparam('body_'.$data['fieldtype'],1);
					break;
					case 13:
					if($data['fieldlong']>11 || $data['fieldlong']<=0){
						
						JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
					}
					$sql .= "INT(".$data['fieldlong'].") DEFAULT ";
					if($data['vdata']){
						$sql .=  "'".$data['vdata']."'";
					}else{
						$sql .= " '0' NOT NULL ";
					}
					$data['body'] = $this->frparam('molds_select',1).','.$this->frparam('molds_list_field',1);
					break;
                    case 16:
                        if($data['fieldlong']>11 || $data['fieldlong']<=0){

                            JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
                        }
                        $sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
                        if($data['vdata'] || $data['vdata']==0){
                            $sql .=  "'".$data['vdata']."'";
                        }else{
                            $sql .= ' NULL ';
                        }
                        $data['body'] = $this->frparam('molds_select_muti',1).','.$this->frparam('molds_list_field_muti',1);
                        break;
                    case 21:
                        if($data['fieldlong']>11 || $data['fieldlong']<=0){

                            JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
                        }
                        $sql .= "INT(".$data['fieldlong'].") DEFAULT ";
                        if($data['vdata']){
                            $sql .=  "'".$data['vdata']."'";
                        }else{
                            $sql .= " '0' NOT NULL ";
                        }
                        $data['body'] = $this->frparam('molds_select_tid',1).','.$this->frparam('molds_list_field_tid',1);
                        break;
                    case 20:
                        if($data['fieldlong']>11 || $data['fieldlong']<=0){

                            JsonReturn(array('code'=>1,'msg'=>JZLANG('字段长度不对！')));
                        }
                        $sql .= "VARCHAR(".$data['fieldlong'].") CHARACTER SET utf8 default ";
                        if($data['vdata'] || $data['vdata']==0){
                            $sql .=  "'".$data['vdata']."'";
                        }else{
                            $sql .= ' NULL ';
                        }
                        $data['body'] = $this->frparam('molds_select_tid_muti',1).','.$this->frparam('molds_list_field_tid_muti',1);
                        break;
					
				}
				$x = M()->runSql($sql);
				
				if(M('Fields')->update(array('id'=>$this->frparam('id')),$data)){
					JsonReturn(array('code'=>0,'msg'=>JZLANG('字段修改成功！')));
					exit;
				}else{
					JsonReturn(array('code'=>1,'msg'=>JZLANG('字段修改失败！')));
					exit;
				}
			}
			
			
			
		}
		if($this->frparam('id')){
			$this->data = M('Fields')->find(array('id'=>$this->frparam('id')));
		}
		
		$this->classtypes = $this->classtypetree;
		$this->display('fields-edit');
		
	}
	
	function get_fields(){
		$tid = $this->frparam('tid',0,0);
		$isext = $this->frparam('isext',0,0);
		$sql = array();
		$molds = strtolower($this->frparam('molds',5));
		$moldsdata = M('molds')->find(['biaoshi'=>$molds]);
		if($tid){
			$sql[] = " (tids like '%,".$tid.",%' or tids is null) ";
		}
        $id = $this->frparam('id');
		if($id){
			$data = M($molds)->find(array('id'=>$id));
		}else{
			$data = array();
		}
		$sql[] = " isext=".$isext;
		$sql[] = " molds = '".$molds."' and isadmin=1 ";
		$sql = implode(' and ',$sql);
		$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
		$l = '';
		$isagree = 0;
		if($this->admin['isadmin']==1 || ($this->admin['isadmin']!=1 && $this->admin['ischeck']==0)){
			$isagree = 1;
		}
		foreach($fields_list as $k=>$v){
			if(($v['field']=='isshow' && $isagree==0) || $v['field']=='tid' || $v['field']=='id'){
				continue;
			}
			if(!array_key_exists($v['field'],$data)){
				//使用默认值
				$data[$v['field']] = $v['vdata'];
			}
			switch($v['fieldtype']){
				case 1:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>';
				break;
				case 2:
				$l .= '<div class="layui-form-item  layui-form-text">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'
                    </label>
                    <div class="layui-input-block">
                        <textarea  class="layui-textarea" id="'.$v['field'].'"  name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  '>'.$data[$v['field']].'</textarea>
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>';
				break;
				case 3:
				$l .= include(APP_PATH.APP_HOME.'/'.HOME_VIEW.'/'.Tpl_template.'/common/uediter.php');
				break;
				case 4:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>';
				break;
				
				
				case 5:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'  
                    </label>
					
					
					<div class="layui-input-inline">
						<input name="'.$v['field'].'" placeholder="'.JZLANG('上传图片').'" type="text" class="layui-input" id="'.$v['field'].'" ';
					if($v['ismust']==1){
						$l.=' required="" lay-verify="required" ';
					}
						$l.=' value="'.$data[$v['field']].'" />
					</div>
					<div class="layui-input-inline">
						<button class="layui-btn layui-btn-primary" id="LAY_'.$v['field'].'_upload" type="button" >'.JZLANG('选择图片').'</button>
					</div>
					<div class="layui-input-inline">
						<img id="'.$v['field'].'_img" class="img-responsive img-thumbnail" style="max-width: 200px;" src="'.$data[$v['field']].'" onerror="javascipt:this.src=\''.Tpl_style.'/style/images/nopic.jpg\'; this.title=\''.JZLANG('图片未找到').'\';this.onerror=\'\'">
						<button type="button" onclick="deleteImage_auto(this,\''.$v['field'].'\')" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger " title="'.JZLANG('删除这张图片').'" >'.JZLANG('删除').'</button>
					</div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,data:{tid:function(){ return $("#tid").val();},molds:"'.$molds.'"}
						,accept:"images"
						,acceptMime:"image/*"
						,done: function(res){
						  
							if(res.code==0){
								 $("#'.$v['field'].'_img").attr("src",res.url);
								 $("#'.$v['field'].'").val(res.url);
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("'.JZLANG('上传异常！').'");
						}
					  });
					});
				</script>';
				break;
				case 6:
				//------
				$l .= '<fieldset class="layui-elem-field">
				  <legend>'.$v['fieldname'].'</legend>
				  <div class="layui-field-box">
					  <div class="layui-input-block">
						  <div class="site-demo-upbar">
							<button type="button" class="layui-btn" id="LAY_'.$v['field'].'_upload">
							  <i class="layui-icon">&#xe67c;</i>'.JZLANG('上传图片').'
							</button>
							 '.$v['tips'].'
						  </div>
						   
					  </div>
					 
					  <div class="layui-input-block">
					  <span class="preview_'.$v['field'].'" >';
					if($data[$v['field']]!=''){
						foreach(explode('||',$data[$v['field']]) as $vv){
                            $pic = explode('|',$vv);
                            $l.='<div class="upload-icon-img layui-input-inline" ><div class="upload-pre-item"><img src="'.$pic[0].'" class="img" width="200px" height="200px" ><input name="'.$v['field'].'_urls[]" type="text" class="layui-input"  value="'.$pic[0].'" /><input name="'.$v['field'].'_des[]" type="text" class="layui-input" placeholder="'.JZLANG('文字描述').'"  value="'.$pic[1].'" /><a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger delete_file">'.JZLANG('删除').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goleft(this)">'.JZLANG('左移').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goright(this)">'.JZLANG('右移').'</a></div></div>';
							
						}
					}	 
					$l .= '</span>
					  </div>
				  </div>
				</fieldset>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,data:{tid:function(){ return $("#tid").val();},molds:"'.$molds.'"}
						,accept:"images"
						,multiple: true
						,acceptMime:"image/*"
						,before: function(obj){ 		
							layer.load(); //上传loading
						  }
						,done: function(res){
							layer.closeAll("loading"); //关闭loading
							if(res.code==0){
                                $(".preview_'.$v['field'].'").append(\'<div class="upload-icon-img layui-input-inline" ><div class="upload-pre-item"><img src="\' + res.url + \'" class="img" width="200px" height="200px" ><input name="'.$v['field'].'_urls[]" type="text" class="layui-input"  value="\' + res.url + \'" /><input name="'.$v['field'].'_des[]" type="text" class="layui-input"  placeholder="'.JZLANG('文字描述').'" value="" /><a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger delete_file">'.JZLANG('删除').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goleft(this)">'.JZLANG('左移').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goright(this)">'.JZLANG('右移').'</a></div></div>\');
								
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("'.JZLANG('上传异常！').'");
						}
					  });
					});
				</script>';
				break;
				case 7:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'  
                    </label>
                    <div class="layui-input-inline">
						<select name="'.$v['field'].'" lay-search="" id="'.$v['field'].'" ><option value="">'.JZLANG('请选择').'</option>';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<option value="'.$s[1].'" ';
					if($data[$v['field']]==$s[1]){
						$l.='selected="selected"';
					}
					$l.='>'.$s[0].'</option>';
				}
					$l.=  '</select>
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>
				<script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				break;
				case 8:
				$l .= '<div class="layui-form-item">
						<label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'  
						</label>
						<div class="layui-input-block">';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<input type="checkbox" title="'.$s[0].'" name="'.$v['field'].'[]" value="'.$s[1].'" ';
					if(strpos($data[$v['field']],','.$s[1].',')!==false){
						$l.='checked="checked"';};
					$l.='>';
				}
				$l 	.= '</div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>
					  <script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				
				break;
				case 9:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'  
                    </label>
					
                    <div class="layui-input-inline">
                      <div class="site-demo-upbar">
                      
					  <input name="'.$v['field'].'" type="text" class="layui-input" id="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}
				$l  .=	'value="'.$data[$v['field']].'" />
						<button type="button" class="layui-btn" id="LAY_'.$v['field'].'_upload">
						  <i class="layui-icon">&#xe67c;</i>上传附件
						</button>

					  
                      </div>
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,data:{tid:function(){ return $("#tid").val();},molds:"'.$molds.'"}
						,accept:"file"
						,exts: "'.$this->webconf['fileType'].'"
						,done: function(res){
							if(res.code==0){
								
								 $("#'.$v['field'].'").val(res.url);
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("'.JZLANG('上传异常！').'");
						}
					  });
					});
				</script>';
				break;
				case 10:
				$l .= '<fieldset class="layui-elem-field">
				  <legend>'.$v['fieldname'].'</legend>
				  <div class="layui-field-box">
					  <div class="layui-input-block">
						  <div class="site-demo-upbar">
							<button type="button" class="layui-btn" id="LAY_'.$v['field'].'_upload">
							  <i class="layui-icon">&#xe67c;</i>'.JZLANG('上传附件').'
							</button>
							 '.$v['tips'].'
						  </div>
						   
					  </div>
					 
					  <div class="layui-input-block">
					  <span class="preview_'.$v['field'].'" >';
					if($data[$v['field']]!=''){
						foreach(explode('||',$data[$v['field']]) as $vv){
                            $pic = explode('|',$vv);
                            $l.='<div class="upload-icon-img layui-input-inline" ><div class="upload-pre-item"><input name="'.$v['field'].'_urls[]" type="text" class="layui-input"  value="'.$pic[0].'" /><input name="'.$v['field'].'_des[]" type="text" class="layui-input" placeholder="'.JZLANG('文字描述').'"  value="'.$pic[1].'" /><a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger delete_file">'.JZLANG('删除').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goleft(this)">'.JZLANG('左移').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goright(this)">'.JZLANG('右移').'</a></div></div>';
							
						}
					}	 
					$l .= '</span>
					  </div>
				  </div>
				</fieldset>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,data:{tid:function(){ return $("#tid").val();},molds:"'.$molds.'"}
						,multiple: true
						,accept:"file"
						,exts: "'.$this->webconf['fileType'].'"
						,before: function(obj){ 		
							layer.load(); //上传loading
						  }
						,done: function(res){
							layer.closeAll("loading"); //关闭loading
							if(res.code==0){
                                $(".preview_'.$v['field'].'").append(\'<div class="upload-icon-img layui-input-inline" ><div class="upload-pre-item"><input name="'.$v['field'].'_urls[]" type="text" class="layui-input"  value="\' + res.url + \'" /><input name="'.$v['field'].'_des[]" type="text" class="layui-input" placeholder="'.JZLANG('文字描述').'"  value="" /><a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger delete_file">'.JZLANG('删除').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goleft(this)">'.JZLANG('左移').'</a><a class="layui-btn layui-btn-sm layui-btn-radius imgorder " onclick="goright(this)">'.JZLANG('右移').'</a></div></div>\');
							
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("'.JZLANG('上传异常！').'");
						}
					  });
					});
				</script>';
				break;
				case 11:
				$laydate = ($data[$v['field']]=='' || $data[$v['field']]==0)?time():$data[$v['field']];
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'
                    </label>
                    <div class="layui-input-inline">
                        <input id="laydate_'.$v['field'].'" value="'.date('Y-m-d H:i:s',$laydate).'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
				$randt = getRandChar(5);
                $l.='</div>
				<script>
layui.use("laydate", function(){
  var laydate'.$randt.' = layui.laydate;
  laydate'.$randt.'.render({elem: "#laydate_'.$v['field'].'",type:"datetime",trigger: "click" });});</script>';
				break;
				case 12:
				$l .= '<div class="layui-form-item" pane>
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'  
                    </label>
                    <div class="layui-input-inline">';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<input type="radio" name="'.$v['field'].'" value="'.$s[1].'" title="'.$s[0].'" ';
					if($data[$v['field']]==$s[1]){
						$l.='checked="checked"';
					}
					$l.=' >';
				}
					$l.='</div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>
					<script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				break;

                case 13:
                case 21:
                    //tid,field

                    $l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
                    if($v['ismust']==1){
                        $l .= '<span class="x-red">*</span>';
                    }
                    $l .= $v['fieldname'].'  
                    </label>
					<div class="layui-input-inline">
					<input type="hidden" id="'.$v['field'].'" name="'.$v['field'].'" value="">
					<div id="'.$v['field'].'_xmselect"></div>
					<script>
					var '.$v['field'].'_xmselect = xmSelect.render({
							el: "#'.$v['field'].'_xmselect", 
							autoRow: true,
							toolbar: { show: false },
							filterable: true,
							radio:true,
							remoteSearch: true,
							remoteMethod: function(val, cb, show){
//								if(!val){
//									return cb([]);
//								}
								$.get("'.U('Fields/getSelect').'",{id:"'.$v['id'].'",key:val},function(res){
									if(res.code==0){
										cb(res.data)
									}else{
										layer.alert(res.msg)
										
									}
									
								},"json")
								
							},
							on:function(r){
								if(r["arr"].length>0){
									$("#'.$v['field'].'").val(r["arr"][0].value)
								}else{
									$("#'.$v['field'].'").val("")
								}
							}
						})
						$.get("'.U('Fields/getSelect').'",{id:"'.$v['id'].'",value:"'.$data[$v['field']].'",check:1},function(res){
									if(res.code==0){
										'.$v['field'].'_xmselect.setValue(res.data);
										$("#'.$v['field'].'").val("'.$data[$v['field']].'");
									}else{
										//layer.alert(res.msg)
										
									}
									
								},"json")
						 
						</script>
					</div>
					';

                    if($v['tips']){
                        $l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
                    }
                    $l.='</div>
				<script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
                    break;
				case 14:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}	
                $l.='</div>';
				break;
				case 15:
				$l .= '<fieldset class="layui-elem-field">
				  <legend>'.$v['fieldname'].'</legend>
				  <div class="layui-field-box">
					  <div class="layui-input-block" id="'.$v['field'].'_space">';
				if($data[$v['field']]){
					$rs = explode('||',$data[$v['field']]);
					foreach($rs as $vv){
						$l.='<div class="layui-input-block"><input type="text"  style="width:500px;" value="'.$vv.'" name="'.$v['field'].'[]" autocomplete="off" class="layui-input layui-input-inline"><button type="button" class="layui-btn layui-btn-danger layui-btn-sm  layui-input-inline '.$v['field'].'_del" >'.JZLANG('删除').'</button></div>';
					}
				}else{
					$l .='<div class="layui-input-block">
							<input type="text"  style="width:500px;" value="'.$data[$v['field']].'" name="'.$v['field'].'[]" autocomplete="off" class="layui-input">
						</div>';
				}
				$l	.=  '</div>
					  <div class="layui-form-mid layui-word-aux">
						  <button type="button" class="layui-btn" id="'.$v['field'].'_add">新增</button>'.$v['tips'].'
				      </div>
				  </div>
				</fieldset>
				<script>
				$(document).ready(function(){
					$("#'.$v['field'].'_add").click(function(){
						var html = \'<div class="layui-input-block"><input type="text"  style="width:500px;" value="" name="'.$v['field'].'[]" autocomplete="off" class="layui-input layui-input-inline"><button type="button" class="layui-btn layui-btn-danger layui-btn-sm  layui-input-inline '.$v['field'].'_del" >'.JZLANG('删除').'</button></div>\';
						
						$("#'.$v['field'].'_space").append(html);
						
						
					});
					$(document).on("click",".'.$v['field'].'_del",function(){
						$(this).parent().remove();
					})
					
					
				})
				</script>';
				break;
                case 16:
                case 20:

                    $l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
                    if($v['ismust']==1){
                        $l .= '<span class="x-red">*</span>';
                    }
                    $l .= $v['fieldname'].'  
                    </label>
					<div class="layui-input-inline">
					<input type="hidden" id="'.$v['field'].'" name="'.$v['field'].'" value="">
					<div id="'.$v['field'].'_xmselect"></div>
					<script>
					var '.$v['field'].'_xmselect = xmSelect.render({
							el: "#'.$v['field'].'_xmselect", 
							autoRow: true,
							toolbar: { show: true },
							filterable: true,
							remoteSearch: true,
							remoteMethod: function(val, cb, show){
//								if(!val){
//									return cb([]);
//								}
								$.get("'.U('Fields/getSelect').'",{id:"'.$v['id'].'",key:val},function(res){
									if(res.code==0){
										cb(res.data)
									}else{
										layer.alert(res.msg)
										
									}
									
								},"json")
								
							},
							on:function(r){
								var s = [];
								for(var i=0;i<r["arr"].length;i++){
									s.push(r["arr"][i].value)
								}
								$("#'.$v['field'].'").val(s.join(","))
							}
						})
						
						$.get("'.U('Fields/getSelect').'",{id:"'.$v['id'].'",value:"'.trim($data[$v['field']],',').'",check:1},function(res){
									if(res.code==0){
										'.$v['field'].'_xmselect.setValue(res.data);
										$("#'.$v['field'].'").val("'.trim($data[$v['field']],',').'");
									}else{
										//layer.alert(res.msg)
										
									}
									
								},"json")
						
						</script>
					</div>
					';

                    if($v['tips']){
                        $l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
                    }
                    $l.='</div>
				<script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
                    break;
				
				case 18:
					$l.='<div class="layui-form-item">
                    <label for="tids" class="layui-form-label">
                        '.JZLANG('副栏目').'
                    </label>
                    <div class="layui-input-inline">
                       <div id="tids" ></div>
                    </div>
                  
                </div>
                <script>
                var tids_obj = xmSelect.render({
        		el: "#tids",
        		language: "zn",
        		data: [';
					foreach($this->classtypetree as $vv){
                        if($vv['molds']==$molds){
                            if($this->admin['classcontrol']==0 || $this->admin['isadmin']==1 || strpos($this->tids,','.$vv['id'].',')!==false || $moldsdata['iscontrol']==0){
                              $l.='{name: "'.str_repeat('--', $vv['level']).$vv['classname'].'", value: '.$vv['id'].'},';
                            }

                        }

					}
                $l.=']
              })
                tids_obj.setValue([';

                foreach($this->classtypetree as $vv){
                    if(strpos($data['tids'],','.$vv['id'].',')!==false){
                        $l.='{name: "'.str_repeat('--', $vv['level']).$vv['classname'].'", value: '.$vv['id'].'},';
                    }
                }
        	   $l.=' ])
                    </script>';
				break;
				case 19:
				$l.='<div class="layui-form-item layui-form-text">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        '.JZLANG('TAG标签').' [ '.JZLANG('按Enter回车自动添加').' ]
                    </label>
                    <div class="layui-input-block">
						 <input id="'.$v['field'].'" type="text" class="'.$v['field'].'" name="'.$v['field'].'" value="'.trim($data[$v['field']],','). '"  autocomplete="off" class="layui-input"  />
                    </div>
                </div>
                <script>
                $(function() {
			  $("#'.$v['field'].'").tagsInput({
					width:"auto",
					defaultText:"'.JZLANG('添加一个标签').'",
                    });
                })</script>';
				break;
				case 20:
				$laydate = ($data[$v['field']]=='' || $data[$v['field']]==0)?time():$data[$v['field']];
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">';
				if($v['ismust']==1){
				$l .= '<span class="x-red">*</span>';	
				}
                $l .= $v['fieldname'].'
                    </label>
                    <div class="layui-input-inline">
                        <input id="laydate_'.$v['field'].'" value="'.date('Y-m-d H:i:s',$laydate).'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>';
				if($v['tips']){
					$l.='<div class="layui-form-mid layui-word-aux">
					  <i data-info="'.$v['tips'].'" data-field="f'.$v['id'].'" class="layui-sys-icon layui-icon layui-icon-about f'.$v['id'].'"></i>
					</div>';
				}
				$randt = getRandChar(5);
                $l.='</div>
				<script>
layui.use("laydate", function(){
  var laydate'.$randt.' = layui.laydate;
  laydate'.$randt.'.render({elem: "#laydate_'.$v['field'].'",type:"datetime",trigger: "click",range:"~" });});</script>';
				break;
				
				
				
				
			}
			
		}
		echo $l;
	}

    function getSelect(){
        $id = $this->frparam('id');
        if(!$id){
            JsonReturn(['code'=>1,'msg'=>'ID错误！']);
        }
        $fields = M('fields')->find(['id'=>$id]);
        if(!$fields){
            JsonReturn(['code'=>1,'msg'=>'未找到字段！']);
        }

        $body = explode(',',$fields['body']);
        $field = strtolower($body[1]);

        $value = $this->frparam('value',1);
        if($this->frparam('check')){

            switch($fields['fieldtype']){
                case 13:
                    $molds = M('molds')->getField(['id'=>$body[0]],'biaoshi');
                    if(!$molds){
                        JsonReturn(['code'=>1,'msg'=>$field.JZLANG('字段关联绑定失败，请重新绑定！')]);
                    }
                    if($value){
                        $lists = M($molds)->findAll(['id'=>$value],null,'id ,'.$field);
                    }else{
                        $lists = [];
                    }

                    foreach($lists as $k=>$v){
                        $lists[$k]['value'] = $v['id'];
                        $lists[$k]['name'] = $v[$field];
                        $lists[$k]['selected'] = true;
                    }
                    JsonReturn(['code'=>0,'data'=>$lists]);

                    break;

                case 16:
                    $molds = M('molds')->getField(['id'=>$body[0]],'biaoshi');
                    if(!$molds){
                        JsonReturn(['code'=>1,'msg'=>$field.JZLANG('字段关联绑定失败，请重新绑定！')]);
                    }
                    if($value){
                        $ids = $value;
                        $sql=" id in(".$ids.") ";
                        $lists = M($molds)->findAll($sql,null,'id ,'.$field);
                    }else{
                        $lists = [];
                    }
                    break;
                case 20:
                    $tid = (int)$body[0];
                    $molds = $this->classtypedata[$tid]['molds'];
                    if(!$molds){
                        JsonReturn(['code'=>1,'msg'=>$field.JZLANG('字段关联绑定失败，请重新绑定！')]);
                    }
                    if($value){
                        $ids = $value;
                        $sql=" id in(".$ids.") ";
                        $lists = M($molds)->findAll($sql,null,'id ,'.$field);
                    }else{
                        $lists = [];
                    }
                    break;
                case 21:

                    $tid = (int)$body[0];
                    $molds = $this->classtypedata[$tid]['molds'];
                    if(!$molds){
                        JsonReturn(['code'=>1,'msg'=>$field.JZLANG('字段关联绑定失败，请重新绑定！')]);
                    }
                    if($value){
                        $tids = array_column($this->classtypedata[$tid]['children']['lists'],'id');
                        $tids[]=$tid;
                        $sql = " id=".$value." and tid in(".implode(',',$tids).")";
                        $lists = M($molds)->findAll(['id'=>$value],null,'id ,'.$field);
                    }else{
                        $lists = [];
                    }

                    break;

            }
            foreach($lists as $k=>$v){
                $lists[$k]['value'] = $v['id'];
                $lists[$k]['name'] = $v[$field];
                $lists[$k]['selected'] = true;
            }
            JsonReturn(['code'=>0,'data'=>$lists]);



        }

        $key = $this->frparam('key',1);
        if(!$key){
           // JsonReturn(['code'=>1,'msg'=>'关键词错误！']);
        }
        switch($fields['fieldtype']){
            case 13:
            case 16:
                $molds = M('molds')->getField(['id'=>$body[0]],'biaoshi');
                if(!$molds){
                    JsonReturn(['code'=>1,'msg'=>'关联绑定配置错误！']);
                }
                $sql = $key ? $field." like '%".$key."%'" : null;
                break;
            case 20:
            case 21:
                $tid = (int)$body[0];
                $molds = $this->classtypedata[$tid]['molds'];
                $tids = array_column($this->classtypedata[$tid]['children']['lists'],'id');
                $tids[]=$tid;
                $sql = $key ? $field." like '%".$key."%' and tid in(".implode(',',$tids).")" : "tid in(".implode(',',$tids).")";

                break;
        }
        $limit = $key ? null : 10;
        $lists = M($molds)->findAll($sql,null,'id ,'.$field,$limit);

        foreach($lists as $k=>$v){
            $lists[$k]['value'] = $v['id'];
            $lists[$k]['name'] = $v[$field];

        }

        JsonReturn(['code'=>0,'data'=>$lists]);
    }

	function deleteFields(){
		$id = $this->frparam('id');
		if($id){
			
			$fields = M('fields')->find(array('id'=>$id));
			//不允许删除字段
			$noallow = ['addtime','member_id','hits','target','ownurl','id','molds','htmlurl','jzattr','tids','tid','litpic','title','keywords','seo_title','body'];
			if(in_array($fields['field'],$noallow)){
				JsonReturn(array('code'=>1,'msg'=>JZLANG('系统字段不允许删除！')));
			}
			if(M('Fields')->delete('id='.$id)){
				$sql = "ALTER TABLE ".DB_PREFIX.$fields['molds']." DROP COLUMN ".$fields['field'];
				$x = M()->runSql($sql);
				
				JsonReturn(array('code'=>0,'msg'=>JZLANG('删除成功！')));
			}else{
				JsonReturn(array('code'=>1,'msg'=>JZLANG('删除失败！')));
			}
		}
	}
	
	function changeOrders(){
		
		$w['orders'] = $this->frparam('orders',0,0);
		$r = M('fields')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));
	}
	
	function changeTid(){
		$ids = $this->frparam('data',1);
		if(!$ids){
			JsonReturn(['code'=>1,'msg'=>JZLANG('请选择字段！')]);
		}
		$tid = $this->frparam('tid');
		if(!$tid){
			JsonReturn(['code'=>1,'msg'=>JZLANG('请选择栏目！')]);
		}
		$sql = 'id in('.$ids.') ';
		$lists = M('fields')->findAll($sql);
		foreach($lists as $v){
			if(strpos($v['tids'],','.$tid.',')===false){
				if($v['tids']){
					$v['tids'] .= $tid.',';
				}else{
					$v['tids'] = ','.$tid.',';
				}
				M('fields')->update(['id'=>$v['id']],['tids'=>$v['tids']]);
			}
		}
		
		JsonReturn(['code'=>0,'msg'=>JZLANG('操作成功！')]);
	}

	function editFieldsValue(){
		$field = $this->frparam('field',1);
		$w[$field] = $this->frparam('value',1);
		$r = M('fields')->update(array('id'=>$this->frparam('id')),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));
	}

	function fieldsList(){
		
		if(!$this->frparam('molds',1)){
			Error(JZLANG('请选择模块！'));
		}
		if($this->frparam('ajax')){

			$data = M('fields')->findAll(array('molds'=>$this->frparam('molds',5)),'islist desc,listorders desc');
			foreach($data as &$v){
				$v['isadmin'] = $v['isadmin']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['isshow'] = $v['isshow']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['islist'] = $v['islist']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['issearch'] = $v['issearch']==1 ? JZLANG('显示') : JZLANG('隐藏');
				$v['ismust'] = $v['ismust']==1 ? JZLANG('是') : JZLANG('否');
				$v['isext'] = $v['isext']==1 ? JZLANG('是') : JZLANG('否');
				switch($v['fieldtype']){
					case 1:
					$v['fieldtypename'] = JZLANG('单行文本');
					break;
					case 2:
					$v['fieldtypename'] = JZLANG('多行文本');
					break;
					case 3:
					$v['fieldtypename'] = JZLANG('文本编辑器');
					break;
					case 4:
					$v['fieldtypename'] = JZLANG('数字');
					break;
					case 5:
					$v['fieldtypename'] = JZLANG('单图片');
					break;
					case 6:
					$v['fieldtypename'] = JZLANG('多图片');
					break;
					case 7:
					$v['fieldtypename'] = JZLANG('单选下拉');
					break;
					case 8:
					$v['fieldtypename'] = JZLANG('多选');
					break;
					case 9:
					$v['fieldtypename'] = JZLANG('单附件');
					break;
					case 10:
					$v['fieldtypename'] = JZLANG('多附件');
					break;
					case 11:
					$v['fieldtypename'] = JZLANG('时间戳');
					break;
					case 12:
					$v['fieldtypename'] = JZLANG('单选按钮');
					break;
					case 13:
					$v['fieldtypename'] = JZLANG('单选关联');
					break;
					case 14:
					$v['fieldtypename'] = JZLANG('小数');
					break;
					case 15:
					$v['fieldtypename'] = JZLANG('多行录入');
					break;
					case 16:
					$v['fieldtypename'] = JZLANG('多选关联');
					break;
                    case 17:
                    $v['fieldtypename'] = JZLANG('栏目');
                    break;
                    case 18:
                    $v['fieldtypename'] = JZLANG('副栏目');
                    break;
					case 19:
                    $v['fieldtypename'] = JZLANG('系统TAG');
                    break;
                    case 20:
                        $v['fieldtypename'] = JZLANG('绑定栏目多选');
                        break;
                    case 21:
                        $v['fieldtypename'] = JZLANG('绑定栏目单选');
                        break;
					
				}
				
				$v['edit_url'] = U('editFields',['id'=>$v['id']]);
				
				$v['edit_url'] = U('editFields',['id'=>$v['id']]);
			}
			JsonReturn(['code'=>0,'data'=>$data,'count'=>count($data)]);
			
			
		}
		$this->molds = M('Molds')->find(array('biaoshi'=>$this->frparam('molds',5)));
		
		$this->display('fields-list-show');
	}
	
	function changeFieldList(){
		$field = $this->frparam('field',1);
		$w[$field] = $this->frparam('value',1);
		if(in_array($field,['issearch','islist','width','listorders'])){
			$r = M('fields')->update(array('id'=>$this->frparam('id')),$w);
			if(!$r){
				JsonReturn(array('code'=>1,'info'=>JZLANG('修改失败！')));
			}
		}else{
			JsonReturn(array('code'=>1,'info'=>JZLANG('非法操作！')));
		}
		
		JsonReturn(array('code'=>0,'info'=>JZLANG('修改成功！')));
		
	}
	
	
}