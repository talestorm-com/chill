<div id="black"></div>
<div id="prez_what">
<div id="treu_1" class="treusy"></div>
<div id="treu_2" class="treusy"></div>
    <div id="prez_what_page_1" class="prez_what_page">
        
        <div class="logo_in_prez">
            <img src="/assets/chill/images/logo_prez.png">
        </div>
        <div class="bug_block">
            <div class="text_in_prez">
                <p>CHILL - первый в России веб-кинотеатр<br>Веб-сериалы - новый уникальный формат короткого контента (обычно 8-15 минут), который уже завоевал сердца зрителей.</p>
                <p>Все жанры - комедии, хорроры, триллеры, драмы, анимация и документалистика. Победители ведущих мировых фестивалей.</p>
                <p>Выбери сериал по эмодзи. CHILL - самый короткий путь к эмоции!</p>
                <p class="grey_in_out">*Сервис работает только на территории РФ, а также странах СНГ и Балтии.</p>
            </div>
        </div>
        <div id="pages_in_1" class="pages_in_in_out">
            <div class="one_round active"></div>
            <div class="one_round"></div>
            <div class="one_round"></div>
        </div>
        <div class="page_bottom">
            <div class="page_to_next">
                <div id="page_btn_2" class="page_btn_next">Далее</div>
            </div>
            <div class="close_prez_out">
                <div class="close_prez_in">Пропустить</div>
            </div>
        </div>
    </div>
    <div id="prez_what_page_2" class="prez_what_page">
        <div class="logo_in_prez">
            <img src="/assets/chill/images/logo_prez.png">
        </div>
        <div class="bug_block">
            <div class="text_in_prez">
                <p>Инновационная система оплаты. Никаких подписок. Плати только за то, что что реально смотришь. Всего 6 рублей за эпизод.</p>
                <p>Серия доступна для просмотра 24 часа. Более 30% контента доступно для просмотра бесплатно.</p>
                <p>При регистрации получите 6 серий. Отправь ссылку другу - и он получит 8 серий, а сам получи ещё 4!</p>
            </div>
        </div>
        <div id="pages_in_2" class="pages_in_in_out">
            <div class="one_round"></div>
            <div class="one_round active"></div>
            <div class="one_round"></div>
        </div>
        <div class="page_bottom">
            <div class="page_to_next">
                <div id="page_btn_3" class="page_btn_next">Далее</div>
            </div>
            <div class="close_prez_out">
                <div class="close_prez_in">Пропустить</div>
            </div>
        </div>
    </div>
    <div id="prez_what_page_3" class="prez_what_page">
        <div class="logo_in_prez">
            <img src="/assets/chill/images/logo_prez.png">
        </div>
        <div class="bug_block">
            <div class="text_in_prez">
                <p>Создаешь свой контент?</p>
                <p><a href="/page/for_authors" title="Выложи его на CHILL!">Выложи его на CHILL! </a></p>
                <p><a href="/Soap/2358" title="Стать участником зоны пилотов">Стать участником зоны пилотов</a> </p>
            </div>
        </div>
        <div id="pages_in_3" class="pages_in_in_out">
            <div class="one_round"></div>
            <div class="one_round"></div>
            <div class="one_round active"></div>
        </div>
        <div class="page_bottom">
        <div class="page_to_next">
        </div>
            <div class="close_prez_out">
                <div class="close_prez_in">Смотреть</div>
            </div>
        </div>
    </div>
</div>
{literal}
<script>
$(document).ready(function(){
    var a = localStorage.getItem("prez");
    if (a != 'off'){
    $("#black").fadeOut(0);
        $("#prez_what").fadeIn(0);
    }else{
        $("#black").fadeOut(0);
    }
});
$("#page_btn_2").click(function(){
    $("#prez_what_page_1").fadeOut(0);
    $("#prez_what_page_2").fadeIn(0);
    $(".treusy").addClass("treu_slide_2");
});
$("#page_btn_3").click(function(){
    $("#prez_what_page_2").fadeOut(0);
    $("#prez_what_page_3").fadeIn(0);
    $(".treusy").removeClass("treu_slide_2").addClass("treu_slide_3");
});
$(".close_prez_in").click(function(){
    localStorage.setItem("prez","off");
    $("#prez_what").fadeOut(0);
    $("#prez_what_page_1").fadeIn(0);
    $("#prez_what_page_2").fadeOut(0);
    $("#prez_what_page_3").fadeOut(0);
    $(".treusy").removeClass("treu_slide_2").removeClass("treu_slide_3");
});

</script>
{/literal}