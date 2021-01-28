{$OUT->add_css("/assets/vendor/datepicker/css.css", 0)|void}
<div class="{$controller->MC}_calendar_outer">
    <div class="{$controller->MC}_calendar_header">Расписание Ваших занаятий</div>
    <div class="{$controller->MC}_block_container">
        <div class="{$controller->MC}_calendar_inner" id="{$controller->MC}handle">


        </div>

        <div class="{$controller->MC}_loader_block" id="{$controller->MC}loader">
            <div class="{$controller->MC}_oader_inner">
                <div class="{$controller->MC}_oader_inner_inner">
                    <svg><use xlink:href="#{$controller->MC}_loader" /></svg>
                </div>
            </div>
        </div>
    </div>
</div>
<script>{literal}
    (function () {
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(ready);
        var day_split = [];
        function ready() {
            var E = window.Eve, EFO = E.EFO, U = EFO.U;
            var handle = jQuery("#{/literal}{$controller->MC}{literal}handle");
            var loader = jQuery("#{/literal}{$controller->MC}{literal}loader");
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
                        do_sort();
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
                                });
                    }
                };

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
                                //ситуации: одна из точек находится внутри другого интервала
                                // другой интервал находится внутри данного
                                // интервалы полностью совпадают
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

                loader.show();
                jQuery.getJSON('/Cabinet/API', {action: "trainer_calendar"})
                        .done(responce)
                        .fail(fail)
                        .always(function () {

                            loader.hide();
                        });

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

            }

        }
    })();
</script>{/literal}

{include './calendar.svg.tpl'}