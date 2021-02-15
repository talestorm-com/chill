{$OUT->add_css("/assets/vendor/datepicker/css.css", 0)|void}
<div class='main_page_form_client'>
    <div class='main_page_form_client_inner'>
        <div class='main_page_form_client_place'>
            <div class='main_page_form_client_field_header'>Фитнес-зал</div>
            <div class='main_page_form_client_place_inner'>
            </div>
        </div>

        <div class='main_page_form_client_trainer' data-command="select_trainer">
            <div class='main_page_form_client_field_header'>Тренер</div>
            <div class='main_page_form_client_trainer_inner'>
            </div>
        </div>
        <div class="main_page_form_cient_date">
            <div class='main_page_form_client_field_header'>Дата</div>
            <div class="main_page_form_cient_date_inner">
                <input type="text" readonly="readonly">
            </div>
        </div>
        <div class="main_page_form_cient_time">
            <div class='main_page_form_client_field_header'>Время</div>
            <div class="main_page_form_client_timelist"></div>
        </div>
    </div>
    <div class="main_page_form_button">
        <div class="main_page_form_button_inner" data-command="do_action">Записаться</div>
    </div>        
    <div class="main_page_form_oader"></div>
</div>
{literal}
    <script type="mustache/template" id="mpf_tpl_place">
        <div class="template_main_page_place">
        <div class="template_main_page_place_inner">
        <div class="template_main_page_place_image">
        {{#default_image}}
        <img src="/media/training_hall/{{id}}/{{default_image}}.SW_250H_250CF_1.jpg" />
        {{/default_image}}
        {{^default_image}}
        <img src="/media/fallback/1/training_hall.SW_250H_250CF_1.jpg" />
        {{/default_image}}
        </div>
        <div class="template_main_page_place_text">
        <div class="template_main_page_place_text_name">{{name}}</div>
        <div class="template_main_page_place_text_address">{{address}}</div>
        </div>
        </div>
        </div>
    </script>
    <script type="mustache/template" id="mpf_tpl_place_stub">
        <div class="template_main_page_place template_main_page_place_stub">
        <div class="template_main_page_place_inner">
        <div class="template_main_page_place_image">                
        <img src="/assets/images/question.png" />        
        </div>
        <div class="template_main_page_place_text">
        <div class="template_main_page_place_text_name">Выбкрите зал на карте</div>
        <div class="template_main_page_place_text_address"></div>
        </div>
        </div>
        </div>
    </script>
    <script type="mustache/template" id="mpf_tpl_trainer_stub">
        <div class="template_main_page_trainer template_main_page_trainer_stub">
        <div class="template_main_page_trainer_inner">
        <div class="template_main_page_trainer_image">                
        <img src="/assets/images/question.png" />        
        </div>
        <div class="template_main_page_trainer_text">
        <div class="template_main_page_place_trainer_name">Нажмите чтобы выбрать</div>        
        </div>
        </div>
        </div>
    </script>
    <script type="mustache/template" id="mpf_tpl_trainer">
        <div class="template_main_page_trainer">
        <div class="template_main_page_trainer_inner">
        <div class="template_main_page_trainer_image">                
        <img src="/media/avatar/{{id}}/aaca0f5eb4d2d98a6ce6dffa99f8254b.SW_200H_200CF_1.jpg" />        
        </div>
        <div class="template_main_page_trainer_text">
        <div class="template_main_page_place_trainer_name">{{family}} {{name}} {{eldername}}</div>        
        </div>
        </div>
        </div>
    </script>    
     <script type="mustache/template" id="mpf_tpl_time">
        {{#times}}
        <div class="template_main_page_time_item {{#is_buisy}} mp_time_buisy {{/is_buisy}} {{#is_time_selected}}{{^is_buisy}} mp_time_selected {{/is_buisy}}{{/is_time_selected}}" data-buisy="{{buisy}}" data-id="{{start}}" data-command="time_select">
        <div class="template_main_page_time_item_inner">
        <div class="template_main_page_time_item_inner_text">{{start_fmt}}</div> - <div class="template_main_page_time_item_inner_text">{{end_fmt}}</div>                
        </div>
        </div>
        {{/times}}
    </script>    
{/literal}
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(r0);
            function r0() {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var handle = jQuery('.main_page_form_client');
                var loader = jQuery('.main_page_form_oader');
                loader.show();
                EFO.Com().js("/assets/vendor/datepicker/js.js").done(r1);
                function r1() {
                    var rx = {
                        on_command_select_trainer: function () {
                            if (!place_info) {
                                U.TError("Сначала выберите зал");
                                return;
                            }
                            loader.show();
                            EFO.Com().load('selectors.trainer_selector')
                                    .done(this, this.trainer_selector_ready)
                                    .fail(this, this.component_fail)
                                    .always(this, this.hide_loader);
                        },
                        hide_loader: function () {
                            loader.hide();
                        },
                        component_fail: function () {
                            U.TError("component load error");
                        },
                        trainer_selector_ready: function (x) {
                            x.show().load(place_info.id).setCallback(this, this.on_trainer_selected);
                        },
                        on_trainer_selected: function (id) {
                            loader.show();
                            jQuery.getJSON('/Info/API', {action: "get_trainer_info", id: id})
                                    .done(this.trainer_response.bindToObject(this))
                                    .fail(this.load_fail.bindToObject(this))
                                    .always(this.hide_loader.bindToObject(this))
                                    .always(this.try_load_times.bindToObject(this));
                        },
                        trainer_response: function (d) {
                            if (U.isObject(d)) {
                                if (d.status === 'ok') {
                                    return this.trainer_success(d.trainer);
                                }
                                if (d.status === 'error') {
                                    return this.load_fail(d.error_info.message);
                                }
                            }
                            return this.load_fail("invalid server response");
                        },
                        load_fail: function (x) {
                            U.TError(U.NEString(x, "network error"));
                        },
                        trainer_success: function (d) {
                            trainer_info = d;
                            update_view();
                        },
                        try_load_times: function () {
                            if (trainer_info && date) {
                                loader.show();
                                jQuery.getJSON('/Info/API', {action: "available_times", t: trainer_info.id, d: date})
                                        .done(this.on_times.bindToObject(this))
                                        .fail(this.load_fail.bindToObject(this))
                                        .always(this.hide_loader.bindToObject(this));
                            }else{
                                this.reset_times();
                            }
                            return this;
                        },
                        reset_times:function(){
                            time=null;
                            handle.find('.main_page_form_client_timelist').html('');
                        },
                        on_times:function(d){
                            if(U.isObject(d)){
                                if(d.status==='ok'){
                                    this.times=U.safeArray(d.times);
                                    this.render_times();
                                    return this;
                                }
                                if(d.status==='error'){
                                    return this.load_fail(d.error_info.message);
                                }
                            }
                            this.load_fail("invalid server response");
                        },
                        render_times:function(){
                            handle.find('.main_page_form_client_timelist').html( Mustache.render( get_template('time'),this) );
                            return this;
                        },
                        init_time_renderers:function(){
                            this.is_buisy = this._is_buisy.bindToObjectWParam(this);
                            this.is_time_selected = this._is_time_selected.bindToObjectWParam(this);
                        },
                        _is_buisy:function(wto){
                            return U.anyBool(wto.buisy,false);
                        },
                        _is_time_selected:function(wto){
                            var test = U.IntMoreOr(time,-1,null);
                            if(null!==test){
                                if(test === U.IntMoreOr(wto.start,-1,null)){
                                    return true;
                                }
                            }
                            return false;
                        },
                        on_command_time_select:function(t){                            
                            if(U.anyBool(t.data('buisy'),false)){
                                U.Error("Это время уже зарезервировано, выберите другое");
                            }
                            var start=U.IntMoreOr(t.data('id'),-1,null);
                            if(start!==null){
                                time=start;
                                this.render_times();
                            }
                        },
                        
                        get_time_selected:function(){
                            var node = handle.find('.main_page_form_client_timelist .mp_time_selected');
                            if(node && node.length){
                                var t = U.IntMoreOr(node.data('id'),-1,null);
                                return t!==null?t:null;
                            }
                            return null;
                        },
                        on_command_do_action:function(){
                            if(place_info && trainer_info && date && time!==null && time===this.get_time_selected()){
                                loader.show();
                                jQuery.getJSON('/Cabinet/API',{action:"create_request",p:place_info.id,t:trainer_info.id,d:date,tm:time})
                                        .done(this.on_create_response.bindToObject(this))
                                        .fail(this.load_fail.bindToObject(this))
                                        .always(this.hide_loader.bindToObject(this));
                            }else{
                                if(!place_info){
                                    U.TError("Выберите фитнес-зал ");
                                }else if(!trainer_info){
                                    U.TError("Выберите тренера");
                                }else if(!date){
                                    U.TError("Выберите дату тренировки");
                                }else{
                                    U.TError("Выберите время");
                                }
                            }
                        },
                        on_create_response:function(d){
                            if(U.isObject(d)){
                                if(d.status==='ok'){
                                    return this.on_create_success(d.training);
                                }
                                if(d.status==='error'){
                                    return this.load_fail(d.error_info.message);
                                }
                            }
                            return this.load_fail("invalid server respose");
                        },
                        on_create_success:function(t){
                            var id=U.IntMoreOr(U.safeObject(t).id,0,null);
                            if(id){
                                window.location.href = "/Cabinet/MyTraining?id="+id;
                            }
                        }
                        
                        
                    };
                    rx.init_time_renderers();

                    handle.on('click', '[data-command]', function (e) {
                        var t = jQuery(this);
                        var cmd = U.NEString(t.data('command'), null);
                        if (cmd) {
                            var cf = ["on_command_", cmd].join('');
                            if (cf && U.isCallable(rx[cf])) {
                                e.stopPropagation();
                                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                                rx[cf](t, e);
                            }
                        }
                    });

                    handle.find('input[type=text]').datetimepicker({
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
                    handle.find('input[type=text]').on('change', function () {
                        date = jQuery(this).val();
                        rx.try_load_times();
                    });
                    var place_info = null;
                    var trainer_info = null;
                    var date = null;
                    var time = null;

                    function get_template(x) {
                        var xx = "#mpf_tpl_" + x;
                        var t = jQuery(xx);
                        return t.get(0).innerHTML;
                    }

                    function update_view() {
                        if (place_info) {
                            handle.find('.main_page_form_client_place_inner').html(Mustache.render(get_template('place'), place_info));
                        } else {
                            handle.find('.main_page_form_client_place_inner').html(Mustache.render(get_template('place_stub'), {}));
                        }
                        if (trainer_info) {
                            handle.find('.main_page_form_client_trainer_inner').html(Mustache.render(get_template('trainer'), trainer_info));
                        } else {
                            handle.find('.main_page_form_client_trainer_inner').html(Mustache.render(get_template('trainer_stub'), {}));
                        }
                    }

                    update_view();


                    function on_point_responce(d) {
                        if (U.isObject(d)) {
                            if (d.status === "ok") {
                                return on_point_success(d.point);
                            }
                            if (d.status === 'error') {
                                return on_point_fail(d.error_info.message);
                            }
                        }
                        on_point_fail("invalid server response");
                    }

                    function on_point_fail(x) {
                        U.TError(U.NEString(x, "network error"));
                    }

                    function on_point_success(data) {
                        data.has_default_image = U.NEString(data.default_image, null) ? true : false;
                        place_info = data;
                        update_view();
                    }

                    function validate_trainer_on_point() {
                        if (trainer_info && place_info) {
                            loader.show();
                            jQuery.getJSON('/Info/API', {action: "check_trainer_in_hall", t: trainer_info.id, p: place_info.id})
                                    .done(function (d) {
                                        if (d.status === 'ok') {
                                            if (U.anyBool(d.result, true) === false) {
                                                trainer_info = null;
                                                update_view();
                                            }
                                        }
                                    }).always(function () {
                                loader.hide();
                            });
                        }
                    }

                    EFO.Events.GEM().on('main_map', window, function (ep) {
                        ep = U.safeObject(ep);
                        if (ep.command === 'select') {
                            var id = U.IntMoreOr(ep.data.id, 0, null);
                            if (id) {
                                loader.show();
                                jQuery.getJSON('/Info/API', {action: "point_info", id: id})
                                        .done(on_point_responce)
                                        .fail(on_point_fail)
                                        .always(function () {
                                            loader.hide();
                                        }).always(validate_trainer_on_point);
                            }
                        }
                    });

                }
            }
        })();
    {/literal}
</script>
