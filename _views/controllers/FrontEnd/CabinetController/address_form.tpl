<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">Редактирование адреса</div>
    <div class="{$controller->MC}AddressEdit">
        <form method="POST" action="{$smarty.server.REQUEST_URI}">
            <div class="{$controller->MC}Row">
                <input type="hidden" data-field="uid" value="{$address.uid}" />
                <label for="a{$controller->MC}label">Метка</label>
                <input type="text" id="a{$controller->MC}label" value="{$address.label}"  data-field="label" placeholder="Офис, дом, дача" />
                <div class="{$controller->MC}InvalidProp" data-error="label"></div>
            </div>
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}address">Адрес</label>
                <textarea id="a{$controller->MC}address" data-field="address" placeholder="Москва, Ул. Ленина, д. 1 кв. 1">{$address.address}</textarea>
                <div class="{$controller->MC}InvalidProp" data-error="address"></div>
            </div>            
            <div class="{$controller->MC}ButtonRow {$controller->MC}ButtonRowFlex">
                <div class="{$controller->MC}submit" data-command="cancel">Отменить</div>
                <div class="{$controller->MC}submit" data-command="submit_apply">Применить</div>
                <div class="{$controller->MC}submit" data-command="submit_address">Сохранить</div>
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
                var handle = jQuery(['.', key, 'AddressEdit'].join(''));
                var form = handle.find('form');
                var submit = handle.find('[data-command=submit_address]');
                var apply = handle.find('[data-command=submit_apply]');
                var cancel = handle.find('[data-command=cancel]');
                var submit_mode = false;
                function collect_data() {
                    var r = {};
                    handle.find('[data-field]').each(function () {
                        var t = jQuery(this);
                        var n = U.NEString(t.data('field'), null);
                        if (n) {
                            if (t.is('input[type=text],input[type=password],textarea,input[type=hidden]')) {
                                r[n] = U.NEString(t.val(), null);
                            } else if (t.is('input[type=checkbox]')) {
                                r[n] = t.prop('checked');
                            }
                        }
                    });
                    return r;
                }

                function check_data(d) {
                    var errors = [];
                    if (!U.NEString(d.label, null)) {
                        errors.push({f: "label", "e": "Обязательное поле"});
                    }
                    if (!U.NEString(d.address, null)) {
                        errors.push({f: "address", "e": "Обязательное поле"});
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


                function on_submit_ok(d) {
                    if (U.isObject(d)) {
                        if (d.status === 'ok') {
                            if (submit_mode) {
                                window.location.href = "/Cabinet/AddressEdit?i=" + d.new_uid;
                            } else {
                                window.location.href = "/Cabinet/Address";
                            }
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


                cancel.on('click', function (e) {
                    window.location.href = "/Cabinet/Address";
                    return;
                });

                apply.on('click', function () {
                    submit_mode = true;
                    do_submit();
                });

                submit.on('click', function (e) {
                    submit_mode = false;
                    do_submit();
                });


                function do_submit() {
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
                    jQuery.post("/Cabinet/API", {action: "submit_address", data: JSON.stringify(data)})
                            .done(on_submit_ok)
                            .fail(on_submit_fail)
                            .always(function () {
                                try {
                                    window.hide_global_loader();
                                } catch (ee) {

                                }
                            });
                }

                EFO.Promise.waitForArray([
                    EFO.Com().js("https://cdn.jsdelivr.net/npm/suggestions-jquery@19.7.1/dist/js/jquery.suggestions.min.js"),
                    EFO.Com().css("https://cdn.jsdelivr.net/npm/suggestions-jquery@19.7.1/dist/css/suggestions.min.css")
                ]).done(function () {
                    handle.find('[data-field=address]').suggestions({
                        token: "{/literal}{$controller->get_preference('DADATA_KEY','')}{literal}",
                        type: "ADDRESS",
                        onSelect: function (suggestion) {
                            console.log(suggestion);
                        }
                    });
                });

            });
        })();
    {/literal}
</script>