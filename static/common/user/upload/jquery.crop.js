/*
 * 移动端头像裁剪插件
 * 整理：呼和浩特米米乐乐高儿童科技中心
 * 
 */
(function($){
	$.fn.crop = function(options){
		var v = {
			w:320,
			h:320,
			r:120,
			res:'',
			barHeight:50,
			touchBgColor:'#58d82c',
			isCircle:true,
			angle:0,
			initialScale:1,
			callback:function(base64Data){}
		}
		var o = $.extend(v,options);
		//重加载时移除
		$(this).html('');
		//如果盒子高度少于宽度提醒设置错误
		if(o.h<o.w){
			alert('容器区域高度须大于等于宽度！');
			return false;
		}
		//如果半径大于盒子宽度的一半，则提醒，用在手机上加限制
		if(o.r>o.w*0.5){
			alert('请在手机上浏览或将浏览器宽度调整到高度二分之一以下！');
			return false;
		}
		var box = $(this);
		if(!box.attr('id')){
			id = 'curbox'+Date.parse(new Date());
			box.attr('id',id);
		}else{
			id = box.attr('id');
		}
		var first = true;
		var element,img,edit_finished,select_img;
		//全局变量
		var x = 0,y = 0,_x = 0,scale = v.initialScale,gx = 0,gy = 0,p_left = 0,p_top = 0,
			gw,gh,ngw,ngh,
			base64Data = '',
			t;
		var pos = new Object();

		//获取图片信息
		var image = new Image();
		if(o.res==''){
			var canvasText = $("<canvas />").attr({
					width: o.r*2,
					height: o.r*2
				}).get(0);
			cvt = canvasText.getContext("2d");
			cvt.font="16px '宋体'";
			cvt.fillStyle = '#aaa';
        	cvt.fillText('请先选择一张图片',o.r-64,o.r);

			o.res=canvasText.toDataURL('image/png');
		}
		image.src = o.res;
		image.onload = function(){
			//添加盒子内元素
			if(box.html()!=''){
				first = false;
				$(select_btn).text('重选');
				gx = (o.w-o.r*2)*0.5;
				gy = (o.h-o.r*2)*0.5;
			}else{
				box.append('\
					<div id="cut-mask" style="width:'+o.w+'px;height:'+o.h+'px;position:relative;left:0px;top:0px;z-index:10;"><canvas id="canvas-mask"></canvas></div>\
					<div id="cut-img" style="width:'+o.w+'px;height:'+o.h+'px;position:relative;top:'+(-o.h)+'px;left:0px;background-image:url('+image.src+');background-size:'+image.width+'px '+image.height+'px;background-position:0px 0px;background-repeat:no-repeat;"></div><canvas id="temp-img" style="display:none;"></canvas>\
				');
				//设置盒子样式
				box.css({
					"width":o.w+"px",
					"height":o.h+"px",
					"position":"relative",
					"left":"0px",
					"top":"0px",
					"overflow":"hidden"
				});
				box.after('<div id="title_text" style="position:absolute;top:5px;left:0px;width:100%;line-height:40px;color:#fff;text-align:center;z-index:12;">设置头像</div><div style="height:'+v.barHeight+'px;width:100%;position:absolute;bottom:0px;"><a style="display:block;position:absolute;left:15px;bottom:15px;color:#fff;z-index:12;background:#f89119;width:60px;height:30px;line-height:30px;text-align:center;border-radius:3px;" id="select_btn">选择</a><a style="display:block;position:absolute;right:15px;bottom:15px;color:#fff;z-index:12;background:#35b345;width:60px;height:30px;line-height:30px;text-align:center;border-radius:3px;" id="edit_finished">完成</a><input type="file" id="selectImageFile" accept="image/*" capture="camera" style="height:0px;width:0px;border:0px;display:none;" /></div>');
				
				//初始化设置遮罩
				var mask = document.getElementById('canvas-mask');
			
				var msk = mask.getContext('2d');
				mask.width = o.w;
				mask.height = o.h;
			
				//画遮罩矩形
				msk.beginPath();
				msk.rect(0,0,o.w,o.h);
				msk.globalAlpha = 0.8;
				msk.fill();//画实心圆
				msk.closePath();
			
				o.h = first?o.h-o.barHeight:o.h;//重置高度
				//画一个实心圆
				msk.globalCompositeOperation = 'destination-out';
				msk.beginPath();
				msk.arc(o.w*0.5,o.h*0.5,o.r,0,(Math.PI/180)*360,false);//圆形选框
				msk.fill();
				msk.closePath();
				//实心矩形
				msk.beginPath();
				msk.rect((o.w-o.r*2)*0.5,(o.h-o.r*2)*0.5,o.r*2,o.r*2);//矩形选框
				msk.fill();
				msk.closePath();
			
				//画矩形选框
				msk.globalCompositeOperation = 'source-over';
				msk.beginPath();
				msk.lineWidth=1;//外框
				msk.strokeStyle="#ffffff";//外框
				msk.strokeRect((o.w-o.r*2)*0.5,(o.h-o.r*2)*0.5,o.r*2,o.r*2);//矩形选框
				msk.fill();
				msk.closePath();
				//绘制线条
				msk.beginPath();  
            	msk.moveTo((o.w-o.r*2)*0.5+o.r*2/3+0.5,(o.h-o.r*2)*0.5);  
            	msk.lineTo((o.w-o.r*2)*0.5+o.r*2/3+0.5,(o.h-o.r*2)*0.5+o.r*2); //竖线1
				msk.moveTo((o.w-o.r*2)*0.5+2*o.r*2/3+0.5,(o.h-o.r*2)*0.5);  
            	msk.lineTo((o.w-o.r*2)*0.5+2*o.r*2/3+0.5,(o.h-o.r*2)*0.5+o.r*2); //竖线2
				msk.moveTo((o.w-o.r*2)*0.5,(o.h-o.r*2)*0.5+o.r*2/3+0.5);  
            	msk.lineTo((o.w-o.r*2)*0.5+o.r*2,(o.h-o.r*2)*0.5+o.r*2/3+0.5); //竖线2
				msk.moveTo((o.w-o.r*2)*0.5,(o.h-o.r*2)*0.5+2*o.r*2/3+0.5);  
            	msk.lineTo((o.w-o.r*2)*0.5+o.r*2,(o.h-o.r*2)*0.5+2*o.r*2/3+0.5); //竖线2
            	msk.stroke();
				msk.closePath();
				
				gw = image.width,
				gh = image.height,
				ngw = image.width,
				ngh = image.height,
			
				//矩形选框位置参数
				pos.l = (o.w-o.r*2)*0.5;
				pos.t = (o.h-o.r*2)*0.5;
				pos.r = (o.w-o.r*2)*0.5+o.r*2;
				pos.b = (o.h-o.r*2)*0.5+o.r*2;
				
				element = document.getElementById(id);
				img = document.getElementById('cut-img');
				select_btn = document.getElementById('select_btn');
				edit_finished = document.getElementById('edit_finished');
				
				$(img).css({'background-position':(o.w-o.r*2)*0.5+'px '+(o.h-o.r*2)*0.5+'px'});
			}
			
			if(first){
				//触控移动
				touch.on('#'+id,'touchstart',start);
				touch.on('#'+id,'drag',move);
				touch.on('#'+id,'dragend',end);
				//触控缩放
				touch.on('#'+id,'pinchstart',startscale);
				touch.on('#'+id,'pinch',movescale);
				touch.on('#'+id,'pinchend',endscale);
			
				//按钮事件
				$('#select_btn').click(function(){
					$('#selectImageFile').click();
				});
				$('#edit_finished').click(function(){
					submitBase64();
				});
				
				$('#selectImageFile').change(function(event){
					var file = event.target.files[0];
					if(!/image\/\w+/.test(file.type)){  
						alert("请确保文件为图像类型");
						return false;  
					}  
            		if(window.FileReader) {  
                		var reader = new FileReader();
						reader.onloadstart = function(e){
							layer.open({
								type:1,
								content:'<img src="data:image/gif;base64,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" /><br/>正在读取...',
								style:'background:#fff;border-radius:10px;padding:10px;color:#666;text-align:center;',
								shadeClose:false
							});
						}
                		reader.onloadend = function(e) {
							var source_img = new Image();
							source_img.src = e.target.result;
							source_img.onload = function(){
								resetImg(source_img);
							}
                		};  
                		reader.readAsDataURL(file);
            		}
				});
			}
			//重置图片
			function resetImg(source_img){
				if(source_img.naturalWidth<o.r*2 || source_img.naturalHeight<o.r*2){
					alert('图片尺寸太小，请重新选择不小于'+o.r*2+'*'+o.r*2+'像素大小的图片');
				}else{
					var quality = 1;					
					var newImgData = compress(source_img,quality);
					var new_img = new Image();
					new_img.src = newImgData;
					new_img.onload = function(){
						if(new_img.width>o.r*2*2 && new_img.height>o.r*2*2){
							gw = ngw = new_img.width/2;
							gh = ngh = new_img.height/2;
						}else{
							gw = ngw = new_img.width;
							gh = ngh = new_img.height;
						}
						
						image.src = newImgData;
						$(img).css({
							'background-image':'url('+newImgData+')',
							'background-size':ngw+'px '+ngh+'px'
						});
						layer.closeAll();
					}
				}
			}
			//压缩图片,若压缩后失真严重，可以把此函数换成压缩包里的本地图片压缩插件
			function compress(source_img,quality){
				var MaxW = 1200,MaxH = 1200;
				if(source_img.width>source_img.height){
					imageWidth=MaxW;
					imageHeight=Math.round(MaxH*(source_img.height/source_img.width));
				}else if(source_img.width<source_img.height){
					imageHeight=MaxH;
					imageWidth=Math.round(MaxW*(source_img.width/source_img.height));
				}else{
					imageWidth=MaxW;
					imageHeight=MaxH;
				}
				quality = imageWidth/source_img.width;
				//quality = quality<0.6?quality+0.4:quality;

				var canvas=document.createElement('canvas');
				canvas.width=imageWidth;
				canvas.height=imageHeight;
				var con=canvas.getContext('2d');
				con.clearRect(0,0,canvas.width,canvas.height);
				con.drawImage(source_img,0,0,canvas.width,canvas.height);
				var base64=canvas.toDataURL('image/png',quality);
				return base64;
			}
			
			//触控移动
			function start(event){
				event.preventDefault();
			}
			function move(event){
				event.preventDefault();
				if(first){
					return;
				}
	
				//定位 防止超出选区
				p_left = gx+event.x;
				p_top = gy+event.y;
				if(p_left>pos.l){
					p_left = pos.l;
				}
				if(p_top>pos.t){
					p_top = pos.t;
				}
				if(p_left+ngw<pos.r){
					p_left = pos.r-ngw+1;
				}
				if(p_top+ngh<pos.b){
					p_top = pos.b-ngh+1;
				}
				
				$('#cut-img').css({
					'background-position':p_left+'px '+p_top+'px',
					'background-repeat':'no-repeat'
				});
			}
			function end(event){
				gx = p_left;
				gy = p_top;
			}
			
			
			//触控缩放
			function startscale(event){
				event.preventDefault();
			}
			function movescale(event){
				event.preventDefault();
				if(first){
					return;
				}
				
				scale = event.scale;
				scale = scale>3?3:scale;
				scale = scale<0?0:scale;
				
				if(gw*scale>=o.r*2 && gh*scale>=o.r*2){
					ngw = gw*scale;
					ngh = gh*scale;
					
					//定位 防止超出选区
					p_left = gx*scale;
					p_top = gy*scale;
					p_left = p_left-(ngw-gw)/2;
					p_top = p_top-(ngh-gh)/2;
					
					if(p_left>pos.l){
						p_left = pos.l;
					}
					
					if(p_top>pos.t){
						p_top = pos.t;
					}
					//宽度小于选区
					if(p_left+ngw<pos.r){
						p_left = pos.r-ngw+1;
					}
					//高度小于选区
					if(p_top+ngh<pos.b){
						p_top = pos.b-ngh+1;
					}
					
					$('#cut-img').css({
						'background-size':ngw+'px '+ngh+'px',
						'background-position':p_left+'px '+p_top+'px',
						'background-repeat':'no-repeat'
					});
				}
			}
			function endscale(event){
				var xy = $(img).css('background-size');
				var arr = xy.split(' ');
				gx = parseInt($(img).css('background-position-x'));
				gy = parseInt($(img).css('background-position-y'));
				gw = parseInt(arr[0]);
				gh = parseInt(arr[1]);
			}

			//保存提交图片Base64数据
			function submitBase64(){
				if(first){
					alert('请先选择一张图片');
					return;
				}
				var canvas = $("<canvas />").attr({
					width: o.r*2,
					height: o.r*2
				}).get(0);
				canvasContext = canvas.getContext("2d");
				canvasContext.fillStyle = "#eee";
				canvasContext.fillRect(0, 0, canvas.width, canvas.height);
				
				var cx = (o.w*0.5-o.r-gx)*image.width/gw,
					cy = (o.h*0.5-o.r-gy)*image.height/gh,
					cw = o.r*2*image.width/gw,
					ch = o.r*2*image.height/gh;
					
				var nx = ny = nw = nh = 0;
				var dx = dy = dw = dh = 0;
				if(cx<0){
					nx = 0;
					if(cx+cw<image.width){
						nw = cx+cw;
					}else{
						nw = image.width;
					}
				}else{
					nx = cx;
					if(cx+cw<image.width){
						nw = cw;
					}else{
						nw = image.width-cx;
					}
				}
				
				if(cy<0){
					ny = 0;
					if(cy+ch<image.height){
						nh = cy+ch;
					}else{
						nh = image.height;
					}
				}else{
					ny = cy;
					if(cy+ch<image.height){
						nh = ch;
					}else{
						nh = image.height-cy;
					}
				}
				//alert(nx +'>'+ ny  +'>'+ nw+'>'+ nh);
				dx = (cx<0?-cx:0)*gw/image.width;
				dy = (cy<0?-cy:0)*gh/image.height;
				dw = (nw*gw/image.width<o.r*2?nw*gw/image.width:o.r*2);
				dh = (nh*gh/image.height<o.r*2?nh*gh/image.height:o.r*2);
				
				canvasContext.drawImage(
					image,
					nx,
					ny,
					nw,
					nh,
					dx,
					dy,
					dw,
					dh
				);
				o.callback(canvas.toDataURL());
			}
		}
	}
})(jQuery);