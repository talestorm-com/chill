{$OUT->add_css('/assets/chill/css/com_eve.css',0)|void}
<div class="container">
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <div class="row">
                <div class="col s10 offset-s1">
                    <div class="{$controller->MC}Wrapper">
                        <div class="{$controller->MC}Inner">
                            <h1><span>Отзывы</span></h1>
                            <p class="podtext">Оставьте свой отзыв о CHILL</p>
                            {include './form.tpl'}
                            <div class='{$controller->MC}CommentList'>
                                {foreach from=$comments item='comment'}
                                    {if $comment->sticker}
                                        {include './comment_w_sticker.tpl'}
                                    {else}
                                        {include './comment_wo_sticker.tpl'}
                                    {/if}
                                    {if ($smarty.get.debug_comments eq 1)}                                        
                                        {if $comment->r}
                                            <div>{$comment->r}</div>
                                        {/if}
                                    {/if}
                                {/foreach}            
                            </div>
                            {include './stickers.tpl'}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                jQuery(ready);
            });

            function ready() {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var MC = '{/literal}{$controller->MC}{literal}';
                var form = jQuery('.' + MC + 'form');
                var sticker_cell = jQuery('#' + MC + 'sticker_place');
                var sticker_id = jQuery('#' + MC + 'sticker_field');
                var token_field = jQuery('#' + MC + 'token');
                var text_cell = jQuery('#' + MC + 'text');
                var send = jQuery('#' + MC + 'send');
                var votetok = '{/literal}{$controller->mk_csrf('commchillvote',true,21600)}{literal}';

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

                jQuery('.{/literal}{$controller->MC}{literal}Wrapper').on('click', '.comment-vote-plus', function (e) {
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

                jQuery('#{/literal}{$controller->MC}{literal}send').on('click', function (e) {
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
    {/literal}
</script>
