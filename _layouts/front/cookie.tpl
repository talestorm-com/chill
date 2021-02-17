<div id="noty_body">
<div id="noty_close">
<i class="mdi mdi-close"></i>
</div>
<div class="noty_body_text">
Этот сайт использует файлы <strong>cookie</strong>. Продолжая пользоваться данным сайтом, Вы соглашаетесь на использование нами Ваших файлов <strong>cookie</strong>. <a href="/page/policy">Узнать больше</a>
</div>
</div>

{literal}
<script>
$(document).ready(function(){
    var a = localStorage.getItem("cookie");
    if(a != "true"){
        $("#noty_body").fadeIn(300);
    }
});
$("#noty_close").click(function(){
    $("#noty_body").fadeOut(300);
    localStorage.setItem("cookie","true"); 
});
</script>
{/literal}