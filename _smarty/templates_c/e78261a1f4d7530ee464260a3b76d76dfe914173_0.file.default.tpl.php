<?php
/* Smarty version 3.1.33, created on 2020-08-01 20:01:28
  from '/var/VHOSTS/site/_views/modules/content/MenuLent/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f259fe8aac7b7_84969721',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e78261a1f4d7530ee464260a3b76d76dfe914173' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/MenuLent/default.tpl',
      1 => 1596301284,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./item_list.tpl' => 1,
    'file:./../MediaContentObject/trailer_player.tpl' => 1,
  ),
),false)) {
function content_5f259fe8aac7b7_84969721 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),2=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_emoji_list.php','function'=>'smarty_function_get_emoji_list',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_script('/assets/chill/player/plyr/plyr.min.js',100,false));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/player/plyr/plyr.css',100));?>

<div id="lenta2" style="color:white!important">
    <div class="container" id="lent_in_menu">
        <div class="row">
            <div class="col s12 m10 offset-m1"> 

                <div class="chill-lenta-content">                    
                    <div class="chill-lenta-content-inner">
                        
                        <?php $_smarty_tpl->_subTemplateRender('file:./item_list.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
    <video id="wiz_hor">
<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_15mb.mp4"  type="video/mp4">
</video>
<video id="wiz_vert">
<source src="https://kino-cache.cdnvideo.ru/kinoteatr/wic/chill_promo_vert_15mb.mp4"  type="video/mp4">
</video>
</div>



    <?php echo '<script'; ?>
>
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
    <?php echo '</script'; ?>
> 
 
<?php $_smarty_tpl->_subTemplateRender('file:./../MediaContentObject/trailer_player.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
