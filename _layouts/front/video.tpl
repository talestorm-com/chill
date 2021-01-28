<div id="video_jj">
</div>
  <div id="video_first">
  <video id="video_video_first_h" autoplay playsinline muted>
    <source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_horiz_clear%20%281920x1080%29.mp4" type="video/mp4">
  </video>
  <video id="video_video_first_v" autoplay playsinline muted>
    <source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_vert_clear%20%281280xauto%29.mp4" type="video/mp4">
  </video>
  <!--<div id="close_video">
  <i class="mdi mdi-close"></i>
  </div>-->
<div id="play_video_first"><i class="mdi mdi-play"></i></div>
<p class="alfa_fc">Нас {visit_counter} <span id="chel" data-count="{visit_counter}"></span>*</p>
<p class="beta_fc">* Количество уникальных пользователей, посетивших платформу с момента запуска.</p>

</div>

 <script>
 function declOfNum(n, text_forms) {  
            n = Math.abs(n) % 100; var n1 = n % 10;
            if (n > 10 && n < 20) { return text_forms[2]; }
            if (n1 > 1 && n1 < 5) { return text_forms[1]; }
            if (n1 == 1) { return text_forms[0]; }
            return text_forms[2];
        }
 $(document).ready(function(){
 var ww = $(window).width();
 var wh = $(window).height();
 if(ww > wh){
 var pw = ww/16;
 var pwl = ww/32*15;
 $("#play_video_first").width(pw).height(pw).css("left",pwl).css("font-size",pw).css("line-height",pw+"px");
 }else{
 var pw = ww/6;
 var pwl = ww/12*5;
 $("#play_video_first").width(pw).height(pw).css("left",pwl).css("font-size",pw).css("line-height",pw+"px");
 }
 });
 $(window).resize(function(){
 var ww = $(window).width();
 var wh = $(window).height();
 if(ww > wh){
 var pw = ww/16;
 var pwl = ww/32*15;
 $("#play_video_first").width(pw).height(pw).css("left",pwl).css("font-size",pw).css("line-height",pw+"px");
 }else{
 var pw = ww/6;
 var pwl = ww/12*5;
 $("#play_video_first").width(pw).height(pw).css("left",pwl).css("font-size",pw).css("line-height",pw+"px");
 }
 });
  $(document).ready(function(){
  $("#play_video_first").click(function(){
    $("#video_first").fadeOut(0);
    $("html").css("overflow-y","scroll");
    try{
    localStorage.setItem("video", "close");
    }catch(e){
    }
    });
  var pathname = window.location.pathname;
  var cid = null;
  try{
      cid = localStorage.getItem("video");
  }catch(e){
      //cid='close'; если это расскоментить то видео показываться не будет (на айфоне)
  }
  if(cid != "close"){
  if(pathname === '/' || pathname === '' || pathname === 'index.html'){
  $("#video_jj").fadeOut(0);
    var ww = $(window).width();
    var wh = $(window).height();
    $("#video_video_first_v").on("timeupdate", function(){
    if(this.currentTime >= 2) {
        $(".alfa_fc").fadeIn(500);
 $(".beta_fc").fadeIn(500);
    }
});

$("#video_video_first_h").on("timeupdate", function(){
    if(this.currentTime >= 2) {
    
              var bx = $("#chel").data("count");
                var nx = declOfNum(bx, ['человек', 'человека', 'человек']);
                $("#chel").text(nx);

        $(".alfa_fc").fadeIn(500);
 $(".beta_fc").fadeIn(500);
    }
});
$("#video_video_first_v").on("timeupdate", function(){
    if(this.currentTime >= 2) {
    
              var bx = $("#chel").data("count");
                var nx = declOfNum(bx, ['человек', 'человека', 'человек']);
                $("#chel").text(nx);

        $(".alfa_fc").fadeIn(500);
 $(".beta_fc").fadeIn(500);
    }
});
      $("html").css("overflow-y","hidden");
      $("#video_first").css("display","flex");
    if (ww > wh){
      $("#video_video_first_h").fadeIn(0).get(0).play();;
      $("#video_video_first_v").fadeOut(0);
    }else{
      $("#video_video_first_v").fadeIn(0).get(0).play();;
      $("#video_video_first_h").fadeOut(0);
    }
    $("#video_video_first_v").on('ended',function(){
      $("#play_video_first").css("opacity","1");
      $("#play_video_first").click(function(){
    $("#video_first").fadeOut(0);
    $("html").css("overflow-y","scroll");
    });
    try{
    localStorage.setItem("video", "close");
    }catch(e){
    }
    });
      $("#video_video_first_v").on('ended',function(){
      $("html").css("overflow-y","scroll");
      localStorage.setItem("video", "close");
    });
    $("#video_video_first_h").on('ended',function(){
    $("#play_video_first").css("opacity","1");
    $("#play_video_first").click(function(){
    $("#video_first").fadeOut(0);
    $("html").css("overflow-y","scroll");
    });
    try{
    localStorage.setItem("video", "close");
    }catch(e){
    }
    });
     
    }else{
    $("#video_jj").fadeOut(0);
$("#video_first").css("display","none");
    }
    }else{
    $("#video_jj").fadeOut(0);
$("#video_first").css("display","none");
    }
    
  });
</script>