<?php

$rd = getRandChar(6);	
if(APP_CONTROLLER=='Sys'){
	return '<div class="layui-collapse">
		  <div class="layui-colla-item">
			<h2 class="layui-colla-title">'.$v['title'].'</h2>
			<div class="layui-colla-content">
				<div  style="width:100%;">
					<script id="'.$v['field'].$rd.'" name="'.$v['field'].'" type="text/plain" style="width:100%;height:400px;">'.$v['data'].'</script>	
				</div>
			</div>
		</div>
	</div>
	<script>$(document).ready(function(){
			var ue_'.$v['field'].$rd.' = UE.getEditor("'.$v['field'].$rd.'",{
				toolbars : [['.$config['ueditor_config'].']]
				}		
			);	
			});
		   </script>';
}else{
	return '<div class="layui-form-item layui-form-text">
			<label for="'.$v['field'].'" class="layui-form-label">
				<span class="x-red">*</span>'.$v['fieldname'].'
			</label>
			<div class="layui-input-block" style="width:100%;">
			<script id="'.$v['field'].$rd.'" name="'.$v['field'].'" type="text/plain" style="width:100%;height:400px;">'.$data[$v['field']].'</script>
				
			</div>
			</div>
			<script>
			$(document).ready(function(){
			var ue_'.$v['field'].$rd.' = UE.getEditor("'.$v['field'].$rd.'",{
				toolbars : [['.$this->webconf['ueditor_config'].']]
				}		
			);	
			});
			</script>';

}

