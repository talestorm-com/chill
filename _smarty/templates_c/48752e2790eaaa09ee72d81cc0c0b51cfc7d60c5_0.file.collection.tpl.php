<?php
/* Smarty version 3.1.33, created on 2021-01-19 22:45:49
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/ChillCatalogController/collection.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_600736ed6967c0_82551086',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '48752e2790eaaa09ee72d81cc0c0b51cfc7d60c5' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/ChillCatalogController/collection.tpl',
      1 => 1611085544,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_600736ed6967c0_82551086 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),));
?>
<div id="podbor">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <h1><span class="rib"><span class="bold"><?php echo $_smarty_tpl->tpl_vars['collection']->value->name;?>
</span></span></h1>
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
                    <div class="row">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['collection']->value, 'soap');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soap']->value) {
?>
                        <div class="col s12 l3">
    
        <div class="one_film_in_list chill-lenta-soap-new-<?php echo $_smarty_tpl->tpl_vars['soap']->value->content_type;?>
">
        
<a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
">
<div class="film_left film_left_aa">
              
                    <?php if ($_smarty_tpl->tpl_vars['soap']->value->default_poster) {?>
                    <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['soap']->value->default_poster;?>
.SW_400H_520CF_1.jpg" loading="lazy">
                    <?php } else { ?>
                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" loading="lazy"/>
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
            <?php if ($_smarty_tpl->tpl_vars['soap']->value->track_language_name) {?>
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
                    <div style="color:white;padding:1em;"><?php if ($_smarty_tpl->tpl_vars['collection']->value->additional_content) {
echo $_smarty_tpl->tpl_vars['collection']->value->additional_content;
}?></div>
                </div>
            </div>
        </div>
    </div>
</div><?php }
}
