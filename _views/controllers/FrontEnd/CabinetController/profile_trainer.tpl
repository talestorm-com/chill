<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">Профиль тренера</div>
    <div class="{$controller->MC}Profile">
        <form method="POST" action="{$smarty.server.REQUEST_URI}">
            <div class="{$controller->MC}Row">
                <div class="{$controller->MC}AvatarContainer">
                    <div class="{$controller->MC}Avatarinner">
                        <img src="/media/avatar/{$user_info->id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?t={random}" />
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
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}about">О себе</label>
                <textarea id="a{$controller->MC}about"   data-field="about" >{$controller->load_trainer_about(true)}</textarea>
                <div class="{$controller->MC}InvalidProp" data-error="about"></div>
            </div>
            <div class="{$controller->MC}Row">
                <label for="a{$controller->MC}sport">Вид спорта</label>
                <input type="text" id="a{$controller->MC}sport"   data-field="sport" value="{$controller->load_trainer_sport(true)}" />
                <div class="{$controller->MC}InvalidProp" data-error="sport"></div>
            </div>

            <div class="{$controller->MC}Row">
                <div class="{$controller->MC}aka_label">Зал, в котором Вы ведете занятия</div>
                {assign var='hole' value=$controller->load_trainer_hole_info()}                
                <div class="{$controller->MC}_hole_info" style="{if !$hole}display:none{/if}" id="trainer_selected_zal">
                    <div class="{$controller->MC}_hole_info_inner">
                        <div class="{$controller->MC}Hole_image"><img src="{if $hole && $hole.default_image}/media/training_hall/{$hole.id}/{$hole.default_image}{else}/media/fallback/1/training_hall{/if}.SW_200H_200CF_1.jpg" /></div>
                        <div class="{$controller->MC}HoleTexts">
                            <div class="{$controller->MC}Hole_name">{if $hole}{$hole.name}{/if}</div>
                            <div class="{$controller->MC}Hole_address">{if $hole}{$hole.address}{/if}</div>                            
                            <div class="{$controller->MC}Hole_phone">{if $hole}{$hole.phone}{/if}</div>
                        </div>
                    </div>
                </div>                
                <div class="{$controller->MC}SelectHolePanel">
                    <a href="#" data-command="select_hole">Изменить зал</a>                    
                </div>
                <input type="hidden" data-field="hole_id" value="{if $hole}{$hole.id}{/if}" />
                <div class="{$controller->MC}InvalidProp" data-error="hole_id"></div>
            </div>
            <div class="{$controller->MC}Row">
                <div class="{$controller->MC}aka_label">Ваше расписание</div>
                <div class="{$controller->MC}raspison_outer" id="trainer_raspison"></div>
                <div class="{$controller->MC}InvalidProp" data-error="rasp"></div>
            </div>
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
<link type="text/css" rel="stylesheet" href="/assets/css/efo.css"/>
<link type="text/css" rel="stylesheet" href="/assets/vendor/datepicker/css.css"/>
<link type="text/css" rel="stylesheet" href="/assets/css/front/cabinet/trainer_calendar.css"/>            
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(ready);
            var day_split = [];
            var default_response ={/literal}{$controller->get_trainer_calendar()|json_encode}{literal};
            function ready() {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var handle = jQuery("#trainer_raspison");
                // var loader = jQuery("#{/literal}{$controller->MC}{literal}loader");
                var days = ["", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"];
                var days_r = ["", "понедельник", "вторник", "среду", "четверг", "пятницу", "субботу", "воскресенье"];
                var TEMPLATES = {/literal}{$controller->get_frontend_templates('calendar_front')|json_encode}{literal};
                var global_base_time = null;
                EFO.Com().js("/assets/vendor/datepicker/js.js")
                        .done(window, ready2);
                function ready2() {
                    var hdlrs = {
                        on_command_add_dow_record: function (t, e) {
                            var dow = U.IntMoreOr(t.data('index'), 0, null);
                            if (dow) {
                                var dow_day = day_split[dow - 1];
                                dow_day.dow_items.push({
                                    uid: U.UUID(),
                                    start_time: null,
                                    length: 0,
                                    time_str: null,
                                    end_time: null
                                });
                                render();
                            }
                        },
                        on_command_remove_time: function (t, e) {
                            var dow = U.IntMoreOr(t.data('index'), 0, null);
                            if (dow) {
                                var uid = U.NEString(t.data('id'), null);
                                if (uid) {
                                    var tt = day_split[dow - 1];
                                    var k = null;
                                    for (var i = 0; i < tt.dow_items.length; i++) {
                                        if (tt.dow_items[i].uid === uid) {
                                            k = tt.dow_items[i];
                                            break;
                                        }
                                    }
                                    if (k) {
                                        var ind = tt.dow_items.indexOf(k);
                                        if (ind >= 0) {
                                            tt.dow_items = tt.dow_items.slice(0, ind).concat(tt.dow_items.slice(ind + 1));
                                            render();
                                        }
                                    }
                                }
                            }
                        },
                        on_command_do_save: function () {
                            /*do_sort();
                             var d = null;
                             try {
                             d = check();
                             } catch (e) {
                             U.TError(e.message);
                             return;
                             }
                             loader.show();
                             jQuery.post('/Cabinet/API', {action: "post_trainer_calendar", data: JSON.stringify(d)})
                             .done(on_post_responce)
                             .fail(on_post_fail)
                             .always(function () {
                             loader.hide();
                             });*/
                        }
                    };

                    function get_data() {
                        do_sort();
                        var d = null;
                        try {
                            d = check();
                            return d;
                        } catch (e) {
                            U.TError(e.message);
                            return;
                        }

                    }
                    window.rasp_get_data = get_data;

                    function on_post_responce(d) {
                        if (U.isObject(d)) {
                            if (d.status === 'ok') {
                                return success_load(d.items);
                            }
                            if (d.status === 'error') {
                                return on_post_fail(d.error_info.message);
                            }
                        }
                        return on_post_fail("invalid server response");
                    }

                    function on_post_fail(x) {
                        x = U.NEString(x, "network error");
                        U.TError(x);
                    }

                    function check() {
                        var r = [];
                        var intervals = [];
                        var base = create_base_time();
                        for (var i = 0; i < day_split.length; i++) {
                            for (var j = 0; j < day_split[i].dow_items.length; j++) {
                                var dr = day_split[i].dow_items[j];
                                var start = U.IntMoreOr(dr.start_time, -1, null);
                                var end = U.IntMoreOr(dr.fin_time, -1, null);
                                if ((start === null || end === null) || start >= end) {
                                    continue;
                                }
                                start = (start - base) / 1000;
                                end = (end - base) / 1000;
                                for (var k = 0; k < intervals.length; k++) {
                                    if ((start >= intervals[k].start && start < intervals[k].end) || // если старт внутри иного интервала
                                            (end >= intervals[k].end && end <= intervals[k].end) || // если end внутри другого интервала
                                            (intervals[k].start >= start && intervals[k].start < end) || // если внутри start-end есть начало другого интервала
                                            (intervals[k].end > start && intervals[k].end < end)  // если внутри start-end есть конец другого интервала                                           
                                            ) {
                                        U.Error(["Пересечение интервалов:<br>", days[i + 1], ':<br>', intervals[k].time_str, '<br>и<br>', [dr.time_str, dr.end_time].join('-')].join(''));
                                    }
                                }
                                intervals.push({
                                    start: start, end: end,
                                    time_str: [dr.time_str, dr.end_time].join('-')
                                });
                                r.push({start: start, length: end - start});
                            }
                        }
                        return {items: r};
                    }

                    handle.on('click', '[data-command]', function (e) {
                        var t = jQuery(this);
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        e.stopPropagation();
                        var cmd = U.NEString(t.data('command'), null);
                        if (cmd) {
                            var command_fn = "on_command_" + cmd;
                            if (U.isCallable(hdlrs[command_fn])) {
                                hdlrs[command_fn](t, e);
                            }
                        }
                    });

                    handle.on('click', 'input[type=text]', function () {
                        var t = jQuery(this);
                        if (!t.data('picker')) {
                            t.datetimepicker({
                                datepicker: false,
                                format: 'H:i',
                                scrollMonth: false,
                                scrollInput: false,
                                lang: "ru", step: 30,
                                onClose: function () {
                                    t.datetimepicker('destroy');
                                    t.data('picker', false);
                                },
                                onSelectTime: function () {

                                    t.change();
                                }
                            });
                            t.data('picker', true);
                            t.trigger('focus');
                        }

                        //t.datetimepicker('show');
                    });

                    handle.on('change', '[data-monitor="start"]', function (e) {
                        var t = jQuery(this);
                        var dow = U.IntMoreOr(t.data('index'), 0, null);
                        if (dow) {
                            var uid = U.NEString(t.data('id'), null);
                            if (uid) {
                                var tt = day_split[dow - 1];
                                var k = null;
                                for (var i = 0; i < tt.dow_items.length; i++) {
                                    if (tt.dow_items[i].uid === uid) {
                                        k = tt.dow_items[i];
                                        break;
                                    }
                                }
                                if (k) {

                                    k.start_time = time_to_int(t.val(), dow - 1, k.start_time);
                                    k.time_str = format_time(k.start_time);
                                    if (k.fin_time <= k.start_time) {
                                        k.fin_time = null;
                                        k.end_time = null;
                                    }
                                    t.val(k.time_str);
                                    t.parent().parent().find('input[data-monitor=end]').val(k.end_time);
                                }
                            }
                        }
                    });
                    handle.on('change', '[data-monitor="end"]', function (e) {
                        var t = jQuery(this);
                        var dow = U.IntMoreOr(t.data('index'), 0, null);
                        if (dow) {
                            var uid = U.NEString(t.data('id'), null);
                            if (uid) {
                                var tt = day_split[dow - 1];
                                var k = null;
                                for (var i = 0; i < tt.dow_items.length; i++) {
                                    if (tt.dow_items[i].uid === uid) {
                                        k = tt.dow_items[i];
                                        break;
                                    }
                                }
                                if (k) {
                                    k.fin_time = time_to_int(t.val(), dow - 1, k.fin_time);
                                    k.end_time = format_time(k.fin_time);
                                    if (k.fin_time <= k.start_time) {
                                        k.start_time = null;
                                        k.time_str = null;
                                    }
                                    t.val(k.end_time);
                                    t.parent().parent().find('input[data-monitor=start]').val(k.time_str);
                                }
                            }
                        }
                    });


                    function time_to_int(v, dd, def) {//required full time!!!!!
                        v = U.NEString(v, '');
                        var m = /^(\d{1,2}):(\d{1,2})$/i.exec(v);
                        if (m) {
                            var d = new Date();
                            d.setTime(create_base_time());
                            d.setDate(d.getDate() + dd);
                            d.setUTCHours(U.IntMoreOr(m[1], 0, 0));
                            d.setUTCMinutes(U.IntMoreOr(m[2], 0, 0));
                            var rr = d.getTime(); //returns full time!!
                            return rr;
                        }

                        return def;
                    }

                    //loader.show();
                    //jQuery.getJSON('/Cabinet/API', {action: "trainer_calendar"})
                    //        .done(responce)
                    //        .fail(fail)
                    //         .always(function () {

//                                loader.hide();
                    //                          });

                    function responce(d) {
                        if (U.isObject(d)) {
                            if (d.status === 'ok') {
                                return success_load(d.items);
                            }
                            if (d.status === 'error') {
                                return fail(d.error_info.message);
                            }
                        }
                        return fail("invalid server response");
                    }



                    function create_base_time() {
                        if (null === global_base_time) {
                            var d = new Date();
                            d.setTime(0);
                            while (d.getUTCDay() !== 1) {
                                d.setDate(d.getDate() + 1); // rewind to monday 00:00:00
                            }
                            global_base_time = d.getTime();
                        }
                        return global_base_time;
                    }

                    function success_load(items) {
                        items = U.safeArray(items);
                        day_split = [];
                        for (var i = 1; i < days.length; i++) {
                            var day = {
                                index: i,
                                text: days[i],
                                text_r: days_r[i],
                                dow_items: []
                            };
                            day_split.push(day);
                        }
                        var base_time = create_base_time();
                        for (var i = 0; i < items.length; i++) {
                            items[i].time_id = U.IntMoreOr(items[i].time_id, -1, null);
                            items[i].start_time = base_time + (items[i].time_id * 1000); //ms offset
                            items[i].time_str = format_time(items[i].start_time);
                            items[i].uid = U.UUID();
                            items[i].dow = get_dow_of(items[i].start_time);
                            items[i].fin_time = items[i].start_time + (U.IntMoreOr(items[i].length, 0, 0) * 1000);// ms offset;
                            items[i].end_time = format_time(items[i].fin_time);
                            day_split[items[i].dow].dow_items.push(items[i]);
                        }

                        render();
                    }


                    function do_sort() {
                        for (var i = 0; i < day_split.length; i++) {

                            day_split[i].dow_items.sort(function (a, b) {
                                if (U.IntMoreOr(a.start_time, -1, null) === null && U.IntMoreOr(b.start_time, -1, null) !== null) {
                                    return 1;
                                } else if (U.IntMoreOr(a.start_time, -1, null) !== null && U.IntMoreOr(b.start_time, -1, null) === null) {
                                    return -1;
                                }
                                return U.IntMoreOr(a.start_time, -1, 0) - U.IntMoreOr(b.start_time, -1, 0);
                            });
                        }
                    }

                    function render() {
                        do_sort();
                        handle.html(Mustache.render(TEMPLATES.dow, {MC: '{/literal}{$controller->MC}{literal}', dows: day_split}, TEMPLATES));
                    }



                    function format_time(x) {
                        var d = new Date();
                        d.setTime(x);
                        return [
                            U.padLeft(d.getUTCHours(), 2, "0"),
                            U.padLeft(d.getUTCMinutes(), 2, "0")
                        ].join(':');
                    }

                    function get_dow_of(x) {
                        var d = new Date();
                        d.setTime(x);
                        var result = d.getUTCDay() - 1;
                        if (result < 0) {
                            result = 7 + result;
                        }
                        return result;
                    }


                    function fail(x) {
                        handle.html(Mustache.render(TEMPLATES.error, {MC: '{/literal}{$controller->MC}{literal}', 'error': U.NEString(x, "network error")}));
                    }

                    responce(default_response);
                }
            }
        })();
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
                handle.find('[data-command=select_hole]').on('click', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    EFO.Com().load('selectors.mapbox_on_selector_trainer')
                            .done(window, on_selector_ready)
                            .always(window, hide_loader);
                });
                function hide_loader() {

                }
                function on_selector_ready(x) {
                    x.set_delegate(x).show().setCallback(window, selector_selected);
                }
                function selector_selected(x) {
                    var t = jQuery('#trainer_selected_zal');
                    var img = t.find('img');
                    img.attr('src',[U.NEString(x.default_image)?["/media/training_hall/",x.id,"/",x.default_image].join(''):"/media/fallback/1/training_hall",".SW_200H_200CF_1.jpg"].join(''));
                    t.find(['.',key,"Hole_name"].join('')).html(x.name);
                    t.find(['.',key,"Hole_address"].join('')).html(x.address);
                    t.find(['.',key,"Hole_phone"].join('')).html(x.phone);
                    t.show();
                    handle.find('input[data-field=hole_id]').val(x.id);                                 
                }
                function collect_data() {
                    var r = {};
                    handle.find('[data-field]').each(function () {
                        var t = jQuery(this);
                        var n = U.NEString(t.data('field'), null);
                        if (n) {
                            if (t.is('input[type=text],input[type=password],textarea,input[type=hidden]')) {
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
                    try {
                        r.time_map = window.rasp_get_data();
                    } catch (e) {
                        r.time_map = e;
                    }
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

                    if (!U.NEString(d.sport, null)) {
                        errors.push({f: "sport", "e": "Укажите вид спорта"});
                    }
                    if (!d.time_map) {
                        errors.push({f: "rasp", "e": "Некорректное расписание"});
                    } else if (d.time_map instanceof Error) {
                        errors.push({f: "rasp", "e": d.time_map.message});
                    } else if (U.isObject(d.time_map)) {
                        d.time_map = JSON.stringify(d.time_map);
                    } else {
                        errors.push({f: "rasp", "e": "Некорректное расписание"});
                    }

                    if (!U.IntMoreOr(d.hole_id, 0, null)) {
                        errors.push({f: "hole_id", "e": "Не выбран зал"});
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
                    jQuery("#main_block").scrollTop(min_offset + jQuery("#main_block").scrollTop());
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
{include './calendar.svg.tpl'}