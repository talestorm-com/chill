<div id="user_info">
    <div id="user_photo">
        <img src="/media/avatar/{$user_info->id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?t={random}">
    </div>
    <div id="user_name">
    {if $hole}{$hole.name}{else}Нет данных{/if}
</div>
<div id="tren_address">
{if $hole}{$hole.address}{else}Нет данных{/if}
</div>
</div>
<div id="tren_cal_block">
    <h2> Забронированные тренировки
    </h2>
    <div id="cal_block_block">
        Календарь
    </div>
    <div id='daylist' style='display:none;margin-bottom:10em'></div>
</div>
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
                        window.xxcalendar = window.xxcalendar || [];
                        window.xxcalendar.push(calendar_ready);
                        jQuery('#calendar').addClass('selected');
                    }
            );
            var current_loading = null;
            var TEMPLATES = {/literal}{$controller->get_frontend_templates('events_front')|json_encode}{literal};
            function calendar_ready() {
                var U = window.Eve.EFO.U;
                var planned_trainings = {/literal}{$planned_trainigs|json_encode|default:'[]'}{literal};
                setup_calendar(planned_trainings);

            }

            function setup_calendar(resp) {
                var U = window.Eve.EFO.U;
                resp = U.safeArray(resp);
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
                    jQuery.getJSON('/Cabinet/API', {action: "get_events_hole", date: date})
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