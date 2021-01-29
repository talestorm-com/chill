<?php
/* Smarty version 3.1.33, created on 2020-08-18 13:27:02
  from '/var/VHOSTS/site/_views/modules/content/MediaContentRibbonTag/tagged.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f3bacf685a429_47396425',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e479117d24b3d3eaed9828637bcecb41e09dccbc' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/MediaContentRibbonTag/tagged.tpl',
      1 => 1587811039,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f3bacf685a429_47396425 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/chill/MediaContentRibbon/module.css',0));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0));?>

<div id="tag_result">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s10">
                            <h1><?php echo $_smarty_tpl->tpl_vars['this']->value->tag_name;?>
</h1>
                        </div>
                        <div class="col s2">
                            <div class="right-align">
                                <a href="javascript:history.back()" class="back_back">Назад</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ((count($_smarty_tpl->tpl_vars['this']->value->gifs))) {?>
    <div class="tag_list">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="owl-carousel" id="owl-tag-gifs">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->gifs, 'gif');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['gif']->value) {
?>
                        <div class="one_gih">
                            <?php if ($_smarty_tpl->tpl_vars['gif']->value->image) {?>
                            <a class="gif_load">
                                <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">
                                <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['gif']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['gif']->value->image;?>
.SW_400CF_1PR_sq.jpg" class="gif_img" alt="<?php echo $_smarty_tpl->tpl_vars['gif']->value->name;?>
">
                                <img data-src="https://<?php echo $_smarty_tpl->tpl_vars['gif']->value->gif_cdn_url;?>
" src="/assets/chill/images/logo.png" class="gif_gif" alt="<?php echo $_smarty_tpl->tpl_vars['gif']->value->name;?>
">
                            </a>
                            <?php } else { ?>
                            <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                            <?php }?>
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
    <?php }?>
    <?php if ((count($_smarty_tpl->tpl_vars['this']->value->soap))) {?>
    <div class="tag_list">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h2>Сериалы</h2>
                    <div class="owl-carousel" id="owl-tag-soap">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->soap, 'soap');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soap']->value) {
?>
                        <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
">
                            <div class="one_film_in_list">
                                <?php if ($_smarty_tpl->tpl_vars['soap']->value->image) {?>
                                <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['soap']->value->image;?>
.SW_400CF_1PR_vposter.jpg"  alt="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
">
                                <?php } else { ?>
                                <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                <?php }?>
                                <div class="one_film_in_list_title"><?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
</div>
                                <div class="top_one_news_stars top_top_stars aga-ratestars-<?php echo $_smarty_tpl->tpl_vars['soap']->value->ratestars;?>
">
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                </div>
                            </div>
                        </a>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
    <?php if ((count($_smarty_tpl->tpl_vars['this']->value->news))) {?>
    <div class="tag_list">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h2>Новости</h2>
                    <div class="owl-carousel" id="owl-tag-news">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->news, 'new');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['new']->value) {
?>
                        <div class="one_news">
                            <div class="one_news_date">
                                <?php echo $_smarty_tpl->tpl_vars['new']->value->news_post_date_string;?>
 <span><?php echo $_smarty_tpl->tpl_vars['new']->value->news_post_time_string;?>
</span>
                            </div>
                            <a href="/News/<?php echo $_smarty_tpl->tpl_vars['new']->value->id;?>
" title="<?php echo $_smarty_tpl->tpl_vars['new']->value->name;?>
">
                                <div class="one_news_main">
                                    <?php if ($_smarty_tpl->tpl_vars['new']->value->image) {?>
                                    <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['new']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['new']->value->image;?>
.SW_400CF_1PR_sq.jpg"  alt="<?php echo $_smarty_tpl->tpl_vars['new']->value->name;?>
">
                                    <?php } else { ?>
                                    <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                    <?php }?>
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-<?php echo $_smarty_tpl->tpl_vars['new']->value->ratestars;?>
">
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star "></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                    </div>
                                    <div class="one_news_title">
                                        <?php echo $_smarty_tpl->tpl_vars['new']->value->name;?>

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
                $(this).find(".gif_sign").toggle(0);
                $(this).find(".gif_img").toggle(0);
                var a = $(this).find(".gif_gif").data("src");
                $(this).find(".gif_gif").attr("src",a);
                $(this).find(".gif_gif").toggle(0);
            });
        });
<?php echo '</script'; ?>
><?php }
}
