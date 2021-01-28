<?php
/* Smarty version 3.1.33, created on 2020-12-17 20:17:06
  from '/var/VHOSTS/site/_layouts/front/signup_inc.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fdb92920af870_27253067',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5d33a5ab83e535da77c9762357bbb567ca0e13df' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/signup_inc.tpl',
      1 => 1608225420,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fdb92920af870_27253067 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),));
?>
<div class="chill_signup_block" style="display:none" id="chill_signup_block">

    <div id="line_out_reg">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="line_in">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="desktop_header_a">
        <div class="container">
            <div class="row">
                <div class="col s3 m5 offset-m1">
                    <div id="logo_in_a">
                        <a title="chill">
                            <img src="/assets/chill/images/logo_grad.png" alt="chill">
                        </a>
                    </div>
                </div>
                <div class="col s9 m5" id="right_b">
                    <div id="close_login_signup_signup">
                        <i class="mdi mdi-close"></i>
                    </div>
                </div>
            </div>
        </div>





    </div>
    <div class="chill-signup-inner">
        <div id="signup_block">


            <div class="in_ls_block">
                <div class="row">
                    <div class="col s12 l8 offset-l2">
                        <div class="head3"><?php echo smarty_function_TT(array('t'=>'Signup'),$_smarty_tpl);?>
</div>
                        <p class="signup_p">Регистрируйся и получай 6 cерий бесплатно!</p>
                        <form id="login_signup_replacer">
                            <div class="ls_input">
                                <label for="su_name">Имя</label>
                                <input type="text" placeholder="Иван" id="su_name" name="su_name">
                                <input type='hidden' name='token' id='signup_csrf' value='<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('signup');?>
' />
                            </div>
                            <div class="ls_input">
                                <label for="su_email"><?php echo smarty_function_TT(array('t'=>'Email'),$_smarty_tpl);?>
</label>
                                <input type="text" placeholder="example@chill.com" id="su_email" name="su_email">
                            </div>
                            <!-- <div class="ls_input">
                                 <label for="su_phone"><?php echo smarty_function_TT(array('t'=>'Phone'),$_smarty_tpl);?>
</label>
                                 <input type="text" placeholder="+7 (000) 000 00 00" id="su_phone" name="su_phone">
                             </div>-->
                            <div class="ls_input">
                                <label for="su_pass"><?php echo smarty_function_TT(array('t'=>'Password'),$_smarty_tpl);?>
</label>
                                <input type="password" placeholder="*******" id="su_pass" name="su_pass">
                            </div>
                            <div class="ls_input" style="display:none">
                                <label for="su_bd"><?php echo smarty_function_TT(array('t'=>'birthday'),$_smarty_tpl);?>
</label> 
                                <input type="text" placeholder="01.01.2001" id="su_bd" name="su_bd" value="01.01.2000">
                            </div>
                            <div class="ls_check">
                                <p>
                                    <input type="checkbox" id="login_check" />
                                    <label for="login_check">Согласен на обработку <a href="/page/policy" target="_blank">персональных данных</a></label>
                                </p>
                            </div>
                            <div class="ls_btn">
                                <button id="chill_signup_do_register"><?php echo smarty_function_TT(array('t'=>'Signup'),$_smarty_tpl);?>
</button>
                            </div>
                        </form>


                        <div id="have_acc">
                            <a href="#" id='iforgotmypassword'>Есть аккаунт</a>
                        </div>
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

            jQuery(function () {
                window.Eve = window.Eve || {};
                window.Eve.EFO = window.Eve.EFO || {};
                window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                window.Eve.EFO.Ready.push(ready);
                function ready() {
                    var E = window.Eve, EFO = E.EFO, U = EFO.U;
                    window.xxpicker = window.xxpicker || [];
                    window.xxpicker.push(function () {
                        jQuery('#su_bd').datetimepicker({
                            lang: 'ru',
                            lazyInit: true,
                            format: 'd.m.Y',
                            closeOnDateSelect: true,
                            closeOnWithoutClick: true,
                            timepicker: false,
                            mask: true,
                            theme: 'dark',
                            maxDate: 0,
                            todayButton: false,
                            scrollMonth: false,
                            scrollTime: false,
                            scrollInput: false,
                            dayOfWeekStart: 1
                        });
                    });

                    function ndl(user_id) {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({event: 'custom_event', event_category: 'Auth_success', event_action: 'registration', event_label: 'direct', user_id: user_id});
                    }
                    function ndl2(user_id) {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({event: 'custom_event', event_category: 'Auth_success', event_action: 'authorization', event_label: 'direct', user_id: user_id});
                    }
                    window.run_registration_sequence = function () {
                        jQuery("#chill_signup_block").show();
                        $("html,body").css("overflow", "hidden");
                    };
                    jQuery('#have_acc').on('click', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        jQuery("#chill_signup_block").hide();
                        run_authorization_sequence();
                    });
                    jQuery('#close_login_signup_signup').on('click', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        jQuery("#chill_signup_block").hide();
                        $("html,body").css("overflow", "auto");
                    });
                    //jQuery('#su_phone').on('change', function (e) {
                    //     jQuery('#su_phone').val(EFO.Checks.tryFormatPhone(jQuery('#su_phone').val()));
                    // });
                    jQuery('#chill_signup_do_register').on('click', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        try {
                            var login = U.NEString(jQuery('#su_email').val(), null);
                            var name = U.NEString(jQuery('#su_name').val(), null);
                            //var phone = U.NEString(jQuery('#su_phone').val(), null);
                            var password = U.NEString(jQuery('#su_pass').val(), null);
                            var bd = U.NEString(jQuery('#su_bd').val(), null);
                            var ppa = U.anyBool(jQuery('#login_check').prop('checked'), false);
                            var csrf = U.NEString(jQuery('#signup_csrf').val(), null);
                            if (!login || !EFO.Checks.isEmail(login)) {
                                U.Error("Укажите email!");
                            }
                            //if (phone && !EFO.Checks.formatPhone(phone)) {
                            //    U.Error("Телефон указан некорректно");
                            //}
                            if (!password) {
                                U.Error("Укажите пароль");
                            }
                            if (!name) {
                                U.Error("Укажите имя");
                            }
                            if (password.length < 6) {
                                U.Error("Минимальный пароль - 6 символов");
                            }
                            if (!bd) {
                                U.Error("Укажите дату рождения");
                            }
                            if (!/^\d{1,2}\.\d{1,2}\.\d{4}$/i.test(bd)) {
                                U.Error("Некорректная дата рождения");
                            }
                            if (!ppa) {
                                U.Error("Требуется Ваше согласие на обработку персональных данных!");
                            }
                            grecaptcha.ready(function () {
                                grecaptcha.execute('<?php echo $_smarty_tpl->tpl_vars['controller']->value->get_preference('RECAPTCHA_SITE_KEY','');?>
', {action: 'register'}).then(function (captcha) {
                                    jQuery.post('/Auth/API', {captcha: captcha, csrf: csrf, action: "register_chill", login: login, password: password, birth_date: bd, ppa: ppa, name: name}, null, 'json')
                                            .done(function (d) {
                                                if (U.isObject(d)) {
                                                    if (d.status === 'error') {
                                                        //alert(d.error_info.message);
                                                        window.Eve.EFO.Alert().set_text(d.error_info.message).set_title("Ошибка").set_close_btn(true)
                                                                .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                        }).show();
                                                        return;
                                                    }
                                                    if (d.status === 'ok') {
                                                        try {
                                                            ndl(d.user_info.id, true);
                                                        } catch (e) {
                                                        }
                                                        jQuery("#chill_signup_block").hide();
                                                        EFO.Events.GEM().run("LOGIN_SUCCESS");
                                                        window.Eve.EFO.Alert().set_text("Вы зарегестрировались. На счет поступило 6 серий").set_title("Успешная регистрация").set_close_btn(true)
                                                                .set_style("green").set_timeout(3000).set_callback(window, function () {
                                                        }).show();
                                                        return;
                                                    }
                                                }
                                                //alert("Некорректный ответ сервера");
                                                window.Eve.EFO.Alert().set_text("Некорректный ответ сервера").set_title("Ошибка").set_close_btn(true)
                                                        .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                }).show();
                                            })
                                            .fail(function () {
                                                //alert("Ошибка связи с сервером")
                                                window.Eve.EFO.Alert().set_text("Ошибка связи с сервером").set_title("Ошибка").set_close_btn(true)
                                                        .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                }).show();
                                            })
                                            .always(function () {

                                            });
                                });
                            });


                        } catch (e) {
                            //alert(e.message);
                            window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                                    .set_style("red").set_timeout(3000).set_callback(window, function () {
                            }).show();
                        }
                    });
                    jQuery('#chill_signup_block').on('click', '.one_soc_in a', function (e) {
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        e.stopPropagation();
                        var w = 600;
                        var h = 600;
                        var left = U.IntMoreOr((screen.width - w) / 2, 0, 100);
                        var top = U.IntMoreOr((screen.height - h) / 2, 0, 100);
                        var wnd = window.open(jQuery(this).data('url'), "socauth", "width=" + w + ",height=" + h + ",left=" + left + ',top=' + top, 'menubar=no,location=no,toolbar=no');
                        if (!wnd) {
                            //alert("Требуется Ваше разрешение на открытие всплывающих окон!");
                            window.Eve.EFO.Alert().set_text("Требуется Ваше разрешение на открытие всплывающих окон!").set_title("Ошибка").set_close_btn(true)
                                    .set_style("red").set_timeout(3000).set_callback(window, function () {
                            }).show();
                        }
                    });


                    try {
                        window.addEventListener('message', function (e) {
                            var m = /^SOCIAL_LOGIN_SUCCESS_(\d{1,})_(1|0)/.exec(U.NEString(e.data, ''));
                            if (m) {
                                var user_id = U.IntMoreOr(m[1], 0, null);
                                var created = U.anyBool(m[2], false);
                                $("html,body").css("overflow", "auto");
                                if (created) {
                                    ndl(user_id);
                                } else {
                                    ndl2(user_id);
                                }
                                window.Eve.EFO.Alert().set_text("Вы зарегестрировались. На счет поступило 6 серий").set_title("Успешная регистрация").set_close_btn(true)
                                        .set_style("green").set_timeout(3000).set_callback(window, function () {
                                }).show();
                                EFO.Events.GEM().run("LOGIN_SUCCESS");
                            }
                        });
                    } catch (e) {

                    }

                    try {
                        window.addEventListener('storage', function (e) {
                            if (e.key === 'SOCIAL_LOGIN_SUCCESS') {
                                var m = /^(\d{1,})_(1|0)$/.exec(e.newValue);
                                if (m) {
                                    var user_id = U.IntMoreOr(m[1], 0, null);
                                    var created = U.anyBool(m[2], false);
                                    if (created) {
                                        ndl(user_id);
                                    } else {
                                        ndl2(user_id);
                                    }
                                    window.Eve.EFO.Alert().set_text("Вы зарегестрировались. На счет поступило 6 серий").set_title("Успешная регистрация").set_close_btn(true)
                                            .set_style("green").set_timeout(3000).set_callback(window, function () {
                                    }).show();
                                    EFO.Events.GEM().run("LOGIN_SUCCESS");
                                }
                            }
                        });
                    } catch (e) {

                    }

                }
            });

        })();

        $(document).ready(function () {
            var a = window.location.pathname;
            if (a === '/Profile') {
                $('#close_login_signup_signup').on('click', function (e) {
                    window.history.back();
                });
            }
        });
        $(document).ready(function () {
            var a = window.location.pathname;
            if (a === '/Profile') {
                $('#close_login_signup').on('click', function (e) {
                    window.history.back();
                });
            }
        });
    <?php echo '</script'; ?>
>
<?php }
}
