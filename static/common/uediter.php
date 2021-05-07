<?php

if(isset($model)){
	$rd = getRandChar(6);
	switch($model){
		case 'article_zdy':
		return '<div class="form-control">
            <label for="">文章内容：</label>
            <div class="layui-input-block" style="width:100%;">
			<script id="body'.$rd.'" name="body" type="text/plain" style="width:100%;height:400px;">'.$data['body'].'</script>
				
			</div>
            <label>1500字以内</label>
        </div>
        <script type="text/javascript">
			$(document).ready(function(){
			var ue_body'.$rd.' = UE.getEditor("body'.$rd.'",{
				toolbars:[["undo", "redo", "|","paragraph","bold","forecolor","fontfamily","fontsize", "italic", "blockquote", "insertparagraph", "justifyleft", "justifycenter", "justifyright","justifyjustify","|","indent", "insertorderedlist", "insertunorderedlist","|", "insertimage", "inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol","mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols", "|","drafts", "|","fullscreen"]]
				});
			});
		</script>';
		
		break;
		case 'product_zdy':
		return '<div class="form-control">
            <label for="">商品详情：</label>
            <div class="layui-input-block" style="width:100%;">
			<script id="body'.$rd.'" name="body" type="text/plain" style="width:100%;height:400px;">'.$data['body'].'</script>
			</div>
            <label>1500字以内</label>
        </div>
        <script type="text/javascript">
			$(document).ready(function(){
			var ue_body'.$rd.' = UE.getEditor("body'.$rd.'",{
				toolbars:[["undo", "redo", "|","paragraph","bold","forecolor","fontfamily","fontsize", "italic", "blockquote", "insertparagraph", "justifyleft", "justifycenter", "justifyright","justifyjustify","|","indent", "insertorderedlist", "insertunorderedlist","|", "insertimage", "inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol","mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols", "|","drafts", "|","fullscreen"]]
				});
			});
		</script>';
		break;
		default:
		return '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <div class="layui-input-block" style="width:100%;">
					<script id="'.$v['field'].$rd.'" name="'.$v['field'].'" type="text/plain" style="width:100%;height:400px;">'.$data[$v['field']].'</script>	
					</div>
		            <label>'.$must.$v['tips'].'</label>
		        </div><script>
						$(document).ready(function(){
						var ue_'.$v['field'].$rd.' = UE.getEditor("'.$v['field'].$rd.'",{
							toolbars : [['.$this->webconf['ueditor_config'].']]
							}		
						);	
						});
						</script>';	
		break;
		
	}
}


