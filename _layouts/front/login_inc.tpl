<div style="display:none" id="login_cover">
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
                    <div id="close_login_signup">
                <i class="mdi mdi-close"></i>
            </div>
                    </div>
                    </div>
                    </div>


                    
                   
               
        </div>
    <div class="login_cover_inner">
        <div id="login_signup">
            
            <div id="login_block">
                <div class="in_ls_block">
                    <div class="row">
                        
                        <div class="col s12 l8 offset-l2">
                            <div class="head3">Авторизация</div>
                            <form id="login">
                            <div id="login_in_in_in">
                                <div class="ls_input">
                                    <label for="login_email">Email</label>
                                    <input type="text" placeholder="example@chill.com" id="login_email" name="login_email">
                                </div>
                                <div class="ls_input">
                                    <label for="login_pass">Пароль</label>
                                    <input type="password" placeholder="*******" id="login_pass" name="login_pass">
                                    <input type="hidden" name="token" id="login_csrf" value="{$controller->mk_csrf('login')}" />
                                </div>
                                <div class="ls_check">
                                <p>
                                    <input type="checkbox" id="login_checka" />
                                    <label for="login_checka">Ознакомлен с <a href="/page/policy" target="_blank">политикой конфиденциальности</a> и <a href="/page/use_rules" target="_blank">правилами пользования сервиса</a></label>
                                </p>
                            </div>
                                </div>
                                <div class="ls_btn">
                                    <button id="__do_login_btn">{TT t='enter'}</button>
                                </div>
                            </form>
                            <div id="no_acc">
                                <a>{TT t='regist_now'}</a>
                            </div>
                            <div id="sign_in_social">
                            
                            <div id="fb_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_fb">
                                    <i class="mdi mdi-facebook">
                                    </i>
                                </a>
                                </div>
                                <div id="gu_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_gu">
                                    <i class="mdi mdi-google">
                                    </i>
                                </a>
                            </div>
                            <div id="vk_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_vk">
                                    <i class="mdi mdi-vk">
                                    </i>
                                </a>
                            </div>
                            <!--<div id="ok_in" class="one_soc_in">
                                <a href="#" data-url="/Auth/Social_ok">
                                    <i class="mdi mdi-odnoklassniki">
                                    </i>
                                </a>
                            </div>-->
                            
                            </div>
                            <div id="recover_password">
                                <a href="#" id='iforgotmypassword'>{TT t='forgot_password'}</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- <div id="policy">
                    <div class="row">
                        <div class="col s12 l8 offset-l2">
                            <a href="/page/policy" target="_blank">
                                Политика конфиденциальности
                            </a>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>
{literal}
    <script>
    
        (function () {
        
            jQuery(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                function ndl(user_id) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({event: 'custom_event', event_category: 'Auth_success', event_action: 'authorization', event_label: 'direct', user_id: user_id});                                        
                }

                window.run_authorization_sequence = function () {
                    jQuery('#login_cover').show();
                    $("html,body").css("overflow","hidden")
                };
                jQuery('#close_login_signup').on('click', function () {
                    jQuery('#login_cover').hide();
                    $("html,body").css("overflow","auto");
                });

                jQuery('#login_email').on('change', function () {
                    var E = window.Eve, EFO = E.EFO, U = EFO.U;
                    jQuery('#login_email').val(EFO.Checks.tryFormatPhone(jQuery('#login_email').val()));
                });


                jQuery('#no_acc a').on('click', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    jQuery('#login_cover').hide();
                    run_registration_sequence();
                });
                jQuery('#__do_login_btn').on('click', function (e) {
                    var E = window.Eve, EFO = E.EFO, U = EFO.U;
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    var login = U.NEString(jQuery('#login_email').val(), null);
                    var password = U.NEString(jQuery('#login_pass').val(), null);
                    var ppa = U.anyBool(jQuery('#login_checka').prop('checked'), false);
                    var csrf = U.NEString(jQuery('#login_csrf').val(),null);
                    if (!ppa) {
                                U.Error("Требуется Ваше согласие на обработку персональных данных!");
                            }
                    try {
                        login ? 0 : U.Error("Укажите email");
                        password ? 0 : U.Error("Укажите пароль");
                        if (!EFO.Checks.isEmail(login) && !EFO.Checks.formatPhone(login)) {
                            U.Error("Укажите email");
                            
                        }
                        jQuery.post('/Auth/API', {action: "auth", login: login, password: password,csrf:csrf}, null, 'json')
                                .done(function (d) {
                                    if (U.isObject(d)) {
                                        if (d.status === 'ok') {
                                            try {
                                                ndl(d.user_info.id, true);
                                            } catch (e) {
                                            }
                                            jQuery('#login_cover').hide();
                                            EFO.Events.GEM().run("LOGIN_SUCCESS");
                                            $("html,body").css("overflow","auto");
                                            return;
                                            
                                        }
                                        if (d.status === 'error') {
                                            //alert(d.error_info.message);
                                            window.Eve.EFO.Alert().set_text(d.error_info.message).set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                            return;
                                        }
                                    }
                                    //alert("Некорректный ответ сервера");
                                    window.Eve.EFO.Alert().set_text("Некорректный ответ сервера").set_title("Ошибка").set_close_btn(true)
                                        .set_style("red").set_timeout(3000).set_callback(window,function(){
                                    }).show();
                                    return;
                                })
                                .fail(function () {
                                    //alert("Ошибка сети");
                                    window.Eve.EFO.Alert().set_text("Ошибка сети").set_title("Ошибка").set_close_btn(true)
                                        .set_style("red").set_timeout(3000).set_callback(window,function(){
                                    }).show();
                                    return;
                                })
                                .always(function () {
                                });
                    } catch (e) {
                        //alert(e.message);
                        window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                            .set_style("red").set_timeout(3000).set_callback(window,function(){
                        }).show();
                        return;
                    }
                });
                jQuery('#login_cover').on('click', '.one_soc_in a', function (e) {
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
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                        }
                    });


                    try {
                        window.addEventListener('message', function (e) {
                            if (e.data === 'SOCIAL_LOGIN_SUCCESS') {
                                EFO.Events.GEM().run("LOGIN_SUCCESS");
                                $("html,body").css("overflow","auto");
                                window.Eve.EFO.Alert().set_text("Вы вошли в систему").set_title("Вход").set_close_btn(true)
                                                .set_style("green").set_timeout(3000).set_callback(window,function(){
                                            }).show();
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
                jQuery('#iforgotmypassword').on('click', function (e) {
                    var E = window.Eve, EFO = E.EFO, U = EFO.U;
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    try {
                        var login = U.NEString(jQuery('#login_email').val(), null);
                        if (!EFO.Checks.isEmail(login) && !EFO.Checks.formatPhone(login)) {
                            U.Error("Укажите email");
                        }
                        jQuery.getJSON('/Auth/API', {login: login, action: "restore"})
                                .done(function (d) {
                                    if (U.isObject(d)) {
                                        if (d.status === 'ok') {
                                            //alert("Инструкции по сбросу пароля отправлены Вам на email");
                                            window.Eve.EFO.Alert().set_text("Инструкции по сбросу пароля отправлены Вам на email").set_title("Инструкция").set_close_btn(true)
                                                .set_style("green").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                            return;
                                        }
                                        if (d.status === 'error') {
                                            //alert(d.error_info.message);
                                            window.Eve.EFO.Alert().set_text(d.error_info.message).set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                            return;
                                        }
                                    }
                                    //alert("Ошибка связи с сервером");
                                    window.Eve.EFO.Alert().set_text("Ошибка связи с сервером").set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                })
                                .fail(function () {
                                    //alert("Ошибка связи с сервером");
                                    window.Eve.EFO.Alert().set_text("Ошибка связи с сервером").set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();
                                })
                    } catch (e) {
                        //alert(e.message);
                        window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                                                .set_style("red").set_timeout(3000).set_callback(window,function(){
                                            }).show();

                    }
                });

            });
        })();
    </script>
{/literal}