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
	function checklogin(){
		if(!$this->islogin){
			if($this->frparam('ajax')){
				JsonReturn(['code'=>1,'msg'=>'您还未登录，请重新登录！']);
			}
			Redirect(U('login/index'));
		}
		
	}
	
	function multiuploads(){
		$file = $this->frparam('filename',1);
		if(!$file){
			$file = 'file';
		}
	    //检测是否允许前台上传文件
	    if(!$this->webconf['isopenhomeupload']){
		   $data['error'] =  "Error: 已关闭前台上传文件功能";
		   $data['code'] = 1004;
		   JsonReturn($data);
	    }
		foreach($_FILES[$file]['name'] as $k=>$v){
			$pix = explode('.',$v);
		    $pix = end($pix);
		    $fileType = webConf('fileType');
			if(strpos($fileType,strtolower($pix))===false){
				$data['error'] =  "Error: 文件类型不允许上传！";
				$data['code'] = 1002;
				JsonReturn($data);
			}
			$fileSize = (int)webConf('fileSize');
			if($fileSize!=0 && $_FILES[$file]["size"][$k]/(1024*1024)>$fileSize){
				$data['error'] =  "Error: 文件大小超过网站内部限制！";
				$data['code'] = 1003;
				JsonReturn($data);
			}
		 
			$filename[]='Public/Home/'.date('Ymd').rand(1000,9999).'.'.$pix; //定义文件名 
		}

		$response = array();
		foreach ($_FILES[$file]['tmp_name'] as $k=>$v){
			if(move_uploaded_file($v, $filename[$k])){
				
				if(isset($_SESSION['member'])){
					$userid = $_SESSION['member']['id'];
				}else{
					$userid = 0;
				}
				$filesize = round(filesize(APP_PATH.$filename[$k])/1024,2);
				M('pictures')->add(['litpic'=>'/'.$filename[$k],'addtime'=>time(),'userid'=>$userid,'size'=>$filesize,'tid'=>$this->frparam('tid',0,0),'molds'=>$this->frparam('molds',1,null),'path'=>'Home']);
				$response[] = $filename[$k];

			}else{  
				$data['error'] =  "Error: 请检查目录[Public/Home]写入权限";
				$data['code'] = 1001;
				JsonReturn($data,true);
			} 
		}
		$data = ['code'=>0,'urls'=>$response,'msg'=>'上传成功！'];
		JsonReturn($data,true);


		
	}

	function uploads(){
		$file = $this->frparam('filename',1);
		if(!$file){
			$file = 'file';
		}
		if ($_FILES[$file]["error"] > 0){
		  $data['error'] =  "Error: 上传错误！";
		  $data['code'] = 1000;
		}else{
		  // echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		  // echo "Type: " . $_FILES["file"]["type"] . "<br />";
		  // echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		  // echo "Stored in: " . $_FILES["file"]["tmp_name"];
		  $pix = explode('.',$_FILES[$file]['name']);
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
			if($fileSize!=0 && $_FILES[$file]["size"]/(1024*1024)>$fileSize){
				$data['error'] =  "Error: 文件大小超过网站内部限制！";
				$data['code'] = 1003;
				JsonReturn($data);
			}
		  	
		  
		    $filename =  'Public/Home/'.date('Ymd').rand(1000,9999).'.'.$pix;
		  
			if(move_uploaded_file($_FILES[$file]['tmp_name'],$filename)){
				$data['url'] = $filename;
				$data['code'] = 0;
				if(isset($_SESSION['member'])){
					$userid = $_SESSION['member']['id'];
				}else{
					$userid = 0;
				}
				$filesize = round(filesize(APP_PATH.$filename)/1024,2);
				M('pictures')->add(['litpic'=>'/'.$filename,'addtime'=>time(),'userid'=>$userid,'size'=>$filesize,'tid'=>$this->frparam('tid',0,0),'molds'=>$this->frparam('molds',1,null),'path'=>'Home']);
				
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
		
		error_reporting(E_ALL^E_NOTICE);
		$molds = strtolower($this->frparam('molds',1,'message'));
		if($molds!='message'){
			$this->checklogin();
		}
		$tid = $this->frparam('tid');
		$sql = array();
		if($tid!=0){
			$sql[] = " tids like '%,".$tid.",%' "; 
		}
		
		if(!M('molds')->find(['biaoshi'=>$molds])){
			JsonReturn(['code'=>1,'msg'=>'参数错误！']);
		}
		if(in_array($molds,['level','level_group','member_group','plugins','sysconfig','ruler','molds','power','hook','layout','fields','comment','classtype'])){
			JsonReturn(['code'=>1,'msg'=>'参数错误！']);
		}
		$id = $this->frparam('id');
		if($id){
			$data = M($molds)->find(array('id'=>$id));
		}else{
			$data = array();
		}
		$sql[] = " molds = '".$molds."' and isshow=1 ";
		$sql = implode(' and ',$sql);
		$fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
		$l = '';
		$rd = time();
		if($molds=='article'){
		$l .= '<div class="form-control">
            <label for="">文章标题：</label>
            <input type="text" name="title" id="title" value="'.$data['title'].'" placeholder="请输入文章标题">
            <label>[必填]</label>
        </div>
		<div class="form-control">
            <label for="">关键词：</label>
            <input type="text" name="keywords" id="keywords" value="'.$data['keywords'].'" placeholder="请输入关键词">
            <label>用逗号（ , ）分隔</label>
        </div>
        <div class="form-control">
            <label for="">文章主图：</label>
              <span class="view_img_litpic">';
            if($data['litpic']){
            $l .= '<img src="'.$data['litpic'].'" height="100"  />';
            }
            $l .= '</span><br/>
              <input name="litpic" type="hidden" id="file_url_litpic" value="'.$data['litpic'].'" /><br/>
              <input type="file" class="upload_input_litpic" name="file_litpic" id="upload_input_litpic">
        </div>
		<script type="text/javascript">
			$(document).ready(function(){
			  $("#upload_input_litpic").change(function(){
			    var form=document.getElementById("jizhiform");
			    var data = new FormData(form);
			    data.append("filename",$(this).attr("name"));
			    $.ajax({
			       url: "'.U('common/uploads').'",//处理图片的文件路径
			       type: "POST",//传输方式
			       data: data,
			       dataType:"json",   //返回格式为json
			       processData: false,  // 告诉jQuery不要去处理发送的数据
			       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
			       success: function(response){
			        if(response.code==0){
			          var result = "";
			          result +=\'<img src="/\' + response["url"] + \'" height="100"  />\';
			          $(".view_img_litpic").html(result);
			          $("#file_url_litpic").val("/"+response["url"]);
			        }else{
			          alert(response.error);
			        }
			        
			       }
			    });
			  });
			});
		</script>
        <div class="form-control">
            <label for="">文章简介：</label>
            <textarea name="description" id="description" placeholder="请输入简介">'.$data['description'].'</textarea>
            <label>150字以内</label>
        </div>
        <div class="form-control">
            <label for="">文章内容：</label>
            <div class="layui-input-block" style="width:100%;">
			<script id="body'.$rd.'" name="body" type="text/plain" style="width:100%;height:400px;">'.$data['body'].'</script>
				
			</div>
            <label>1500字以内</label>
        </div>
        <script type="text/javascript">
			$(document).ready(function(){
			var ue_body'.$rd.' = UE.getEditor("body'.$rd.'",{
				toolbars:[["undo", "redo", "|","paragraph","bold", "italic", "blockquote", "insertparagraph", "justifyleft", "justifycenter", "justifyright","justifyjustify","|","indent", "insertorderedlist", "insertunorderedlist","|", "insertimage", "inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol","mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols", "|","drafts", "|","fullscreen"]]
				});
			});
		</script>';
		}else if($molds=='product'){
			$l .= '<div class="form-control">
            <label for="">商品名称：</label>
            <input type="text" name="title" id="title" value="'.$data['title'].'" placeholder="请输入商品名称">
            <label>[必填]</label>
        </div>
		<div class="form-control">
            <label for="">关键词：</label>
            <input type="text" name="keywords" id="keywords" value="'.$data['keywords'].'" placeholder="请输入关键词">
            <label>用逗号（ , ）分隔</label>
        </div>
        <div class="form-control">
            <label for="">商品主图：</label>
              <span class="view_img_litpic">';
            if($data['litpic']){
            $l .= '<img src="'.$data['litpic'].'" height="100"  />';
            }
            $l .= '</span><br/>
              <input name="litpic" type="hidden" id="file_url_litpic" value="'.$data['litpic'].'" /><br/>
              <input type="file" class="upload_input_litpic" name="file_litpic" id="upload_input_litpic">
        </div>
		<script type="text/javascript">
			$(document).on("change","#upload_input_litpic",function(){
			    var form=document.getElementById("jizhiform");
			    var data =new FormData(form);
			    data.append("filename",$(this).attr("name"));
			    $.ajax({
			       url: "'.U('common/uploads').'",//处理图片的文件路径
			       type: "POST",//传输方式
			       data: data,
			       dataType:"json",   //返回格式为json
			       processData: false,  // 告诉jQuery不要去处理发送的数据
			       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
			       success: function(response){
			        if(response.code==0){
			          var result = "";
			          result +=\'<img src="/\' + response["url"] + \'" height="100"  />\';
			          $(".view_img_litpic").html(result);
			          $("#file_url_litpic").val("/"+response["url"]);
			        }else{
			          alert(response.error);
			        }
			        
			       }
			    });
			});
		</script>
		<div class="form-control">
            <label for="">商品图集：</label>
              <span class="view_img_pictures">';
            if($data['pictures']){
            	foreach(explode('||',$data['pictures']) as $v){
            		if($v!=''){
            			 $l .= '<span><img src="'.$v.'" height="100"  /><input name="pictures_url[]" type="text" value="'.$v.'"><span onclick="deleteImage_auto(this)">删除</span></span>';
            		}
            	}
           
            }
            $l .= '</span><br/>
              <input name="pictures" type="hidden" id="pictures" value="'.$data['pictures'].'" /><br/>
              <input type="file" class="upload_input_pictures" file-name="file_pictures" name="file_pictures[]" multiple="multiple" id="upload_input_pictures">
        </div>
		<script type="text/javascript">
			 $(document).on("change","#upload_input_pictures",function(){
			    var form=document.getElementById("jizhiform");
			    var data =new FormData(form);
			    data.append("filename",$(this).attr("file-name"));
			    $.ajax({
			       url: "'.U('common/multiuploads').'",//处理图片的文件路径
			       type: "POST",//传输方式
			       data: data,
			       dataType:"json",   //返回格式为json
			       processData: false,  // 告诉jQuery不要去处理发送的数据
			       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
			       success: function(response){
			       	console.log(response);
			        if(response.code==0){
			          var result = "";
			          for(var i=0;i<response["urls"].length;i++){
			          	 result +=\'<span><img src="/\' + response["urls"][i] + \'" height="100"  /><input name="pictures_urls[]" type="text" value="/\' + response["urls"][i] + \'" ><span onclick="deleteImage_auto(this)">删除</span></span>\';
			             	
			          }
			          $(".view_img_pictures").append(result);
			         
			        }else{
			          alert(response.error);
			        }
			        
			       }
			    });
			  });
		</script>
        <div class="form-control">
            <label for="">商品简介：</label>
            <textarea name="description" id="description" placeholder="请输入简介">'.$data['description'].'</textarea>
            <label>150字以内</label>
        </div>
        <div class="form-control">
            <label for="">商品详情：</label>
            <div class="layui-input-block" style="width:100%;">
			<script id="body'.$rd.'" name="body" type="text/plain" style="width:100%;height:400px;">'.$data['body'].'</script>
			</div>
            <label>1500字以内</label>
        </div>
        <script type="text/javascript">
			$(document).ready(function(){
			var ue_body'.$rd.' = UE.getEditor("body'.$rd.'",{
				toolbars:[["undo", "redo", "|","paragraph","bold", "italic", "blockquote", "insertparagraph", "justifyleft", "justifycenter", "justifyright","justifyjustify","|","indent", "insertorderedlist", "insertunorderedlist","|", "insertimage", "inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol","mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols", "|","drafts", "|","fullscreen"]]
				});
			});
		</script>
          ';
		}

		foreach($fields_list as $k=>$v){
			if(!array_key_exists($v['field'],$data)){
				//使用默认值
				$data[$v['field']] = $v['vdata'];
			}
			if($v['ismust']==1){
				$must = '[必填]';
			}else{
				$must = '';
			}
			switch($v['fieldtype']){
				case 1:
				case 14:
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <input type="text" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'">
		            <label>'.$must.$v['tips'].'</label>
		        </div>';
				break;
				case 2:
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <textarea id="'.$v['field'].'"  name="'.$v['field'].'" >'.$data[$v['field']].'</textarea>
		            <label>'.$must.$v['tips'].'</label>
		        </div>';
				break;
				case 3:
				$rd = time();
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <div class="layui-input-block" style="width:100%;">
					<script id="body'.$rd.'" name="body" type="text/plain" style="width:100%;height:400px;">'.$data[$v['field']].'</script>
						
					</div>
		            <label>'.$must.$v['tips'].'</label>
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
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <input type="number" id="'.$v['field'].'" value="'.$data[$v['field']].'" name="'.$v['field'].'">
		            <label>'.$must.$v['tips'].'</label>
		        </div>';
				break;
				case 11:
				$laydate = ($data[$v['field']]=='' || $data[$v['field']]==0)?time():$data[$v['field']];
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <input type="date" id="'.$v['field'].'" value="'.date('Y-m-d H:i:s',$laydate).'" name="'.$v['field'].'">
		            <label>'.$must.$v['tips'].'</label>
		        </div>';
				break;
				case 5:
				$l .= '<div class="form-control">
		              <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		              <span class="view_img_'.$v['field'].'">';
		            if($data[$v['field']]){
		            $l .= '<img src="'.$data[$v['field']].'" height="100"  />';
		            }
		            $l .= '</span><br/>
		              <input name="'.$v['field'].'" type="hidden" id="file_url_'.$v['field'].'" value="'.$data[$v['field']].'" /><br/>
		              <input type="file" class="upload_input_'.$v['field'].'" name="file_'.$v['field'].'" id="upload_input_'.$v['field'].'">
		        </div>
				<script type="text/javascript">
					
				  $(document).on("change","#upload_input_'.$v['field'].'",function(){
				    var form=document.getElementById("jizhiform");
				    var data =new FormData(form);
				    data.append("filename",$(this).attr("name"));
				    $.ajax({
				       url: "'.U('common/uploads').'",//处理图片的文件路径
				       type: "POST",//传输方式
				       data: data,
				       dataType:"json",   //返回格式为json
				       processData: false,  // 告诉jQuery不要去处理发送的数据
				       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
				       success: function(response){
				        if(response.code==0){
				          var result = "";
				          result +=\'<img src="/\' + response["url"] + \'" height="100"  />\';
				          $(".view_img_'.$v['field'].'").html(result);
				          $("#file_url_'.$v['field'].'").val("/"+response["url"]);
				        }else{
				          alert(response.error);
				        }
				        
				       }
				    });
				  });
					
				</script>';
				break;
				case 6:
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <span class="view_img_'.$v['field'].'">';
		            if($data[$v['field']]){
		            	foreach(explode('||',$data[$v['field']]) as $v){
		            		if($v!=''){
		            			 $l .= '<span><img src="'.$v.'" height="100"  /><input name="'.$v['field'].'_url[]" type="text" value="'.$v.'"><span onclick="deleteImage_auto(this)">删除</span></span>';
		            		}
		            	}
		           
		            }
		            $l .= '</span><br/>
		              <input name="'.$v['field'].'" type="hidden" id="'.$v['field'].'" value="'.$data[$v['field']].'" /><br/>
		              <input type="file" class="upload_input_'.$v['field'].'" file-name="file_'.$v['field'].'" name="file_'.$v['field'].'[]" multiple="multiple" id="upload_input_'.$v['field'].'">
		        </div>
				<script type="text/javascript">
					$(document).on("change","#upload_input_'.$v['field'].'",function(){
					    var form=document.getElementById("jizhiform");
					    var data =new FormData(form);
					    data.append("filename",$(this).attr("file-name"));
					    $.ajax({
					       url: "'.U('common/multiuploads').'",//处理图片的文件路径
					       type: "POST",//传输方式
					       data: data,
					       dataType:"json",   //返回格式为json
					       processData: false,  // 告诉jQuery不要去处理发送的数据
					       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
					       success: function(response){
					        if(response.code==0){
					          var result = "";
					          for(var i=0;i<response["urls"].length;i++){
					          	result +=\'<span><img src="/\' + response["urls"][i] + \'" height="100"  /><input name="'.$v['field'].'_urls[]" type="text" value="/\' + response["urls"][i] + \'" ><span onclick="deleteImage_auto(this)">删除</span></span>\';
					          	
					          }
					          $(".view_img_'.$v['field'].'").append(result);	
					         
					        }else{
					          alert(response.error);
					        }
					        
					       }
					    });
					});
				</script>';
				break;
				case 7:
				$l .= '<div class="form-control">
                    <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
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
						<label>'.$must.$v['tips'].'</label>
	                </div>';
				break;
				case 12:
				$l .= '<div class="form-control">
                    <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
                    <div class="check-box">';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<input type="radio" name="'.$v['field'].'" value="'.$s[1].'" title="'.$s[0].'" ';
					if($data[$v['field']]==$s[1]){
						$l.='checked="checked"';
					}
					$l.=' >'.$s[0];
				}
					$l.='</div>
					<label>'.$must.$v['tips'].'</label>
					</div>';
				break;
				case 8:
				$l .= ' <div class="form-control">
					<label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
					<div class="check-box">';
				foreach(explode(',',$v['body']) as $vv){
					$s=explode('=',$vv);
					$l.='<input type="checkbox" title="'.$s[0].'" name="'.$v['field'].'[]" value="'.$s[1].'" ';
					if(strpos($data[$v['field']],','.$s[1].',')!==false){
						$l.='checked="checked"';};
					$l.='>'.$s[0];
				}
				$l 	.= '</div>
					<label>'.$must.$v['tips'].'</label>
					</div>';
				break;
				case 9:
				$l .= '<div class="form-control">
		              <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		              <span class="view_img_'.$v['field'].'">';
		            $l .= '</span><br/>
		              <input name="'.$v['field'].'" type="hidden" id="file_url_'.$v['field'].'" value="'.$data[$v['field']].'" /><br/>
		              <input type="file" class="upload_input_'.$v['field'].'" name="file_'.$v['field'].'" id="upload_input_'.$v['field'].'">
		        </div>
				<script type="text/javascript">
					$(document).on("change","#upload_input_'.$v['field'].'",function(){
					    var form=document.getElementById("jizhiform");
					    var data =new FormData(form);
					    data.append("filename",$(this).attr("name"));
					    $.ajax({
					       url: "'.U('common/uploads').'",//处理图片的文件路径
					       type: "POST",//传输方式
					       data: data,
					       dataType:"json",   //返回格式为json
					       processData: false,  // 告诉jQuery不要去处理发送的数据
					       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
					       success: function(response){
					        if(response.code==0){
					          $("#file_url_'.$v['field'].'").val("/"+response["url"]);
					        }else{
					          alert(response.error);
					        }
					        
					       }
					    });
					});
				</script>';
				break;
				case 10:
				$l .= '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <span class="view_img_'.$v['field'].'">';
		            if($data[$v['field']]){
		            	foreach(explode('||',$data[$v['field']]) as $v){
		            		if($v!=''){
		            			 $l .= '<span><input name="'.$v['field'].'_url[]" type="text" value="'.$v.'"><span onclick="deleteImage_auto(this)">删除</span></span>';
		            		}
		            	}
		           
		            }
		            $l .= '</span><br/>
		              <input name="'.$v['field'].'" type="hidden" id="'.$v['field'].'" value="'.$data[$v['field']].'" /><br/>
		              <input type="file" class="upload_input_'.$v['field'].'" file-name="file_'.$v['field'].'" name="file_'.$v['field'].'[]" multiple="multiple" id="upload_input_'.$v['field'].'">
		        </div>
				<script type="text/javascript">
					$(document).on("change","#upload_input_'.$v['field'].'",function(){
					    var form=document.getElementById("jizhiform");
					    var data =new FormData(form);
					    data.append("filename",$(this).attr("file-name"));
					    $.ajax({
					       url: "'.U('common/multiuploads').'",//处理图片的文件路径
					       type: "POST",//传输方式
					       data: data,
					       dataType:"json",   //返回格式为json
					       processData: false,  // 告诉jQuery不要去处理发送的数据
					       contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
					       success: function(response){
					        if(response.code==0){
					          var result = "";
					          for(var i=0;i<response["urls"].length;i++){
					          	result +=\'<span><input name="'.$v['field'].'_urls[]" type="text" value="/\' + response["urls"][i] + \'" ><span onclick="deleteImage_auto(this)">删除</span></span>\';
					          }
					          $(".view_img_'.$v['field'].'").append(result);
					          
					         
					        }else{
					          alert(response.error);
					        }
					        
					       }
					    });
					  
					});
				</script>';
				break;
				case 13:
				//tid,field
				$l .= '<div class="form-control">
                    <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
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
                    <label>'.$must.$v['tips'].'</label>
                </div>';
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

		function deleteImage_auto(elm){
			$(elm).parent().remove();
		}
		</script>';
		
		//echo $l;
		JsonReturn(['code'=>0,'fields_list'=>$fields_list,'tpl'=>$l]);
	}
	
	function jizhi(){
		$this->display($this->template.'/404');
		exit;
	}
	function error($msg){
		$this->display($this->template.'/404');
		exit;
	}

}