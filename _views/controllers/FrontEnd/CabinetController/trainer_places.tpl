<div class="{$controller->MC}_calendar_outer">
    <div class="{$controller->MC}_calendar_header">Залы в которых Вы ведете занятия</div>    
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
        function ready() {
            var E = window.Eve, EFO = E.EFO, U = EFO.U;
            var handle = jQuery('#{/literal}{$controller->MC}{literal}handle');
            var loader = jQuery('#{/literal}{$controller->MC}{literal}loader');
            var TEMPLATES = {/literal}{$controller->get_frontend_templates('places_front')|json_encode}{literal};
            var handlers = {
                selection: {},
                is_selected: function (x) {
                    x = U.IntMoreOr(x, 0, null);
                    return this.selection[["P", x].join('')] === x;
                },

                load_response: function (d) {
                    if (U.isObject(d)) {
                        if (d.status === "ok") {
                            return this.on_load_success(d.items);
                        }
                        if (d.status === 'error') {
                            return this.on_load_error(d.error_info.message);
                        }
                    }
                    return this.on_load_error("invalid server response");
                },
                on_load_error: function (x) {
                    U.TError(U.NEString(x, "network error"));
                },
                hide_loader: function () {
                    loader.hide();
                },
                on_load_success: function (items) {
                    items = U.safeArray(items);
                    this.items = items;
                    this.render();
                },
                render: function () {
                    handle.html(Mustache.render(TEMPLATES.places, this, TEMPLATES));
                },
                init: function () {
                    this.has_default_image = this._has_default_image.bindToObjectWParam(this);
                    this.ms_is_selected = this._ms_is_selected.bindToObjectWParam(this);
                    this.MC = '{/literal}{$controller->MC}{literal}';
                },
                _ms_is_selected: function (mi) {
                    return this.is_selected(mi.id);
                },
                _has_default_image: function (x) {
                    return !!(U.NEString(x.default_image, null));
                },
                on_command_add_place: function (t, e) {
                    this.selection = {};
                    for (var i = 0; i < this.items.length; i++) {
                        this.selection[["P", this.items[i].id].join("")] = U.IntMoreOr(this.items[i].id, 0, null);
                    }
                    loader.show();
                    EFO.Com().load('selectors.mapbox_on_selector')
                            .done(this, this.on_selector_ready)
                            .fail(this, this.on_component_fail)
                            .always(this, this.hide_loader);
                },
                on_command_remove_hole: function (t) {
                    var id = U.IntMoreOr(t.data('id'), 0, null);
                    if (id) {
                        loader.show();
                        jQuery.getJSON('/Cabinet/API', {action: 'remove_trainer_place', id: id})
                                .done(this.load_response.bindToObject(this))
                                .fail(this.on_load_error.bindToObject(this))
                                .always(this.hide_loader.bindToObject(this));
                    }
                },
                on_selector_ready: function (x) {
                    x.set_delegate(this).show();
                },
                on_component_fail: function () {
                    U.TError('component load error');
                },
                mbsd_load_points: function (cb) {
                    jQuery.getJSON('/Cabinet/API', {action: "trainer_api_get_point_list"})
                            .done(function (d) {
                                cb(U.safeArray(d.items));
                            })
                            .fail(function () {
                                cb([]);
                            });
                },
                mbsd_get_marker_color: function (mi) {
                    var id = U.IntMoreOr(mi.id, 0, null);
                    if (this.is_selected(id)) {
                        return '#ff0000';
                    }
                    return '#ababab';
                },

                mbsd_ok: function () {

                    var items_to_add = [];
                    for (var k in this.selection) {
                        if (this.selection.hasOwnProperty(k)) {
                            var id = U.IntMoreOr(this.selection[k]);
                            if (this.selection[["P", id].join('')] === id) {
                                items_to_add.push(id);
                            }
                        }
                    }
                    loader.show();
                    jQuery.post('/Cabinet/API', {action: "trainer_points_selected", points: items_to_add})
                            .done(this.load_response.bindToObject(this))
                            .fail(this.on_load_error.bindToObject(this))
                            .always(this.hide_loader.bindToObject(this));

                },
                mbsd_fill_popup: function (mi) {
                    this.crip = mi;
                    return Mustache.render(TEMPLATES.popup, this, TEMPLATES);
                },
                mbsd_on_command: function (cmd, src, mi) {                    
                    var cmd = "on_sel_command_" + cmd;
                    if (U.isCallable(this[cmd])) {
                        this[cmd](src, mi);
                    }
                },
                on_sel_command_add_to_selection: function (src, mi) {
                    var id = U.IntMoreOr(mi.id, 0, null);
                    if (id) {
                        this.selection[["P", id].join('')] = id;
                    }
                },
                on_sel_command_remove_from_selection: function (src, mi) {
                    var id = U.IntMoreOr(mi.id, 0, null);
                    if (id) {
                        var key = ["P", id].join('');
                        if (this.selection[key] === id) {
                            delete(this.selection[key]);
                        }
                    }
                }
            };
            handlers.init();
            handle.on('click', '[data-command]', function (e) {
                var t = jQuery(this);
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                e.stopPropagation();
                var cmd = U.NEString(t.data('command'), null);
                if (cmd) {
                    var command_fn = "on_command_" + cmd;
                    if (U.isCallable(handlers[command_fn])) {
                        handlers[command_fn](t, e);
                    }
                }
            });
            function reload() {
                loader.show();                
                jQuery.getJSON('/Cabinet/API', {action: "trainer_places"})
                        .done(handlers.load_response.bindToObject(handlers))
                        .fail(handlers.on_load_error.bindToObject(handlers))
                        .always(handlers.hide_loader.bindToObject(handlers));
            }

            reload();
        }
    })();
</script>{/literal}
{include './calendar.svg.tpl'}