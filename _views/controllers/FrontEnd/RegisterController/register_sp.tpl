<!DOCTYPE html>
<html lang="ru-RU">
    <head>
        <title>HOMY. Индивидуальные тренировки Москва</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <link type="text/css" rel="stylesheet" href="/assets/front_main/css/materialize.min.css">
        <link href="/assets/front_main/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css">        
        <link href="/assets/front_main/css/main.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/mob.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/login.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/main_new.css" media="all" rel="stylesheet" type="text/css">
        <script async="async" src="/assets/js/efo.js"></script>
        <script>
            (function () {
                window.Eve = window.Eve || {};
                window.Eve.EFO = window.Eve.EFO || {};
                window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                window.Eve.EFO.Ready.push(function () {
                    var urls = [
                        "/assets/front_main/js/materialize.min.js"
                    ];
                    for (var i = 0; i < urls.length; i++) {
                        window.Eve.EFO.Com().js(urls[i]);
                    }
                });
            })();
        </script>
    </head>
    <body>
        <div class="login_background">
            <div class="login_background_inner">
                <div id="login_form">
                    <h4>Регистрация</h4>
                    <form id="login_form_form" action="/Auth/API" method="POST" onsubmit="javascript:return false;">
                        <div class="in_form_input">
                            <input type="text" placeholder="Фамилия" name="family">
                        </div>
                        <div class="in_form_input">
                            <input type="text" placeholder="Ваше имя" name="name">
                        </div>
                        <div class="in_form_input">
                            <input type="text" placeholder="email" name="login">
                        </div>
                        <div class="in_form_input">
                            <input type="text" placeholder="Телефон" name="phone">
                        </div>
                        <p>Зарегистрироваться как:</p>
                        <div class="in_form_select">
                            <p>
                                <input name="groupa" type="radio" id="a11_1" value="client" />
                                <label for="a11_1">Клиент</label>
                            </p>
                            <p>
                                <input name="groupa" type="radio" id="a11_2" value="trainer" />
                                <label for="a11_2">Тренер</label>
                            </p>
                            <p>
                                <input name="groupa" type="radio" id="a11_3" value="hole" />
                                <label for="a11_3">Фитнес-зал</label>
                            </p>
                        </div>
                        <div class="in_form_input">
                            <input type="password" placeholder="Пароль" name="password">
                        </div>
                        <div class="in_form_input">
                            <input type="password" placeholder="Повтор пароля" name="repassword">
                        </div>                    
                        <div class="in_form_btn">
                            <button type="submit" id="login_button">Регистрация</button>
                        </div>                    
                    </form>
                </div>
            </div>
        </div>
        {literal}
            <script>
                (function () {
                    window.Eve = window.Eve || {};
                    window.Eve.EFO = window.Eve.EFO || {};
                    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                    window.Eve.EFO.Ready.push(function () {
                        var U = window.Eve.EFO.U;
                        var handle = jQuery('#login_form');
                        handle.show();
                        var button = jQuery('#login_button');
                        button.on('click', function (e) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault : e.returnValue = false;
                            if (button.hasClass('disabled')) {
                                return;
                            }
                            try {
                                var email = U.NEString(handle.find('input[name=login]').val(), null);
                                email ? 0 : U.Error("Укажите email");
                                window.Eve.EFO.Checks.isEmail(email) ? 0 : U.Error("Некорректный email");
                                var phone = U.NEString(handle.find('input[name=phone]').val(), null);
                                var phone = window.Eve.EFO.Checks.formatPhone(phone);
                                phone ? 0 : U.Error("Укажите Телефон");
                                var name = U.NEString(handle.find('input[name=name]').val(), null);
                                var family = U.NEString(handle.find('input[name=family]').val(), null);
                                name ? 0 : U.Error("Укажите имя");
                                family ? 0 : U.Error("Укажите фамилию");
                                var password = U.NEString(handle.find('input[name=password]').val(), null);
                                var role = U.NEString(handle.find('input[name=groupa]:checked').val(), null);
                                role ? 0 : U.Error("Укажите тип регистрации");
                                password ? 0 : U.Error("Укажите пароль");
                                var repassword = U.NEString(handle.find('input[name=repassword]').val(), null);
                                repassword ? 0 : U.Error("Укажите пароль еще раз");
                                password === repassword ? 0 : U.Error("Пароли не совпадают");
                                button.addClass('disabled');

                                jQuery.post(handle.find('form').attr('action'), {
                                    action: "register2", data: JSON.stringify({
                                        name: name,
                                        family: family,
                                        email: email, phone: phone,
                                        password: password, repassword: repassword,
                                        role: role
                                    })
                                })
                                        .done(function (d) {
                                            if (U.isObject(d)) {
                                                if (d.status === 'ok') {
                                                    window.location.href = "/Cabinet/profile";
                                                    return;
                                                }
                                                if (d.status === 'error') {
                                                    alert(d.error_info.message);
                                                    return;
                                                }
                                            }
                                            alert("Некорректный ответ сервера");
                                        })
                                        .fail(function () {
                                            alert("Ошибка связи с сервером");
                                        })
                                        .always(function () {
                                            button.removeClass('disabled');
                                        })
                            } catch (e) {
                                alert(e.message);
                                return;
                            }
                        });
                    });
                })();
            </script>
        {/literal}
    </body>
</html>