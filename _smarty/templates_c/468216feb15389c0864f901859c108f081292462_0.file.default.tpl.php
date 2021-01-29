<?php
/* Smarty version 3.1.33, created on 2020-06-07 09:54:03
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/NewsListController/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5edc8f0b76d068_86873799',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '468216feb15389c0864f901859c108f081292462' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/NewsListController/default.tpl',
      1 => 1587893410,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5edc8f0b76d068_86873799 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="all_news">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l10">
                            <h1>Новости</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="cat_slide">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div id="slider_cat" class="owl-carousel">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'new', false, NULL, 'foo', array (
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['new']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index']++;
?>
  <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index'] : null)%2 == 0) {?>
                        <div>
                            <a href="/News/<?php echo $_smarty_tpl->tpl_vars['new']->value['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['new']->value['name'];?>
">
                                <div class="one_film_janr">
                                    <?php if ($_smarty_tpl->tpl_vars['new']->value['default_poster']) {?>
                                    <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['new']->value['id'];?>
/<?php echo $_smarty_tpl->tpl_vars['new']->value['default_poster'];?>
.SW_1200H_400CF_1PR_hposter.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['new']->value['name'];?>
">
                                    <?php } else { ?>
                                    <img src="/media/fallback/1/media_content_poster.SW_1200H_400CF_1PR_hposter.jpg" />
                                    <?php }?>
                                    <div class="one_film_janr_title"><?php echo $_smarty_tpl->tpl_vars['new']->value['name'];?>
</div>
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-<?php echo $_smarty_tpl->tpl_vars['new']->value['ratestars'];?>
">
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php }?>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="list_news">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'new', false, NULL, 'foo', array (
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['new']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index']++;
?>
  <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index'] : null)%2 == 0) {?>
  <?php } else { ?>
                        <div class="col s12 m6 l4">
                            <div class="one_news">
                                <div class="one_news_date">
                                    <?php echo $_smarty_tpl->tpl_vars['new']->value['news_post_date_string'];?>
 <span><?php echo $_smarty_tpl->tpl_vars['new']->value['news_post_time_string'];?>
</span>
                                </div>
                                <a href="/News/<?php echo $_smarty_tpl->tpl_vars['new']->value['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['new']->value['name'];?>
">
                                    <div class="one_news_main">
                                        <?php if ($_smarty_tpl->tpl_vars['new']->value['default_poster']) {?>
                                        <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['new']->value['id'];?>
/<?php echo $_smarty_tpl->tpl_vars['new']->value['default_poster'];?>
.SW_400CF_1PR_sq.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['new']->value['name'];?>
">
                                        <?php } else { ?>
                                        <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                        <?php }?>
                                        <div class="one_news_stars aga-ratestars-<?php echo $_smarty_tpl->tpl_vars['new']->value['ratestars'];?>
">
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                        </div>
                                        <div class="one_news_title">
                                            <?php echo $_smarty_tpl->tpl_vars['new']->value['name'];?>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php }?>
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
<?php echo '<script'; ?>
>
$('#slider_cat').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    items: 1
});
<?php echo '</script'; ?>
><?php }
}
