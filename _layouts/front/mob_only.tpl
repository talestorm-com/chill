<div id="mob_only_back" style="display:none;">
    <div id="mob_only">
        <div id="close_mob_only">
            <i class="mdi mdi-close"></i>
        </div>
        <div id="mob_only_top">
            <img src="/assets/chill/images/logo_out.jpg">
            <h6>Chill</h6>
        </div>
        <div id="mob_only_body">
            <p class="mob_only_grey">Добавь это приложения на главный экран для удобного доступа</p>
            <p id="mob_only_per"></p>
        </div>
        <div id="mob_only_bottom">
            <div id="mob_only_btn">
            ОК
            </div>
        </div>
    </div>
</div>
{literal}
<script>
$(document).ready(function(){
    var a = localStorage.getItem("mob_only");
    var ua = navigator.userAgent.toLowerCase();
    console.log(ua);
var isAndroid = ua.indexOf("android") > -1; 
var isiOS = ua.indexOf("iphone") > -1; 
if(isAndroid) {
  console.log("android")
var b = 'mobile';
var c = 'Нажми <i class="mdi mdi-dots-vertical-circle-outline"></i>, затем ‘Добавить на главный экран’';
}
if(isiOS) {
  console.log("iphone")
  var b = 'mobile';
  var c = 'Нажми <i class="mdi mdi-export-variant"></i>, затем ‘На экран Домой <i class="mdi mdi-plus-box-outline"></i>’';
}
    if (a != 'ok' && b === 'mobile'){
        $("#mob_only_back").fadeIn(0);
        $("#mob_only_per").html(c);
        $("html,body").css("overflow-y","hidden");
    }else{
        $("#mob_only_back").fadeOut(0);
        $("html,body").css("overflow-y","auto");
    }

});
$("#close_mob_only").click(function(){
    $("#mob_only_back").fadeOut(300);
    $("html,body").css("overflow-y","auto");
});
$("#mob_only_btn").click(function(){
    $("#mob_only_back").fadeOut(300);
    localStorage.setItem("mob_only","ok");
    $("html,body").css("overflow-y","auto");
});

</script>
{/literal}