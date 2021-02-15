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
<div id="stud_stat_block">
    <div class="row">
        <div class="col s12 l4">
            {foreach from=$trainers item='trainer'}
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
                    </div>
                    <div class="col s4">
                        <div class="my_tren_photo">
                            <img src="/media/avatar/{$trainer.id}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg?t={random}">
                        </div>
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
<div id="stud_info_block">
    <div id="stat_trens">
        <div class="row">
            <div class="col l6 s12">
                <h2>Всего проведено тренировок - <span>{$total_trainings_qty}</span></h2>
                <ul>
                    {foreach from=$traing_group item='train'}
                    <li>{$train.sport} - <span>{$train.qty}</span></li>                    
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>
</div>
                            {literal}
    <script>
        (function () {
            var chart_data = {};{/literal}{*$diagramm_data|json_encode*}{literal}
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var U = window.Eve.EFO.U;
                
                window.Eve.EFO.Promise.waitForArray([
                    window.Eve.EFO.Com().js('/assets/vendor/Chart.js/Chart.bundle.min.js').promise,
                    window.Eve.EFO.Com().css('/assets/vendor/Chart.js/Chart.min.css').promise
                ])
                        .done(window, chart_ready0)
                        .fail(window, chart_fail);
                jQuery('#statistics').addClass('selected');
                var CHART_USAGE = false;
                function chart_fail() {
                    jQuery('#chart_area').hide();
                    CHART_USAGE = false;
                }
                function chart_ready0() {
                    window.xxchart = window.xxchart || [];
                    window.xxchart.push(chart_ready);
                }
                function chart_ready() {

                    CHART_USAGE = true;
                    jQuery('#chart_area').show();
                    build_chart(chart_data);
                    jQuery('#attribute_selector').on('click', 'li', function () {
                        jQuery.getJSON('/Cabinet/API', {action: "get_client_chart", attribute: jQuery(this).data('attribute')})
                                .done(function (d) {
                                    if (U.isObject(d) && d.status === "ok") {
                                        build_chart(d.chart_data);
                                        return;
                                    }
                                    jQuery('#chart_area').hide();
                                })
                                .fail(function () {
                                    jQuery('#chart_area').hide();
                                })
                    });
                }


                function prepare_date(x) {
                    return U.parseSQLDateTime(x);
                }

                function build_chart(d) {
                    if (U.isObject(d)) {
                        if (U.isArray(d.items) && d.items.length > 1) {
                            jQuery('#chart_area').show();
                            var ctx = document.getElementById('__chart').getContext('2d');
                            var data = [];

                            for (var i = 0; i < d.items.length; i++) {
                                data.push({x: prepare_date(d.items[i].date), y: U.FloatOr(d.items[i].value, 0)});
                            }
                            var myChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    datasets: [{
                                            label: U.NEString(d.label, ''),
                                            data: data,
                                        }]
                                },
                                options: {
                                    scales: {
                                        xAxes: [{
                                                type: 'time',
                                                unit: 'hour'
                                            }]
                                    }
                                }
                            });
                            return;
                        }
                    }
                    jQuery('#chart_area').hide();
                }

            });
        })();
    </script>
{/literal}