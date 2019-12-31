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