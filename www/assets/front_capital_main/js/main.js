   $(document).ready(function() {
       $(".bg_bg").click(function() {
           $(".order").fadeOut(0);
           $(".bg_bg").fadeOut(0);
           $("body").css("overflow-y", "scroll");
       });
       $(".close_btn").click(function() {
           $(".order").fadeOut(0);
           $(".bg_bg").fadeOut(0);
           $("body").css("overflow-y", "scroll");
       });
       $("#to_form").click(function() {
           $("#zakaz_katalog").fadeIn(0);
           $("body").css("overflow-y", "hidden");
           $(".bg_bg").fadeIn(0);
           var a = $(window).width();
           var b = $(window).height();
           var c = $("#zakaz_katalog").width();
           var d = $("#zakaz_katalog").height();
           var aa = (a - c) / 2 - 10;
           var bb = (b - d) / 2 - 10;
           $("#zakaz_katalog").css("left", aa);
           $("#zakaz_katalog").css("top", bb);
       });
   });
   $(document).ready(function() {
       $('select').material_select();
   });

   $(document).ready(function() {
       $("#new_zaya").click(function() {
           $("#new_zaya_main").fadeIn(0);
           $("#all_zaya_main").fadeOut(0);
           $("#zav_zaya_main").fadeOut(0);
           $("#new_zaya").addClass("active");
           $("#all_zaya").removeClass("active");
           $("#zav_zaya").removeClass("active");
       });
      
   });
   $(document).ready(function() {
       $(".uveren_niz button#net").click(function() {
           $(".bg_bg").fadeOut(0);
           $("#uveren").fadeOut(0);
       });
       $(".bg_bg").click(function() {
           $(".bg_bg").fadeOut(0);
           $("#uveren").fadeOut(0);
       });
//       $(".cancel").click(function() {
//           $(".bg_bg").fadeIn(0);
//           $("#uveren").fadeIn(0);
//       });

   });