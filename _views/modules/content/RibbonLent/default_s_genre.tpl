{$OUT->add_script('/assets/chill/player/plyr/plyr.min.js',100,false)|void}
{$OUT->add_css('/assets/chill/player/plyr/plyr.css',100)|void}
<div id="lenta2" style="color:white!important">
    <div class="container">
    <div class="row">
            <div class="col s12 m10 offset-m1"> 
    <div id="adfox_159613374370079574"></div>
    </div>
    </div>
    </div>
    <!-- adfox start -->
<script>
    window.Ya.adfoxCode.create({
        ownerId: 211731,
        containerId: 'adfox_159613374370079574',
        params: {
            p1: 'cltlz',
            p2: 'y',
            puid1: '',
            puid2: '',
            puid3: '',
            puid4: ''
        }
    });
</script>
<!-- adfox end -->
<div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1"> 

                <div class="chill-lenta-content">                    
                    <div class="chill-lenta-content-inner">
                                         <div class="row">
                                         <div class="col s12 l6 chill-lenta-item-new chill-lenta-item-new-static">
<div id="what_chill">
</div>
</div>
<div class="col s12 l6 chill-lenta-item-new chill-lenta-item-new-static">
<a href="/page/for_authors"><div id="what_chill_iz" style="background-image:url(/assets/chill/images/chill_1.gif)">
</div></a>
</div>

</div>
                        {include './item_list.tpl'}
                        
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div> 
<div class="load_more"> loading </div> 
<!-- <div id ="popup_select_janr">
    <div class="popup_select_janr_in">
        <div id="popup_select_janr_a">
            <div id="close_popup_select_janr">
                <i class="mdi mdi-close"></i>
            </div>
            <div id="popup_select_janr_in_in">
                <div class="in_ls_block">
                    <div class="row">
                        
                        <div class="col s12">
                            <h3>Выберите жанр</h3>
                            <div id="select_janr_select">
                            {get_genre_list assign='genre_list'}
                            <ul class="row">
                            {foreach from=$genre_list item='genre'}
        <li class="col s6"><a href="/search/by_genre/{$genre.id}">{$genre.name}</a></li>
    {/foreach}
    </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    </div>  -->
<div id="popup_select_emo">
    <div class="popup_select_emo_in">
        <div id="popup_select_emo_a">
            <div id="close_popup_select_emo">
                <i class="mdi mdi-close"></i>
            </div>
            <div id="popup_select_emo_in_in">
                <div class="in_ls_block">
                    <div class="row">
                        
                        <div class="col s12">
                            <h3>{TT t='select_emo'}</h3>
                            <div id="select_emoji_select">
                            {get_emoji_list assign='emoji_list'}
                            <ul>
                            {foreach from=$emoji_list item='emo'}
        <li><a href="/search/by_emoji/{$emo.id}"><img src="/media/SMILE/{$emo.id}/smile.SW_60H_60.png"></a></li>
    {/foreach}
    </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div> 
<div id="wiz">
<div id="close_wiz">
<i class="mdi mdi-close"></i>
</div>
<video id="wiz_hor">
<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_15mb.mp4"  type="video/mp4">
</video>
<video id="wiz_vert">
<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_vert_15mb.mp4"  type="video/mp4">
</video>
</div>
<!-- <div id="popup_select_lang">
    <div class="popup_select_lang_in">
        <div id="popup_select_lang_a">
            <div id="close_popup_select_lang">
                <i class="mdi mdi-close"></i>
            </div>
            <div id="popup_select_lang_in_in">
                <div class="in_ls_block">
                    <div class="row">
                       
                        <div class="col s12">
                            <h3>Выберите язык</h3>
                            <div id="select_lang_select">
                            {get_tracklang_list assign='list'}
                            <ul>
                            {foreach from=$list item='lang'}
        <li class="col s10 offset-s1 l4 offset-l4"><a href="/search/by_track_language/{$lang.id}">{$lang.name}</a></li>
    {/foreach}
    </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>  -->
            {literal}

    <script>
    function declOfNum(n, text_forms) {  
            n = Math.abs(n) % 100; var n1 = n % 10;
            if (n > 10 && n < 20) { return text_forms[2]; }
            if (n1 > 1 && n1 < 5) { return text_forms[1]; }
            if (n1 == 1) { return text_forms[0]; }
            return text_forms[2];
        }
        $(document).ready(function(){
            $('.seas_count_sl').each(function(){
                var bx = $(this).data("seas");
                var nx = declOfNum(bx, ['сезон', 'сезона', 'сезонов']);
                $(this).text(nx);
            });
            $('.series_count_sl').each(function(){
                var bx = $(this).data("ser");
                var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
                $(this).text(nx);
            });
        });
    (function() {
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function() {
            jQuery(function() {
                var E = window.Eve,
                    EFO = E.EFO,
                    U = EFO.U,
                    APS = Array.prototype.slice;
                var T = null;
                var loading = false;
                if(jQuery('main').get(0).style.display==='none'){
                    loading = true;
                }
                var pagenum = 1;

                function onscroll() {
                    if(jQuery('main').get(0).style.display==='none'){
                  return;
                }
                    if (!T) {
                        T = jQuery('.load_more').get(0);
                    }
                    var rect = T.getBoundingClientRect();
                    if (rect.top < window.innerHeight * 2) {
                        if (!loading) {
                            loading = true;
                            jQuery.get('/lent_v_2/more/' + pagenum + "?sys_render_layout=raw")
                                .done(function(d) {
                                    pagenum += 1;
                                    jQuery('.chill-lenta-content-inner').append(d);
                                    loading = false;
                                    onscroll();
                                    $('.seas_count_sl').each(function(){
                                        var bx = $(this).data("seas");
                                        var nx = declOfNum(bx, ['сезон', 'сезона', 'сезонов']);
                                        $(this).text(nx);
                                    });
                                    $('.series_count_sl').each(function(){
                                        var bx = $(this).data("ser");
                                        var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
                                        $(this).text(nx);
                                    });
                                });
                        }
                    }
                }
                jQuery(window).on('scroll',function() {
                    onscroll();
                });
                onscroll();
                jQuery('body').on('click','.run_trailer',function() {
                    var t = U.IntMoreOr(jQuery(this).data('id'),0,0);
                    var srca = $(this).data('srca');
                    $(".go_to_cinema a").attr("href",srca);
                    var a = $(".go_to_cinema a").attr("href");
                if (a != ''){
                $(".go_to_cinema").fadeIn(0);
                }else{
                $(".go_to_cinema").fadeOut(0);
                }
                    if (t) {
                        window.run_trailers_player({ content_type:'ctRAWURL',title:'aaa',url:jQuery(this).data('video_url'), lent_mode:"video",id:t});
                    }
                });

            });
        });
    })();
$(window).scroll(function(){
var a = $(window).scrollTop() -50;
var b = $(window).height();
var c = a+b -50;
$(".gif_load").each(function(){
   var pos = $(this).offset();
   var posa = pos.top;
   if (posa > a && posa < c){
   $(this).find(".gif_sign").fadeOut(0);
    $(this).find(".gif_img").fadeOut(0);
    $(this).find(".gif_gif").fadeIn(0);
    }else{
    $(this).find(".gif_sign").fadeIn(0);
    $(this).find(".gif_img").fadeIn(0);
    $(this).find(".gif_gif").fadeOut(0);
    }
});
});
// jQuery('body').on('click', '.gif_load', function() {
//     $(".gif_sign").fadeIn(0);
//     $(".gif_img").fadeIn(0);
//     $(".gif_gif").fadeOut(0);
//     $(this).find(".gif_sign").fadeOut(0);
//     $(this).find(".gif_img").fadeOut(0);
//     var a = $(this).find(".gif_gif").data("src");
//     $(this).find(".gif_gif").attr("src", a);
//     $(this).find(".gif_gif").fadeIn(0);
// });
jQuery('body').on('click', '.emo_block_main', function() {
    $("#popup_select_emo").fadeIn(0);

});
$("#close_popup_select_emo").click(function() {
    $("#popup_select_emo").fadeOut(0);

});
// jQuery('body').on('click', '.janr_block_main', function() {
//     $("#popup_select_janr").fadeIn(0);

// });
// $("#close_popup_select_janr").click(function() {
//     $("#popup_select_janr").fadeOut(0);

// });
// jQuery('body').on('click', '.lang_block_main', function() {
//     $("#popup_select_lang").fadeIn(0);

// });
// $("#close_popup_select_lang").click(function() {
//     $("#popup_select_lang").fadeOut(0);

// });
$(document).ready(function() {
    var wh = $(window).height();
    var ww = $(window).width();
    if (ww < 993){
if(ww <wh){
    var aaa = $(".chill_main_lent_block").width();
    var aab = aaa/3*2;
    $("head").append('<style type="text/css">.chill_main_lent_block,#what_chill,#what_chill_iz{height:'+aab+'px}</style>');
}else{
    var aaa = $(".chill_main_lent_block").width();
    var aab = aaa/3*2;
    $("head").append('<style type="text/css">.chill_main_lent_block,#what_chill,#what_chill_iz{height:'+aab+'px}</style>');
}
    }else{
    var aaa = $(".chill_main_lent_block").width();
    var aab = aaa/3*2;
    $("head").append('<style type="text/css">.chill_main_lent_block,#what_chill,#what_chill_iz{height:'+aab+'px}</style>');    
    }
});
$(window).resize(function() {
    var wh = $(window).height();
    var ww = $(window).width();
    if (ww < 993){
if(ww <wh){
    var aaa = $(".chill_main_lent_block").width();
    var aab = aaa/3*2;
    $("head").append('<style type="text/css">.chill_main_lent_block,#what_chill,#what_chill_iz{height:'+aab+'px}</style>');
}else{
    var aaa = $(".chill_main_lent_block").width();
    var aab = aaa/3*2;
    $("head").append('<style type="text/css">.chill_main_lent_block,#what_chill,#what_chill_iz{height:'+aab+'px}</style>');
}
    }else{
    var aaa = $(".chill_main_lent_block").width();
    var aab = aaa/3*2;
    $("head").append('<style type="text/css">.chill_main_lent_block,#what_chill,#what_chill_iz{height:'+aab+'px}</style>');    
    }
});
$(document).ready(function(){
     $("#what_chill").click(function(){
$("#wiz").fadeIn(0);
var video = $("#wiz video");
video.currentTime = 0;
$("#close_wiz").delay(500).fadeIn(500);
$("#wiz_hor").get(0).play();
$("#wiz_vert").get(0).play();
    });
var www = $(window).width();
var hhh = $(window).height();
if(hhh>www){
    $("#wiz_hor").fadeOut(0);
    $("#wiz_vert").fadeIn(0);
    var wwwa = www/1080*1920;
    $("#wiz video").width(www).height(wwwa);
    $("#wiz_hor").get(0).pause();
}else{
    $("#wiz_hor").fadeIn(0);
    $("#wiz_vert").fadeOut(0);
    var wwwa = www/1920*1080;
    $("#wiz video").width(www).height(wwwa);
    $("#wiz_vert").get(0).pause();
}
    $("#close_wiz").click(function(){
        $("#wiz").fadeOut(500);
        $("#wiz_vert").get(0).pause();
        $("#wiz_hor").get(0).pause();
    });
});
$(window).resize(function(){
var www = $(window).width();
var hhh = $(window).height();
if(hhh>www){
    $("#wiz_hor").fadeOut(0);
    $("#wiz_vert").fadeIn(0);
    var wwwa = www/1080*1920;
    $("#wiz video").width(www).height(wwwa);
}else{
    $("#wiz_hor").fadeIn(0);
    $("#wiz_vert").fadeOut(0);
    var wwwa = www/1920*1080;
    $("#wiz video").width(www).height(wwwa);
}
    });
$("#wiz_hor").on('ended',function(){
    $("#wiz").fadeOut(500);
});
   

$("#wiz_vert").on('ended',function(){
    $("#wiz").fadeOut(500);
});
</script> 
{/literal} 
{include './../MediaContentObject/trailer_player.tpl' }

