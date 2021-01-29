<?php
/* Smarty version 3.1.33, created on 2020-08-12 13:07:08
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/SearchController/chill_search.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f33bf4cb894c2_60717564',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '90fa99a41a6b8c617af5bf4a0bf2fdd974da231b' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/SearchController/chill_search.tpl',
      1 => 1597226815,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f33bf4cb894c2_60717564 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/chill/MediaContentRibbon/module.css',0));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0));?>

    <div id="genre_result">
<?php if ($_smarty_tpl->tpl_vars['result']->value) {?>

    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <h1><span class="rib">Поиск <span class="bold">"<?php echo $_smarty_tpl->tpl_vars['result']->value->search_query;?>
"</span></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ((count($_smarty_tpl->tpl_vars['result']->value->soap))) {?>
  
                        
     <div class="tag_list">
            <div class="container">
                <div class="row">
                    <div class="col s12 m10 offset-m1">
                        <div class="row">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['result']->value->soap, 'soap');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soap']->value) {
?>
                                <div class="col s12 l3">
    
        <div class="one_film_in_list chill-lenta-soap-new-<?php echo $_smarty_tpl->tpl_vars['soap']->value->content_type;?>
">
        
<a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
">
<div class="film_left">
                
                    <?php if ($_smarty_tpl->tpl_vars['soap']->value->image) {?>
                    <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['soap']->value->image;?>
.SW_400H_520CF_1.jpg">
                    <?php } else { ?>
                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                    <?php }?>
                
            </div>
            </a>
        
            <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
">
            <div class="film_right">
            <div class="film_right_in">
            <div class="in_film_right">
            <?php if ($_smarty_tpl->tpl_vars['soap']->value->free) {?>
            <div class="film_right_free">
                <span>Free</span>
            </div>
            <?php }?>
            
            <div class="film_right_lang ru_lang">
                <span>ru</span>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['soap']->value->track_language_name != "ru") {?>
                                        <div class="film_right_lang <?php echo $_smarty_tpl->tpl_vars['soap']->value->track_language_name;?>
_lang">
                                            <span><?php echo $_smarty_tpl->tpl_vars['soap']->value->track_language_name;?>
</span>
                                        </div>
                                        <?php }?>
            </div>
                <div class="one_film_in_list_title_a"><?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
</div>
                <div class="one_film_prop"><span><?php if ($_smarty_tpl->tpl_vars['soap']->value->origin_country_name) {
echo $_smarty_tpl->tpl_vars['soap']->value->origin_country_name;
}?></span>, <span><?php if ($_smarty_tpl->tpl_vars['soap']->value->genre_name) {
echo $_smarty_tpl->tpl_vars['soap']->value->genre_name;
}?></span></div>
                <div class="one_film_prop"><span><?php if ($_smarty_tpl->tpl_vars['soap']->value->seasons_count) {
echo $_smarty_tpl->tpl_vars['soap']->value->seasons_count;
}?> <?php echo smarty_function_TT(array('t'=>'season'),$_smarty_tpl);?>
 (<?php if ($_smarty_tpl->tpl_vars['soap']->value->series_count) {
echo $_smarty_tpl->tpl_vars['soap']->value->series_count;
}?> <?php echo smarty_function_TT(array('t'=>'series_lent'),$_smarty_tpl);?>
)</span></div>
            </div>
            </div>
        </div>
       </a>
    </div>
                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <?php }?>
    <?php } else { ?>
        <div class="white_error">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="inner_white_error">
                        Нет контента, соответствующего вашему поиску
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
<?php echo '<script'; ?>
>
$('#owl-tag-news').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
});
$('#owl-tag-soap').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 2
        },
        600: {
            items: 3
        },
        1000: {
            items: 4
        }
    }
});
$('#owl-tag-gifs').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
});
$(".gif_load").each(function() {
            $(this).click(function() {
                    var a = $(this).data("gifa");
                    $(".gif_display").fadeIn(0);
                    $("body").css("overflow","hidden");
                    $(".gif_img").each(function() {
                        var b = $(this).data("gif");
                        if (b === a) {
                            $(".gif_img").fadeOut(0);
                            $(this).fadeIn(0);
                        }
                    });
                });
            });
             $(".gif_display").click(function() {
            $(".gif_display").fadeOut(0);
            $("body").css("overflow","scroll");
        });
<?php echo '</script'; ?>
>
<?php }
}
