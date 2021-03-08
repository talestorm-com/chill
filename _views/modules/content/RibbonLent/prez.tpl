<div id="black"></div>
<div id="prez_what">
    <div id="prez_what_page_1" class="prez_what_page">
        
        <div class="logo_in_prez logoImage">
            <img src="/assets/chill/images/logo_prez_new.png">
        </div>
        <div class="bug_block backgroundPrez">
            <div>
                <img class="imagePrez" src="/assets/chill/images/prez_1.png" alt="prez_1" />
            </div>
            <div class="text_in_prez textContainer">
                <p class="titleText">CHILL - первый в России веб-кинотеатр</p>
                <p class="simpleText">Веб-сериалы - новый уникальный формат короткого контента (обычно 8-15 минут), который уже завоевал сердца зрителей.</p>
                <p class="simpleText">Выбери сериал по эмодзи.<br />CHILL - самый короткий путь к эмоции!</p>
                <p class="grey_in_out descriptionText">*Сервис работает только на территории РФ, а также странах СНГ и Балтии.</p>
            </div>
        </div>
        <div id="pages_in_1" class="pages_in_in_out">
            <div class="one_round active one_roundActive"></div>
            <div class="one_round"></div>
            <div class="one_round"></div>
        </div>
        <div class="page_bottom">
            <div class="page_to_next">
                <div id="page_btn_2" class="page_btn_next btnNext">Далее</div>
            </div>
            <div class="close_prez_out">
                <div class="close_prez_in btnSkip">Пропустить</div>
            </div>
        </div>
    </div>
    <div id="prez_what_page_2" class="prez_what_page">
        <div class="logo_in_prez logoImage">
            <img src="/assets/chill/images/logo_prez_new.png">
        </div>
        <div class="bug_block backgroundPrez">
            <div>
                <img class="imagePrez" src="/assets/chill/images/prez_2.png" alt="prez_2" />
            </div>
            <div class="text_in_prez textContainer">
                <p class="titleText">Инновационная система оплаты</p>
                <p class="simpleText">Никаких подписок. Плати только за то, что что реально смотришь. Всего 6 рублей за эпизод.</p>
                <p class="simpleText">При регистрации получите 6 серий. Отправь ссылку другу - и он получит 8 серий, а сам получи ещё 4!</p>
            </div>
        </div>
        <div id="pages_in_2" class="pages_in_in_out">
            <div class="one_round"></div>
            <div class="one_round active one_roundActive"></div>
            <div class="one_round"></div>
        </div>
        <div class="page_bottom">
            <div class="page_to_next">
                <div id="page_btn_3" class="page_btn_next btnNext">Далее</div>
            </div>
            <div class="close_prez_out">
                <div class="close_prez_in btnSkip">Пропустить</div>
            </div>
        </div>
    </div>
    <div id="prez_what_page_3" class="prez_what_page">
        <div class="logo_in_prez logoImage">
            <img src="/assets/chill/images/logo_prez_new.png">
        </div>
        <div class="bug_block backgroundPrez">
            <div>
                <img class="imagePrez" src="/assets/chill/images/prez_3.png" alt="prez_3" />
            </div>
            <div class="text_in_prez textContainer">
                <p class="titleText">Создаешь свой контент?</p>
                <p><a href="/page/for_authors" title="Выложи его на CHILL!">Выложи его на CHILL! </a></p>
                <p><a href="/Soap/2358" title="Стать участником зоны пилотов">Стать участником зоны пилотов</a> </p>
            </div>
        </div>
        <div id="pages_in_3" class="pages_in_in_out">
            <div class="one_round"></div>
            <div class="one_round"></div>
            <div class="one_round active one_roundActive"></div>
        </div>
        <div class="page_bottom">
        <div class="page_to_next">
        </div>
            <div class="close_prez_out" style="margin-top: -40px">
                <div class="page_btn_next btnNext done_prez">Готово</div>
            </div>
        </div>
    </div>
</div>
<style>
    .btnNext {
        background: linear-gradient(270deg, #FFA98C 4.03%, #F89A90 16.84%, #E4719C 41.66%, #C430AF 75.7%, #AD00BD 99.12%);
        border-radius: 25px;
        color: #fff;
    }
    .btnSkip {
        color: #828282;
        font-family: Roboto;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        text-align: center;
        letter-spacing: -0.02em;
        background: none;
    }
    .one_roundActive {
        background: #C72DF7 !important;
    }
    .backgroundPrez {
        background: linear-gradient(191.81deg, #39065E -0.28%, #12054E 100%);
        display: block;
    }
    .titleText {
        font-family: Roboto;
        font-style: normal;
        font-weight: bold !important;
        font-size: 26px !important;
        line-height: 120% !important;
    }
    .simpleText {
        font-family: Roboto;
        font-style: normal;
        font-weight: normal !important;
        font-size: 15px !important;
        line-height: 120% !important;
        /* or 18px */
        color: #FFFFFF;
    }
    .descriptionText {
        font-family: Roboto;
        font-style: normal;
        font-weight: normal !important;
        font-size: 12px !important;
        line-height: 120% !important;
    }
    .logoImage {
        left: 70px;
        top: 25px;
    }
    .textContainer {
        width: auto;
        padding: 24px;
    }
    .imagePrez {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: auto;
        height: 190px;
        margin-top: 60px;
        background: radial-gradient(50% 50% at 50% 50%, rgba(121, 27, 196, 0.5) 0%, rgba(120, 27, 194, 0) 100%);
    }
</style>
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
$(".done_prez").click(function(){
    localStorage.setItem("prez","off");
    $("#prez_what").fadeOut(0);
    $("#prez_what_page_1").fadeIn(0);
    $("#prez_what_page_2").fadeOut(0);
    $("#prez_what_page_3").fadeOut(0);
    $(".treusy").removeClass("treu_slide_2").removeClass("treu_slide_3");
});

</script>
{/literal}