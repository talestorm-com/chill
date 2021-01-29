<?php
/* Smarty version 3.1.33, created on 2020-08-25 17:25:38
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f451f62501b31_88868945',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0a2e2a4726862b279b52bb5b106384cb0d3c77a3' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/default.tpl',
      1 => 1598365533,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./form.tpl' => 1,
    'file:./comment_w_sticker.tpl' => 1,
    'file:./comment_wo_sticker.tpl' => 1,
    'file:./stickers.tpl' => 1,
  ),
),false)) {
function content_5f451f62501b31_88868945 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/css/com_eve.css',0));?>

<div class="container">
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <div class="row">
                <div class="col s10 offset-s1">
                    <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
Wrapper">
                        <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
Inner">
                            <h1><span>Отзывы</span></h1>
                            <p class="podtext">Оставьте свой отзыв о CHILL</p>
                            <?php $_smarty_tpl->_subTemplateRender('file:./form.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                            <div class='<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
CommentList'>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['comments']->value, 'comment');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->value) {
?>
                                    <?php if ($_smarty_tpl->tpl_vars['comment']->value->sticker) {?>
                                        <?php $_smarty_tpl->_subTemplateRender('file:./comment_w_sticker.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
                                    <?php } else { ?>
                                        <?php $_smarty_tpl->_subTemplateRender('file:./comment_wo_sticker.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
                                    <?php }?>
                                    <?php if (($_GET['debug_comments'] == 1)) {?>                                        
                                        <?php if ($_smarty_tpl->tpl_vars['comment']->value->r) {?>
                                            <div><?php echo $_smarty_tpl->tpl_vars['comment']->value->r;?>
</div>
                                        <?php }?>
                                    <?php }?>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>            
                            </div>
                            <?php $_smarty_tpl->_subTemplateRender('file:./stickers.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
>
    
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                jQuery(ready);
            });

            function ready() {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var MC = '<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
';
                var form = jQuery('.' + MC + 'form');
                var sticker_cell = jQuery('#' + MC + 'sticker_place');
                var sticker_id = jQuery('#' + MC + 'sticker_field');
                var token_field = jQuery('#' + MC + 'token');
                var text_cell = jQuery('#' + MC + 'text');
                var send = jQuery('#' + MC + 'send');
                var votetok = '<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('commchillvote',true,21600);?>
';

                sticker_cell.on('click', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    jQuery('#sticker_list_wrapper').show();
                });
                jQuery('#sticker_list_wrapper').on('click', '.one-sticker-from-list', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    jQuery('#sticker_list_wrapper').hide();
                    var t = jQuery(this);
                    sticker_id.val(U.IntMoreOr(t.data('id'), 0, null));
                    sticker_cell.html(['<img src="//', U.NEString(t.data('url'), null), '" />'].join(''));

                });
                jQuery('#close_sticker_list').on('click', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    jQuery('#sticker_list_wrapper').hide();
                });

                function send_vote(comment_id, vec, node) {
                    node.closest('.votepanel').find('a').addClass('sending');
                    jQuery.post('/ComChill/API', {action: "vote", comment_id: comment_id, value: vec, token: votetok})
                            .done(function (d) {
                                if (d.status === 'ok') {
                                    alert("Ваш голос учтен!");
                                    //node.closest('.votepanel').find('a').addClass('disabled');
                                    return;
                                }
                                if (d.status === 'error' && d.error_info.message === "auth") {
                                    window.run_authorization_sequence();
                                    return;
                                }
                                if (d.status === 'error') {
                                    alert(d.error_info.message);
                                    return;
                                }
                                alert("Некорректный ответ сервера!");
                            })
                            .fail(function () {
                                alert("Ошибка сязи с сервером\nпопробуйте повторить через несколько минут");
                            })
                            .always(function () {
                                node.closest('.votepanel').find('a').removeClass('sending');
                            });
                }

                jQuery('.<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
Wrapper').on('click', '.comment-vote-plus', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    if (!jQuery(this).hasClass('disabled') && !jQuery(this).hasClass('sending')) {
                        send_vote(jQuery(this).data('id'), 1, jQuery(this));
                    }
                }).on('click', '.comment-vote-minus', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    if (!jQuery(this).hasClass('disabled') && !jQuery(this).hasClass('sending')) {
                        send_vote(jQuery(this).data('id'), -1, jQuery(this));
                    }
                });

                jQuery('#<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
send').on('click', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    var t = jQuery(this);
                    if (t.hasClass('sending')) {
                        return;
                    }
                    var sticker = U.IntMoreOr(sticker_id.val(), 0, null);
                    var text = U.NEString(form.find('textarea').val(), null);
                    var token = U.NEString(token_field.val(), null);
                    if (!text && !sticker) {
                        alert("Напишите отзыв или выберите стикер\n(Или и то и другое)");
                        return;
                    }
                    t.addClass('sending');
                    jQuery.post('/ComChill/API', {action: "comment", text: text, sticker: sticker, token: token})
                            .done(function (d) {
                                if (d.status === 'ok') {
                                    alert("Ваш голос учтен!\nВаш комментарий будет опубликован после модерации.");
                                    form.remove();
                                    return;
                                }
                                if (d.status === 'error' && d.error_info.message === "auth") {
                                    window.run_authorization_sequence();
                                    return;
                                }
                                if (d.status === 'error') {
                                    alert(d.error_info.message);
                                    return;
                                }
                                alert("Некорректный ответ сервера!");
                            })
                            .fail(function () {
                                alert("Ошибка сязи с сервером\nпопробуйте повторить через несколько минут");
                            })
                            .always(function () {
                                t.removeClass('sending');
                            });
                });
            }
        })();
    
<?php echo '</script'; ?>
>
<?php }
}
