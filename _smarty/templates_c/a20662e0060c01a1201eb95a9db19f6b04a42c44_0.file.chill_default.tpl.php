<?php
/* Smarty version 3.1.33, created on 2020-09-30 13:15:06
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f745aaa9fe2d6_04888080',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a20662e0060c01a1201eb95a9db19f6b04a42c44' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_default.tpl',
      1 => 1601460904,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f745aaa9fe2d6_04888080 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),2=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.lang_list.php','function'=>'smarty_function_lang_list',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/css/lk_eve.css',0));?>

<div id="lk">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <h1><span class="rib"><span class="bold">Профиль</span></span></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="price_aa_pay" style="display:none">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <h2><?php echo smarty_function_TT(array('t'=>'Amount'),$_smarty_tpl);?>
</h2>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="one_lk_form_block">
                            <label for="balans"><?php echo smarty_function_TT(array('t'=>'Balance'),$_smarty_tpl);?>
</label>
                            <div class="one_lk_form_input">
                                <input type="text" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['controller']->value->get_user_ballance_fmt();?>
" id="balans">
                            </div>
                            <div id="balanse_ser" class="subtext_aa"></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="/Cabinet/money_money_money" id="moneyform">
                    <div class="row">
                        <div><?php echo smarty_function_TT(array('t'=>'Put_money'),$_smarty_tpl);?>
:</div>
                        <div class="col s12 l6">
                            <!--<label for="summ_amount"><?php echo smarty_function_TT(array('t'=>'Sum'),$_smarty_tpl);?>
</label>-->
                            <div class="lk_ballance_up_btns">
                                <button class="lk_ballance_up" data-price="6">6р<span>(1 серия)</span></button>
                                <button class="lk_ballance_up" data-price="60">60р<span>(10 серий)</span></button>

                                <button class="lk_ballance_up" data-price="120">120р<span>(20 серий)</span></button>
                            </div>
                            <input type="hidden" id="summ_amount" value="60.00" name="summ_amount" placeholder="введите сумму" />
                            <input type='hidden' id='payment_token' name='token' value='<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('wallet');?>
' />

                            <!--<div id="summ_amount_ser" class="subtext_aa"></div>-->
                        </div>
                        <div class="col s6 l2" style="display:none">
                            <div class="lk_btn_akt">
                                <button id="lk_ballance_up"><?php echo smarty_function_TT(array('t'=>'Put_on'),$_smarty_tpl);?>
</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form method="POST" action="/Cabinet/promo_promo_promo" id="promoform" onsubmit="return false">
                    <div class="row">
                        <div><?php echo smarty_function_TT(array('t'=>'Accept_promo'),$_smarty_tpl);?>
:</div>
                        <div class="col s6 l4">
                            <label for="cabinet_promo"><?php echo smarty_function_TT(array('t'=>'Promo'),$_smarty_tpl);?>
</label>
                            <div class="one_lk_form_input">
                                <input type="text" id="cabinet_promo" value="" name="promocode" placeholder="" />
                                <input type='hidden' id='promo_token' name='promo_token' value='<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('promo');?>
' />
                            </div>
                        </div>
                        <div class="col s6 l2">
                            <div class="lk_btn_akt">
                                <button id="lk_promo_send"><?php echo smarty_function_TT(array('t'=>'Accept'),$_smarty_tpl);?>
</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php echo '<script'; ?>
>
        $.get('https://kino-cache.cdnvideo.ru/kinoteatr/wic/pix.jpg').done(function () {
            $("#price_aa_pay").fadeIn(0);
        })
                .fail(function () {

                    $("#price_aa_pay").remove();
                });
    <?php echo '</script'; ?>
>
    <a name="referuu"></a>
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <h2><?php echo smarty_function_TT(array('t'=>'your_referal_link'),$_smarty_tpl);?>
</h2>
                <div class="row">
                    <div class="col s12 l6">

                        <div class="one_lk_form_block">

                            <label for="referal_link_value">отправь ссылку другу - и он получит 8 серий, а сам получи ещё 4!</label>
                            <input type="text" readonly="readonly" value="<?php echo $_smarty_tpl->tpl_vars['referal_link']->value;?>
" id="referal_link_value" />
                            <div class="lk_btn_akt">

                                <button id="referal_link_btn">Скопировать ссылку</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 l10">
                            <h2><?php echo smarty_function_TT(array('t'=>'User_data'),$_smarty_tpl);?>
</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="lk_form_1"  method="POST" action="/Cabinet/Chill_save" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 l6">
                            <div class="one_lk_form_block">
                                <label for="lk_name"><?php echo smarty_function_TT(array('t'=>'Name'),$_smarty_tpl);?>
</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="<?php echo smarty_function_TT(array('t'=>'Name'),$_smarty_tpl);?>
" value="<?php echo $_smarty_tpl->tpl_vars['user_info']->value->name;?>
" id="lk_name" name="name">
                                    <input type='hidden' name='token' id='cabinet_token' value='<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('profile');?>
' />
                                </div>
                            </div>
                            <!-- <div class="one_lk_form_block">
                                <label for="lk_surname"><?php echo smarty_function_TT(array('t'=>'FamilyName'),$_smarty_tpl);?>
</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="<?php echo smarty_function_TT(array('t'=>'FamilyName'),$_smarty_tpl);?>
" value="<?php echo $_smarty_tpl->tpl_vars['user_info']->value->family;?>
" id="lk_surname" name="family">
                                </div>
                            </div> -->
                            <div class="one_lk_form_block">
                                <label for="lk_email"><?php echo smarty_function_TT(array('t'=>'Email'),$_smarty_tpl);?>
</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="<?php echo smarty_function_TT(array('t'=>'Email'),$_smarty_tpl);?>
" value="<?php echo $_smarty_tpl->tpl_vars['user_info']->value->login;?>
" id="lk_email" name="email">
                                </div>
                            </div>
                            <!-- <div class="one_lk_form_block">
                                <label for="lk_phone"><?php echo smarty_function_TT(array('t'=>'Phone'),$_smarty_tpl);?>
</label>
                                <div class="one_lk_form_input">
                                    <input type="text" placeholder="+7900 000 00 00" value="<?php echo $_smarty_tpl->tpl_vars['user_info']->value->phone;?>
" id="lk_phone" name="phone">
                                </div>
                            </div>-->
                        </div>
                        <!--<div class="col s12 l6 center-align">
                            <div class="lk-avatar-block">
                                <div class="lk-avatar-block-inner">
                                    <div id="lk_photo">
                                        <img src="/media/avatar/<?php echo $_smarty_tpl->tpl_vars['user_info']->value->id;?>
/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?ch=<?php echo time();?>
" id="avatar" />
                                    </div>
                                    <div id="lk_change_photo">
                                        <?php echo smarty_function_TT(array('t'=>'Change_photo'),$_smarty_tpl);?>

                                    </div>
                                </div>
                                <input type="file" accept="image/*" id="new_ava" name="ava" />
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <h2><?php echo smarty_function_TT(array('t'=>'Change_password'),$_smarty_tpl);?>
</h2>
                    <div class="row">
                        <div class="col s12 l6">                            
                            <div class="one_lk_form_block">
                                <label for="new_pass"><?php echo smarty_function_TT(array('t'=>'New_password'),$_smarty_tpl);?>
</label>
                                <div class="one_lk_form_input">
                                    <input type="password" placeholder="<?php echo smarty_function_TT(array('t'=>'New_password'),$_smarty_tpl);?>
" value="" autocomplete="new-password" id="new_pass" name="password">
                                </div>
                            </div>
                            <div class="one_lk_form_block">
                                <label for="new_pass2"><?php echo smarty_function_TT(array('t'=>'New_password_one_more'),$_smarty_tpl);?>
</label>
                                <div class="one_lk_form_input">
                                    <input type="password" placeholder="<?php echo smarty_function_TT(array('t'=>'New_password_one_more'),$_smarty_tpl);?>
" value="" autocomplete="new-password" id="new_pass2" name="repassword">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s6 l3">
                            <div class="lk_btn_akt">
                                <button id="lk_save_w"><?php echo smarty_function_TT(array('t'=>'Save'),$_smarty_tpl);?>
</button>
                            </div>
                        </div>
                        <div class="col s6 l3">
                            <div class="lk_btn_desakt">
                                <button id="lk_cancel_w"><?php echo smarty_function_TT(array('t'=>'Cancel'),$_smarty_tpl);?>
</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>                 
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <h2><?php echo smarty_function_TT(array('t'=>'Interface_lang'),$_smarty_tpl);?>
</h2>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="one_lk_form_block">
                            <label for="lang_uang_cabinet_sel"><?php echo smarty_function_TT(array('t'=>'Interface_lang'),$_smarty_tpl);?>
</label>
                            <div class="one_lk_form_input">
                                <?php echo smarty_function_lang_list(array('assign'=>'lang_list','assign_current'=>'cur_lang'),$_smarty_tpl);?>

                                <select id="lang_uang_cabinet_sel" class='browser-default'>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lang_list']->value, 'lang_item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['lang_item']->value) {
?>
                                        <?php if ($_smarty_tpl->tpl_vars['lang_item']->value->enabled) {?>
                                            <option value='<?php echo $_smarty_tpl->tpl_vars['lang_item']->value->id;?>
' <?php if ($_smarty_tpl->tpl_vars['lang_item']->value->id == $_smarty_tpl->tpl_vars['cur_lang']->value->id) {?>selected='selected'<?php }?>><?php echo $_smarty_tpl->tpl_vars['lang_item']->value->name;?>
 (<?php echo $_smarty_tpl->tpl_vars['lang_item']->value->name_en;?>
)</option>
                                        <?php }?>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </select>    
                                <?php echo '<script'; ?>
>
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
                                <?php echo '</script'; ?>
>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s12 l3">
                        <div class="lk_btn_akt">
                            <a id="logout" href="/Auth/Logout"><?php echo smarty_function_TT(array('t'=>'Logout'),$_smarty_tpl);?>
</a>
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
                jQuery(function () {
                    var E = window.Eve, EFO = E.EFO, U = EFO.U;
                    jQuery(".lk_ballance_up").on('click', function (e) {
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        e.stopPropagation();
                        var a = U.FloatMoreOr(jQuery(this).data('price'), 0, 0);
                        if (a) {
                            jQuery("#summ_amount").val(a);
                            try {
                                window.dataLayer = window.dataLayer || [];
                                window.dataLayer.push({event: 'custom_event', event_category: 'balance', event_action: 'init_button','event_label': a.toString()});
                            } catch (e) {

                            }
                            jQuery('#moneyform').submit();
                        }
                    });                 
                });
            });
        })();
        $(document).ready(function () {
            $("#referal_link_btn").click(function () {
                var copyText = document.getElementById("referal_link_value");
                copyText.select();
                document.execCommand("copy");
                window.Eve.EFO.Alert().set_text("Ссылка скопирована в буфер обмена, отправь ее другу").set_title("Реферальная ссылка").set_close_btn(true)
                        .set_style("green").set_timeout(3000).set_callback(window, function () {
                }).show();
            });
        });
        $(document).ready(function () {
            var a = $("#summ_amount").val();
            var ax = Math.round(a);
            var b = a / 6;
            var bx = Math.round(b);
            function declOfNum(n, text_forms) {
                n = Math.abs(n) % 100;
                var n1 = n % 10;
                if (n > 10 && n < 20) {
                    return text_forms[2];
                }
                if (n1 > 1 && n1 < 5) {
                    return text_forms[1];
                }
                if (n1 == 1) {
                    return text_forms[0];
                }
                return text_forms[2];
            }
            var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
            $("#summ_amount_ser").text("*На счет поступит " + bx + " " + nx);

        });
        $(document).ready(function () {

            var a = $("#balans").val();
            var ax = Math.round(a);
            var b = a / 6;
            var bx = Math.round(b);
            function declOfNum(n, text_forms) {
                n = Math.abs(n) % 100;
                var n1 = n % 10;
                if (n > 10 && n < 20) {
                    return text_forms[2];
                }
                if (n1 > 1 && n1 < 5) {
                    return text_forms[1];
                }
                if (n1 == 1) {
                    return text_forms[0];
                }
                return text_forms[2];
            }
            var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
            $("#balanse_ser").text("*На вашем счету " + bx + " " + nx);
        });
        $("#summ_amount").on('keyup', function () {
            var a = $(this).val();
            var ax = Math.round(a);
            var b = a / 6;
            var bx = Math.round(b);
            function declOfNum(n, text_forms) {
                n = Math.abs(n) % 100;
                var n1 = n % 10;
                if (n > 10 && n < 20) {
                    return text_forms[2];
                }
                if (n1 > 1 && n1 < 5) {
                    return text_forms[1];
                }
                if (n1 == 1) {
                    return text_forms[0];
                }
                return text_forms[2];
            }
            var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
            $("#summ_amount_ser").text("*На счет поступит " + bx + " " + nx);
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
                    var csrf = U.NEString(jQuery('#cabinet_token').val(), '');
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
                                .set_style("red").set_timeout(3000).set_callback(window, function () {
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
                    var csrf = U.NEString(jQuery('#promo_token').val(), null);
                    try {
                        promo_value ? 0 : U.Error("Введите промокод");
                        promo_value.length > 100 ? U.Error('Такого длинного промокода не бывает!') : 0;
                        jQuery.post('/Cabinet/API', {action: 'apply_promo', value: promo_value, token: csrf})
                                .done(function (d) {
                                    if (U.isObject(d)) {
                                        if (d.status === 'ok') {
                                            jQuery('#cabinet_promo').val('');
                                            jQuery('#balans').val(d.money);
                                            var b = d.money / 6;
                                            var bx = Math.round(b);
                                            function declOfNum(n, text_forms) {
                                                n = Math.abs(n) % 100;
                                                var n1 = n % 10;
                                                if (n > 10 && n < 20) {
                                                    return text_forms[2];
                                                }
                                                if (n1 > 1 && n1 < 5) {
                                                    return text_forms[1];
                                                }
                                                if (n1 == 1) {
                                                    return text_forms[0];
                                                }
                                                return text_forms[2];
                                            }
                                            var nx = declOfNum(bx, ['серия', 'серии', 'серий']);

                                            $("#balans_in").html('<a href="/Profile"><img src="/assets/chill/images/00_wallet.svg"> <span id="balans_in_out_ser">' + bx + '</span> ' + nx + '</a>');

                                            try {
                                                window.dataLayer = window.dataLayer || [];
                                                window.dataLayer.push({event: 'custom_event', event_category: 'balance', event_action: 'promocode', event_label: promo_value});

                                            } catch (e) {

                                            }
                                            //alert("Промокод применен!\n Ваш баланс пополнен.");
                                            window.Eve.EFO.Alert().set_text("Ваш баланс пополнен.").set_title("Промокод применен").set_close_btn(true)
                                                    .set_style("green").set_timeout(3000).set_callback(window, function () {
                                            }).show();
                                            return;
                                        }
                                        if (d.status === 'error') {
                                            if (d.error_info.message === 'not found' || d.error_info.message === 'not_found') {
                                                //alert("Промокод не найден!\nПроверьте правильность написания!");
                                                window.Eve.EFO.Alert().set_text("Проверьте правильность написания!").set_title("Промокод не найден!").set_close_btn(true)
                                                        .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                }).show();
                                                return;
                                            } else if (d.error_info.message === 'alredy_used') {
                                                //alert("Вы уже использовали этот промокод!\nПромокод не может быть использован повторно.");
                                                window.Eve.EFO.Alert().set_text("Промокод не может быть использован повторно.").set_title("Вы уже использовали этот промокод!").set_close_btn(true)
                                                        .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                }).show();
                                                return;
                                            }
                                            //alert(d.error_info.message);
                                            window.Eve.EFO.Alert().set_text(d.error_info.message).set_title("Ошибка").set_close_btn(true)
                                                    .set_style("red").set_timeout(3000).set_callback(window, function () {
                                            }).show();
                                            return;
                                        }
                                    }
                                    //alert("Ошибка связи с сервером!");
                                    window.Eve.EFO.Alert().set_text("Ошибка связи с сервером!").set_title("Ошибка").set_close_btn(true)
                                            .set_style("red").set_timeout(3000).set_callback(window, function () {
                                    }).show();
                                })
                                .fail(function () {
                                    //alert("Ошибка сети!");
                                    window.Eve.EFO.Alert().set_text("Ошибка сети!").set_title("Ошибка").set_close_btn(true)
                                            .set_style("red").set_timeout(3000).set_callback(window, function () {
                                    }).show();
                                });
                    } catch (e) {
                        //alert(e.message);
                        window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                                .set_style("red").set_timeout(3000).set_callback(window, function () {
                        }).show();
                    }
                });
            });
        })();
    
<?php echo '</script'; ?>
><?php }
}
