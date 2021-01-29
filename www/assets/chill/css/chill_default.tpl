{$OUT->add_css('/assets/chill/css/lk_eve.css',0)|void}
<div id="lk">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l10">
                            <h1>{TT t='User_data'}</h1>
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
                                <label for="lk_name">{TT t='Name'}</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="{TT t='Name'}" value="{$user_info->name}" id="lk_name" name="name">
                                </div>
                            </div>
                            <!-- <div class="one_lk_form_block">
                                <label for="lk_surname">{TT t='FamilyName'}</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="{TT t='FamilyName'}" value="{$user_info->family}" id="lk_surname" name="family">
                                </div>
                            </div> -->
                            <div class="one_lk_form_block">
                                <label for="lk_email">{TT t='Email'}</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="{TT t='Email'}" value="{$user_info->login}" id="lk_email" name="email">
                                </div>
                            </div>
                            <!-- <div class="one_lk_form_block">
                                <label for="lk_phone">{TT t='Phone'}</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="+7900 000 00 00" value="{$user_info->phone}" id="lk_phone" name="phone">
                                </div>
                            </div>-->
                        </div>
                        <div class="col s12 l6 center-align">
                            <div class="lk-avatar-block">
                                <div class="lk-avatar-block-inner">
                                    <div id="lk_photo">
                                        <img src="/media/avatar/{$user_info->id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?ch={$smarty.now}" id="avatar" />
                                    </div>
                                    <div id="lk_change_photo">
                                        {TT t='Change_photo'}
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
                    <h2>{TT t='Change_password'}</h2>
                    <div class="row">
                        <div class="col s12 l6">                            
                            <div class="one_lk_form_block">
                                <label for="new_pass">{TT t='New_password'}</label>
                                <div class="one_lk_form_input">
                                    <input type="password" placeholder="{TT t='New_password'}" value="" autocomplete="new-password" id="new_pass" name="password">
                                </div>
                            </div>
                            <div class="one_lk_form_block">
                                <label for="new_pass2">{TT t='New_password_one_more'}</label>
                                <div class="one_lk_form_input">
                                    <input type="password" placeholder="{TT t='New_password_one_more'}" value="" autocomplete="new-password" id="new_pass2" name="repassword">
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
                                <button id="lk_save_w">{TT t='Save'}</button>
                            </div>
                        </div>
                        <div class="col s6 l3">
                            <div class="lk_btn_desakt">
                                <button id="lk_cancel_w">{TT t='Cancel'}</button>
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
                <h2>{TT t='Interface_lang'}</h2>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="one_lk_form_block">
                            <label for="lang_uang_cabinet_sel">{TT t='Interface_lang'}</label>
                            <div class="one_lk_form_input">
                                {lang_list assign='lang_list' assign_current='cur_lang'}
                                <select id="lang_uang_cabinet_sel" class='browser-default'>
                                    {foreach from=$lang_list item='lang_item'}
                                        {if $lang_item->enabled}
                                            <option value='{$lang_item->id}' {if $lang_item->id eq $cur_lang->id}selected='selected'{/if}>{$lang_item->name} ({$lang_item->name_en})</option>
                                        {/if}
                                    {/foreach}
                                </select>    
                                <script>{literal}
                                    (function () {
                                        window.Eve = window.Eve || {};
                                        window.Eve.EFO = window.Eve.EFO || {};
                                        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                                        window.Eve.EFO.Ready.push(function () {
                                            jQuery('#lang_uang_cabinet_sel').on('change', function () {
                                                jQuery.getJSON('/Info/API', {action: 'set_preferred_language', language: jQuery('#lang_uang_cabinet_sel').val()})
                                                        .done(function (d) {
                                                            if (window.Eve.EFO.U.isObject(d) && d.status === 'ok') {
                                                                location.reload();
                                                            }
                                                        });
                                            });
                                        });
                                    })();
                                </script>{/literal}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<a name="referuu"></a>
<!--    <div class="container">
        <div class="row">
            <div class="col s12 l10 offset-l1">
                <h2>{TT t='your_referal_link'}</h2>
                <div class="row">
                    <div class="col s12 l6">
                    
                        <div class="one_lk_form_block">
                        
                            <label for="referal_link_value">Отправьте ссылку и получите 4 серии</label>
                            <input id="referal_link_value" type="hidden" value="">
                            <div class="lk_btn_akt">

                            <button id="referal_link_btn">Скопировать ссылку</button>
                        </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
-->
    <div class="container">
        <div class="row">
            <div class="col s12 l10 offset-l1">
                <h2>{TT t='Amount'}</h2>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="one_lk_form_block">
                            <label for="balans">{TT t='Balance'}</label>
                            <div class="one_lk_form_input">
                                <input type="text" disabled="disabled" value="{$controller->get_user_ballance_fmt()}" id="balans">
                            </div>
                            <div id="balanse_ser" class="subtext_aa"></div>
                        </div>
                    </div>
                </div>
                <form method="GET" action="/Cabinet/money_money_money" id="moneyform">
                    <div class="row">
                        <div>{TT t='Put_money'}:</div>
                        <div class="col s12 l6">
                            <!--<label for="summ_amount">{TT t='Sum'}</label>-->
                            <div class="lk_ballance_up_btns">
                            <button class="lk_ballance_up" data-price="6">6р<span>(1 серия)</span></button>
                            <button class="lk_ballance_up" data-price="60">60р<span>(10 серий)</span></button>
                            
                            <button class="lk_ballance_up" data-price="120">120р<span>(20 серий)</span></button>
                            </div>
                            <input type="hidden" id="summ_amount" value="60.00" name="summ_amount" placeholder="введите сумму" />

                          
                            <!--<div id="summ_amount_ser" class="subtext_aa"></div>-->
                        </div>
                        <div class="col s6 l2" style="display:none">
                            <div class="lk_btn_akt">
                                <button id="lk_ballance_up">{TT t='Put_on'}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form method="POST" action="/Cabinet/promo_promo_promo" id="promoform" onsubmit="return false">
                    <div class="row">
                        <div>{TT t='Accept_promo'}:</div>
                        <div class="col s6 l4">
                            <label for="cabinet_promo">{TT t='Promo'}</label>
                            <div class="one_lk_form_input">
                            <input type="text" id="cabinet_promo" value="" name="promocode" placeholder="" />
                            </div>
                        </div>
                        <div class="col s6 l2">
                            <div class="lk_btn_akt">
                                <button id="lk_promo_send">{TT t='Accept'}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col s12 l10 offset-l1">
                <div class="row">
                    <div class="col s12 l3">
                        <div class="lk_btn_akt">
                            <a id="logout" href="/Auth/Logout">{TT t='Logout'}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    {literal}
    $(document).ready(function(){
    $(".lk_ballance_up").each(function(){
    $(this).click(function(){
    var a = $(this).data("price");
    $("#summ_amount").val(a);
    })
    })
        $("#referal_link_btn").click(function(){
            var copyText = document.getElementById("referal_link_value");
            copyText.select();
            document.execCommand("copy");
            window.Eve.EFO.Alert().set_text("Ссылка скопирована в буфер обмена, отправь ее другу").set_title("Реферальная ссылка").set_close_btn(true)
                                                .set_style("green").set_timeout(3000).set_callback(window,function(){
                                            }).show();
        });
    });
  $(document).ready(function(){
  var a = $("#summ_amount").val();
          var ax = Math.round(a);
        var b = a / 6;
        var bx = Math.round(b);
        function declOfNum(n, text_forms) {  
            n = Math.abs(n) % 100; var n1 = n % 10;
            if (n > 10 && n < 20) { return text_forms[2]; }
            if (n1 > 1 && n1 < 5) { return text_forms[1]; }
            if (n1 == 1) { return text_forms[0]; }
            return text_forms[2];
        }
        var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
$("#summ_amount_ser").text("*На счет поступит "+bx+" "+nx);

});
$(document).ready(function(){
    var a = $("#balans").val();
          var ax = Math.round(a);
        var b = a / 6;
        var bx = Math.round(b);
        function declOfNum(n, text_forms) {  
            n = Math.abs(n) % 100; var n1 = n % 10;
            if (n > 10 && n < 20) { return text_forms[2]; }
            if (n1 > 1 && n1 < 5) { return text_forms[1]; }
            if (n1 == 1) { return text_forms[0]; }
            return text_forms[2];
        }
        var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
$("#balanse_ser").text("*На вашем счету "+bx+" "+nx);
});
$("#summ_amount").on('keyup', function() {
var a = $(this).val();
 var ax = Math.round(a);
        var b = a / 6;
        var bx = Math.round(b);
        function declOfNum(n, text_forms) {  
            n = Math.abs(n) % 100; var n1 = n % 10;
            if (n > 10 && n < 20) { return text_forms[2]; }
            if (n1 > 1 && n1 < 5) { return text_forms[1]; }
            if (n1 == 1) { return text_forms[0]; }
            return text_forms[2];
        }
        var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
$("#summ_amount_ser").text("*На счет поступит "+bx+" "+nx);
});
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
                    jQuery('#summ_amount').val(EFO.Checks.formatPriceNSD(U.FloatMoreOr(jQuery('#summ_amount').val(), 6, 6), 2));
                });
                jQuery('#lk_phone').on('change', function () {
                    jQuery('#lk_phone').val(EFO.Checks.tryFormatPhone(jQuery('#lk_phone').val()));
                });

                jQuery('#lk_cancel_w').on('click', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    window.location.reload(true);
                });

                jQuery('#lk_save_w').on('click', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    var name = U.NEString(jQuery('#lk_name').val(), null);
                    var family = U.NEString(jQuery('#lk_surname').val(), '');
                    var email = U.NEString(jQuery('#lk_email').val(), null);
                    var phone = U.NEString(jQuery('#lk_phone').val(), null);
                    var password = U.NEString(jQuery('#new_pass').val(), null);
                    var repassword = U.NEString(jQuery('#new_pass2').val(), null);
                    var avatar = jQuery('#new_ava').get(0).files.length ? jQuery('#new_ava').get(0).files[0] : null;
                    try {
                        if (!name) {
                            U.Error("Имя - обязательное поле");
                        }
                        if (!family) {
                            
                            //U.Error("Фамилия - обязательное поле");
                        }
                        if (!email || !EFO.Checks.isEmail(email)) {
                            U.Error("email - обязательное поле");
                        }
                        if (phone && !EFO.Checks.formatPhone(phone)) {
                            U.Error("Номер телефона указан некорректно");
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
                        //alert(e.message);
                        window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                    }
                });
                jQuery('#lk_ballance_up').on('click', function (e) {
                    try {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({event: 'custom_event', event_category: 'balance', event_action: 'init_button'});
                    } catch (e) {

                    }
                    jQuery('#moneyform').submit();
                });
                
                jQuery('#lk_promo_send').on('click', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    var promo_value = U.NEString(jQuery('#cabinet_promo').val(), null);
                    try {
                        promo_value ? 0 : U.Error("Введите промокод");
                        promo_value.length > 100 ? U.Error('Такого длинного промокода не бывает!') : 0;
                        jQuery.post('/Cabinet/API', {action: 'apply_promo', value: promo_value})
                                .done(function (d) {
                                    if (U.isObject(d)) {
                                        if (d.status === 'ok') {
                                            jQuery('#cabinet_promo').val('');
                                            jQuery('#balans').val(d.money);
                                            try {
                                                window.dataLayer = window.dataLayer || [];
                                                window.dataLayer.push({event: 'custom_event', event_category: 'balance', event_action: 'promocode', event_label: promo_value});

                                            } catch (e) {

                                            }
                                            //alert("Промокод применен!\n Ваш баланс пополнен.");
                                            window.Eve.EFO.Alert().set_text("Ваш баланс пополнен.").set_title("Промокод применен").set_close_btn(true)
                                                .set_style("green").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                            return;
                                        }
                                        if (d.status === 'error') {
                                            if (d.error_info.message === 'not found' || d.error_info.message === 'not_found') {
                                                //alert("Промокод не найден!\nПроверьте правильность написания!");
                                                window.Eve.EFO.Alert().set_text("Проверьте правильность написания!").set_title("Промокод не найден!").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                                return;
                                            } else if (d.error_info.message === 'alredy_used') {
                                                //alert("Вы уже использовали этот промокод!\nПромокод не может быть использован повторно.");
                                                window.Eve.EFO.Alert().set_text("Промокод не может быть использован повторно.").set_title("Вы уже использовали этот промокод!").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                                return;
                                            }
                                            //alert(d.error_info.message);
                                            window.Eve.EFO.Alert().set_text(d.error_info.message).set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                            return;
                                        }
                                    }
                                    //alert("Ошибка связи с сервером!");
                                    window.Eve.EFO.Alert().set_text("Ошибка связи с сервером!").set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                })
                                .fail(function () {
                                    //alert("Ошибка сети!");
                                    window.Eve.EFO.Alert().set_text("Ошибка сети!").set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                });
                    } catch (e) {
                        //alert(e.message);
                        window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                    }
                });
            });
        })();
    {/literal}
</script>