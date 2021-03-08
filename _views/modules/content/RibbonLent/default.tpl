{$OUT->add_script('/assets/chill/player/plyr/plyr.min.js',100,false)|void}
{$OUT->add_css('/assets/chill/player/plyr/plyr.css',100)|void}
{include './prez.tpl'}
<div id="lenta2" style="color:white!important">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1"> 
                
                        <!-- <a href="/Soap/3021" style="display:block; margin-bottom:15px;">
                            <img src="/assets/chill/images/banners/banner_chilltop.png" style="width:100%">
                        </a> -->
                        <a href="/Soap/3021" style="display:block; margin-bottom:15px;">
                            <img src="/assets/chill/images/banners/banner_newyear_chilltop.jpg" style="width:100%">
                        </a>
                    
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1"> 

                <div class="chill-lenta-content">                    
                    <div class="chill-lenta-content-inner">
                        <div class="row">
                            <div class="col s12 l6 chill-lenta-item-new chill-lenta-item-new-static">
                               <a href="/collection/1878" title="Первые серии бесплатно">

                            <div class="chill_main_lent_block chill_main_lent_block_topa" style="background-image:url(/media/lent_poster/1878/49e20b8766c85b92858bdf473be281af.SW_600H_600CF_1.jpg)">
                            </div>
                        </a>
                            </div>
                            <div class="col s12 l6 chill-lenta-item-new chill-lenta-item-new-static">
                                <a href="/collection/1877" title="Смотри бесплатно">

                            <div class="chill_main_lent_block chill_main_lent_block_topa" style="background-image:url(/media/lent_poster/1877/49e20b8766c85b92858bdf473be281af.SW_600H_600CF_1.jpg)">
                            </div>
                        </a>
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
    <video id="wiz_pl">

    </video>
</div>

{literal}

    <script>
        $(document).ready(function () {
            $.get('https://kino-cache.cdnvideo.ru/kinoteatr/wic/pix.jpg')
                    .fail(function () {
                        $(".gif_gif").remove();
                        $(".gif_img").fadeIn(0);
                        $(".run_trailer").each(function () {
                            $(this).removeAttr("data-video_url").removeClass("run_trailer");
                        });
                        $(".film_left_text_box").remove()
                        $("body").append("<style>.gif_img{display:block!important}</style>");
                    });
        });
        function declOfNum(n, text_forms) {
            n = Math.abs(n) % 100;
            var n1 = n % 10;
            if (n > 10 && n < 20) {
                return text_forms[2];
            }
            if (n1 > 1 && n1 < 5) {
                return text_forms[1];
            }
            if (n1 == 1) {
                return text_forms[0];
            }
            return text_forms[2];
        }
        $(document).ready(function () {
            $('.seas_count_sl').each(function () {
                var bx = $(this).data("seas");
                var nx = declOfNum(bx, ['сезон', 'сезона', 'сезонов']);
                $(this).text(nx);
            });
            $('.series_count_sl').each(function () {
                var bx = $(this).data("ser");
                var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
                $(this).text(nx);
            });
        });
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                jQuery(function () {
                    var E = window.Eve,
                            EFO = E.EFO,
                            U = EFO.U,
                            APS = Array.prototype.slice;
                    var T = null;
                    var loading = false;
                    if (jQuery('main').get(0).style.display === 'none') {
                        loading = true;
                    }
                    var pagenum = 1;

                    function onscroll() {
                        if (jQuery('main').get(0).style.display === 'none') {
                            return;
                        }
                        if (!T) {
                            T = jQuery('.load_more').get(0);
                        }
                        var rect = T.getBoundingClientRect();
                        if (rect.top < window.innerHeight * 2) {
                            if (!loading) {
                                loading = true;
                                jQuery.get('/lent_v_2/more/' + pagenum + "?sys_render_layout=raw{/literal}{if $this->get_debug_enabled()}&debug_enabled_lent_index{/if}{literal}")
                                        .done(function (d) {
                                            pagenum += 1;
                                            jQuery('.chill-lenta-content-inner').append(d);
                                            loading = false;
                                            onscroll();
                                            $('.seas_count_sl').each(function () {
                                                var bx = $(this).data("seas");
                                                var nx = declOfNum(bx, ['сезон', 'сезона', 'сезонов']);
                                                $(this).text(nx);
                                            });
                                            $('.series_count_sl').each(function () {
                                                var bx = $(this).data("ser");
                                                var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
                                                $(this).text(nx);
                                            });
                                            $.get('https://kino-cache.cdnvideo.ru/kinoteatr/wic/pix.jpg')
                                                    .fail(function () {
                                                        $(".gif_gif").remove();
                                                        $(".gif_img").fadeIn(0);
                                                        $(".run_trailer").each(function () {
                                                            $(this).removeAttr("data-video_url").removeClass("run_trailer");
                                                        });
                                                        $(".film_left_text_box").remove();
                                                        $("body").append("<style>.gif_img{display:block!important}</style>");

                                                    });

                                        });
                            }
                        }
                    }
                    jQuery(window).on('scroll', function () {
                        onscroll();
                    });
                    onscroll();
                    jQuery('body').on('click', '.run_trailer', function () {
                        var t = U.IntMoreOr(jQuery(this).data('id'), 0, 0);
                        var srca = $(this).data('srca');
                        $(".go_to_cinema a").attr("href", srca);
                        var a = $(".go_to_cinema a").attr("href");
                        if (a != '') {
                            $(".go_to_cinema").fadeIn(0);
                        } else {
                            $(".go_to_cinema").fadeOut(0);
                        }
                        if (t) {
                            var id = U.NEString(jQuery(this).data('videoId'), null);

                            var nxdeb = true;                            
                            if (nxdeb && id) {
                                window.run_trailers_player({content_type: 'ctRAWID', title: 'aaa', url: id, lent_mode: "video", id: t});
                            } else {
                                window.run_trailers_player({content_type: 'ctRAWURL', title: 'aaa', url: jQuery(this).data('video_url'), lent_mode: "video", id: t});
                            }
                        }
                    });

                });
            });
        })();
        /*$(window).scroll(function(){
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
         });*/

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
        jQuery('body').on('click', '.emo_block_main', function () {
            $("#popup_select_emo").fadeIn(0);

        });
        $("#close_popup_select_emo").click(function () {
            $("#popup_select_emo").fadeOut(0);

        });
$(document).ready(function(){

const playeraaa = new Plyr('#wiz_pl', {
controls:[]
});
     $("#what_chill").click(function(){
     var www = $(window).width();
var hhh = $(window).height();
     $("#wiz_pl").html('');
     if(hhh>www){
$('#wiz_pl').html('<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_vert_new.mp4"  type="video/mp4" id="wiz_vert">');
}else{
$('#wiz_pl').html('<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_new.mp4"  type="video/mp4" id="wiz_hor">');
}
$("#wiz").fadeIn(0);
$("#close_wiz").delay(500).fadeIn(500);
playeraaa.play();
    });



    $("#close_wiz").click(function(){
        $("#wiz").fadeOut(500);
        playeraaa.stop();

    });
    playeraaa.on('ended', event => {
  $("#wiz").fadeOut(500);
        playeraaa.stop();
});
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

const playeraaa = new Plyr('#wiz_pl', {
controls:[]
});
     $("#what_chill").click(function(){
     var www = $(window).width();
var hhh = $(window).height();
     $("#wiz_pl").html('');
     if(hhh>www){
$('#wiz_pl').html('<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_vert_new.mp4"  type="video/mp4" id="wiz_vert">');
}else{
$('#wiz_pl').html('<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_new.mp4"  type="video/mp4" id="wiz_hor">');
}
$("#wiz").fadeIn(0);
$("#close_wiz").delay(500).fadeIn(500);
playeraaa.play();
    });



    $("#close_wiz").click(function(){
        $("#wiz").fadeOut(500);
        playeraaa.stop();

    });
    playeraaa.on('ended', event => {
  $("#wiz").fadeOut(500);
        playeraaa.stop();
});
});



    </script> 
{/literal} 
{include './../MediaContentObject/trailer_player.tpl' }

