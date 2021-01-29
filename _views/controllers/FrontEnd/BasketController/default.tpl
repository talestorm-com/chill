{$OUT->add_css("/assets/css/front/basket/basket.default.css", 0)|void}
<div class="BasketFilsafeWidthDetector" id="{$controller->MC}_width_detect">{literal}<style type="text/css">.BasketFilsafeWidthDetector {width: 100%;box-sizing: border-box;height: 0;padding: 0;margin: 0;min-height: 0;max-height: 0;}</style>{/literal}</div>
<div class="BasketWrapper">
    <div class="BasketWrapperHeader">Ваш заказ:</div>    
    <div class="BasketWrapperLoader">
        {include {$controller->common_templtes("preloader")}}
    </div>
    <div class="BasketIsEmptyText">Ваша корзина пуста :(</div>
    <div class="BasketIsErrorText">Произошла ошибка :( <br>Попробуйте перезагрузить страницу через несколько минут</div>
    <div class="BasketWrapperContent">
        <table>
            <tbody></tbody>
            <tfoot></tfoot>
        </table>
        <div class="BasketWrapperLoaderContent">
            {include {$controller->common_templtes("preloader")}}
        </div>
    </div>
    <div class="BasketWrapperControlOuter">
        <div class="BasketWrapperControl">
            <div class="BasketWrapperControlInfoItem">
                <div class="BasketWrapperControlText">Оформить заказ </div>
                <div class="BasketWrapperContentIcon"><svg><use xlink:href="#basket_icon_arrow_down" /></svg></div>
            </div>
            <div class="BasketWrapperControlButton" data-command="one_click_order">Купить в один клик</div>        
        </div>
    </div>
    <div class="BasketWrapperFormOutside">
        <div class="BasketWrapperFormOuter">
            <div class="BasketWrapperForm">
                <div class="BasketWrapperInputLink">
                    <a href="#" data-command="do_login" class="login_trigger">
                        <div class="BasketWrapperInputIcon">
                            <svg><use xlink:href="#basket_icon_user"/></svg>
                        </div> 
                        <div class="BasketWrapperLoginText">Войти в личный кабинет</div>
                    </a>                    
                </div>
                <div class="BasketWrapperFormContent">
                    <div class="BasketWrapperFormRow">
                        <label for="BasketWrapperFormRowFamily" class="BasketWrapperLabelRequired">Фамилия</label>
                        <input type="text" id="BasketWrapperFormRowFamily" data-field="family" data-monitor="c" />                    
                    </div>
                    <div class="BasketWrapperFormRow">
                        <label for="BasketWrapperFormRowName" class="BasketWrapperLabelRequired">Имя (или имя и отчество, по желанию)</label>
                        <input type="text" id="BasketWrapperFormRowName" data-field="name" data-monitor="c" />                    
                    </div>
                    <div class="BasketWrapperFormRow">
                        <label for="BasketWrapperFormRowPhone" class="BasketWrapperLabelRequired">Телефон</label>
                        <input type="text" id="BasketWrapperFormRowPhone" data-field="phone" data-monitor="phone,c" />                    
                    </div>
                    <div class="BasketWrapperFormRow">
                        <label for="BasketWrapperFormRowEmail" class="BasketWrapperLabelRequired">Email</label>
                        <input type="text" id="BasketWrapperFormRowEmail" data-field="email" data-monitor="c" />                    
                    </div>
                    <div class="BasketWrapperFormRow">
                        <label for="BasketWrapperFormRowDelivery" class="BasketWrapperLabelRequired">А<span style="display:none">fvfjvfjv</span>дрес дос<span style="display:none">fvfjvfjv</span>тавки</label>
                        <div class="BasketWrapperFormRowDeliveryWrapper">
                            <textarea id="BasketWrapperFormRowDelivery" data-field="delivery" data-monitor="c" autocomplete="off"></textarea>                   
                            <div class="BasketWrapperFormDeliveryAdressButton" data-command="">
                                <svg><use xlink:href="#basket_icon_arrow_down_address" /></svg>
                                <select data-monitor="address_select"></select>
                            </div>
                        </div>
                    </div>
                    <div class="BasketWrapperFormRow">
                        <label for="BasketWrapperFormRowComment" class="">Комментарии, пожелания к заказу</label>
                        <textarea id="BasketWrapperFormRowComment" data-field="comment" data-monitor="c" ></textarea>                   
                    </div>
                    <div class="BasketWrapperFormRow BasketWrapperFormRowCheck">
                        <input type="checkbox" id="BasketWrapperFormRowSubscribe" checked="checked" data-field="news" />
                        <label for="BasketWrapperFormRowSubscribe">Хочу узнавать о новинках и акциях</label>
                    </div>
                    <div class="BasketWrapperFormRow BasketWrapperFormRowCheck">
                        <input type="checkbox" id="BasketWrapperFormRowPersonal" checked="checked" data-field="apd" />
                        <label for="BasketWrapperFormRowPersonal"><span>Даю согласие на обработку персональных данных в соответствии с <a href="/page/politika_konfidencialnosti"> политикой конфиденциальности</a></span></label>
                    </div>     
                    <div class="BasketWrapperFormRow">
                        <div class="BasketWrapperDoSubmitButton" data-command="do_order">Отправить заказ</div>
                    </div>
                </div>
            </div>
            <div class="BasketWrapperRight">
                <a href="/page/delivery">Условия доставки и оплаты</a>
            </div>
        </div>
    </div>
</div>
<div style="display:none!important">
    {include './infographic.tpl'}
</div>
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                window.Eve.product_manager_ready = window.Eve.product_manager_ready || [];
                window.Eve.product_manager_ready.push(ready);
            });
            function ready() {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;

                var handle = jQuery('.BasketWrapper');
                var loader = handle.find('.BasketWrapperLoader');
                var content_loader = handle.find('.BasketWrapperLoaderContent');
                var content = handle.find('.BasketWrapperContent');
                var content_table = content.find('tbody');
                var control = handle.find('.BasketWrapperControlOuter');
                var form = handle.find('.BasketWrapperFormOutside');
                var hempty = handle.find('.BasketIsEmptyText');
                var herror = handle.find('.BasketIsErrorText');
                var address_wrap = handle.find('.BasketWrapperFormDeliveryAdressButton');
                var address_select = address_wrap.find('select');
                var basket_info = null;
                var templates ={/literal}{$controller->get_frontend_templates('M')|json_encode}{literal};
                content_table.on('focus', 'input[type=text]', function () {
                    jQuery(this).select();
                });
                var fx_width = screen.width;
                var fx_height = screen.height;
                var fsm = jQuery('.BasketFilsafeWidthDetector');
                var fxx_width = fsm.get(0).getBoundingClientRect().width;                
                jQuery(window).on('resize orientationchange', function () {
                    if (basket_info) {
                        var t_width = fsm.get(0).getBoundingClientRect().width;                        
                        if (fx_width !== screen.width || fx_height !== screen.height || fxx_width!==t_width) {
                            fx_width = screen.width;
                            fx_height = screen.height;
                            fxx_width = fsm.get(0).getBoundingClientRect().width;                
                            try {
                                handlers.update_basket();
                            } catch (e) {

                            }
                        }
                    }
                });
                var handlers = {
                    last_item: null,
                    TO: null,
                    row_id: function (x, part) {
                        part = U.NEString(part, 'row');
                        var xx = null;
                        if (BasketItem.is(x)) {
                            xx = U.NEString(x.hash, null);
                        } else {
                            xx = U.NEString(x, null);
                        }
                        if (xx) {
                            return ["#BasketDriverBasketRowPart", part, xx].join('');
                        }
                        return null;
                    },
                    render_row: function (bi) {
                        return Mustache.render(templates[this.template_row], bi, templates);
                    },
                    detect_row_template: function () {
                        var c = {/literal}{if $controller->is_device}1{else}0{/if}{literal};
                        if (c && Math.floor(fxx_width) < 800) {
                            return "row_m";
                        }
                        return "row";
                    },
                    update_basket: function () {
                        if (this.template_row !== this.detect_row_template()) {
                            this.template_row = this.detect_row_template();
                            content_table.html('');
                        }
                        var bb = BasketInfo.is(basket_info) ? basket_info : null;
                        if (!bb) {
                            return show_error();
                        }
                        if (!bb.count) {
                            return show_empty();
                        }
                        this.last_item = null;
                        var exists_ids = {

                        };
                        for (var i = 0; i < bb.items.length; i++) {
                            var row_key = this.row_id(bb.items[i]);
                            exists_ids[row_key] = row_key;
                            var row = jQuery(row_key);
                            if (!(row && row.length)) {
                                row = jQuery(this.render_row(bb.items[i]));
                                if (this.last_item) {
                                    this.last_item.after(row);
                                } else {
                                    content_table.append(row);
                                }
                            } else {
                                var counter = row.find(this.row_id(bb.items[i], 'counter'));
                                var price = row.find(this.row_id(bb.items[i], 'price'));
                                counter.val([bb.items[i].get_actual_qty(), "шт"].join(' '));
                                price.html(bb.items[i].render_actual_price());
                            }
                            this.last_item = row;
                        }
                        var rows_to_remove = [];
                        var all_rows = content_table.find('tr');
                        for (var i = 0; i < all_rows.length; i++) {
                            var cr = jQuery(all_rows.get(i));
                            var cri = ["#", cr.attr('id')].join('');
                            if (exists_ids[cri] !== cri) {
                                rows_to_remove.push(cr);
                            }
                        }
                        for (var i = 0; i < rows_to_remove.length; i++) {
                            rows_to_remove[i].remove();
                        }
                        this.update_total();
                        show_basket();
                        handle[(basket_info.user_auth ? 'addClass' : 'removeClass')]('BasketValidUserState');
                    },
                    update_total: function () {
                        var total_row = jQuery(this.row_id('total', 'row'));
                        if (!(total_row && total_row.length)) {
                            total_row = jQuery(Mustache.render(templates.total_row, this, templates));
                            content.find('tfoot').append(total_row);
                        } else {
                            var total_cell = jQuery(this.row_id('total', 'value'));
                            total_cell.html(this.render_basket_total());
                        }
                        return this;
                    },
                    render_basket_total: function () {
                        var t = 0.0;
                        for (var i = 0; i < basket_info.items.length; i++) {
                            var item = basket_info.items[i];
                            t += U.FloatMoreEqOr((item.get_actual_price() * item.get_actual_qty()), 0, 0);
                        }
                        return EFO.Checks.formatPriceNSD(t, 2);
                    },
                    update_row: function (hash) {
                        var item = basket_info.get_item(hash);
                        if (item) {
                            var row = jQuery(this.row_id(hash, 'row'));
                            if (row && row.length) {
                                var counter = jQuery(this.row_id(hash, 'counter'));
                                counter.val([item.get_actual_qty(), "шт"].join(' '));
                            }
                        }
                        this.update_total();
                        return this.reset_update_timeout();
                    },
                    on_command_remove_item: function (t) {
                        content_loader.show();
                        jQuery.post("/Basket/API", {action: "remove_item", id: t.data('id'), history: JSON.stringify(this.history.get_data())}, null, 'json')
                                .done(this.on_basket_responce.bindToObject(this))
                                .fail(this.on_basket_fail.bindToObject(this))
                                .always(this.hide_content_loader.bindToObject(this));
                        return this;
                    },
                    hide_content_loader: function () {
                        content_loader.hide();
                    },
                    on_basket_fail: function () {
                        debugger;
                    },
                    on_basket_responce: function (d) {
                        basket_info = BasketInfo(d);
                        this.update_basket();
                    },
                    reset_update_timeout: function () {
                        if (this.TO) {
                            window.clearTimeout(this.TO);
                            this.TO = null;
                        }
                        this.TO = window.setTimeout(this.on_timeout.bindToObject(this), 1500);
                        return this;
                    },
                    on_timeout: function () {
                        if (this.TO) {
                            window.clearTimeout(this.TO);
                            this.TO = null;
                        }
                        jQuery.post('/Basket/API', {action: "sync", history: JSON.stringify(this.history.get_data())})
                                .done(this.on_basket_responce.bindToObject(this))
                                .fail(this.on_basket_fail.bindToObject(this));
                    },
                    on_command_increment: function (t) {
                        var hash = U.NEString(t.data('id'), null);
                        if (hash) {
                            this.history.add(hash, 'INC');
                            this.update_row(hash);
                            this.reset_update_timeout();
                        }
                        return this;
                    },
                    on_command_decrement: function (t) {
                        var hash = U.NEString(t.data('id'), null);
                        if (hash) {
                            var item = basket_info.get_item(hash);
                            if (item) {

                                if (item.get_actual_qty() > 1) {
                                    this.history.add(hash, 'DEC');
                                    this.update_row(hash);
                                    this.reset_update_timeout();
                                }
                            }
                        }

                        return this;
                    },
                    on_monitor_qty: function (t) {
                        var hash = U.NEString(t.data('id'), null);
                        if (hash) {
                            var item = basket_info.get_item(hash);
                            if (item) {
                                var nq = U.IntMoreOr(t.val(), 0, item.qty);
                                this.history.add(hash, 'SET', nq);
                                this.update_row(hash);
                                this.reset_update_timeout();
                            }
                        }
                    },
                    on_monitor_phone: function (t) {
                        t.val(EFO.Checks.tryFormatPhone(t.val()));
                        return this;
                    },
                    update_personal: function () {
                        form.find('[data-field=name]').val(U.NEString([U.NEString(basket_info.split_name, ''), U.NEString(basket_info.split_eldername)].join(' '), ''));
                        form.find('[data-field=family]').val(U.NEString(basket_info.split_family, null));
                        form.find('[data-field=phone]').val(U.NEString(basket_info.user_phone), null);
                        form.find('[data-field=phone]').change();
                        form.find('[data-field=email]').val(U.NEString(basket_info.user_email), null);
                        if (U.isArray(basket_info.address_list) && basket_info.address_list.length) {
                            address_wrap.show();
                            this.alist = basket_info.address_list;
                            address_select.html(Mustache.render(templates.address, this));
                            address_select.val("0");
                            this.alist = null;
                        } else {
                            address_wrap.hide();
                            address_select.html('');
                        }
                    },

                    on_monitor_address_select: function () {
                        var k = U.NEString(address_select.val(), null);
                        if (k && k.length > 2) {
                            var t = U.safeArray(basket_info.address_list);
                            var sa = null;
                            for (var i = 0; i < t.length; i++) {
                                if (t[i].uid === k) {
                                    sa = t[i];
                                    break;
                                }
                            }
                            if (sa) {
                                handle.find('[data-field=delivery]').val(sa.address);
                            }
                        }
                        address_select.val('0');
                    },
                    on_login: function () {
                        try {
                            window.show_global_loader();
                        } catch (ee) {
                            content_loader.show();
                        }
                        jQuery.post('/Basket/API', {action: "sync", history: JSON.stringify(this.history.get_data())})
                                .done(this.on_basket_responce.bindToObject(this))
                                .fail(this.on_basket_fail.bindToObject(this))
                                .always(function () {
                                    try {
                                        window.hide_global_loader();
                                    } catch (ee) {
                                        content_loader.show();
                                    }
                                })
                                .done(handlers.update_personal.bindToObject(handlers));
                    },
                    show_error_alert: function (m) {
                        EFO.Alert().set_title("Ошибка").set_style("red").set_close_btn(true).set_text(m)
                                .set_timeout(3500).set_icon('!').set_image("!").show();
                    },
                    on_command_do_order: function () {
                        var data = null;
                        try {
                            data = this.collect_data();
                        } catch (ee) {
                            this.show_error_alert(ee.message);
                            return;
                        }
                        var post_data = {
                            action: 'do_order',
                            order_data: JSON.stringify(data),
                            history: this.history.get_data()
                        };
                        try {
                            window.show_global_loader();
                        } catch (e) {

                        }
                        jQuery.post("/Basket/API", post_data, null, 'json')
                                .done(this.on_post_success.bindToObject(this))
                                .fail(this.on_post_fail.bindToObject(this))
                                .always(function () {
                                    try {
                                        window.hide_global_loader();
                                    } catch (e) {

                                    }
                                    jQuery(window).scrollTop(0);
                                });
                    },
                    on_post_success: function (d) {
                        d = U.safeObject(d);
                        if (d.status === "ok") {
                            var ts = U.safeObject(d.transaction_state);
                            if(ts.status==="ok"){
                                handle.addClass('BasketEmptyInvisible');
                            }
                            this.on_basket_responce(d);                            
                            this.on_transaction(ts);
                        }
                        if (d.status === 'error') {
                            this.show_error_alert(d.error_info.message);
                        }
                    },
                    on_transaction: function (d) {
                        if (d.status === "ok") {
                          try{
                            window.location.href = "/Basket/Success?id=" + d.order_id;
                          }catch(e){
                            
                          }
                          return;
                        }
                        if (d.status === 'error') {
                            this.show_error_alert(d.error_info.message);
                        }
                    },
                    on_post_fail: function (x) {
                        this.show_error_alert("Ошибка связи с сервером");
                    },
                    collect_data: function () {
                        //history:this.history.get_data(),данные для истории собирать  на последнем этапе
                        var r = {};
                        handle.find('[data-field]').each(function () {
                            var field = U.NEString(jQuery(this).data('field'), null);
                            if (field) {
                                if (jQuery(this).is('input[type=text]') || jQuery(this).is('textarea')) {
                                    r[field] = U.NEString(jQuery(this).val(), null);
                                } else if (jQuery(this).is('input[type=checkbox]')) {
                                    r[field] = U.anyBool(jQuery(this).prop('checked'), false);
                                }
                            }
                        });
                        if (!r.family) {
                            U.Error("Укажите фамилию");
                        }
                        if (!r.name) {
                            U.Error("Укажите имя");
                        }
                        if (!r.phone) {
                            U.Error("Укажите номер телефона");
                        }
                        var pt = EFO.Checks.formatPhone(r.phone);
                        if (!pt) {
                            U.Error("Номер телефона указан некорректно");
                        }
                        r.phone = pt;

                        if (!r.email) {
                            U.Error("Укажите адрес электронной почты!");
                        }
                        if (!EFO.Checks.isEmail(r.email)) {
                            U.Error("Email указан некорректно");
                        }
                        if (!r.delivery) {
                            U.Error("Укажите адрес доставки!");
                        }
                        if (!r.apd) {
                            U.Error("Для исполнения заказа требуется Ваше согласие на обработку персональных данных");
                        }
                        return r;
                    },
                    on_command_one_click_order: function () {
                        try {
                            window.show_global_order();
                        } catch (e) {

                        }
                        EFO.Com().load('front.one_click_order')
                                .done(this, this.on_one_click_ready)
                                .fail(this, this.on_com_fail)
                                .always(this, function () {
                                    try {
                                        window.hide_global_order();
                                    } catch (e) {

                                    }
                                });
                        return this;
                    },
                    on_one_click_ready: function (x) {
                        x.show().load(handle.find('[data-field=phone]').val(), handle.find('[data-field=name]').val())
                                .setCallback(this, this.on_one_click_done);
                        return this;
                    },
                    on_com_fail: function () {
                        this.show_error_alert("Ошибка при загрузке компонента");
                    },
                    on_one_click_done: function (udata) {
                        var data = null;
                        try {
                            data = this.collect_data_simple(udata);
                        } catch (ee) {
                            this.show_error_alert(ee.message);
                            return;
                        }
                        var post_data = {
                            action: 'do_order_simple',
                            order_data: JSON.stringify(data),
                            history: this.history.get_data()
                        };
                        try {
                            window.show_global_loader();
                        } catch (e) {

                        }
                        jQuery.post("/Basket/API", post_data, null, 'json')
                                .done(this.on_post_success.bindToObject(this))
                                .fail(this.on_post_fail.bindToObject(this))
                                .always(function () {
                                    try {
                                        window.hide_global_loader();
                                    } catch (e) {

                                    }
                                    jQuery(window).scrollTop(0);
                                });
                    },
                    collect_data_simple: function (edata) {
                        //history:this.history.get_data(),данные для истории собирать  на последнем этапе
                        var r = {};
                        handle.find('[data-field]').each(function () {
                            var field = U.NEString(jQuery(this).data('field'), null);
                            if (field) {
                                if (jQuery(this).is('input[type=text]') || jQuery(this).is('textarea')) {
                                    r[field] = U.NEString(jQuery(this).val(), null);
                                } else if (jQuery(this).is('input[type=checkbox]')) {
                                    r[field] = U.anyBool(jQuery(this).prop('checked'), false);
                                }
                            }
                        });
                        r.phone = edata.phone;
                        r.name = edata.name;
                        if (!r.name) {
                            U.Error("Укажите имя");
                        }
                        if (!r.phone) {
                            U.Error("Укажите номер телефона");
                        }
                        var pt = EFO.Checks.formatPhone(r.phone);
                        if (!pt) {
                            U.Error("Номер телефона указан некорректно");
                        }
                        r.phone = pt;
                        r.apd = true;
                        return r;
                    }
                };

                EFO.Events.GEM().on('SYS_LOGIN_SUCCESS', handlers, handlers.on_login);
                handle.on('click', '[data-command]', function (e) {
                    var t = jQuery(this);
                    var command = U.NEString(t.data('command'), null);
                    if (command) {
                        var fn = ["on_command_", command].join('');
                        if (U.isCallable(handlers[fn])) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                            handlers[fn](t, e);
                        }
                    }
                });
                handle.on('change', '[data-monitor]', function (e) {
                    var t = jQuery(this);
                    var ams = U.NEString(t.data('monitor'), null);
                    if (ams) {
                        var mons = ams.split(',');
                        for (var i = 0; i < mons.length; i++) {
                            var xmon = U.NEString(mons[i], null);
                            if (xmon) {
                                var fn = ["on_monitor_", xmon].join('');
                                if (U.isCallable(handlers[fn])) {
                                    e.stopPropagation();
                                    e.preventDefault() ? e.preventDefault : e.returnValue = false;
                                    handlers[fn](t, e);
                                }
                            }
                        }
                    }
                });

                function show_error() {
                    herror.show();
                    content.hide();
                    control.hide();
                    form.hide();
                    hempty.hide();
                }
                function show_empty() {
                    herror.hide();
                    content.hide();
                    control.hide();
                    form.hide();
                    hempty.show();
                }

                function show_basket() {
                    herror.hide();
                    content.show();
                    control.show();
                    form.show();
                    hempty.hide();
                    content_loader.hide();
                }
                handle.removeClass('BasketEmptyInvisible');
                jQuery.getJSON('/Basket/API', {action: "basket_content"})
                        .done(function (d) {
                            if (U.isObject(d)) {
                                if (d.status === 'ok') {
                                    basket_info = BasketInfo(d);
                                    handlers.history = history();
                                    handlers.update_basket();
                                    handlers.update_personal();
                                    return;
                                }
                            }
                            show_error();
                        })
                        .fail(function (d) {
                            show_error();
                        })
                        .always(function (d) {
                            loader.hide();
                        });

                //basket_info
                function BasketInfo() {
                    return (BasketInfo.is(this) ? this.init : BasketInfo.F).apply(this, Array.prototype.slice.call(arguments));
                }
                var BIP = U.FixCon(BasketInfo).prototype;
                BIP.count = null;
                BIP.items = null;
                BIP.user_auth = null;
                BIP.dealer = null;
                BIP.user_email = null;
                BIP.user_name = null;
                BIP.user_phone = null;
                BIP.user_id = null;
                BIP.split_name = null;
                BIP.split_family = null;
                BIP.split_eldername = null;
                BIP.address_list = null;
                BIP.index = null;

                BIP.init = function (d) {
                    d = U.safeObject(d);
                    this.count = U.IntMoreOr(d.basket_count, 0, 0);
                    this.user_auth = U.anyBool(d.user_auth, false);
                    this.dealer = U.anyBool(d.user_dealer, false) && this.user_auth;
                    var ui = U.safeObject(d.user_info);
                    this.user_name = U.NEString(ui.name, null);
                    this.split_name = U.NEString(ui.split_name, null);
                    this.split_family = U.NEString(ui.split_family, null);
                    this.split_eldername = U.NEString(ui.split_eldername, null);
                    this.user_phone = U.NEString(ui.phone, null);
                    this.user_email = U.NEString(ui.email, null);
                    this.user_id = U.IntMoreOr(ui.id, 0, null);
                    this.address_list = U.isArray(ui.addresses) ? U.safeArray(ui.addresses) : null;
                    this.items = [];
                    this.index = {};
                    var bi = U.safeArray(U.safeObject(d.basket).items);
                    for (var i = 0; i < bi.length; i++) {
                        var item = BasketItem(bi[i]);
                        if (item && item.is_valid()) {
                            this.items.push(item);
                            this.index[item.hash] = item;
                        }
                    }
                    this.count = this.items.length;
                    return this;
                };
                BIP.get_item = function (x) {
                    var xx = U.NEString(x, null);
                    if (xx) {
                        if (BasketItem.is(this.index[xx])) {
                            return this.index[xx];
                        }
                    }
                    return null;
                };


                function BasketItem() {
                    return (BasketItem.is(this) ? this.init : BasketItem.F).apply(this, Array.prototype.slice.call(arguments));
                }
                var IP = U.FixCon(BasketItem).prototype;
                IP.color_html = null;//: "#000000"
                IP.color_id = null;//: "a85aed82-a151-11e9-9352-2c56dc9ba4ec"
                IP.color_name = null;//: "черный"
                IP.hash = null;//: "32cf9be0ee1dbb4a1f88f8e77e752e2e"
                IP.id = null;//: 49417
                IP.price_gross = null;//: 3450
                IP.price_retai = null;//l: 6900
                IP.product_article = null;//: "4517/1"
                IP.product_name = null;//: "Ярусная юбка с бантом"
                IP.qty = null;//: 1
                IP.sizes = null;//: "44"
                IP.default_image = null;
                IP.init = function (d) {
                    d = U.safeObject(d);
                    this.color_html = U.NEString(d.color_html, null);
                    this.color_id = U.NEString(d.color_id, null);
                    this.color_name = U.NEString(d.color_name, null);
                    this.hash = U.NEString(d.hash, null);
                    this.id = U.IntMoreOr(d.id, 0, null);
                    this.price_gross = U.FloatMoreEqOr(d.price_gross, 0, null);
                    this.price_retail = U.FloatMoreEqOr(d.price_retail, 0, null);
                    this.product_article = U.NEString(d.product_article, null);
                    this.product_name = U.NEString(d.product_name, null);
                    this.sizes = U.NEString(d.sizes, null);
                    this.qty = U.IntMoreOr(d.qty, 0, null);
                    this.default_image = U.NEString(d.default_image, null);
                    return this;
                };
                IP.has_default_image = function () {
                    return this.default_image ? true : false;
                };
                IP.render_actual_price = function () {
                    var v = basket_info.dealer ? this.price_gross : this.price_retail;
                    return EFO.Checks.formatPriceNSD(v, 2);
                };
                IP.get_actual_price = function () {
                    var v = basket_info.dealer ? this.price_gross : this.price_retail;
                    return U.FloatMoreEqOr(v, 0, 0);
                };
                IP.has_image = IP.has_default_image;
                IP.has_color = function () {
                    return !!(U.NEString(this.color_id, null));
                };
                IP.has_sizes = function () {
                    return !!(U.NEString(this.sizes, null));
                };
                IP.is_valid = function () {
                    var a = this.id && this.hash && this.product_article && this.product_name && this.qty;
                    var b = true;
                    if (this.color_id) {
                        b = this.color_html && this.color_name;
                    }

                    return (a && b) ? true : false;
                };
                IP.get_actual_qty = function () {
                    return handlers.history.sync_qty(this);
                };


                function history() {
                    return (history.is(this) ? this.init : history.F).apply(this, Array.prototype.slice.call(arguments));
                }
                var HP = U.FixCon(history).prototype;
                HP.items = null;
                HP.init = function () {
                    this.items = [];
                    return this;
                };
                HP.add = function (id, act, param) {
                    param = U.coalesceDefined(param, null);
                    this.items.push({i: id, a: act, p: param});
                    return this;
                };
                HP.sync_qty = function (x) {
                    if (BasketItem.is(x)) {
                        var r = x.qty;
                        var hash = x.hash;
                        for (var i = 0; i < this.items.length; i++) {
                            if (this.items[i].i === hash) {
                                if (this.items[i].a === 'INC') {
                                    r++;
                                } else if (this.items[i].a === 'DEC') {
                                    r--;
                                } else if (this.items[i].a === 'SET') {
                                    r = U.IntMoreOr(this.items[i].p, 0, 1);
                                }
                            }
                        }
                        return r;
                    }
                    return 0;
                };
                HP.get_data = function () {
                    var result = JSON.parse(JSON.stringify(this.items));
                    this.items = [];// при синхронизации зачищаем всю историю
                    return result;
                };
                EFO.Promise.waitForArray([
                    EFO.Com().js("https://cdn.jsdelivr.net/npm/suggestions-jquery@19.7.1/dist/js/jquery.suggestions.min.js"),
                    EFO.Com().css("https://cdn.jsdelivr.net/npm/suggestions-jquery@19.7.1/dist/css/suggestions.min.css")
                ]).done(function () {
                    handle.find('[data-field=delivery]').suggestions({
                        token: "{/literal}{$controller->get_preference('DADATA_KEY','')}{literal}",
                        type: "ADDRESS",
                        onSelect: function (suggestion) {
                            console.log(suggestion);
                        }
                    });
                });
            }
        })();
    {/literal}
</script>