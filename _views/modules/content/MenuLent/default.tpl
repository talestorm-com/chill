{$OUT->add_script('/assets/chill/player/plyr/plyr.min.js',100,false)|void}
{$OUT->add_css('/assets/chill/player/plyr/plyr.css',100)|void}
<div id="lenta2" style="color:white!important">
    <div class="container" id="lent_in_menu">
        <div class="row">
            <div class="col s12 m10 offset-m1"> 

                <div class="chill-lenta-content">                    
                    <div class="chill-lenta-content-inner">
                        
                        {include './item_list.tpl'}

                    </div>
                </div>                
            </div>
        </div>
    </div>
</div> 

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

{literal}

    <script>
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
                    var pagenum = 3;

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
                                jQuery.get('/lent_v_2/more/' + pagenum + "?sys_render_layout=raw")
                                        .done(function (d) {
                                            pagenum += 3;
                                            jQuery('.chill-lenta-content-inner').append(d);
                                            loading = false;
                                            onscroll();
                                        });
                            }
                        }
                    }
                    jQuery(window).on('scroll', function () {
                   //     onscroll();
                    });
                  //  onscroll();
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
                            window.run_trailers_player({content_type: 'ctRAWURL', title: 'aaa', url: jQuery(this).data('video_url'), lent_mode: "video", id: t});
                        }
                    });
                });
            });
        })();
        $(window).scroll(function () {
            var a = $(window).scrollTop() - 50;
            var b = $(window).height();
            var c = a + b - 50;
            console.log(a);
            $(".gif_load").each(function () {
                var pos = $(this).offset();
                console.log(pos);
                var posa = pos.top;
                console.log(posa);
                if (posa > a && posa < c) {
                    $(this).find(".gif_sign").fadeOut(0);
                    $(this).find(".gif_img").fadeOut(0);
                    $(this).find(".gif_gif").fadeIn(0);
                } else {
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
        jQuery('body').on('click', '.emo_block_main', function () {
            $("#popup_select_emo").fadeIn(0);

        });
        $("#close_popup_select_emo").click(function () {
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
        $(document).ready(function () {
            var a = $(".div_kv").width();
            var b = a*2/3;
            $(".div_kv").height(b);
        });
        $(window).resize(function () {
            var a = $(".div_kv").width();
            var b = a*2/3;
            $(".div_kv").height(b);
        });
        $(document).ready(function () {
            $("#what_chill").click(function () {
                $("#wiz").fadeIn(0);
                var video = $("#wiz video");
                video.currentTime = 0;
                $("#close_wiz").delay(500).fadeIn(500);
                $("#wiz video").get(0).play();

            });
            var www = $(window).width();
            var hhh = $(window).height();
            if (hhh > www) {
                $("#wiz_hor").fadeOut(0);
                $("#wiz_vert").fadeIn(0);
                var wwwa = www / 1080 * 1920;
                $("#wiz video").width(www).height(wwwa);
            } else {
                $("#wiz_hor").fadeIn(0);
                $("#wiz_vert").fadeOut(0);
                var wwwa = www / 1920 * 1080;
                $("#wiz video").width(www).height(wwwa);
            }
            $("#close_wiz").click(function () {
                $("#wiz").fadeOut(500);
            });
        });
        $(window).resize(function () {
            var www = $(window).width();
            var hhh = $(window).height();
            if (hhh > www) {
                $("#wiz_hor").fadeOut(0);
                $("#wiz_vert").fadeIn(0);
                var wwwa = www / 1080 * 1920;
                $("#wiz video").width(www).height(wwwa);
            } else {
                $("#wiz_hor").fadeIn(0);
                $("#wiz_vert").fadeOut(0);
                var wwwa = www / 1920 * 1080;
                $("#wiz video").width(www).height(wwwa);
            }
        });
    </script> 
{/literal} 
{include './../MediaContentObject/trailer_player.tpl' }

