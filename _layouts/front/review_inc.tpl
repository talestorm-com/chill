<div  id="otz_cover" data-a="1" style="display:none">
    <div class="otz_cover_inner">
        <div id="otz_cover_in">
            <div id="otz_cover_close">
                <i class="mdi mdi-close"></i>
            </div>
            <div id="otz_block">
                <div class="in_ls_block">
                    <div class="row">
                        <div class="col s12 l8 offset-l2">
                            <div class="head3">Оставить отзыв</div>
                            <form id="otz_form" onsubmit="return false;">
                                <div class="ls_input">
                                    <label for="otz_text" class="active">Текст отзыва</label>
                                    <textarea placeholder="Отзыв" id="otz_text" name="comment"></textarea>
                                    <input type='hidden' name='content_id' />
                                    <input type='hidden' name='token' value="{$controller->mk_csrf('review')}" />
                                </div>
                                <div class="input_stars_out">
                                    <div class="input_stars">
                                        <label>
                                            <input type="radio" name="stars" value="1">
                                            <span class="icon">★</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="stars" value="2">
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="stars" value="3">
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="stars" value="4">
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="stars" value="5">
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                            <span class="icon">★</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="ls_btn">
                                    <button id='review-send_btn'>Отправить</button>
                                </div>
                            </form>
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
                var handle = jQuery('#otz_cover');
                var form = jQuery('#otz_form');
                var event_id = null;

                if (!U.isCallable(window.init_review_seqence)) {
                    window.init_review_seqence = function (content_id) {
                        if (U.IntMoreOr(content_id, 0, null)) {
                            form.find('input[name=content_id]').val(content_id);
                            handle.show();
                        }
                    };
                    jQuery('body').on('click', '.init_review_seqence', function (e) {
                        var t = U.IntMoreOr(jQuery(this).data('contentId'), 0, null);
                        if (t) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                            window.init_review_seqence(t);
                        }
                    });

                    function close_form() {
                        form.find('textarea[name=comment]').val('');
                        form.find('input[type=radio]:checked').prop('checked', false);
                        form.find('input[name=content_id]').val('');
                        handle.hide();
                    }
                    handle.on('click', '#review-send_btn', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        var comment = U.NEString(form.find('textarea[name=comment]').val(), null);
                        var rate = U.IntMoreOr(form.find('input[type=radio]:checked').val(), 0, 0);
                        var content_id = U.IntMoreOr(form.find('input[name=content_id]').val(), 0, 0);
                        var csrf = U.NEString(form.find('input[name=token]').val(),null);
                        try {
                            if (!comment) {
                                U.Error("Напишите отзыв!");
                            }
                            if (comment.length > 2048) {
                                U.Error("Ваш отзыв слишком большой!");
                            }
                            if (!rate) {
                                U.Error("Поставьте оценку!");
                            }
                            jQuery.post('/Info/API', {action: "post_review", comment: comment, rate: rate, content_id: content_id,csrf:csrf})
                                    .done(function (d) {
                                        if (U.isObject(d)) {
                                            if (d.status === 'ok') {
                                                alert("Ваш голос учтен.\nПосле проверки мы опубликуем Ваш отзыв.\nСпасибо.");
                                                close_form();
                                                return;
                                            }
                                            if (d.status === 'error') {
                                                if (d.error_info.message === "auth_rqrd") {
                                                    handle.hide();
                                                    if (event_id) {
                                                        EFO.Events.GEM().off(event_id);
                                                        event_id = null;
                                                    }
                                                    event_id = EFO.Events.GEM().on('LOGIN_SUCCESS', window, reinit).id;
                                                    window.run_authorization_sequence();
                                                    return;
                                                }
                                                alert(d.error_info.message);
                                                return;
                                            }
                                            alert("Ошибка связи с сервером!");
                                        }
                                    })
                                    .fail(function () {
                                        alert("Ошибка связи с сервером!");
                                    });
                        } catch (e) {
                            alert(e.message);
                        }
                    });
                    function reinit() {
                        handle.show();
                        if (event_id) {
                            EFO.Events.GEM().off(event_id);
                            event_id = null;
                        }
                    }

                }
            }
        })();
        $("#otz_cover_close").click(function(){
        $("#otz_cover").fadeOut(0);
        });
       
    {/literal}
</script>