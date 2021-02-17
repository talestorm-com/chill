<div class="chill_signup_block" style="display:none" id="chill_signup_block">
    <div class="chill-signup-inner">
        <div id="signup_block">
            <div id="to_login_block">
                <i class="mdi mdi-chevron-left"></i>
            </div>
            <div id="close_login_signup_signup">
                <i class="mdi mdi-close"></i>
            </div>
            <div class="in_ls_block">
                <div class="row">
                    <div class="col s12">
                        <div class="in_ls_logo">
                            <img src="/assets/chill/images/logo_black.png" alt="Chill">
                        </div>
                    </div>
                    <div class="col s12 l8 offset-l2">
                        <h3>{TT  t='Signup'}</h3>
                        <form id="login_signup_replacer">
                            <div class="ls_input">
                                <label for="su_name">Имя</label>
                                <input type="text" placeholder="Иван" id="su_name" name="su_name">
                            </div>
                            <div class="ls_input">
                                <label for="su_email">{TT  t='Email'}</label>
                                <input type="text" placeholder="example@chill.com" id="su_email" name="su_email">
                            </div>
                           <div class="ls_input">
                                <label for="su_phone">{TT  t='Phone'}</label>
                                <input type="text" placeholder="+7 (000) 000 00 00" id="su_phone" name="su_phone">
                            </div>
                            <div class="ls_input">
                                <label for="su_pass">{TT  t='Password'}</label>
                                <input type="password" placeholder="*******" id="su_pass" name="su_pass">
                            </div>
                            <div class="ls_input">
                                <label for="su_bd">{TT  t='birthday'}</label>
                                <input type="text" placeholder="01.01.2001" id="su_bd" name="su_bd">
                            </div>
                            <div class="ls_check">
                                <p>
                                    <input type="checkbox" id="login_check" />
                                    <label for="login_check">{TT t='policy_ok'}</label>
                                </p>
                            </div>
                            <div class="ls_btn">
                                <button id="chill_signup_do_register">{TT  t='Signup'}</button>
                            </div>
                        </form>
                        <div class="text_in_lib">
                            {TT t='or_old'}
                        </div>
                        <div id="sign_in_social">
                            <div id="vk_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_vk">
                                    <i class="mdi mdi-vk">
                                    </i>
                                </a>
                            </div>
                            <div id="fb_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_fb">
                                    <i class="mdi mdi-facebook">
                                    </i>
                                </a>
                            </div>
                            <!--<div id="tw_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_tw">
                                    <i class="mdi mdi-twitter">
                                    </i>
                                </a>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{literal}
    <script>
    
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
                            mask:true,
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
                    window.run_registration_sequence = function () {
                        jQuery("#chill_signup_block").show();
                    };
                    jQuery('#to_login_block').on('click', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        jQuery("#chill_signup_block").hide();
                        run_authorization_sequence();
                    });
                    jQuery('#close_login_signup_signup').on('click', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        jQuery("#chill_signup_block").hide();
                    });
                    jQuery('#su_phone').on('change', function (e) {
                        jQuery('#su_phone').val(EFO.Checks.tryFormatPhone(jQuery('#su_phone').val()));
                  });
                    jQuery('#chill_signup_do_register').on('click', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        try {
                            var login = U.NEString(jQuery('#su_email').val(), null);
                            var name = U.NEString(jQuery('#su_name').val(), null);
                            var phone = U.NEString(jQuery('#su_phone').val(), null);
                            var password = U.NEString(jQuery('#su_pass').val(), null);
                            var bd = U.NEString(jQuery('#su_bd').val(), null);
                            var ppa = U.anyBool(jQuery('#login_check').prop('checked'), false);
                            if (!login || !EFO.Checks.isEmail(login)) {
                                U.Error("Укажите email!");
                            }
                            if (phone && !EFO.Checks.formatPhone(phone)) {
                                U.Error("Телефон указан некорректно");
                            }
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
                            jQuery.post('/Auth/API', {action: "register_chill", login: login, password: password, birth_date: bd, ppa: ppa, phone: phone, name: name}, null, 'json')
                                    .done(function (d) {
                                        if (U.isObject(d)) {
                                            if (d.status === 'error') {
                                                alert(d.error_info.message);
                                                return;
                                            }
                                            if (d.status === 'ok') {                                                
                                                try {
                                                    ndl(d.user_info.id, true);
                                                } catch (e) {
                                                }
                                                jQuery("#chill_signup_block").hide();
                                                EFO.Events.GEM().run("LOGIN_SUCCESS");
                                                
                                                return;
                                            }
                                        }
                                        alert("Некорректный ответ сервера");
                                    })
                                    .fail(function () {
                                        alert("Ошибка связи с сервером")
                                    })
                                    .always(function () {

                                    })

                        } catch (e) {
                            alert(e.message);
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
                            alert("Требуется Ваше разрешение на открытие всплывающих окон!");
                        }
                    });


                    try {
                        window.addEventListener('message', function (e) {
                            if (e.data === 'SOCIAL_LOGIN_SUCCESS') {
                                EFO.Events.GEM().run("LOGIN_SUCCESS");
                            }
                        });
                    } catch (e) {

                    }

                    try {
                        window.addEventListener('storage', function (e) {
                            if (e.key === 'SOCIAL_LOGIN_SUCCESS') {
                                if (e.newValue === 1) {
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
    </script>
{/literal}