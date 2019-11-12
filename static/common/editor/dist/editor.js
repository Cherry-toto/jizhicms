/*
// jQuery Ajax File Uploader
//
// @author: Jordan Feldstein <jfeldstein.com>
//
//  - Ajaxifies an individual <input type="file">
//  - Files are sandboxed. Doesn't matter how many, or where they are, on the page.
//  - Allows for extra parameters to be included with the file
//  - onStart callback can cancel the upload by returning false
*/
jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();
(function($) {
    $.fn.ajaxfileupload = function(options) {
        var settings = {
            params: {},
            action: '',
            sizes: 5120,
            onStart: function() {
                console.log('starting upload');
                console.log(this);
            },
            onComplete: function(response) {
                console.log('got response: ');
                console.log(response);
                console.log(this);
            },
            onCancel: function() {
                console.log('cancelling: ');
                console.log(this);
            },
            validate_extensions: true,
            valid_extensions: ['gif', 'png', 'jpg', 'jpeg', 'bmp'],
            submit_button: null
        };

        var uploading_file = false;

        if (options) {
            $.extend(settings, options);
        }


        // 'this' is a jQuery collection of one or more (hopefully)
        //  file elements, but doesn't check for this yet
        return this.each(function() {
            var $element = $(this);

            // Skip elements that are already setup. May replace this
            //  with uninit() later, to allow updating that settings
            if ($element.data('ajaxUploader-setup') === true) {
                return;
            }

            $element.change(function() {
                // since a new image was selected, reset the marker
                uploading_file = false;

                // only update the file from here if we haven't assigned a submit button
                if (!settings.submit_button) {
                    upload_file();
                }
            });

            if (settings.submit_button) {
                settings.submit_button.click(function(e) {
                    // Prevent non-AJAXy submit
                    e.preventDefault();

                    // only attempt to upload file if we're not uploading
                    if (!uploading_file) {
                        upload_file();
                    }
                });
            }

            var upload_file = function() {
                if ($element.val() === '') {
                    return settings.onCancel.apply($element, [settings.params]);
                }

                var isIE = /msie/i.test(navigator.userAgent) && !window.opera;

                if (isIE && !$element[0].files) {
                    var filePath = $element[0].value;
                    var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
                    if(!fileSystem.FileExists(filePath)){
                        settings.onComplete.apply($element, [{
                                hasError: true,
                                message: "附件不存在，请重新输入！"
                            },
                            settings.params
                        ]);
                        // alert("附件不存在，请重新输入！");
                        return false;
                    }
                    var file = fileSystem.GetFile(filePath);
                    fileSize = file.Size;
                } else {
                    fileSize = $element[0].files[0].size;
                }

                var size = fileSize / 1024;
                if(size>settings.sizes){
                    settings.onComplete.apply($element, [{
                            hasError: true,
                            message: "附件大小不能大于"+settings.sizes/1024+"M！"
                        },
                        settings.params
                    ]);
                    // alert("附件大小不能大于"+settings.sizes/1024+"M！");
                    $element[0].value ="";
                    return false;
                }
                if(size<=0){
                    settings.onComplete.apply($element, [{
                            hasError: true,
                            message: "附件大小不能为0M！"
                        },
                        settings.params
                    ]);
                    // alert("附件大小不能为0M！");
                    $element[0].value ="";
                    return false;
                }

                // make sure extension is valid
                var ext = $element.val().split('.').pop().toLowerCase();
                if (true === settings.validate_extensions && $.inArray(ext, settings.valid_extensions) === -1) {
                    // Pass back to the user
                    settings.onComplete.apply($element, [{
                            status: false,
                            message: '文件类型错误，只支持 ' + settings.valid_extensions.join(', ') + ' 格式的文件。'
                        },
                        settings.params
                    ]);
//                  $.tips('文件类型错误，只支持 ' + settings.valid_extensions.join(', ') + ' 格式的文件。');
                } else {
                    uploading_file = true;

                    // Creates the form, extra inputs and iframe used to
                    //  submit / upload the file
                    wrapElement($element);

                    // Call user-supplied (or default) onStart(), setting
                    //  it's this context to the file DOM element
                    var ret = settings.onStart.apply($element, [settings.params]);

                    // let onStart have the option to cancel the upload
                    if (ret !== false) {
                        $element.parent('form').submit(function(e) {
                            e.stopPropagation();
                        }).submit();
                    }
                }
            };

            // Mark this element as setup
            $element.data('ajaxUploader-setup', true);

            /*
          // Internal handler that tries to parse the response
          //  and clean up after ourselves.
          */
            var handleResponse = function(loadedFrame, element) {
                var response, responseStr = loadedFrame.contentWindow.document.body.innerText || loadedFrame.contentWindow.document.body.textContent;
                try {
                    //response = $.parseJSON($.trim(responseStr));
                    response = JSON.parse(responseStr);
                } catch (e) {
                    response = responseStr;
                }

                // Tear-down the wrapper form
                element.siblings().remove();
                element.unwrap();

                uploading_file = false;

                // Pass back to the user
                settings.onComplete.apply(element, [response, settings.params]);
            };

            /*
          // Wraps element in a <form> tag, and inserts hidden inputs for each
          //  key:value pair in settings.params so they can be sent along with
          //  the upload. Then, creates an iframe that the whole thing is
          //  uploaded through.
          */
            var wrapElement = function(element) {
                // Create an iframe to submit through, using a semi-unique ID
                var frame_id = 'ajaxUploader-iframe-' + Math.round(new Date().getTime() / 1000);
                $('body').after('<iframe width="0" height="0" style="display:none;" name="' + frame_id + '" id="' + frame_id + '"/>');
                $('#' + frame_id).load(function() {
                    handleResponse(this, element);
                });

                // Wrap it in a form
                element.wrap(function() {
                    return '<form action="' + settings.action + '" method="POST" enctype="multipart/form-data" target="' + frame_id + '" />';
                })
                // Insert <input type='hidden'>'s for each param
                .before(function() {
                    var key, html = '';
                    for (key in settings.params) {
                        var paramVal = settings.params[key];
                        if (typeof paramVal === 'function') {
                            paramVal = paramVal();
                        }
                        html += '<input type="hidden" name="' + key + '" value="' + paramVal + '" />';
                    }
                    return html;
                });
            };

        });
    };
})(jQuery);

var addEvent = (function () {
    if (document.addEventListener) {
        return function (el, type, fn) {
            el.addEventListener(type, fn, false);
        };
    } else {
        return function (el, type, fn) {
            el.attachEvent('on' + type, function () {
                return fn.call(el, window.event);
            });
        }
    }
})();

;(function (name, fun) {
    if(typeof module !== 'undefined' && module.exports) {
        module.exports = fun();
    } else if(typeof define === 'function' && define.amd) {
        define(fun);
    }else {
        this[name] = fun();
    }
})('editor', function () {
    "use strict";
    function editor(containerId, editorId, options) {
        this._container = '#'+containerId;
        this.options = options || {};
        this.init(editorId);
        this._editorId = editorId;
    }

    editor.prototype.init = function(editorId) {
        var str = '<div class="g-editor-container"><div class="header">' +
            '<div class="type">' +
                '<div class="item undo" command="undo">' +
                    '<i class="undo-icon"></i>' +
                    '撤销' +
                '</div>' +
                '<div class="item redo" command="redo">' +
                    '<i class="redo-icon"></i>' +
                    '重做' +
                '</div>' +
            '</div>' +
            '<div class="type">' +
                '<div class="item bold" style="position:relative;" command="bold">' +
                    '<i class="bold-icon"></i>' +
                    '加粗' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
                '<div class="item italic" style="position:relative;" command="italic">' +
                    '<i class="italic-icon"></i>' +
                    '斜体' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
            '</div>' +
            '<div class="type">' +
                '<div class="item font-size-item" style="position:relative;" command="">' +
                    '<i class="size-icon"></i>' +
                    '字体大小' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
                '<div class="item-font-content" style="    width: 250px;">' +
                    '<i class="arrow-top"></i><i class="arrow-bottom"></i>' +
                    '<div class="link-title">' +
                        '字体大小' +
                    '</div><div class="link-item-content" style="    padding: 12px;">' +
                '<select id="font-size-a" style="width: 70%;height: 30px;"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option>'+
                '</select><button id="changefontsize" type="button">确定</button></div></div>' +
            '</div>' +
            '<div class="type">' +
                '<div class="item image" style="position:relative;">' +
                    '<i class="image-icon"></i>' +
                    '插入图片' +
                    '<input type="file" name="file" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
            '</div>' +
            '<div class="type">' +
                '<div class="item title" style="position:relative;" command="formatBlock", params="<H2>">' +
                    '<i class="title-icon"></i>' +
                    '二级标题' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
            '</div>' +
            '<div class="type">' +
                '<div class="item left" style="position:relative;" command="justifyleft">' +
                    '<i class="left-icon"></i>' +
                    '左对齐' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
                '<div class="item center" style="position:relative;" command="justifycenter">' +
                    '<i class="center-icon"></i>' +
                    '居中' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
                '<div class="item right" style="position:relative;" command="justifyright">' +
                    '<i class="right-icon"></i>' +
                    '右对齐' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
                '<div class="item justify" style="position:relative;" command="justifyFull">' +
                    '<i class="justify-icon"></i>' +
                    '两端对齐' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
            '</div>' +
            '<div class="type">' +
                '<div class="item more" style="position:relative;" command="indent">' +
                    '<i class="more-icon"></i>' +
                    '增加缩进' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
                '<div class="item less" style="position:relative;" command="outdent">' +
                    '<i class="less-icon"></i>' +
                    '减少缩进' +
                    '<input type="button" style="position: absolute;left: 0;top: 0;width: 100%;height:100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">' +
                '</div>' +
            '</div>' +
            '<div class="type" style="position:relative;">' +
                '<div class="item link-item" command="">' +
                    '<i class="tolink-icon"></i>' +
                    '插入链接' +
                '</div>' +
                '<div class="item unlink" command="unlink">' +
                    '<i class="unlink-icon"></i>' +
                    '去除链接' +
                '</div>' +
                '<div class="link-content" style="    width: 250px;">' +
                    '<i class="arrow-top"></i><i class="arrow-bottom"></i>' +
                    '<div class="link-title">' +
                        '插入链接' +
                    '</div><div class="link-item-content" style="    padding: 12px;">' +
                '<input id="inserturl" /><button id="intourl" type="button">确定</button></div></div>' +
            '</div>' +
            '<div class="type" style="position:relative;">' +
                '<div class="item copy" command="copy">' +
                    '<i class="copy-icon"></i>' +
                    '复制' +
                '</div>' +
                '<div class="item cut" command="cut">' +
                    '<i class="cut-icon"></i>' +
                    '剪切' +
                '</div>' +
            '</div>' +
             '<div class="type" style="position:relative;">' +
              '<div class="item empty" command="">' +
                    '<i class="clear-icon"></i>' +
                    '清除' +
                '</div>' +
             '</div>' +
        '</div>' +
        '<div class="editor-contains">' +
            '<iframe id="'+ editorId +'" class="editor" frameborder="0"></iframe> ' +
        '</div></div>';
        $(this._container).html(str);

        this._editor = document.getElementById(editorId).contentWindow;
        this._editor.document.designMode="on";
        this._editor.document.contentEditable = true;
        this._editor.document.open();
        this._editor.document.write('<head><style type="text/css">body{font-family:"Hiragino Sans GB", "Microsoft YaHei","微软雅黑", "宋体", Arial,Verdana, sans-serif;font-size:14px;word-wrap: break-word;}.link-flag {position:relative;color:#666666;cursor:pointer;overflow:hidden;}.link-tips {display: none;position: absolute;left: -23px;background: #fff;border: 1px solid #ccc;padding: 20px;top: 25px;white-space: nowrap;z-index:9;}.arrow-top {position:absolute;width:0;height:0;border-color:transparent transparent #cccccc transparent;border-style:dashed dashed solid dashed;border-width:10px;top:-21px;}.arrow-bottom {position:absolute;width:0;height:0;border-color:transparent transparent #f6f6f6 transparent;border-style:dashed dashed solid dashed;border-width:10px;top:-20px;}.upload-img{max-width: 180px;max-height:120px}.placeholder{color:#cccccc;}</style></head><body>'+ this.options.placeholder +'</body>');
        this._editor.document.close();
        this.initHandler();
        var _this = this;
        var lastEditRang = '';
        this._editor.document.onmouseup = function() {
            if(_this._editor.getSelection && _this._editor.getSelection().toString().length >= 1) {
                $('.undo,.redo,.bold,.italic,.image,.title,.link-item,.unlink,.copy,.cut,.empty,.font-size-item', _this._container).addClass('on');
            } else if(!_this._editor.getSelection && _this._editor.document.selection.createRange().text) {
                $('.bold,.italic', _this._container).addClass('on');
            } else {
                $('.bold,.italic', _this._container).removeClass('on');
            }
        };


        addEvent(this._editor,'focus',function(){
            $('.undo,.redo,.image,.title,.left,.center,.justify,.center,.right,.more,.less,.link-item,.unlink,.copy,.cut,.empty,.font-size-item', _this._container).addClass('on');
            if($.trim($(_this._editor.document.body).html()) === _this.options.placeholder) {
                $(_this._editor.document.body).html('');
            }
        });

        // 清除粘贴格式
        addEvent(this._editor,'paste',function(event){
            var selection = _this._editor.getSelection ? _this._editor.getSelection() : _this._editor.document.selection;
            lastEditRang = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
            var $doc = $(_this._editor.document),
                _textArea = '<textarea class="paste-content" style="width: 0px;height: 0px;opacity: 0;filter:alpha(opacity=0);resize: none;"></textarea>';
            $doc.find("body").append(_textArea);
            var $pasteContent = $doc.find('.paste-content');
            $pasteContent.focus();
            setTimeout(function() {
                var html = $pasteContent.val();
                $pasteContent.remove();
                // console.log(html);
                $(_this._editor.document.body).trigger('focus');
                var selection = _this._editor.getSelection ? _this._editor.getSelection() : _this._editor.document.selection;
                var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
                if(lastEditRang) range = lastEditRang;
                // console.log(lastEditRang);
                if (!_this._editor.getSelection) {
                    range.pasteHTML(html);
                    range.select();
                } else {
                    range.collapse(false);
                    var hasR = range.createContextualFragment(html);
                    var hasR_lastChild = hasR.lastChild;
                    while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") {
                        var e = hasR_lastChild;
                        hasR_lastChild = hasR_lastChild.previousSibling;
                        hasR.removeChild(e)
                    }

                    range.insertNode(hasR);
                    if (hasR_lastChild) {
                        range.setEndAfter(hasR_lastChild);
                        range.setStartAfter(hasR_lastChild)
                    }
                    selection.removeAllRanges();
                    selection.addRange(range)
                }
                
            }, 0);
        });

        addEvent(this._editor,'blur',function(){
            if(!$.trim($(_this._editor.document.body).html())) {
                $(_this._editor.document.body).html(_this.options.placeholder);
            }
            if((_this._editor.getSelection && _this._editor.getSelection().toString().length >= 1) || (!_this._editor.getSelection && _this._editor.document.selection.createRange().text)) {
                return;
            }
            $('.undo,.redo,.bold,.italic,.image,.title,.left,.center,.justify,.center,.right,.more,.less,.link-item,.unlink,.copy,.cut,.empty,.font-size-item', _this._container).removeClass('on');
            
        });
    };
    // 让光标聚焦到内容末尾
    editor.prototype.setFocus = function() {
        var _this = this,
            obj = _this._editor.document.body;
        $(_this._editor.document.body).trigger('focus');
        if($.trim(obj.innerHTML)) {
            if(_this._editor.document.getSelection){
                var selection = _this._editor.getSelection ? _this._editor.getSelection() : _this._editor.document.selection;           
                var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
                if (!_this._editor.getSelection) {
                    range.select();
                } 
                else {
                    range.setStartAfter(obj.lastChild, 0);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            }
        }   
    }
    editor.prototype.initHandler = function() {
        var _this = this;
        $(this._container).find('.item').click(function() {
            var command = this.getAttribute('command'),
                params = this.getAttribute('params');
            if(!command) {
                return;
            }
            _this._editor.document.execCommand(command, false, params);
            _this._editor.focus();
        });

        // 插入图片
        $(_this._container).find('input[type="file"]').ajaxfileupload({
            valid_extensions: ['gif', 'png', 'jpg', 'jpeg', 'bmp'],
            action: _this.options.imageUpload,
            onStart: function() {

            },
            onComplete: function(re) {
                console.log(re);
                if(re.code!=0){
                    alert(re.error);return false;
                }
                // 图片预览
                var img = '<img class="upload-img-x" src="/'+ re.url +'" />';
                _this.insertImage(img);

                /**
                 * 重置内容，解决用户选择同一张图的时候无法触发擅长的问题
                 * IE下面通过重置上传按钮解决
                 */
                
                if ($.browser.msie) {
                    $(_this._container).find('input[type="file"]').replaceWith('<input type="file" style="position: absolute;left: 0;top: 0;width: 100%;bottom: 0;opacity: 0;filter:alpha(opacity=0);">');
                    // 重新绑定上传功能
                    _this.uploadCore();
                } else {
                    $(_this._container).find('input[type="file"]').val('');
                }
            }
        });

        // 清除
        $(_this._container).find('.empty').on('click', function() {
            _this._editor.document.body.innerHTML = '';
        });

        $(_this._container).find('.link').hover(function() {
            var str = '';
            $('.J_edit_item_references').find('.J_references_item').each(function(i, v) {
                str += '<button class="link-item" data-link="'+ $(this).find('.J_url').text() +'">' +
                        $(this).find('.J_title').text() +
                        '<br>' +
                        $(this).find('.J_url').text() +
                    '</button>';
            });
            $(_this._container).find('.link-item-content').html(str);
            $(_this._container).find('.link-content').show();
        }, function() {
            $(_this._container).find('.link-content').hide();
        });
        $(_this._container).find('.link-content').hover(function() {
            $(this).show();
        }, function() {
            $(this).hide();
        });

        $(this._editor.document).off('mouseover').on('mouseover', '.link-flag', function() {
            var html = $(this).find('img').data('html');
            $(this).find('.link-tips-content').html(html);
            $(this).find('.link-tips').show();
        });
        $(this._editor.document).on('mouseout', '.link-flag', function() {
            $(this).find('.link-tips').hide();
        });

        // 插入链接
        $(_this._container).on('click', '.link-item', function() {
            $("#inserturl").val('');
            $("#inserturldiv").show();
            var link = $(this).data('link');
          
            $(_this._container).find('.link-content').show();
        });
        $(_this._container).on('click', '#intourl', function() {
            
            var url = $("#inserturl").val();
           _this._editor.document.execCommand('createLink', false, url);
            _this._editor.focus();
          
            $(_this._container).find('.link-content').hide();
        });
         // 设置字体大小
        $(_this._container).on('click', '.font-size-item', function() {
            $(".item-font-content").show();
           
            $(_this._container).find('.link-item-content').show();
        });
        $(_this._container).on('click', '#changefontsize', function() {
            
            var url = $("#font-size-a").val();
           _this._editor.document.execCommand('fontSize', false, url);
            _this._editor.focus();
          
            $(_this._container).find('.item-font-content').hide();
        });


    };

    editor.prototype.insertImage = function(src) {
        if($.trim($(this._editor.document.body).html()) === this.options.placeholder) {
            $(this._editor.document.body).html('');
        }
        this._editor.focus();
        var selection = this._editor.getSelection ? this._editor.getSelection() : this._editor.document.selection;
        var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);
        if (!this._editor.getSelection) {
            range.pasteHTML(src);
            range.collapse(false);
            range.select();
        } else {
            range.collapse(false);
            var hasR = range.createContextualFragment(src);
            var hasLastChild = hasR.lastChild;
            while (hasLastChild && hasLastChild.nodeName.toLowerCase() == "br" && hasLastChild.previousSibling && hasLastChild.previousSibling.nodeName.toLowerCase() == "br") {
                var e = hasLastChild;
                hasLastChild = hasLastChild.previousSibling;
                hasR.removeChild(e);
            }
            range.insertNode(range.createContextualFragment(src));
            if (hasLastChild) {
                range.setEndAfter(hasLastChild);
                range.setStartAfter(hasLastChild);
            }
        }
    };

    editor.prototype.getValue = function() {
        var _htm = $.trim($(this._editor.document.body).html());
        if(_htm === this.options.placeholder || $.trim(_htm.replace(/&nbsp;/g,' ')) ==='') {
            return '';
        }
        return String(this._editor.document.body.innerHTML)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/'/g, '&#39;')
          .replace(/"/g, '&quot;');
    };

    editor.prototype.setValue = function(htm) {
        if($.trim(htm) ==='') {
            htm = '';
        }
        this._editor.document.body.innerHTML = htm;
    };

    return editor;
});
