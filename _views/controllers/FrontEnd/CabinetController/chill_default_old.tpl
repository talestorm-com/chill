{$OUT->add_css('/assets/chill/css/lk_eve.css',0)|void}
<div id="lk">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l10">
                            <h1>Личные данные</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="lk_form_1"  method="POST" action="/Cabinet/Chill_save" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l6">
                            <div class="one_lk_form_block">
                                <label for="lk_name">Имя</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="Имя" value="{$user_info->name}" id="lk_name" name="name">
                                </div>
                            </div>
                            <div class="one_lk_form_block">
                                <label for="lk_surname">Фамилия</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="Фамилия" value="{$user_info->family}" id="lk_surname" name="family">
                                </div>
                            </div>
                        </div>
                        <div class="col s12 l6 center-align">
                            <div class="lk-avatar-block">
                                <div class="lk-avatar-block-inner">
                                    <div id="lk_photo">
                                        <img src="/media/avatar/{$user_info->id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg" id="avatar" />
                                    </div>
                                    <div id="lk_change_photo">
                                        Заменить
                                    </div>
                                </div>
                                <input type="file" accept="image/*" id="new_ava" name="ava" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h2>Сменить пароль</h2>
                    <div class="row">
                        <div class="col s12 l6">                            
                            <div class="one_lk_form_block">
                                <label for="new_pass">Новый пароль</label>
                                <div class="one_lk_form_input">
                                    <input type="password" placeholder="Новый пароль" value="" autocomplete="new-password" id="new_pass" name="password">
                                </div>
                            </div>
                            <div class="one_lk_form_block">
                                <label for="new_pass2">Повторить пароль</label>
                                <div class="one_lk_form_input">
                                    <input type="password" placeholder="Повторить пароль" value="" autocomplete="new-password" id="new_pass2" name="repassword">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s6 l3">
                            <div class="lk_btn_akt">
                                <button id="lk_save_w">Сохранить</button>
                            </div>
                        </div>
                        <div class="col s6 l3">
                            <div class="lk_btn_desakt">
                                <button id="lk_cancel_w">Отменить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="container">
        <div class="row">
            <div class="col s12 l10 offset-l1">
                <h2>Счет</h2>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="one_lk_form_block">
                            <label for="balans">Баланс</label>
                            <div class="one_lk_form_input">
                                <input type="text" disabled="disabled" value="{$controller->get_user_ballance_fmt()}" id="balans">
                            </div>
                        </div>
                    </div>
                </div>
                <form method="GET" action="/Cabinet/money_money_money" id="moneyform">
                    <div class="row">
                        <div>Пополнить баланс:</div>
                        <div class="col s12 l6">
                            <label for="summ_amount">Сумма</label>
                            <input type="text" id="summ_amount" value="100.00" name="summ_amount" />
                        </div>
                        <div class="col s12 l3">
                            <div class="lk_btn_akt">
                                <button id="lk_ballance_up">Пополнить</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                jQuery('#new_ava').on('change', function () {
                    if (this.files.length) {
                        var f = this.files[0];
                        jQuery('#avatar').attr('src', URL.createObjectURL(f));
                    }
                });

                jQuery('#summ_amount').on('change', function () {
                    jQuery('#summ_amount').val(EFO.Checks.formatPriceNSD(U.FloatMoreOr(jQuery('#summ_amount').val(), 100, 100), 2));
                });

                jQuery('#lk_cancel_w').on('click', function () {
                    window.location.reload(true);
                });

                jQuery('#lk_save_w').on('click', function () {
                    var name = U.NEString(jQuery('#lk_name').val(), null);
                    var family = U.NEString(jQuery('#lk_surname').val(), null);
                    var password = U.NEString(jQuery('#new_pass').val(), null);
                    var repassword = U.NEString(jQuery('#new_pass2').val(), null);
                    var avatar = jQuery('#new_ava').get(0).files.length ? jQuery('#new_ava').get(0).files[0] : null;
                    try {
                        if (!name) {
                            U.Error("Имя - обязательное поле");
                        }
                        if (password || repassword) {
                            if (password.length < 6) {
                                U.Error("Минимальный пароль - 6 символов");
                            }
                            if (password !== repassword) {
                                U.Error('Пароли не совпадают!');
                            }
                        }
                        jQuery('#lk_form_1').submit();
                    } catch (e) {
                        alert(e.message);
                    }
                });
                jQuery('#lk_ballance_up').on('click', function (e) {
                    jQuery('#moneyform').submit();
                });
            });
        })();
    {/literal}
</script>