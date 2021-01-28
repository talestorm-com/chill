<div id="prez_promo">
<div id="treu_1" class="treusy"></div>
<div id="treu_2" class="treusy"></div>
    <div id="prez_what_page_1" class="prez_what_page">
        
        <div class="logo_in_prez">
            <img src="/assets/chill/images/logo_prez.png">
        </div>
        <div class="bug_block">
            <div class="text_in_prez">
                <p>CHILL - первый в Росии веб-кинотеатр<br>Веб-сериалы - новый уникальный формат короткого контента (обычно 8-15 минут), который уже завоевал сердца зрителей.</p>
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
</div>
{literal}
<script>
var hash = window.location.hash;
var hashTag = "promo";
var hashSave = localStorage.getItem("promo");
if(hash === hashTag && hashSave != "off"){
    $("#prez_promo").fadeIn(0);
    console.log(hashTag);
    $("#prez_what").fadeOut(0);
    localStorage.setItem("prez","off");
}
</script>
{/literal}