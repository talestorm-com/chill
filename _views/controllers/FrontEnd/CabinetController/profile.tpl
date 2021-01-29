<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">Профиль</div>
    <div class="{$controller->MC}Profile">
        <form method="POST" action="{$smarty.server.REQUEST_URI}">
            <div class="{$controller->MC}Row">
                <div class="{$controller->MC}AvatarContainer">
                    <div class="{$controller->MC}Avatarinner">
                        <img src="/media/avatar/{$user_info->id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg" />
                        <input type="file" name="avatar" accept=".jpg, .png" data-field="ava" />
                    </div>
                </div>
            </div>
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}name">Имя</label>
                <input type="text" id="a{$controller->MC}name" value="{$user_info->name}" name="name" data-field="name" placeholder="Иван"/>
                <div class="{$controller->MC}InvalidProp" data-error="name"></div>
            </div>
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}family">Фамилия</label>
                <input type="text" id="a{$controller->MC}family" value="{$user_info->family}" name="family" data-field="family" placeholder="Иванов" />
                <div class="{$controller->MC}InvalidProp" data-error="family"></div>
            </div>
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}eldername">Отчество</label>
                <input type="text" id="a{$controller->MC}eldername" value="{$user_info->eldername}" name="eldername" data-field="eldername" placeholder="Иванович" />
                <div class="{$controller->MC}InvalidProp" data-error="eldername"></div>
            </div>
            {if $controller->auth_is_client()}
                <div class="{$controller->MC}Row">
                    <label for="a{$controller->MC}package">Пакет занятий</label>
                    {assign var='package' value=$controller->get_client_package()}
                    {if $package!=null}
                        <div class="{$controller->MC}_package_info">
                            <div class="{$controller->MC}_package_info_inner">
                                <div class="{$controller->MC}_package_info_image">
                                    {if $package->default_image}
                                        <img src="/media/package/{$package->id}/{$package->default_image}.SW_250H_250CF_1.jpg" />
                                    {else}
                                        <img src="/media/fallback/1/package.SW_250H_250CF_1.jpg" />
                                    {/if}
                                </div>
                                <div class="{$controller->MC}_package_info_name">
                                    {$package->name}
                                </div>
                                <div class="{$controller->MC}_package_info_rem">
                                    <div class="{$controller->MC}_package_info_rem1">Осталось {$package->usages_remain} занятий</div>
                                    <div class="{$controller->MC}_package_info_rem2">До {$package->format_expiration}</div>
                                </div>
                            </div>
                        </div>
                    {else}
                        <div class="{$controller->MC}-no-client-package">
                            У вас нет активного пакета. <a href="/Cabinet/Price">Купить пакет</a>
                        </div>
                    {/if}
                </div>
            {/if}
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}phone">Телефон</label>
                <input type="text" id="a{$controller->MC}phone" value="{$user_info->phone}" name="phone" data-field="phone" data-monitor="phone" placeholder="+7 (000) 000 00 00" />
                <div class="{$controller->MC}InvalidProp" data-error="phone"></div>
            </div>
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}login">Email</label>
                <input type="text" id="a{$controller->MC}login" value="{$user_info->login}" name="login" data-field="login" autocomplete="nope" placeholder="ivan@gmail.com"/>
                <div class="{$controller->MC}InvalidProp" data-error="login"></div>
            </div>
            <div class="{$controller->MC}Row {$controller->MC}checkrow">
                <input type="hidden" name="news" value="0">
                <input type="checkbox" id="a{$controller->MC}news" data-field="news" name="news" value="1" {if $controller->get_user_is_news($user_info->id)}checked="checked"{/if} />
                <label for="a{$controller->MC}news">Подписка на новости</label>
            </div>
            {if $controller->auth_is_trainer()}
                <div class="{$controller->MC}Row">
                    <label for="a{$controller->MC}about">О себе</label>
                    <textarea id="a{$controller->MC}about"   data-field="about" >{$controller->load_trainer_about(true)}</textarea>
                    <div class="{$controller->MC}InvalidProp" data-error="about"></div>
                </div>
            {/if}
            <div class="{$controller->MC}Row {$controller->MC}RowTop">
                <div class="{$controller->MC}Section">
                    <label for="a{$controller->MC}password">Пароль<div class="{$controller->MC}hint">Пароль следует указывать только если Вы хотите его сменить.</div></label>                    
                    <input type="password" id="a{$controller->MC}password" value="" name="password" data-field="password" autocomplete="new-password" />
                    <div class="{$controller->MC}InvalidProp" data-error="password"></div>
                </div>
                <div class="{$controller->MC}Section">
                    <label for="a{$controller->MC}repassword">Повтор пароля</label>
                    <input type="password" id="a{$controller->MC}repassword" value="" name="repassword" data-field="repassword" autocomplete="new-password"/>
                    <div class="{$controller->MC}InvalidProp" data-error="repassword"></div>
                </div>

            </div>
            <div class="{$controller->MC}ButtonRow">
                <div class="{$controller->MC}submit" data-command="submit_profile">Сохранить</div>
            </div>
        </form>
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
                var key = '{/literal}{$controller->MC}{literal}';
                var handle = jQuery(['.', key, 'Profile'].join(''));
                var form = handle.find('form');
                var submit = handle.find('[data-command=submit_profile]');
                handle.find('[data-monitor=phone]').on('change', function () {
                    var t = jQuery(this);
                    var v = EFO.Checks.tryFormatPhone(t.val());
                    t.val(v);
                });
                handle.find('input[type=file]').on('change', function () {
                    if (this.files.length) {
                        var f = this.files[0];
                        handle.find('img:first').attr('src', URL.createObjectURL(f));
                    }
                });
                function collect_data() {
                    var r = {};
                    handle.find('[data-field]').each(function () {
                        var t = jQuery(this);
                        var n = U.NEString(t.data('field'), null);
                        if (n) {
                            if (t.is('input[type=text],input[type=password],textarea')) {
                                r[n] = t.val();
                            } else if (t.is('input[type=checkbox]')) {
                                r[n] = t.prop('checked');
                            } else if (t.is('input[type=file]')) {
                                if (t.get(0).files.length) {
                                    r[n] = t.get(0).files[0];
                                }
                            }
                        }
                    });
                    return r;
                }

                function check_data(d) {
                    var errors = [];
                    if (!U.NEString(d.name, null)) {
                        errors.push({f: "name", "e": "Обязательное поле"});
                    }
                    if (!U.NEString(d.family, null)) {
                        errors.push({f: "family", "e": "Обязательное поле"});
                    }
                    if (!U.NEString(d.phone, null)) {
                        errors.push({f: "phone", "e": "Обязательное поле"});
                    } else if (!EFO.Checks.formatPhone(d.phone)) {
                        errors.push({f: "phone", "e": "Некорректный номер телефона"});
                    }
                    if (!U.NEString(d.login, null)) {
                        errors.push({f: "login", "e": "Обязательное поле"});
                    } else if (!EFO.Checks.isEmail(d.login)) {
                        errors.push({f: "login", "e": "Некорректный email"});
                    }

                    if (U.NEString(d.password, null) || U.NEString(d.repassword, null)) {
                        if (!U.NEString(d.password, null) || !U.NEString(d.repassword, null)) {
                            errors.push({f: "password", "e": "Оба поля должны иметь одинаковое значение"});
                            errors.push({f: "repassword", "e": "Оба поля должны иметь одинаковое значение"});
                        } else if (U.NEString(d.password, '').length < 6) {
                            errors.push({f: "password", "e": "Не менее 6 символов"});
                        } else if (U.NEString(d.password, '') !== U.NEString(d.repassword, null)) {
                            errors.push({f: "repassword", "e": "Пароли не совпадают"});
                        }
                    }

                    return errors;
                }

                function show_errors(a) {
                    var min_offset = 9999999;
                    for (var i = 0; i < a.length; i++) {
                        var t = handle.find("[data-error=" + a[i].f + "]");
                        t.html(a[i].e);
                        min_offset = Math.min(min_offset, t.get(0).getBoundingClientRect().top - 100);
                    }
                    jQuery(window).scrollTop(min_offset + jQuery(window).scrollTop());
                }

                function show_email_exists() {
                    show_errors([{f: "login", e: "Такой email уже зарегистрирован"}]);
                }

                function on_submit_ok(d) {
                    if (U.isObject(d)) {
                        if (d.status === 'ok') {
                            window.location.reload(true);
                            return;
                        }
                        if (d.status === "error") {
                            if (d.error_info.message === "login_exists") {
                                return show_email_exists();
                            } else if (d.error_info.message === "login_required") {
                                window.location.reload(true);
                                return;
                            } else {
                                return on_submit_fail(d.error_info.message);
                            }
                        }
                    }
                    return on_submit_fail("Некорректный ответ сервера");
                }

                function on_submit_fail(x) {
                    x = U.NEString(x, "Ошибка связи с сервером");
                    U.TError(x);
                }

                submit.on('click', function (e) {
                    handle.find('[data-error]').html('');
                    var data = collect_data();
                    var ers = check_data(data);
                    if (U.isArray(ers) && ers.length) {
                        show_errors(ers);
                        return;
                    }
                    try {
                        window.show_global_loader();
                    } catch (ee) {

                    }
                    var fd = new FormData();
                    for (var k in data) {
                        if (data.hasOwnProperty([k]) && !U.isCallable(data[k])) {
                            fd.append(k, data[k]);
                        }
                    }
                    fd.append('action', 'submit_profile_fd');
                    jQuery.ajax({
                        url: '/Cabinet/API',
                        data: fd,
                        type: 'POST',
                        processData: false,
                        contentType: false
                    }).done(on_submit_ok)
                            .fail(on_submit_fail)
                            .always(function () {
                                try {
                                    window.hide_global_loader();
                                } catch (ee) {

                                }
                            });


                    //jQuery.post("/Cabinet/API", {action: "submit_profile_raw", data: JSON.stringify(data)})

                });
            });
        })();
    {/literal}
</script>