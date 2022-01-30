/*
 * @Author: shu binqi 
 * @Date: 2019-12-05 00:18:54 
 * @Last Modified by: shu binqi
 * @Last Modified time: 2019-12-05 00:19:04
 */
// 密码显示隐藏
$(document).ready(function(){
  $(".iconyanjing").click(function(){
    $(".password1").attr("type","text");
    $(".iconyanjing").hide();
    $(".iconyanjing-guan").show();
  });
  $(".iconyanjing-guan").click(function(){
    $(".password1").attr("type","password");
    $(".iconyanjing-guan").hide();
    $(".iconyanjing").show();
  });
});

$(function(){
	
	var interval =  setInterval(function(){
	$.ajax({
		 url:"/common/updateactive",
		 dataType:"json",
		 async:true,
		 type:"GET",
		 success:function(r){
		 }
	})
	},30000);
	var interval2 =  setInterval(function(){
	$.ajax({
		 url:"/user/getmsg",
		 async:true,
		 type:"GET",
		 success:function(r){
			var n = parseInt(r);
			if(n>0){
			$("#notifiy-icon").addClass('new-notification')
			}else{
			$("#notifiy-icon").removeClass('new-notification')
			}
			$("#notifiy-num").html(n);
			
		 }
	})
	},30000);


 })

