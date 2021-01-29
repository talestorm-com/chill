// $(document).ready(function() {
//     var wind = $(window).width();
//     var ls = $("#login_signup").width();
//     var wind_ls = (wind - ls) / 2;
//     $("#login_signup").css("margin-left", wind_ls);
//     $("#close_login_signup").click(function() {
//         $("#login_signup").fadeOut(0);
//         $("#bg_bg").fadeOut(0);
//     });
//     $("#bg_bg").click(function() {
//         $("#login_signup").fadeOut(0);
//         $("#bg_bg").fadeOut(0);
//     });
//     $("#login_btn").click(function() {
//         $("#login_signup").fadeIn(0);
//         $("#bg_bg").fadeIn(0);
//     });
//     $("#no_acc a").click(function(){
//         $("#login_block").fadeOut(0);
//         $("#signup_block").fadeIn(0);
//     });
//     $("#to_login_block").click(function(){
//         $("#login_block").fadeIn(0);
//         $("#signup_block").fadeOut(0);
//     });
// });
// $(window).resize(function() {
//     var wind = $(window).width();
//     var ls = $("#login_signup").width();
//     var wind_ls = (wind - ls) / 2;
//     $("#login_signup").css("margin-left", wind_ls);
// });
$(document).ready(function() {
    $('select').material_select();
  });
