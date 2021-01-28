<?php
/* Smarty version 3.1.33, created on 2021-01-20 14:36:22
  from '/var/VHOSTS/site/_views/modules/content/MediaContentGenredList/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_600815b64287f4_22221451',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0c7a23c182f3a7e7aca222f6855cf57e59030fd3' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/MediaContentGenredList/default.tpl',
      1 => 1611142579,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_600815b64287f4_22221451 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/chill/MediaContentRibbon/module.css',0));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0));?>

<div id="genre_result">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <h1><span class="rib"><span class="bold"><?php echo $_smarty_tpl->tpl_vars['this']->value->genre_name;?>
</span></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ((count($_smarty_tpl->tpl_vars['this']->value->soap))) {?>
        <div class="tag_list">
            <div class="container">
                <div class="row">
                    <div class="col s12 m10 offset-m1">
                        <div class="row">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->soap, 'soap');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soap']->value) {
?>
                                <div class="col s12 l3">
                                    
                                        <div class="one_film_in_list">
                                        <div class="film_left">
                                        <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
">
                                            <?php if ($_smarty_tpl->tpl_vars['soap']->value->image) {?>
                                                <img src="/media/media_content_poster/<?php echo $_smarty_tpl->tpl_vars['soap']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['soap']->value->image;?>
.SW_400H_400CF_1PR_sq.jpg">
                                            <?php } else { ?>
                                                <img src="/media/fallback/1/media_content_poster.SW_400H_400CF_1PR_sq.jpg" />
                                            <?php }?>
                                            </a>
                                            </div>
                                            <div class="film_right">
                <div class="one_film_in_list_title_a truncate"><?php echo $_smarty_tpl->tpl_vars['soap']->value->name;?>
</div>
                <div class="one_film_prop">Страна: <span><?php if ($_smarty_tpl->tpl_vars['soap']->value->origin_country_name) {
echo $_smarty_tpl->tpl_vars['soap']->value->origin_country_name;
}?></span></div>
                <div class="one_film_prop">Жанр: <span><?php if ($_smarty_tpl->tpl_vars['soap']->value->genre_name) {
echo $_smarty_tpl->tpl_vars['soap']->value->genre_name;
}?></span></div>
                <div class="one_film_prop"><span><?php if ($_smarty_tpl->tpl_vars['soap']->value->seasons_count) {
echo $_smarty_tpl->tpl_vars['soap']->value->seasons_count;
}?> сезон (<?php if ($_smarty_tpl->tpl_vars['soap']->value->series_count) {
echo $_smarty_tpl->tpl_vars['soap']->value->series_count;
}?> серий)</span></div>
            </div>
            <div class="film_right_lang">
                <span><?php if ($_smarty_tpl->tpl_vars['soap']->value->track_language_name) {
echo $_smarty_tpl->tpl_vars['soap']->value->track_language_name;
}?></span>
            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        </div>
                        <div style="color:white;padding:1em;"><?php if ($_smarty_tpl->tpl_vars['this']->value->additional_content) {
echo $_smarty_tpl->tpl_vars['this']->value->additional_content;
}?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="white_error">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="inner_white_error center-align">
                        Нет сериалов, соответствующих выбранным параметрам
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }?>
</div>


<?php }
}
