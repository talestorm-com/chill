(function () {
    jQuery(function () {
        window.Eve = window.Eve || {};
        window.Eve.basket = window.Eve.basket || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(ready);
        function ready() {
            var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice, H = null;
            var T = null;
            /* <?=get_templates()?> */
            if (!U.isCallable(E.basket)) {
                function basket() {
                    return basket.is(H) ? H : ((basket.is(this) ? this.init : basket.F).apply(this, APS.call(arguments)));
                }
                var P = U.FixCon(basket).prototype;

                P.items = null;
                P.index = null;
                P.selected_items = null;
                P.selected_index = null;
                P.list_node = null;
                P.selected_node = null;
                P.selected_root_node = null;
                P.filter_field = null;
                P.total_node = null;
                P.comparator = null;
                P.filter = null;
                P.LEM = null;
                P.image_cache = null;
                P.init = function (available_items_pojo, list_node, basket_node, basket_root_node, filter_field) {
                    H = this;
                    this.LEM = EFO.Events.LEM();
                    this.init_items(available_items_pojo);
                    this.list_node = list_node;
                    this.sort_items();
                    this.can_display_item = this._can_display_item.bindToObjectWParam(this);
                    this.render_retail_price = this._render_retail_price.bindToObjectWParam(this);
                    this.render_gross_price = this._render_gross_price.bindToObjectWParam(this);
                    this.render_big_gross_price = this._render_big_gross_price.bindToObjectWParam(this);
                    this.is_item_in_selected = this._is_item_in_selected.bindToObjectWParam(this);
                    this.get_name = this._get_name.bindToObjectWParam(this);
                    this.get_image = this._get_image.bindToObjectWParam(this);
                    this.get_stock = this._get_stock.bindToObjectWParam(this);
                    this.get_qty_of = this._get_qty_of.bindToObjectWParam(this);
                    this.get_nl2br_info = this._get_nl2br_info.bindToObjectWParam(this);
                    this.strip_id = this._strip_id.bindToObjectWParam(this);
                    this.selected_node = basket_node;
                    this.selected_root_node = basket_root_node;
                    this.init_selected_items();
                    this.sort_selected_items();
                    this.filter_field = filter_field;
                    jQuery(this.filter_field).on('change keyup', this.on_filter.bindToObjectWParam(this));
                    jQuery(this.list_node).on('change', 'input[type=text]', this.on_qty_monitor.bindToObjectWParam(this))
                            .on('click', ".one_item_add_to", this.on_command_add_to_cart.bindToObjectWParam(this));
                    jQuery(this.selected_node).on('click', '.one_item_add_to.cart.remove_from_cart', this.remove_item.bindToObjectWParam(this))
                            .on('change', 'input[type=text]', this.on_qty_monitor.bindToObjectWParam(this));
                    this.image_cache = {};
                    this.render();
                    H = this;
                    return this;
                };

                P._strip_id = function (x) {
                    if (!x._strip_id) {
                        x._strip_id = x.id.replace(/-/g, '');
                    }
                    return x._strip_id;
                };

                P._render_retail_price = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    return i ? i.get_price_with_discount(0, true, true, 2) : '';
                };
                P._get_name = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    return i ? i.name : '';
                };
                P._get_stock = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    return i ? i.stock : 0;
                };
                P._get_image = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    if (i) {
                        var r = U.NEString(i.image, null);
                        if(r){
                            if(!this.image_cache[r]){
                                this.image_cache[r]=new Image();
                                this.image_cache[r].src="/mc_images/"+r+".jpg";
                            }
                        }
                    }
                    return i ? i.image : '';
                };
                P._render_gross_price = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    return i ? i.get_price_with_discount(40.0, true, true, 2) : '';
                };
                P._render_big_gross_price = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    return i ? i.get_price_with_discount(50.0, true, true, 2) : '';
                };

                P._can_display_item = function (x) {
                    if (SEItem.is(this)) {
                        return true;
                    }
                    return this.filter ? this.filter.filter(this, x) : true;
                };
                P._is_item_in_selected = function (x) {
                    return this.get_selected_item(x.id) ? true : false;
                };

                P._get_qty_of = function (x) {
                    return this.get_selected_qty(x.id);
                };

                P._get_nl2br_info = function (x) {
                    var i = AVItem.is(this.index[x.id]) ? this.index[x.id] : null;
                    return i ? U.NEString(i.info, '').replace(/\r/g, '').replace(/\n/g, "<br/>") : '';
                };

                P.on_qty_monitor = function (n, e) {
                    var jn = jQuery(n);
                    var row = jn.closest('.one_item');
                    var id = U.NEString(row.data('mcId'), null);
                    if (id) {
                        var selected_item = this.get_selected_item(id);
                        if (!selected_item) {
                            selected_item = SEItem({id: id, qty: 1});
                            this.selected_index[selected_item.id] = selected_item;
                            this.selected_items.push(selected_item);
                            this.render_selected();
                        }
                        selected_item.qty = U.IntMoreOr(jn.val(), 0, selected_item.qty);
                        this.save_selection();
                        this.update_item_state(id);
                    }
                    return this;
                };

                P.on_command_add_to_cart = function (n, e) {
                    var jn = jQuery(n);
                    var row = jn.closest('.one_item');
                    var id = U.NEString(row.data('mcId'), null);
                    if (id) {
                        var selected_item = this.get_selected_item(id);
                        if (!selected_item) {
                            selected_item = SEItem({id: id, qty: 1});
                            this.selected_index[selected_item.id] = selected_item;
                            this.selected_items.push(selected_item);
                            this.render_selected();
                        } else {
                            selected_item.qty++;
                        }
                        this.save_selection();
                        this.update_item_state(id);
                    }
                };

                P.remove_item = function (n, e) {
                    var jn = jQuery(n);
                    var row = jn.closest('.one_item');
                    var id = U.NEString(row.data('mcId'), null);
                    if (id) {
                        var selected_item = this.get_selected_item(id);
                        if (selected_item) {
                            delete(this.selected_index[selected_item.id]);
                            var ii = this.selected_items.indexOf(selected_item);
                            this.selected_items = this.selected_items.slice(0, ii).concat(this.selected_items.slice(ii + 1));
                            this.render_selected();
                        }
                        this.save_selection();
                        this.update_item_state(id);
                    }
                    return this;
                };

                P.get_display_count = function () {
                    return jQuery(this.list_node).find('.one_item').length;
                };

                P.on_filter = function (n, e) {
                    var jn = jQuery(n);
                    var vv = U.NEString(jn.val(), null);
                    if (vv) {
                        var rx = new RegExp(vv.replace(/\s/g,'.*'), 'i');
                        this.filter = {
                            filter: function (x, n) {
                                return rx.test(n.name);
                            }
                        };
                    } else {
                        this.filter = null;
                    }
                    this.render_available();
                    jQuery("#count").html(this.get_display_count());
                    if (vv) {
                        jQuery('#search_text').text('Товары содержащие "' + vv + '"');
                    } else {
                        jQuery('#search_text').text('Все товары');
                    }
                    return this;
                };

                P.update_item_state = function (id) {
                    var qty = this.get_selected_qty(id);
                    var sid = id.replace(/-/g, '');
                    var row = jQuery(["#basket_item_avl_", sid].join(''));
                    var row2 = jQuery(["#basket_item_sel_", sid].join(''));
                    if (qty) {
                        row.find('input[type=text]').val(qty);
                        row.addClass('item_in_basket_hilight');
                        row2.find('input[type=text]').val(qty);
                    } else {
                        row.find('input[type=text]').val('');
                        row.removeClass('item_in_basket_hilight');
                        row2.remove();
                    }
                    this.calculate();
                    return this;
                };



                P.sort_items = function () {
                    if (this.comparator) {
                        this.items.sort(this.comparator.compare_fn);
                    }
                    return this;
                };
                P.sort_selected_items = function () {
                    if (this.comparator) {
                        this.selected_items.sort(this.comparator.compare_fn);
                    }
                    return this;
                };

                P.set_comparator = function (x) {
                    if (U.isObject(x) && U.isCallable(x.compare_fn)) {
                        this.comparator = x;
                    } else {
                        this.comparator = null;
                    }
                    this.sort_items();
                    this.sort_selected_items();
                    this.render();
                    return this;
                };

                P.set_filter = function (x) {
                    if (U.isObject(x) && U.isCallable(x.filter)) {
                        this.filter = x;
                    } else {
                        this.filter = null;
                    }
                    return this;
                };

                P.init_items = function (l) {
                    l = U.safeArray(l);
                    this.items = [];
                    this.index = {};
                    for (var i = 0; i < l.length; i++) {
                        var item = AVItem(l[i]);
                        if (item && item.is_valid()) {
                            this.items.push(item);
                            this.index[item.id] = item;
                        }
                    }
                    return this;
                };


                P.has_item = function (key) {
                    key = U.NEString(key, null);
                    if (key) {
                        return AVItem.is(this.index[key]);
                    }
                    return false;
                };

                P.get_selected_qty = function (id) {
                    if (this.has_item(id)) {
                        var t = this.get_selected_item(id);
                        return t ? t.qty : 0;
                    }
                    return 0;
                };

                P.get_selected_item = function (id) {
                    id = U.NEString(id, null);
                    if (id) {
                        return SEItem.is(this.selected_index[id]) ? this.selected_index[id] : null;
                    }
                    return null;
                };

                P.render = function () {

                    this.render_selected();
                    this.render_available();
                    this.calculate();
                    return this;
                };

                P.render_selected = function () {
                    this.selected_node.innerHTML = Mustache.render(T.selected_item, this);
                    jQuery(this.selected_root_node).find('[data-role="basket_length"]').html(this.selected_items.length);
                    if (this.selected_items.length) {
                        jQuery(this.selected_root_node).show();
                    } else {
                        jQuery(this.selected_root_node).hide();
                    }
                    return this;
                };


                P.render_available = function () {
                    this.list_node.innerHTML = Mustache.render(T.item, this);
                    return this;
                };

                P.calculate = function () {
                    var raw = 0;
                    var cost = 0;
                    for (var i = 0; i < this.selected_items.length; i++) {
                        var ri = AVItem.is(this.index[this.selected_items[i].id]) ? this.index[this.selected_items[i].id] : null;
                        if (ri) {
                            var q = this.selected_items[i].qty;
                            raw += U.FloatMoreOr(this._render_retail_price(ri), 0, 0) * q;
                            cost += U.FloatMoreOr(q > 9 ? this._render_big_gross_price(ri) : this._render_gross_price(ri), 0, 0) * q;
                        }
                    }
                    var delta = raw - cost;
                    jQuery('.total_price').html(EFO.Checks.formatPriceNSD(raw, 2));
                    jQuery('.total_discount_rur').html(EFO.Checks.formatPriceNSD(delta, 2));
                    jQuery('.total_cost_rur').html(EFO.Checks.formatPriceNSD(cost, 2));
                    return this;
                };


                P.init_selected_items = function () {
                    this.selected_items = [];
                    this.selected_index = {};
                    try {
                        var se = U.safeArray(JSON.parse(U.NEString(localStorage.getItem("basket"), null)));
                        for (var i = 0; i < se.length; i++) {
                            var item = SEItem(se[i]);
                            if (item && item.is_valid() && this.has_item(item.id)) {
                                this.selected_index[item.id] = item;
                                this.selected_items.push(item);
                            }
                        }
                    } catch (e) {

                    }
                    return this;
                };


                P.reset = function () { 
                    localStorage.setItem("basket","[]");
                    this.init_selected_items();                    
                    this.render();
                    return this;
                };

                P.save_selection = function () {
                    var data = JSON.stringify(this.selected_items);
                    localStorage.setItem("basket", data);
                    return this;
                };

                //<editor-fold defaultstate="collapsed" desc="available_item">  
                function AVItem() {
                    return (AVItem.is(this) ? this.init : AVItem.F).apply(this, APS.call(arguments));
                }
                var IP = U.FixCon(AVItem).prototype;

                IP.id = null;
                IP.name = null;
                IP.base_price = null;
                IP.info = null;
                IP.stock = null;
                IP.image = null;

                IP.init = function (d) {
                    d = U.safeObject(d);
                    this.id = U.NEString(d.mc_uid, null);
                    this.name = U.NEString(d.name, null);
                    this.base_price = U.FloatMoreOr(d.price, null);
                    this.info = U.NEString(d.info, '');
                    this.image = U.NEString(d.image, null);
                    this.stock = U.IntMoreOr(d.stock, 0, 0);
                    return this;
                };

                IP.is_valid = function () {
                    return !!(this.id && this.name && this.base_price > 0);
                };

                IP.get_price_with_discount = function (discount, round, format, dec) {
                    discount = U.FloatMoreOr(discount, 0, 0);
                    round = U.anyBool(round, true);
                    format = U.anyBool(format, true);
                    dec = U.IntMoreOr(dec, -1, 0);
                    var v = this.base_price - ((this.base_price / 100) * discount);
                    if (round) {
                        v = Math.round(v);
                    }
                    return format ? EFO.Checks.formatPriceNSD(v, dec) : v.toFixed(dec);
                };
                //</editor-fold>
                //<editor-fold defaultstate="collapsed" desc="selected item">

                function SEItem() {
                    return (SEItem.is(this) ? this.init : SEItem.F).apply(this, APS.call(arguments));
                }
                var SP = U.FixCon(SEItem).prototype;

                SP.id = null;
                SP.qty = null;

                SP.init = function (d) {
                    d = U.safeObject(d);
                    this.id = U.NEString(d.id, null);
                    this.qty = U.IntMoreOr(d.qty, 0, 0);
                    return this;
                };
                SP.is_valid = function () {
                    return !!(this.id && this.qty);
                };

                SP.set_qty = function (x) {
                    this.qty = U.IntMoreOr(x, 0, this.qty);
                    return this;
                };
                //</editor-fold>        



                window.Eve.basket.basket = basket;
                window.Eve.basket.Ready = window.Eve.basket.Ready || [];
                var d = [].concat(window.Eve.basket.Ready);
                window.Eve.basket.Ready = {push: function () {
                        var args = APS.call(arguments);
                        for (var i = 0; i < args.length; i++) {
                            if (U.isCallable(args[i])) {
                                try {
                                    args[i]();
                                } catch (e) {
                                    U.TError(e);
                                }
                            }
                        }
                    }};
                for (var i = 0; i < d.length; i++) {
                    window.Eve.basket.Ready.push(d[i]);
                }

            }
        }

    });
})();