<div id="user_info">
    <div id="user_photo">
        <img src="/media/avatar/{$user_info->id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?t={random}">
    </div>
    <div id="user_name">
        {$user_info->family} {$user_info->name}
    </div>
    <div id="user_sport">
        Вид спорта - {$user_last_sport}
    </div>
</div>
<div id="cal_block">
    <div class="row">
        <div class="col s12 l8">
            <div id="cal_block_block">
                Календарь
            </div>
            <div id='daylist' style='display:none'>
            </div>
            <div id="zapis_all">
                <h5>Новая тренировка</h5>
                <div class="register_part">
                    <div class="register_part_row register_part_row_date">
                        <div class="register_part_row_header">Дата</div>
                        <input type="text" readonly="readonly" id="nt_date" />
                    </div>
                    <div class="register_part_row register_part_row_hall">
                        <div class="register_part_row_header">Место тренировки</div>
                        <div class="register_part_field" style="display:none">
                            <div class="hole_item_wrapper">
                                <div class="hole_item_inner">
                                    <div class="hole_item_img"><img></div>
                                    <div class="hole_item_texts">
                                        <div class="hole_item_text_name"></div>
                                        <div class="hole_item_text_address"></div>
                                        <div class="hole_item_text_phone"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="register_part_placeholder">
                            <a href="#" data-command="select_place">выбрать</a>
                        </div>
                    </div>
                    <div class="register_part_row register_part_row_trainer">
                        <div class="register_part_row_header">Тренер</div>
                        <div class="register_part_field" style="display:none">
                            <div class="trainer_item_wrapper">
                                <div class="trainer_item_inner">
                                    <div class="trainer_item_img"><img></div>
                                    <div class="trainer_item_texts">
                                        <div class="trainer_item_text_name"></div>                                        
                                        <div class="trainer_item_text_phone"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="register_part_placeholder">
                            <a href="#" data-command="select_trainer">выбрать</a>
                        </div>
                    </div>

                    <div class="register_part_row register_part_row_time">
                        <div class="register_part_row_header">Время</div>
                        <div class="register_part_timelist">
                            Выберите место, тренера и дату
                        </div>
                    </div>
                </div>
                <div class="zapis_btn" id="do_record" data-command="do_record">
                    Записаться
                </div>
            </div>
        </div>
        <div class="col s12 l4">
            <div id="my_trens">
                {foreach from=$last_trainers item=trainer}
                    <div class="one_tren">
                        <div class="row">
                            <div class="col s8">
                                <div class="my_tren_name">
                                    {$trainer.family} {$trainer.name}
                                </div>
                                <div class="my_tren_spec">
                                    {$trainer.sport}
                                </div>
                                <div class="my_tren_ready">
                                    Пройдено <span>{$trainer.qty}</span> тренировок
                                </div>
                                <div class="my_tren_zapis">
                                    <div class="zapis_btn btn_do_setup_trainer" data-trainer-id="{$trainer.trainer_id}" data-place-id="{$trainer.place_id}" data-command="setup_trainer">
                                        Записаться
                                    </div>
                                </div>
                            </div>
                            <div class="col s4">
                                <div class="my_tren_photo">
                                    <img src="/media/avatar/{$trainer.trainer_id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?t={random}">
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
<link type="text/css" rel="stylesheet" href="/assets/css/efo.css"/>
{literal}
    <script>
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || {};
            window.Eve.EFO.Ready.push(
                    function () {
                        var U = window.Eve.EFO.U;
                        window.Eve.EFO.Com().js('/assets/vendor/caleandar/js/caleandar.js');
                        window.Eve.EFO.Com().css('/assets/vendor/caleandar/css/theme2.css');
                        window.Eve.EFO.Com().css('/assets/vendor/datepicker/css.css');
                        window.Eve.EFO.Com().js('/assets/vendor/datepicker/js_async.js');
                        window.xxcalendar = window.xxcalendar || [];
                        window.xxcalendar.push(calendar_ready);
                        window.xxpicker = window.xxpicker || [];
                        window.xxpicker.push(picker_ready);
                        jQuery('#calendar').addClass('selected');
                        var form_handle = jQuery('.register_part');
                        var handlers = {
                            on_command_select_place: function () {
                                window.Eve.EFO.Com().load('selectors.mapbox_on_selector_trainer')
                                        .done(this, this.point_selector_ready);
                                return this;
                            },
                            point_selector_ready: function (x) {
                                x.set_delegate(x).show().setCallback(this, this.on_point_selected);
                                return this;
                            },
                            on_point_selected: function (x) {
                                this.point_data = x;
                                this.trainer_data = null;
                                this.selected_times = null;
                                this.render();
                            },
                            render: function () {
                                if (this.point_data) {
                                    this.render_point_data();
                                    form_handle.find('.register_part_row_hall .register_part_field').show();
                                    form_handle.find('input[name=hall_id]').val(this.point_data.id);
                                } else {
                                    form_handle.find('.register_part_row_hall .register_part_field').hide();
                                    form_handle.find('input[name=hall_id]').val('');
                                }
                                if (this.trainer_data) {
                                    this.render_trainer_data();
                                    form_handle.find('.register_part_row_trainer .register_part_field').show();
                                    form_handle.find('input[name=trainer_id]').val(this.trainer_data.id);
                                } else {
                                    form_handle.find('.register_part_row_trainer .register_part_field').hide();
                                    form_handle.find('input[name=trainer_id]').val('');
                                }
                                if (this.selected_date && this.selected_times) {
                                    this.selected_time = null;
                                    var html = [];
                                    for (var i = 0; i < this.selected_times.length; i++) {
                                        var s = this.selected_times[i];
                                        html.push([
                                            '<div class="free_time_item free_time_item-', s.buisy, ' ', this.is_selected_time(s) ? 'selected' : '',
                                            '" data-command="do_select_time" data-start="', s.start, '" data-buisy="', s.buisy,
                                            '" data-end="', s.end, '">',
                                            '<span>', s.start_fmt, '</span>-<span>', s.end_fmt, '</span></div>'
                                        ].join(''));
                                    }
                                    form_handle.find('.register_part_timelist').html(html.join(''));
                                } else if (this.selected_date && this.trainer_data) {
                                    form_handle.find('.register_part_timelist').html("Тренер в этот день не работает");
                                    this.selected_time = null;
                                } else {
                                    form_handle.find('.register_part_timelist').html("Выберите место, тренера и дату");
                                    this.selected_time = null;
                                }

                                if (this.point_data && this.selected_date && this.trainer_data && !this.selected_times) {

                                    this.load_times();
                                }
                            },
                            render_point_data: function () {
                                var r = form_handle.find('.register_part_row_hall .register_part_field');
                                r.find('.hole_item_img img').attr('src', [this.point_data.default_image ? ['/media/training_hall/', this.point_data.id, '/', this.point_data.default_image].join('') : '/media/fallback/1/training_hall', '.SW_200H_200CF_1.jpg'].join(''));
                                r.find('.hole_item_text_name').html(this.point_data.name);
                                r.find('.hole_item_text_address').html(this.point_data.address);
                                r.find('.hole_item_text_phone').html(this.point_data.phone);
                                return this;
                            },
                            render_trainer_data: function () {
                                var r = form_handle.find('.register_part_row_trainer .register_part_field');
                                r.find('.trainer_item_img img').attr('src', ["/media/avatar/", this.trainer_data.id, "/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?t=",(new Date()).getTime()].join(''));
                                r.find('.trainer_item_text_name').html(U.NEString([this.trainer_data.family, this.trainer_data.name, this.trainer_data.eldername].join(' '), ''));
                                r.find('.trainer_item_text_phone').html(U.NEString([this.trainer_data.phone].join(''), ''));
                            },
                            on_command_select_trainer: function () {
                                if (!this.point_data) {
                                    alert("Сначала выберите место");
                                    return;
                                }
                                window.Eve.EFO.Com().load('selectors.trainer_selector').done(this, this.trainer_selector_ready);
                                return this;
                            },
                            trainer_selector_ready: function (x) {
                                x.show().load(this.point_data.id).setCallback(this, this.on_trainer_selected);
                            },
                            on_trainer_selected: function (d, t) {
                                if (t) {
                                    this.trainer_data = t;
                                    this.selected_times = null;
                                }
                                this.render();
                            },
                            on_date: function (d) {
                                this.selected_date = d;
                                this.selected_times = null;
                                this.render();


                            },
                            load_times: function () {
                                if (this.trainer_data && this.point_data && this.selected_date) {
                                    jQuery.getJSON("/Info/API", {action: "available_times", t: this.trainer_data.id, d: this.selected_date})
                                            .done(this.on_times_available.bindToObject(this));
                                }
                            },
                            on_times_available: function (d) {

                                this.selected_times = U.safeArray(d.times);
                                this.render();
                            },

                            on_command_do_select_time: function (t, e) {
                                if (!U.anyBool(t.data('buisy'), false)) {
                                    var a = [U.IntMoreOr(t.data('start'), -1, null), U.IntMoreOr(t.data('end'), 0, null)];
                                    if (this.check_time(a)) {
                                        this.selected_time = a;
                                        t.parent().find('.selected').removeClass('selected');
                                        t.addClass('selected');
                                    }
                                }
                                return this;
                            },
                            check_time: function (x) {
                                return U.isArray(x) && x.length === 2 && x[0] !== null && x[1] !== null && x[1] > x[0];
                            },
                            is_selected_time: function (a) {
                                if (this.selected_time) {
                                    if (U.IntMoreOr(a.start, -1, null) === this.selected_time[0]) {
                                        if (U.IntMoreOr(a.end, 0, null) === this.selected_time[1]) {
                                            return true;
                                        }
                                    }
                                }
                                return false;
                            },
                            on_command_do_record: function (t) {
                                debugger;
                                if (!t.hasClass('disabled')) {
                                    if (this.point_data && this.trainer_data && this.selected_date && this.selected_time && this.selected_times) {
                                        t.addClass('disabled');
                                        jQuery.getJSON("/Cabinet/API", {action: "create_request", p: this.point_data.id, t: this.trainer_data.id, d: this.selected_date, tm: this.selected_time[0]})
                                                .done(this.on_record_response.bindToObject(this))
                                                .always(function () {
                                                    t.removeClass('disabled');
                                                });
                                        return;
                                    }
                                    alert("Заполните форму");
                                }
                                return this;
                            },
                            on_record_response: function (d) {
                                if (U.isObject(d)) {
                                    if (d.status === "ok") {
                                        alert("Запланировано");
                                        window.location.reload(true);
                                        return;
                                    }
                                    if (d.status === "error") {
                                        alert(d.error_info.message);
                                        return;
                                    }

                                }
                                alert("Некорректный ответ сервера");
                            },
                            setup_trainer: function (t) {
                                var trainer_id = U.IntMoreOr(t.data('trainerId'), 0, null);
                                if (trainer_id) {
                                    jQuery.getJSON("/Info/API", {action: "trainer_info_w_point", t: trainer_id})
                                            .done(this.on_trainer_data_available.bindToObject(this));
                                }
                            },
                            on_trainer_data_available: function (d) {
                                if (U.isObject(d)) {
                                    if (d.status === "ok") {
                                        this.point_data = d.point_data;
                                        this.trainer_data = d.trainer_data;
                                        this.render();
                                    }
                                }
                            }
                        };
                        form_handle.on('click', '[data-command]', function (e) {

                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                            e.stopPropagation();
                            var t = jQuery(this);
                            var command = U.NEString(t.data('command'), null);
                            if (command) {
                                var fn = ["on_command_", command].join('');
                                if (U.isCallable(handlers[fn])) {
                                    handlers[fn](t, e);
                                }
                            }
                        });

                        jQuery('#do_record').on('click', function (e) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = null;
                            handlers.on_command_do_record(jQuery(this));
                        });
                        jQuery('.btn_do_setup_trainer').on('click', function (e) {
                            debugger;
                            var t = jQuery(this);
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = null;
                            handlers.setup_trainer(t);
                        });
                        function picker_ready() {
                            form_handle.find('#nt_date').datetimepicker({
                                lang: "ru",
                                format: "d.m.Y",
                                formatDate: "d.m.Y",
                                closeOnDateSelect: true,
                                timepicker: false,
                                minDate: 0,
                                onSelectDate: function () {
                                    //handle.find('input').change();
                                },
                                scrollMonth: false,
                                scrollInput: false,
                                dayOfWeekStart: 1

                            });
                            form_handle.find('#nt_date').on('change', function () {
                                var v = U.NEString(jQuery(this).val(), null);
                                if (v && v !== handlers.selected_date) {
                                    handlers.on_date(v);
                                }
                            });
                        }

                    }



            );
            var current_loading = null;
            var TEMPLATES = {/literal}{$controller->get_frontend_templates('events_front')|json_encode}{literal};
            function calendar_ready() {
                var U = window.Eve.EFO.U;
                var planned_trainings = {/literal}{$planned_trainigs|json_encode}{literal};
                setup_calendar(planned_trainings);

            }

            function setup_calendar(resp) {
                var U = window.Eve.EFO.U;
                var events = [];
                for (var i = 0; i < resp.length; i++) {
                    var ri = resp[i];
                    var d = U.parseSQLDateTime(ri.datum);
                    d.setHours(0, 0, 0, 0);
                    events.push({
                        'Date': d,
                        'Title': 'Тренировка',
                        xdata: {ri}
                    });
                }

                caleandar(document.getElementById('cal_block_block'), events, {
                    Color: '#999', //(string - color) font color of whole calendar.
                    LinkColor: '#333', //(string - color) font color of event titles.
                    NavShow: true, //(bool) show navigation arrows.
                    NavVertical: false, //(bool) show previous and coming months.
                    NavLocation: null, //(string - element) where to display navigation, if not in default position.
                    DateTimeShow: true, //(bool) show current date.
                    DateTimeFormat: 'mmm, yyyy', //(string - dateformat) format previously mentioned date is shown in.
                    DatetimeLocation: '', //(string - element) where to display previously mentioned date, if not in default position.
                    EventClick: function (xm) {
                        run_load_events_for_date(xm.xdata.ri.datum);
                    }, //(function) a function that should instantiate on the click of any event. parameters passed in via data link attribute.
                    EventTargetWholeDay: true, //(bool) clicking on the whole date will trigger event action, as opposed to just clicking on the title.
                    DisabledDays: [], //(array of numbers) days of the week to be slightly transparent. ie: [1,6] to fade Sunday and Saturday.
                    dd: "dd"
                });


            }

            function run_load_events_for_date(datemark) {
                var U = window.Eve.EFO.U;
                var xs = datemark.split(' ');
                var date = xs[0];
                if (current_loading !== date) {
                    current_loading = date;
                    jQuery('#daylist').hide();
                    jQuery.getJSON('/Cabinet/API', {action: "get_events_client", date: date})
                            .done(function (d) {
                                if (U.isObject(d)) {
                                    if (d.status === "ok") {
                                        if (U.isArray(d.events) && d.events.length) {
                                            render_events(d.events);
                                            return;
                                        }
                                    }
                                }
                                jQuery('#daylist').hide();
                            })
                            .fail(function () {
                                jQuery('#daylist').hide();
                            })
                            .always(function () {
                                current_loading = null;
                            });
                }
            }
            function render_events(events) {
                jQuery('#daylist').html(Mustache.render(TEMPLATES.event, {events: events}, TEMPLATES));
                jQuery('#daylist').show();
            }
        })();
    </script>
{/literal}