<?php

if(isset($model)){
    $rd = getRandChar(6);
    switch($model){
        case 'article_zdy':
            return '<div class="form-control">
            <label for="">文章内容：</label>
            <div class="layui-input-block" style="width:100%;">
			 <div style="border: 1px solid #ccc;">
			        <div id="editor-toolbar-'.$rd.'" style="border-bottom: 1px solid #ccc;"></div>
                    <div id="editor-text-area-'.$rd.'" style="height: 350px"></div>
                  </div>
                  <textarea id="editor-content-textarea-'.$rd.'" style="display:none" name="body">'.$data['body'].'</textarea>
				
			</div>
            <label>1500字以内</label>
        </div>
        <script type="text/javascript">
			$(document).ready(function (){
    var html = document.getElementById("editor-content-textarea-'.$rd.'").value
     var E_'.$rd.' = window.wangEditor
    // 切换语言
    E_'.$rd.'.i18nChangeLanguage("zh-CN")
    window.editor = E_'.$rd.'.createEditor({
      selector: "#editor-text-area-'.$rd.'",
      html: html,
      mode: "simple",
      config: {
        placeholder: "请输入内容...",
        MENU_CONF: {
            uploadImage: {
                fieldName: "file",
                server: "'.U('common/uploads').'",
                 // 单个文件的最大体积限制，默认为 2M
                maxFileSize: 1 * 1024 * 1024, // 1M
            
                // 最多可上传几个文件，默认为 100
                maxNumberOfFiles: 10,
           
                allowedFileTypes: ["image/*"],
                // 超时时间，默认为 10 秒
                timeout: 30 * 1000, // 5 秒
                 // 单个文件上传成功之后
                //onSuccess(file: File, res: any) {  // TS 语法
                onSuccess(file, res) {          // JS 语法
                    console.log(`${file.name} 上传成功`, res)
                },
            
                // 单个文件上传失败
                //onFailed(file: File, res: any) {   // TS 语法
                onFailed(file, res) {           // JS 语法
                    console.log(`${file.name} 上传失败`, res)
                },
            
                // 上传错误，或者触发 timeout 超时
                //onError(file: File, err: any, res: any) {  // TS 语法
                onError(file, err, res) {               // JS 语法
                    console.log(`${file.name} 上传出错`, err, res)
                },
                //customInsert(res: any, insertFn: InsertFnType) {  // TS 语法
                customInsert(res, insertFn) {                  // JS 语法
                    // res 即服务端的返回结果
                    console.log(res,insertFn)
                    // 从 res 中找到 url alt href ，然后插入图片
                    insertFn(res.url)
                },
          },
            uploadVideo: {
                fieldName: "file",
                server: "'.U('common/uploads').'",
                 // 单个文件的最大体积限制，默认为 2M
                maxFileSize: 10 * 1024 * 1024 * 1024, // 1M
            
                // 最多可上传几个文件，默认为 100
                maxNumberOfFiles: 100,
     
                allowedFileTypes: ["video/*"],
                // 超时时间，默认为 10 秒
                timeout: 60 * 1000, // 5 秒
                 // 单个文件上传成功之后
                //onSuccess(file: File, res: any) {  // TS 语法
                onSuccess(file, res) {          // JS 语法
                    console.log(`${file.name} 上传成功`, res)
                    if(res.code!=0){
                        alert(res.error)
                    }
                },
            
                // 单个文件上传失败
                //onFailed(file: File, res: any) {   // TS 语法
                onFailed(file, res) {           // JS 语法
                    console.log(`${file.name} 上传失败`, res)
                },
            
                // 上传错误，或者触发 timeout 超时
                //onError(file: File, err: any, res: any) {  // TS 语法
                onError(file, err, res) {               // JS 语法
                    console.log(`${file.name} 上传出错`, err, res)
                },
                //customInsert(res: any, insertFn: InsertFnType) {  // TS 语法
                customInsert(res, insertFn) {                  // JS 语法
                    // res 即服务端的返回结果
                    console.log(res,insertFn)
                    // 从 res 中找到 url alt href ，然后插入图片
                    insertFn(res.url)
                },
          },
        },
        onChange(editor) {
          var html = editor.getHtml()
          var num = 0,
          reg = /<p>(&nbsp;|&nbsp;\s+)+<\/p>|<p>(<br>)+<\/p>/g;
          while (num < html.length && html != "")
          {
            num++;
            let k = html.match(reg);
            if (k) {
              html = html.replace(k[0], "");
            }
          } 
          document.getElementById("editor-content-textarea-'.$rd.'").value = html
        }
      }
    })
     class MyMenu'.$rd.' {
      constructor() {
        this.title = "源码"
        this.tag = "button"
        this.sourceActive = false
      }
      getValue(editor) {
        if (this.sourceActive) {
            return editor.getText()
        } else {
            return editor.getHtml()
        }
    
      }
      isActive(editor) {
         return this.sourceActive
      }
      isDisabled(editor) {
        return false // or true
      }
      exec(editor, value) {
            this.sourceActive = !this.sourceActive
            editor.clear()
            E_'.$rd.'.SlateTransforms.setNodes(editor, { type: "paragraph" }, { mode: "highest"})
            var num = 0,
            reg = /<p>(&nbsp;|&nbsp;\s+)+<\/p>|<p>(<br>)+<\/p>/g;
            while (num < value.length && value != "")
            {
              num++;
              let k = value.match(reg);
              if (k) {
                value = value.replace(k[0], "");
              }
            } 
            if (this.isActive()) {
                editor.insertText(value)
            } else {
                editor.dangerouslyInsertHtml(value)
            }
        


      }
    }
    const myMenuConf'.$rd.' = {
      key: "'.$rd.'html",
      factory() {
        return new MyMenu'.$rd.'()
      }
    }
    E_'.$rd.'.Boot.registerMenu(myMenuConf'.$rd.')
    window.toolbar = E_'.$rd.'.createToolbar({
      editor,
      selector: "#editor-toolbar-'.$rd.'",
      config: {
      	insertKeys: {
          index: 0,
          keys: ["'.$rd.'html"],
        }
      }
    })

    
	})
		</script>';

            break;
        case 'product_zdy':
            return '<div class="form-control">
            <label for="">商品详情：</label>
            <div class="layui-input-block" style="width:100%;">
			      <div style="border: 1px solid #ccc;">
			        <div id="editor-toolbar-'.$rd.'" style="border-bottom: 1px solid #ccc;"></div>
                    <div id="editor-text-area-'.$rd.'" style="height: 350px"></div>
                  </div>
                  <textarea id="editor-content-textarea-'.$rd.'" style="display:none" name="body">'.$data['body'].'</textarea>
			</div>
            <label>1500字以内</label>
        </div>
        <script type="text/javascript">
			$(document).ready(function (){
    var html = document.getElementById("editor-content-textarea-'.$rd.'").value
     var E_'.$rd.' = window.wangEditor
    // 切换语言
    E_'.$rd.'.i18nChangeLanguage("zh-CN")
    window.editor = E_'.$rd.'.createEditor({
      selector: "#editor-text-area-'.$rd.'",
      html: html,
      mode: "simple",
      config: {
        placeholder: "请输入内容...",
        MENU_CONF: {
            uploadImage: {
                fieldName: "file",
                server: "'.U('common/uploads').'",
                 // 单个文件的最大体积限制，默认为 2M
                maxFileSize: 1 * 1024 * 1024, // 1M
            
                // 最多可上传几个文件，默认为 100
                maxNumberOfFiles: 10,
           
                allowedFileTypes: ["image/*"],
                // 超时时间，默认为 10 秒
                timeout: 30 * 1000, // 5 秒
                 // 单个文件上传成功之后
                //onSuccess(file: File, res: any) {  // TS 语法
                onSuccess(file, res) {          // JS 语法
                    console.log(`${file.name} 上传成功`, res)
                    if(res.code!=0){
                        alert(res.error)
                    }
                },
            
                // 单个文件上传失败
                //onFailed(file: File, res: any) {   // TS 语法
                onFailed(file, res) {           // JS 语法
                    console.log(`${file.name} 上传失败`, res)
                },
            
                // 上传错误，或者触发 timeout 超时
                //onError(file: File, err: any, res: any) {  // TS 语法
                onError(file, err, res) {               // JS 语法
                    console.log(`${file.name} 上传出错`, err, res)
                },
                //customInsert(res: any, insertFn: InsertFnType) {  // TS 语法
                customInsert(res, insertFn) {                  // JS 语法
                    // res 即服务端的返回结果
                    console.log(res,insertFn)
                    // 从 res 中找到 url alt href ，然后插入图片
                    insertFn(res.url)
                },
          },
            uploadVideo: {
                fieldName: "file",
                server: "'.U('common/uploads').'",
                 // 单个文件的最大体积限制，默认为 2M
                maxFileSize: 10 * 1024 * 1024 * 1024, // 1M
            
                // 最多可上传几个文件，默认为 100
                maxNumberOfFiles: 100,
     
                allowedFileTypes: ["video/*"],
                // 超时时间，默认为 10 秒
                timeout: 60 * 1000, // 5 秒
                 // 单个文件上传成功之后
                //onSuccess(file: File, res: any) {  // TS 语法
                onSuccess(file, res) {          // JS 语法
                    console.log(`${file.name} 上传成功`, res)
                    if(res.code!=0){
                        alert(res.error)
                    }
                },
            
                // 单个文件上传失败
                //onFailed(file: File, res: any) {   // TS 语法
                onFailed(file, res) {           // JS 语法
                    console.log(`${file.name} 上传失败`, res)
                },
            
                // 上传错误，或者触发 timeout 超时
                //onError(file: File, err: any, res: any) {  // TS 语法
                onError(file, err, res) {               // JS 语法
                    console.log(`${file.name} 上传出错`, err, res)
                },
                //customInsert(res: any, insertFn: InsertFnType) {  // TS 语法
                customInsert(res, insertFn) {                  // JS 语法
                    // res 即服务端的返回结果
                    console.log(res,insertFn)
                    // 从 res 中找到 url alt href ，然后插入图片
                    insertFn(res.url)
                },
          },
        },
        onChange(editor) {
          var html = editor.getHtml()
          var num = 0,
          reg = /<p>(&nbsp;|&nbsp;\s+)+<\/p>|<p>(<br>)+<\/p>/g;
          while (num < html.length && html != "")
          {
            num++;
            let k = html.match(reg);
            if (k) {
              html = html.replace(k[0], "");
            }
          } 
          document.getElementById("editor-content-textarea-'.$rd.'").value = html
        }
      }
    })
     class MyMenu'.$rd.' {
      constructor() {
        this.title = "源码"
        this.tag = "button"
        this.sourceActive = false
      }
      getValue(editor) {
        if (this.sourceActive) {
            return editor.getText()
        } else {
            return editor.getHtml()
        }
    
      }
      isActive(editor) {
         return this.sourceActive
      }
      isDisabled(editor) {
        return false // or true
      }
      exec(editor, value) {
            this.sourceActive = !this.sourceActive
            editor.clear()
            E_'.$rd.'.SlateTransforms.setNodes(editor, { type: "paragraph" }, { mode: "highest"})
            var num = 0,
            reg = /<p>(&nbsp;|&nbsp;\s+)+<\/p>|<p>(<br>)+<\/p>/g;
            while (num < value.length && value != "")
            {
              num++;
              let k = value.match(reg);
              if (k) {
                value = value.replace(k[0], "");
              }
            } 
            if (this.isActive()) {
                editor.insertText(value)
            } else {
                editor.dangerouslyInsertHtml(value)
            }
        


      }
    }
    const myMenuConf'.$rd.' = {
      key: "'.$rd.'html",
      factory() {
        return new MyMenu'.$rd.'()
      }
    }
    E_'.$rd.'.Boot.registerMenu(myMenuConf'.$rd.')
    window.toolbar = E_'.$rd.'.createToolbar({
      editor,
      selector: "#editor-toolbar-'.$rd.'",
      config: {
      	insertKeys: {
          index: 0,
          keys: ["'.$rd.'html"],
        }
      }
    })

    
	})
		</script>';
            break;
        default:
            return '<div class="form-control">
		            <label for="'.$v['field'].'">'.$v['fieldname'].'：</label>
		            <div class="layui-input-block" style="width:100%;">
		            
		                <div style="border: 1px solid #ccc;">
                            <div id="editor-toolbar-'.$v['field'].$rd.'" style="border-bottom: 1px solid #ccc;"></div>
                            <div id="editor-text-area-'.$v['field'].$rd.'" style="height: 350px"></div>
                        </div>
                        <textarea id="editor-content-textarea-'.$v['field'].$rd.'" style="display:none" name="'.$v['field'].'">'.$data[$v['field']].'</textarea>
		            
					</div>
		            <label  class="fields_tips">'.$must.$v['tips'].'</label>
		        </div><script>
						    
	$(document).ready(function (){
    var html = document.getElementById("editor-content-textarea-'.$v['field'].$rd.'").value
     var E_'.$v['field'].$rd.' = window.wangEditor
    // 切换语言
    E_'.$v['field'].$rd.'.i18nChangeLanguage("zh-CN")
    window.editor = E_'.$v['field'].$rd.'.createEditor({
      selector: "#editor-text-area-'.$v['field'].$rd.'",
      html: html,
      config: {
        placeholder: "请输入内容...",
        MENU_CONF: {
            uploadImage: {
                fieldName: "file",
                server: "'.U('common/uploads').'",
                 // 单个文件的最大体积限制，默认为 2M
                maxFileSize: 1 * 1024 * 1024, // 1M
            
                // 最多可上传几个文件，默认为 100
                maxNumberOfFiles: 10,
           
                allowedFileTypes: ["image/*"],
                // 超时时间，默认为 10 秒
                timeout: 30 * 1000, // 5 秒
                 // 单个文件上传成功之后
                //onSuccess(file: File, res: any) {  // TS 语法
                onSuccess(file, res) {          // JS 语法
                    console.log(`${file.name} 上传成功`, res)
                    if(res.code!=0){
                        alert(res.error)
                    }
                },
            
                // 单个文件上传失败
                //onFailed(file: File, res: any) {   // TS 语法
                onFailed(file, res) {           // JS 语法
                    console.log(`${file.name} 上传失败`, res)
                },
            
                // 上传错误，或者触发 timeout 超时
                //onError(file: File, err: any, res: any) {  // TS 语法
                onError(file, err, res) {               // JS 语法
                    console.log(`${file.name} 上传出错`, err, res)
                },
                //customInsert(res: any, insertFn: InsertFnType) {  // TS 语法
                customInsert(res, insertFn) {                  // JS 语法
                    // res 即服务端的返回结果
                    console.log(res,insertFn)
                    // 从 res 中找到 url alt href ，然后插入图片
                    insertFn(res.url)
                },
          },
            uploadVideo: {
                fieldName: "file",
                server: "'.U('common/uploads').'",
                 // 单个文件的最大体积限制，默认为 2M
                maxFileSize: 10 * 1024 * 1024 * 1024, // 1M
            
                // 最多可上传几个文件，默认为 100
                maxNumberOfFiles: 100,
     
                allowedFileTypes: ["video/*"],
                // 超时时间，默认为 10 秒
                timeout: 60 * 1000, // 5 秒
                 // 单个文件上传成功之后
                //onSuccess(file: File, res: any) {  // TS 语法
                onSuccess(file, res) {          // JS 语法
                    console.log(`${file.name} 上传成功`, res)
                    if(res.code!=0){
                        alert(res.error)
                    }
                },
            
                // 单个文件上传失败
                //onFailed(file: File, res: any) {   // TS 语法
                onFailed(file, res) {           // JS 语法
                    console.log(`${file.name} 上传失败`, res)
                },
            
                // 上传错误，或者触发 timeout 超时
                //onError(file: File, err: any, res: any) {  // TS 语法
                onError(file, err, res) {               // JS 语法
                    console.log(`${file.name} 上传出错`, err, res)
                },
                //customInsert(res: any, insertFn: InsertFnType) {  // TS 语法
                customInsert(res, insertFn) {                  // JS 语法
                    // res 即服务端的返回结果
                    console.log(res,insertFn)
                    // 从 res 中找到 url alt href ，然后插入图片
                    insertFn(res.url)
                },
          },
        },
        onChange(editor) {
          var html = editor.getHtml()
          var num = 0,
          reg = /<p>(&nbsp;|&nbsp;\s+)+<\/p>|<p>(<br>)+<\/p>/g;
          while (num < html.length && html != "")
          {
            num++;
            let k = html.match(reg);
            if (k) {
              html = html.replace(k[0], "");
            }
          } 
          document.getElementById("editor-content-textarea-'.$v['field'].$rd.'").value = html
        }
      }
    })
    class MyMenu'.$v['field'].$rd.' {
      constructor() {
        this.title = "源码"
        this.tag = "button"
        this.sourceActive = false
      }
      getValue(editor) {
        if (this.sourceActive) {
            return editor.getText()
        } else {
            return editor.getHtml()
        }
    
      }
      isActive(editor) {
         return this.sourceActive
      }
      isDisabled(editor) {
        return false // or true
      }
      exec(editor, value) {
            this.sourceActive = !this.sourceActive
            editor.clear()
            E_'.$v['field'].$rd.'.SlateTransforms.setNodes(editor, { type: "paragraph" }, { mode: "highest"})
            var num = 0,
            reg = /<p>(&nbsp;|&nbsp;\s+)+<\/p>|<p>(<br>)+<\/p>/g;
            while (num < value.length && value != "")
            {
              num++;
              let k = value.match(reg);
              if (k) {
                value = value.replace(k[0], "");
              }
            } 
            if (this.isActive()) {
                editor.insertText(value)
            } else {
                editor.dangerouslyInsertHtml(value)
            }
        


      }
    }
    const myMenuConf'.$v['field'].$rd.' = {
      key: "'.$v['field'].$rd.'html",
      factory() {
        return new MyMenu'.$v['field'].$rd.'()
      }
    }
    E_'.$v['field'].$rd.'.Boot.registerMenu(myMenuConf'.$v['field'].$rd.')
    window.toolbar = E_'.$v['field'].$rd.'.createToolbar({
      editor,
      selector: "#editor-toolbar-'.$v['field'].$rd.'",
      config: {
      	insertKeys: {
          index: 0,
          keys: ["'.$v['field'].$rd.'html"],
        }
      }
    })
    

    
	})
						</script>';
            break;

    }
}


