<?php
/* Smarty version 3.1.33, created on 2020-12-10 16:44:43
  from '/var/VHOSTS/site/_views/modules/content/RibbonLent/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fd2264bc82334_06170390',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '37f01379f0af7d6abdee89344e850bc6f9bb9b3a' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/RibbonLent/default.tpl',
      1 => 1607607861,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./prez.tpl' => 1,
    'file:./item_list.tpl' => 1,
    'file:./../MediaContentObject/trailer_player.tpl' => 1,
  ),
),false)) {
function content_5fd2264bc82334_06170390 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),2=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_emoji_list.php','function'=>'smarty_function_get_emoji_list',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_script('/assets/chill/player/plyr/plyr.min.js',100,false));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/player/plyr/plyr.css',100));?>

<?php $_smarty_tpl->_subTemplateRender('file:./prez.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div id="lenta2" style="color:white!important">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1"> 
                
                        <!-- <a href="/collection/2847" style="display:block; margin-bottom:15px;">
                            <img src="/assets/chill/images/banners/banner_chilltop.png" style="width:100%">
                        </a> -->
                        <a href="/collection/2881" style="display:block; margin-bottom:15px;">
                            <img src="/assets/chill/images/banners/banner_newyear_chilltop.gif" style="width:100%">
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
                        <?php $_smarty_tpl->_subTemplateRender('file:./item_list.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
                            <h3><?php echo smarty_function_TT(array('t'=>'select_emo'),$_smarty_tpl);?>
</h3>
                            <div id="select_emoji_select">
                                <?php echo smarty_function_get_emoji_list(array('assign'=>'emoji_list'),$_smarty_tpl);?>

                                <ul>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['emoji_list']->value, 'emo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['emo']->value) {
?>
                                        <li><a href="/search/by_emoji/<?php echo $_smarty_tpl->tpl_vars['emo']->value['id'];?>
"><img src="/media/SMILE/<?php echo $_smarty_tpl->tpl_vars['emo']->value['id'];?>
/smile.SW_60H_60.png"></a></li>
                                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
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



    <?php echo '<script'; ?>
>
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
                                jQuery.get('/lent_v_2/more/' + pagenum + "?sys_render_layout=raw<?php if ($_smarty_tpl->tpl_vars['this']->value->get_debug_enabled()) {?>&debug_enabled_lent_index<?php }?>")
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



    <?php echo '</script'; ?>
> 
 
<?php $_smarty_tpl->_subTemplateRender('file:./../MediaContentObject/trailer_player.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
