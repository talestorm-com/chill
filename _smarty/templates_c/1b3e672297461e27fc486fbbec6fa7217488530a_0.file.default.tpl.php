<?php
/* Smarty version 3.1.33, created on 2020-06-07 09:54:11
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/ChillCatalogController/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5edc8f131cf848_38223869',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1b3e672297461e27fc486fbbec6fa7217488530a' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/ChillCatalogController/default.tpl',
      1 => 1590757204,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5edc8f131cf848_38223869 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="a_one_cat">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col l10 s12">
                            <h1>Каталог</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $_smarty_tpl->_assignInScope('last_x_items', $_smarty_tpl->tpl_vars['controller']->value->last_contents());?>
    <div id="cat_slide">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div id="slider_cat" class="owl-carousel">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['last_x_items']->value, 'item', false, NULL, 'foo', array (
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index']++;
?>
                        <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foo']->value['index'] : null)%2 == 0) {?>
                        <div>
                            <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                <div class="one_film_janr">
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value->image) {?>
                                    <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['item']->value->image;?>
.SW_1200H_400CF_1PR_hposter.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                    <?php } else { ?>
                                    <img src="/media/fallback/1/media_content_poster.SW_1200H_400CF_1PR_hposter.jpg" />
                                    <?php }?>
                                    <div class="one_film_janr_title"><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</div>
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-<?php echo $_smarty_tpl->tpl_vars['item']->value->ratestars;?>
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
    <div id="films_filter">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l9">
                            <div class="col_1"></div>
                            <div id="filters" class="row">
                                <div class="col s12 l4">
                                    <label for="select_country">Страна</label>
                                    <select id="select_country">
                                        <option disabled selected>Выберите страну</option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['country_list']->value, 'c');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
?>
                                        
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                </div>
                                <div class="col s12 l4" id="emo_block">
                                    <label for="select_emoji">Эмоции</label>
                                    <select id="select_emoji">
                                        <option disabled selected>Выберите эмоцию</option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['emoji_list']->value, 'c');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
?>
                                        
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
" data-icon="/media/emojirenderer/<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
/emoji.SW_45H_45CF_1B_ffffff.jpg"><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                </div>
                                <div class="col s12 l4">
                                    <label for="select_genre">Жанр</label>
                                    <select id="select_genre">
                                        <option disabled selected>Выберите жанр</option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['genre_list']->value, 'c');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 l3">
                            <div class="center-align">
                                <a id="filter_open" class="back_back">Фильтры <i class="mdi mdi-chevron-up"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="films_list_a">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rows']->value, 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                    <?php if (count($_smarty_tpl->tpl_vars['row']->value->soap) || count($_smarty_tpl->tpl_vars['row']->value->video)) {?>
                    <div class="one_films_group">
                        <div class="row">
                            <div class="col s8">
                                <h2><?php echo $_smarty_tpl->tpl_vars['row']->value->genre_name;?>
</h2>
                            </div>
                            <div class="s4">
                                <div class="right-align">
                                    <a href="/search/by_genre/<?php echo $_smarty_tpl->tpl_vars['row']->value->genre_id;?>
" class="back_back">посмотреть все</a>
                                </div>
                            </div>
                        </div>
                        <div class="owl-carousel owl-carousela" id="<?php echo $_smarty_tpl->tpl_vars['row']->value->genre_id;?>
">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value->soap, 'soap');
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
.SW_400CF_1PR_vposter.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
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
<?php echo '<script'; ?>
>
$('.owl-carousela').owlCarousel({
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

$('#slider_cat').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    items: 1
});

$("#select_country").change(function() {
    var selectcountry = $(this).children("option:selected").val();
    window.location.assign('/search/by_origin/' + selectcountry);
});
$("#select_emoji").change(function() {
    var selectemoji = $(this).children("option:selected").val();
    window.location.assign('/search/by_emoji/' + selectemoji);

});
$("#select_genre").change(function() {
    var selectgenre = $(this).children("option:selected").val();
    window.location.assign('/search/by_genre/' + selectgenre);
});
$("#filter_open").click(function() {
    $("#filters").fadeToggle(0);
});
<?php echo '</script'; ?>
><?php }
}
