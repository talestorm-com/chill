<?php
/* Smarty version 3.1.33, created on 2020-10-30 14:12:46
  from '/var/VHOSTS/site/_views/modules/content/RibbonLent/item_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f9bf52eaee0e5_81850274',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bd90159d1ce63d953fae475636e88920b8ba4b33' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/RibbonLent/item_list.tpl',
      1 => 1604056360,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f9bf52eaee0e5_81850274 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
$_smarty_tpl->_assignInScope('PAGE_UUID', $_smarty_tpl->tpl_vars['OUT']->value->get_uuid());?>

<div class="row">

    <?php $_smarty_tpl->_assignInScope('absolute_index', $_smarty_tpl->tpl_vars['this']->value->get_index_remainder(50));?>    <div class="abindexmarker" data-id="<?php echo $_smarty_tpl->tpl_vars['absolute_index']->value;?>
"></div>
    <?php $_smarty_tpl->_assignInScope('index', 0);?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->items, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>        
        <?php if ($_smarty_tpl->tpl_vars['index']->value > 49) {?>
            <?php $_smarty_tpl->_assignInScope('index', 0);?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['absolute_index']->value > 49) {?>
            <?php $_smarty_tpl->_assignInScope('absolute_index', 0);?>
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['this']->value->inset_exists($_smarty_tpl->tpl_vars['absolute_index']->value)) {?>
            <?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['this']->value->get_inset_path($_smarty_tpl->tpl_vars['absolute_index']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
        <?php }?>



        <?php if ($_smarty_tpl->tpl_vars['item']->value === null) {?>
            <div class="col s12 l3 hide-on-med-and-down">
                <div id="empty_block"></div>
            </div>
        <?php } else { ?>    
            <?php $_smarty_tpl->_assignInScope('image_url', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_600H_400CF_1.jpg");?>
            <?php $_smarty_tpl->_assignInScope('image_urla', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_600H_600CF_1.jpg");?>
            <?php $_smarty_tpl->_assignInScope('image_url_sq', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_400H_400CF_1.jpg");?>
            <?php $_smarty_tpl->_assignInScope('image_url_qq', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_400H_520CF_1.jpg");?>

            <?php if ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctSEASON') {?>
                <div class="col s12 l3">
                    <div class="one_film_in_list chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
" id="<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
">
                        <?php if ($_smarty_tpl->tpl_vars['this']->value->get_debug_enabled()) {?><div style="color:red;font-size:14px;position:absolute;z-index:22;background:white;top:0;left:0"><?php echo $_smarty_tpl->tpl_vars['absolute_index']->value;?>
</div><div style="color:blue;font-size:14px;position:absolute;z-index:22;background:white;top:0;left:20px;"><?php echo $_smarty_tpl->tpl_vars['index']->value;?>
</div><?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value->lent_mode === 'poster') {?>
                            <div class="film_left">
                                <?php if ($_smarty_tpl->tpl_vars['item']->value->age_restriction_name === '0+') {?>
                                    <div class="age_age"><?php echo $_smarty_tpl->tpl_vars['item']->value->age_restriction_name;?>
</div>
                                <?php }?>
                                <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                    <div class="lent_omg_in">
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value->lent_image_name) {?>
                                            <img loading="lazy" src="/media/lent_poster/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['item']->value->lent_image_name;?>
.SW_400H_520CF_1.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
" class="lazyload">
                                        <?php } else { ?>
                                            <img loading="lazy" src="/media/fallback/1/media_content_poster.SW_400H_520CF_1PR_sq.jpg" />
                                        <?php }?>
                                    </div>
                                </a>
                            </div>

                        <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->lent_mode === 'gif') {?>

                            <div class="film_left one_gih">
                                <?php if ($_smarty_tpl->tpl_vars['item']->value->age_restriction_name === '0+') {?>
                                    <div class="age_age"><?php echo $_smarty_tpl->tpl_vars['item']->value->age_restriction_name;?>
</div>
                                <?php }?>
                                <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                    <div class="gif_load">
                                        <div class="lent_omg_in">
                                            <!-- <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">-->
                                            <img src="/media/lent_poster/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['item']->value->lent_image_name;?>
.SW_400H_520CF_1.jpg" class="gif_img" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
" style="display:none;"> 
                                            <img loading="lazy" src="https://<?php echo $_smarty_tpl->tpl_vars['item']->value->gif_cdn_url;?>
" class="gif_gif" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                        </div>
                                    </div>
                                </a>
                            </div>

                        <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->lent_mode === 'video') {?>
                            <div class="film_left">
                                <?php if ($_smarty_tpl->tpl_vars['item']->value->age_restriction_name === '0+') {?>
                                    <div class="age_age"><?php echo $_smarty_tpl->tpl_vars['item']->value->age_restriction_name;?>
</div>
                                <?php }?>
                                <div class="run_trailer" data-id='<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' data-srca='/Soap/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' data-video_url="<?php echo $_smarty_tpl->tpl_vars['item']->value->video_cdn_url;?>
" data-video-id="<?php echo $_smarty_tpl->tpl_vars['item']->value->video_cdn_id;?>
">
                                    <div class="film_left_text_box">
                                        <a class="film_left_text_box_box">

                                            <i class="mdi mdi-play"></i> <?php echo $_smarty_tpl->tpl_vars['item']->value->lent_message;?>


                                        </a>

                                    </div>
                                    <div class="lent_omg_in">
                                        <img loading="lazy" src="/media/lent_poster/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['item']->value->lent_image_name;?>
.SW_400H_520CF_1.jpg" class="gif_img" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                    </div>

                                </div>
                            </div>
                        <?php } else { ?>

                            <div class="film_left">
                                <?php if ($_smarty_tpl->tpl_vars['item']->value->age_restriction_name === '0+') {?>
                                    <div class="age_age"><?php echo $_smarty_tpl->tpl_vars['item']->value->age_restriction_name;?>
</div>
                                <?php }?>
                                <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                    <div class="lent_omg_in">
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value->image) {?>
                                            <img loading="lazy" src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg">
                                        <?php } else { ?>
                                            <img loading="lazy" src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                                        <?php }?>
                                    </div>
                                </a>
                            </div>

                        <?php }?>

                        <div class="film_right">
                            <a href='/Soap/<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                                <div class="film_right_in">
                                    <div class="in_film_right">
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value->free) {?>
                                            <div class="film_right_free">
                                                <span>Free</span>
                                            </div>
                                        <?php }?>
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value->track_language_name) {?>
                                            <div class="film_right_lang ru_lang">
                                                <span>ru</span>
                                            </div>
                                            <?php if ($_smarty_tpl->tpl_vars['item']->value->track_language_name != "ru") {?>
                                                <div class="film_right_lang <?php echo $_smarty_tpl->tpl_vars['item']->value->track_language_name;?>
_lang">
                                                    <span><?php echo $_smarty_tpl->tpl_vars['item']->value->track_language_name;?>
</span>
                                                </div>
                                            <?php }?>
                                        <?php }?>
                                    </div>    
                                    <div class="one_film_in_list_title_a" <?php if ($_smarty_tpl->tpl_vars['item']->value->origin_country_name != '' && $_smarty_tpl->tpl_vars['item']->value->genre_name != '') {
} else { ?>style="max-height: 96px;"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</div>
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value->origin_language != '') {?><div class="one_film_prop one_film_prop_duo"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['item']->value->origin_language,'|','<br>');?>
</div><?php } else { ?>
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value->origin_country_name && $_smarty_tpl->tpl_vars['item']->value->genre_name) {?><div class="one_film_prop"><?php if ($_smarty_tpl->tpl_vars['item']->value->origin_country_name) {?><span><?php echo $_smarty_tpl->tpl_vars['item']->value->origin_country_name;?>
</span>, <?php }
if ($_smarty_tpl->tpl_vars['item']->value->genre_name) {?><span><?php echo $_smarty_tpl->tpl_vars['item']->value->genre_name;?>
</span><?php }?></div><?php }?>
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value->seasons_count && $_smarty_tpl->tpl_vars['item']->value->series_count) {?><div class="one_film_prop"><span><?php if ($_smarty_tpl->tpl_vars['item']->value->seasons_count) {
echo $_smarty_tpl->tpl_vars['item']->value->seasons_count;?>
 <span class="seas_count_sl" data-seas="<?php echo $_smarty_tpl->tpl_vars['item']->value->seasons_count;?>
"></span><?php }?> <?php if ($_smarty_tpl->tpl_vars['item']->value->series_count) {?>(<?php echo $_smarty_tpl->tpl_vars['item']->value->series_count;?>
 <span class="series_count_sl" data-ser="<?php echo $_smarty_tpl->tpl_vars['item']->value->series_count;?>
"></span>)<?php }?> </span></div><?php }?>
                                    <?php }?>
                                </div>
                        </div>
                        </a>
                    </div>

                </div>

            <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctGIF') {?>
                <div class="col s12 l3">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value->gif_target_url;?>
">
                        <div class="one_film_in_list chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
">
                            <div class="film_left one_gih">
                                <div class="gif_load">
                                    <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['image_url_sq']->value;?>
" class="gif_img" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">

                                    <img src="https://<?php echo $_smarty_tpl->tpl_vars['item']->value->gif_cdn_url;?>
" class="gif_gif" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">

                                </div>
                            </div>
                            <div class="film_right">
                                <div class="in_film_right">
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value->free) {?>
                                        <div class="film_right_free">
                                            <span>Free</span>
                                        </div>
                                    <?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value->track_language_name) {?>
                                        <div class="film_right_lang <?php echo $_smarty_tpl->tpl_vars['item']->value->track_language_name;?>
_lang">
                                            <span><?php echo $_smarty_tpl->tpl_vars['item']->value->track_language_name;?>
</span>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="one_film_in_list_title_a"><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</div>
                                <div class="one_film_prop">Страна: <span><?php if ($_smarty_tpl->tpl_vars['item']->value->origin_country_name) {
echo $_smarty_tpl->tpl_vars['item']->value->origin_country_name;
}?></span></div>
                                <div class="one_film_prop">Жанр: <span><?php if ($_smarty_tpl->tpl_vars['item']->value->genre_name) {
echo $_smarty_tpl->tpl_vars['item']->value->genre_name;
}?></span></div>
                                <div class="one_film_prop"><span><?php if ($_smarty_tpl->tpl_vars['item']->value->seasons_count) {
echo $_smarty_tpl->tpl_vars['item']->value->seasons_count;
}?> сезон (<?php if ($_smarty_tpl->tpl_vars['item']->value->series_count) {
echo $_smarty_tpl->tpl_vars['item']->value->series_count;
}?> серий)</span></div>
                            </div>

                        </div>
                    </a>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctTRAILER') {?>
                <div class="col s12 l3">
                    <div class="run_trailer" data-id='<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
' data-srca='<?php echo $_smarty_tpl->tpl_vars['item']->value->trailer_target_url;?>
'>
                        <div class="one_film_in_list chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
">
                            <div class="film_left">
                                <img src="/assets/chill/images/play_sign.png" class="gif_sign" alt="Загрузка">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['image_url_sq']->value;?>
" class="gif_img" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">
                            </div>

                            <div class="film_right">
                                <div class="in_film_right">
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value->free) {?>
                                        <div class="film_right_free">
                                            <span>Free</span>
                                        </div>
                                    <?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value->track_language_name) {?>
                                        <div class="film_right_lang <?php echo $_smarty_tpl->tpl_vars['item']->value->track_language_name;?>
_lang">
                                            <span><?php echo $_smarty_tpl->tpl_vars['item']->value->track_language_name;?>
</span>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="one_film_in_list_title_a"><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</div>
                                <div class="one_film_prop">Страна: <span><?php if ($_smarty_tpl->tpl_vars['item']->value->origin_country_name) {
echo $_smarty_tpl->tpl_vars['item']->value->origin_country_name;
}?></span></div>
                                <div class="one_film_prop">Жанр: <span><?php if ($_smarty_tpl->tpl_vars['item']->value->genre_name) {
echo $_smarty_tpl->tpl_vars['item']->value->genre_name;
}?></span></div>
                                <div class="one_film_prop"><span><?php if ($_smarty_tpl->tpl_vars['item']->value->seasons_count) {
echo $_smarty_tpl->tpl_vars['item']->value->seasons_count;
}?> сезон (<?php if ($_smarty_tpl->tpl_vars['item']->value->series_count) {
echo $_smarty_tpl->tpl_vars['item']->value->series_count;
}?> серий)</span></div>
                            </div>

                        </div>
                    </div>
                </div>

            <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctBANNER') {?>
                <div class="chill-lenta-item-new chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
 col s12 l4">
                    <div class="banner_collection">
                        <a <?php if ($_smarty_tpl->tpl_vars['item']->value->banner_url != '') {?>href="<?php echo $_smarty_tpl->tpl_vars['item']->value->banner_url;?>
" target="_blank"<?php }?> class="ribbon_link_out">
                            <div class="chill_main_lent_block" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['image_urla']->value;?>
)">
                            </div>
                        </a>
                    </div>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctCOLLECTION') {?>
                <div class="chill-lenta-item-new chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
 col s12 l4"  id="<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
">
                    <div class="lenta_collection"> 
                        <?php if ($_smarty_tpl->tpl_vars['this']->value->get_debug_enabled()) {?><div style="color:red;font-size:14px;position:absolute;z-index:22;background:white;top:0;left:0"><?php echo $_smarty_tpl->tpl_vars['absolute_index']->value;?>
</div><div style="color:blue;font-size:14px;position:absolute;z-index:22;background:white;top:0;left:20px;"><?php echo $_smarty_tpl->tpl_vars['index']->value;?>
</div><?php }?>
                        <a href="/collection/<?php echo $_smarty_tpl->tpl_vars['item']->value->content_id;?>
" title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">

                            <div class="chill_main_lent_block" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['image_urla']->value;?>
)">
                            </div>
                        </a>
                    </div>
                </div>
            <?php }?>
        <?php }?>
        <?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['index']->value+1);?>
        <?php $_smarty_tpl->_assignInScope('absolute_index', $_smarty_tpl->tpl_vars['absolute_index']->value+1);?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php echo '<script'; ?>
>

        
            (function () {
                window.global_season_counter_ribbon = window.global_season_counter_ribbon || 0;
                window.global_collection_slot_number = window.global_collection_slot_number || 0;
                var items = [];
                var ids_to_monitor = [];
                var monitorable_data = {};
        
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->items, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
            <?php if ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctSEASON' || $_smarty_tpl->tpl_vars['item']->value->content_type === 'ctCOLLECTION') {?>
                
                        ids_to_monitor.push('<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
');
                <?php if ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctSEASON') {?>
                                window.global_season_counter_ribbon++;
                                monitorable_data['<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
'] = {
                                            'name': '<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
',
                                            'id': '<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
',
                                            'price': '0', // стоимость
                                            'brand': '<?php echo $_smarty_tpl->tpl_vars['item']->value->origin_country_name;?>
',
                                            'category': '<?php echo $_smarty_tpl->tpl_vars['item']->value->genre_name;?>
',
                                            'list': 'Lenta',
                                            'position': window.global_season_counter_ribbon
                                        };
                <?php } else { ?>
                                        window.global_collection_slot_number++;
                                        monitorable_data['<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
'] = {
                                                    'name': '<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
',
                                                    'id': '<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
',
                                                    'creative': '<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
',
                                                    'position': window.global_collection_slot_number
                                                };

                <?php }?>
                
            <?php }?>

        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        
                        function is_in_viewport(n) {
                            var r = n.getBoundingClientRect();
                            return (
                                    r.top <= (window.innerHeight || document.documentElement.clientHeight)
                                    //r.top >= 0 &&
                                    //r.left >= 0 
                                    //&& r.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                                    //r.right <= (window.innerWidth || document.documentElement.clientWidth)
                                    );
                        }
                        if (ids_to_monitor.length) {
                            var o = {};
                            o['scroll_<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
'] = function (e) {
                                // check who is in viewport
                                // debugger;
                                var items_in_viewport = [];
                                var collections_in_viewport = [];
                                var nmi = [];
                                for (var i = 0; i < ids_to_monitor.length; i++) {
                                    var node = document.getElementById(ids_to_monitor[i]);
                                    if (!node) {
                                        console.log('no node:' + ids_to_monitor[i]);
                                        continue;
                                    }
                                    //debugger;
                                    if (is_in_viewport(node)) {
                                        var dta = monitorable_data[ids_to_monitor[i]];
                                        if (dta && (typeof (dta) === 'object') && dta.hasOwnProperty('creative')) {
                                            collections_in_viewport.push(monitorable_data[ids_to_monitor[i]]);
                                        } else {
                                            items_in_viewport.push(monitorable_data[ids_to_monitor[i]]);
                                        }
                                    } else {
                                        nmi.push(ids_to_monitor[i]);
                                    }
                                }
                                ids_to_monitor = nmi;
                                // console.log(ids_to_monitor);
                                if (!ids_to_monitor.length) {
                                    document.removeEventListener('scroll', o['scroll_<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
']);
                                    monitorable_data = null;
                                }

                                if (items_in_viewport.length) {
                                    window.dataLayer = window.dataLayer || [];
                                    window.dataLayer.push({event: 'gtm-ee-event', "gtm-ee-event-category": 'Enhanced Ecommerce',
                                        "gtm-ee-event-action": 'Product Impressions', "gtm-ee-event-non-interaction": 'True',
                                        ecommerce: {currencyCode: 'RUB', impressions: items_in_viewport}});
                                    console.log('ga_posted_items', items_in_viewport);
                                }
                                if (collections_in_viewport.length) {
                                    window.dataLayer = window.dataLayer || [];
                                    window.dataLayer.push({
                                        'ecommerce': {
                                            'promoView': {
                                                'promotions': collections_in_viewport
                                            }
                                        },
                                        'event': 'gtm-ee-event', 'gtm-ee-event-category': 'Enhanced Ecommerce', 'gtm-ee-event-action': 'Promotion Impressions', 'gtm-ee-event-non-interaction': 'True'
                                    });
                                    console.log('ga_posted_collections', collections_in_viewport);
                                }
                            };
                            o['scroll_<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
']();
                            document.addEventListener('scroll', o['scroll_<?php echo $_smarty_tpl->tpl_vars['PAGE_UUID']->value;?>
']);
                        }
                    })();
        
    <?php echo '</script'; ?>
>
</div><?php }
}
