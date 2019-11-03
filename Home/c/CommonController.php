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


namespace Home\c;

use FrPHP\lib\Controller;

class CommonController extends Controller
{
	function _init(){

		$webconf = webConf();
		$template = get_template();
		$this->webconf = $webconf;
		$this->template = $template;
		if(isset($_SESSION['terminal'])){
			$classtypedata = $_SESSION['terminal']=='mobile' ? classTypeDataMobile() : classTypeData();
		}else{
			$classtypedata = (isMobile() && $webconf['iswap']==1)?classTypeDataMobile():classTypeData();
		}
		
		
		$this->classtypetree = $classtypedata;
		foreach($classtypedata as $k=>$v){
			$classtypedata[$k]['children'] = get_children($v,$classtypedata);
		}
		$this->classtypedata = $classtypedata;
		$this->common = Tpl_style.'common/';
		$this->tpl = Tpl_style.$template.'/';
		$this->frpage = $this->frparam('page',0,1);
		$customconf = get_custom();
		$this->customconf = $customconf;
		if(isset($_SESSION['member'])){
			$this->islogin = true;
			$this->member = $_SESSION['member'];
			if($this->webconf['isopenhomepower']==1){
				if(strpos($_SESSION['member']['paction'],','.APP_CONTROLLER.',')!==false){
				   
				}else{
					$action = APP_CONTROLLER.'/'.APP_ACTION;
					if(strpos($_SESSION['member']['paction'],','.$action.',')==false){
						$ac = M('Power')->find(array('action'=>$action));
						
						if($this->frparam('ajax')){
							JsonReturn(['code'=>1,'msg'=>'您没有【'.$ac['name'].'】的权限！','url'=>U('Home/index')]);
						}
						Error('您没有【'.$ac['name'].'】的权限！',U('Home/index'));
					}
				}
			   
			  
			}
			
			
		}else{
			$this->islogin = false;
		}
		
    
    }
	
	
	function vercode(){
		if($this->frparam('code_name',1)){
			frvercode(4,$this->frparam('code_name',1));
		}else{
			 frvercode();
		}
		 
	}
	
	function uploads(){
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
		  //检测是否允许前台上传文件
		  if(!$this->webconf['isopenhomeupload']){
			  $data['error'] =  "Error: 已关闭前台上传文件功能";
			  $data['code'] = 1004;
			  JsonReturn($data);
		  }
		 
			$fileType = webConf('fileType');
			if(strpos($fileType,strtolower($pix))===false){
				$data['error'] =  "Error: 文件类型不允许上传！";
				$data['code'] = 1002;
				JsonReturn($data);
			}
			$fileSize = (int)webConf('fileSize');
			if($fileSize!=0 && $_FILES["file"]["size"]>$fileSize){
				$data['error'] =  "Error: 文件大小超过网站内部限制！";
				$data['code'] = 1003;
				JsonReturn($data);
			}
		  
		    $filename =  'Public/Home/'.date('Ymd').rand(1000,9999).'.'.$pix;
		  
			if(move_uploaded_file($_FILES["file"]['tmp_name'],$filename)){
				$data['url'] = $filename;
				$data['code'] = 0;
				if(isset($_SESSION['member'])){
					$userid = $_SESSION['member']['id'];
				}else{
					$userid = 0;
				}
				M('pictures')->add(['litpic'=>'/'.$filename,'addtime'=>time(),'userid'=>$userid,'size'=>$_FILES["file"]["size"]]);
				
			}else{
				$data['error'] =  "Error: 请检查目录[Public/Home]写入权限";
				$data['code'] = 1001;
				  
			} 

			  
		  
		  }
		  
		  
		  JsonReturn($data,true);
		  exit;
		  
		
		
	}
	
	
	function qrcode(){
		extendFile('phpqrcode/phpqrcode.php');
		$data = $this->frparam('data',1);
		//$pic = 'qrcode.png';
		$errorCorrectionLevel = "L";
		$matrixPointSize = 6;
		//$value = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
		
		\QRcode::png($data, false, $errorCorrectionLevel, $matrixPointSize, 2);
	}
	
	function get_fields(){
		$tid = $this->frparam('tid');
		$sql = array();
		if($tid!=0){
			$sql[] = " tids like '%,".$tid.",%' "; 
		}
		$molds = $this->frparam('molds',1);
		$id = $this->frparam('id');
		$isdata = $this->frparam('isdata');
		if($id){
			$data = M($molds)->find(array('id'=>$id));
		}else{
			$data = array();
		}
		$sql[] = " molds = '".$molds."' ";
		$sql = implode(' and ',$sql);
		$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
		$l = '';
		foreach($fields_list as $k=>$v){
			if(!array_key_exists($v['field'],$data)){
				//使用默认值
				$data[$v['field']] = $v['vdata'];
			}
			switch($v['fieldtype']){
				case 1:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red"></span>'.$v['fieldname'].'
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
					
                </div>';
				break;
				case 2:
				$l .= '<div class="layui-form-item  layui-form-text">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red"></span>'.$v['fieldname'].'
                    </label>
                    <div class="layui-input-block">
                        <textarea  class="layui-textarea" id="'.$v['field'].'"  name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  '>'.$data[$v['field']].'</textarea>
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
					
                </div>';
				break;
				case 3:
				$rd = time();
				$l .= '<div class="layui-form-item layui-form-text">
							<label for="'.$v['field'].'" class="layui-form-label">
								<span class="x-red">*</span>'.$v['fieldname'].'
							</label>
							<div class="layui-input-block" style="width:100%;">
							<script id="'.$v['field'].$rd.'" name="'.$v['field'].'" type="text/plain" style="width:100%;height:400px;">'.$data[$v['field']].'</script>
								
							</div>
						</div>
						<script>
						$(document).ready(function(){
						var ue_'.$v['field'].$rd.' = UE.getEditor("'.$v['field'].$rd.'",{';
					if($this->webconf['ueditor_config']!=''){
						$l.= 'toolbars : [['.$this->webconf['ueditor_config'].']]';
					}		
						$l.='}		
						);	
						});
						</script>';
				
				break;
				case 4:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red"></span>'.$v['fieldname'].'
                    </label>
                    <div class="layui-input-block">
                        <input type="number" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
					
                </div>';
				break;
				case 11:
				$laydate = ($data[$v['field']]=='' || $data[$v['field']]==0)?time():$data[$v['field']];
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red"></span>'.$v['fieldname'].'
                    </label>
                    <div class="layui-input-block">
                        <input id="laydate_'.$v['field'].'" value="'.date('Y-m-d H:i:s',$laydate).'" name="'.$v['field'].'" ';
				if($v['ismust']==1){
					$l.=' required="" lay-verify="required" ';
				}		
                $l .=  'autocomplete="off" class="layui-input">
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div><script>
layui.use("laydate", function(){
  var laydate = layui.laydate;
  laydate.render({elem: "#laydate_'.$v['field'].'",type:"datetime" });});</script>';
				break;
				case 5:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
						<span class="x-red">*</span>'.$v['fieldname'].'  
                    </label>
					
                    <div class="layui-input-inline">
						<input name="'.$v['field'].'" placeholder="上传图片" type="text" class="layui-input" id="'.$v['field'].'" ';
					if($v['ismust']==1){
						$l.=' required="" lay-verify="required" ';
					}
						$l.=' value="'.$data[$v['field']].'" />
					</div>
					<div class="layui-input-inline">
						<button class="layui-btn layui-btn-primary" id="LAY_'.$v['field'].'_upload" type="button" >选择图片</button>
					</div>
					<div class="layui-input-inline">
						<img id="'.$v['field'].'_img" class="img-responsive img-thumbnail" style="max-width: 200px;" src="" onerror="javascipt:this.src=\''.Tpl_style.'/style/images/nopic.jpg\'; this.title=\'图片未找到\';this.onerror=\'\'">
						<button type="button" onclick="deleteImage_auto(this,\''.$v['field'].'\')" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger " title="删除这张图片" >删除</button>
					</div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,accept:"images"
						,acceptMime:"image/*"
						,done: function(res){
						  
							if(res.code==0){
								 $("#'.$v['field'].'_img").attr("src","/"+res.url);
								 $("#'.$v['field'].'").val("/"+res.url);
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("上传异常！");
						}
					  });
					});
				</script>';
				break;
				case 6:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
						<span class="x-red">*</span>'.$v['fieldname'].'  
                    </label>
                    <div class="layui-input-inline">
                      <div class="site-demo-upbar">
                     <span class="preview_'.$v['field'].'" >';
				if($data[$v['field']]!=''){
					foreach(explode('||',$data[$v['field']]) as $vv){
						$l.='<span class="upload-icon-img" ><div class="upload-pre-item"><img src="'.$vv.'" class="img" max-width="200px" ><input name="'.$v['field'].'_urls[]" type="text" class="layui-input"  value="'.$vv.'" /><i  class="layui-icon delete_file" >&#xe640;</i></div></span>';
					}
				}	 
				$l .= '</span>
						<button type="button" class="layui-btn" id="LAY_'.$v['field'].'_upload">
						  <i class="layui-icon">&#xe67c;</i>上传图片
						</button>
                      </div>
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,accept:"images"
						,multiple: true
						,acceptMime:"image/*"
						,before: function(obj){ 		
							layer.load(); //上传loading
						  }
						,done: function(res){
							layer.closeAll("loading"); //关闭loading
							if(res.code==0){
							
							$(".preview_'.$v['field'].'").append(\'<span class="upload-icon-img" ><div class="upload-pre-item"><img src="/\' + res.url + \'" class="img" max-width="200px" ><input name="'.$v['field'].'_urls[]" type="text" class="layui-input"  value="/\' + res.url + \'" /><i  class="layui-icon delete_file" >&#xe640;</i></div></span>\');
								
								
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("上传异常！");
						}
					  });
					});
				</script>';
				break;
				case 7:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red">*</span>'.$v['fieldname'].'  
                    </label>
                    <div class="layui-input-inline">
						<select name="'.$v['field'].'" id="'.$v['field'].'" >';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<option value="'.$s[1].'" ';
					if($data[$v['field']]==$s[1]){
						$l.='selected="selected"';
					}
					$l.='>'.$s[0].'</option>';
				}
					$l.=  '</select>
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div><script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				break;
				case 12:
				$l .= '<div class="layui-form-item" pane>
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red">*</span>'.$v['fieldname'].'  
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
					$l.='</div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
					</div><script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				break;
				case 8:
				$l .= '<div class="layui-form-item">
						<label for="'.$v['field'].'" class="layui-form-label">
							<span class="x-red">*</span>'.$v['fieldname'].'  
						</label>
						<div class="layui-input-block">';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<input type="checkbox" title="'.$s[0].'" name="'.$v['field'].'[]" value="'.$s[1].'" ';
					if(strpos($data[$v['field']],','.$s[1].',')!==false){
						$l.='checked="checked"';};
					$l.='>';
				}
				$l 	.= '</div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
					  </div>
					  <script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				
				break;
				case 9:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
						<span class="x-red">*</span>'.$v['fieldname'].'  
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
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,accept:"file"
						,exts: "'.$this->webconf['fileType'].'"
						,done: function(res){
							if(res.code==0){
								
								 $("#'.$v['field'].'").val("/"+res.url);
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("上传异常！");
						}
					  });
					});
				</script>';
				break;
				case 10:
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
						<span class="x-red">*</span>'.$v['fieldname'].'  
                    </label>
					
                    <div class="layui-input-inline">
                      <div class="site-demo-upbar">
                     <span class="preview_'.$v['field'].'" >';
				if($data[$v['field']]!=''){
					foreach(explode('||',$data[$v['field']]) as $vv){
						$l.='<span class="upload-icon-img" ><div class="upload-pre-item"><i class="layui-icon layui-icon-file"></i><input type="text" value="'.$vv.'" name="'.$v['field'].'_urls[]" class="layui-input" /><i   class="layui-icon delete_file">&#xe640;</i></div></span>';
					}
				}	
				$l	.= '</span>
					  
						<button type="button" class="layui-btn" id="LAY_'.$v['field'].'_upload">
						  <i class="layui-icon">&#xe67c;</i>上传附件
						</button>
                      </div>
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div>
				<script>
				
				layui.use("upload", function(){
					  var upload_'.$v['field'].' = layui.upload;
					   
					  //执行实例
					  var uploadInst = upload_'.$v['field'].'.render({
						elem: "#LAY_'.$v['field'].'_upload" //绑定元素
						,url: "'.U('Common/uploads').'" //上传接口
						,multiple: true
						,accept:"file"
						,exts: "'.$this->webconf['fileType'].'"
						,before: function(obj){ 		
							layer.load(); //上传loading
						  }
						,done: function(res){
							layer.closeAll("loading"); //关闭loading
							if(res.code==0){
							var fileurl = $("#'.$v['field'].'").val();
							$(".preview_'.$v['field'].'").append(\'<span class="upload-icon-img" ><div class="upload-pre-item"><i class="layui-icon layui-icon-file"></i><input type="text" value="/\'+res.url+\'" name="'.$v['field'].'_urls[]" class="layui-input delete_file" /><i   class="layui-icon">&#xe640;</i></div></span>\');
								
							}else{
								 layer.alert(res.error, {icon: 5});
							}
						}
						,error: function(){
						  //请求异常回调
						  layer.alert("上传异常！");
						}
					  });
					});
				</script>';
				break;
				case 13:
				//tid,field
				$l .= '<div class="layui-form-item">
                    <label for="'.$v['field'].'" class="layui-form-label">
                        <span class="x-red">*</span>'.$v['fieldname'].'  
                    </label>
                    <div class="layui-input-inline">
						<select name="'.$v['field'].'" id="'.$v['field'].'" >';
						$body = explode(',',$v['body']);
				$biaoshi = M('molds')->getField(['id'=>$body[0]],'biaoshi');
				$datalist = M($biaoshi)->findAll();
				foreach($datalist as $vv){
					$l.='<option value="'.$vv['id'].'" ';
					if($data[$v['field']]==$vv['id']){
						$l.='selected="selected"';
					}
					$l.='>'.$vv[$body[1]].'</option>';
				}
					$l.=  '</select>
                    </div>
					<div class="layui-form-mid layui-word-aux">
					  '.$v['tips'].'
					</div>
                </div><script>
							layui.use("form", function () {
								var form_'.$v['field'].' = layui.form;
								form_'.$v['field'].'.render();
							});
							 
						</script>';
				break;
			}
			
		}
		$l.='<script>
		$(function(){
			$(document).on("click",".delete_file",function(){
				//删除图片信息
				$(this).parent().remove();
			})
		})

		function deleteImage_auto(elm,field){
			$(elm).prev().attr("src", "/A/t/tpl/style/images/nopic.jpg");
			$("#"+field).val("");
		}
		</script>';
		
		//echo $l;
		JsonReturn(['code'=>0,'fields_list'=>$fields_list,'tpl'=>$l]);
	}
	
	function jizhi(){
		$this->display($this->template.'/404');
	}
	function error($msg){
		$this->display($this->template.'/404');
	}

}