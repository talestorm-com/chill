(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(function () {
        return;
        window.Eve.product_manager_ready = window.Eve.product_manager_ready || [];
        window.Eve.product_manager_ready.push(ready);
    });
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PPM = window.Eve.product_manager(), H = null, APS = Array.prototype.slice;
        function basket_request() {
            return   basket_request.is(H) ? H : ((basket_request.is(this) ? this.init : basket_request.F).apply(this, APS.call(arguments)));
        }
        var T = null;
        /*<?=$this->templates()?>*/
        var P = U.FixCon(basket_request).prototype;
        P.LEM = null;


        P.init = function () {
            H = this;
            this.LEM = EFO.Events.LEM();
            this.has_colors = this._has_colors.bindToObject(this);
            this.has_sizes = this._has_sizes.bindToObject(this);
            this.has_alter_sizes = this._has_alter_sizes.bindToObject(this);
            this.set_current_size_id = this._set_current_size_id.bindToObjectWParam(this);

            this.get_item_sizes_defs = this._get_item_sizes_defs.bindToObject(this);
            this.set_current_size_def_id = this._set_current_size_def_id.bindToObjectWParam(this);
            this.get_current_size_def_value = this._get_current_size_def_value.bindToObject(this);
            this.simple_button_block = this._simple_button_block.bindToObject(this);
            return this;
        };

        P._has_colors = function () {
            return (this.item && this.item.colors && !this.item.colors.is_empty()) ? true : false;
        };
        P._has_sizes = function () {
            return (this.item && this.item.sizes && !this.item.sizes.is_empty()) ? true : false;
        };

        P._has_alter_sizes = function () {
            return (this.item && this.item.sizes && this.item.sizes.defs.length) ? true : false;
        };

        P._set_current_size_id = function (x) {
            this._current_size = x.key;
            return '';
        };
        P._get_item_sizes_defs = function () {
            return this.item.sizes.defs;
        };

        P._set_current_size_def_id = function (x) {
            this._current_def = x.key;
            return '';
        };

        P._get_current_size_def_value = function () {
            try {
                return U.NEString(this.item.sizes.index[this._current_size].alters[this._current_def].value, '--');
            } catch (ee) {

            }
            return '--';
        };

        P._simple_button_block = function () {
            return true;
        };




        P.mk_request = function (p, shop_id, preorder) {///
            var item = PPM.get(p);
            if (item) {
                this.selected_shop_id = U.IntMoreOr(shop_id, 0, null);
                this.preorder = U.anyBool(preorder, false); //дозагрузка магаза?
                if (!item.loaded) {
                    this.run_query_request(item);
                } else {
                    this.on_item_loaded(item);
                }
            }
            return this;
        };


        P.on_network_starts = function () {
            this.LEM.Run("REQUEST_BEGIN");
            return this;
        };
        P.run_query_request = function (pitem) {
            this.init_handle();
            this.on_network_starts();
            this.show().show_loader();
            jQuery.getJSON("/Basket/API", {action: "get_product_info", product_id: pitem.product_id})
                    .done(this.product_info_done.bindToObject(this))
                    .fail(this.product_info_fail.bindToObject(this))
                    .always(this.on_network_ends.bindToObject(this));
            return this;
        };

        P.product_info_done = function (x) {
            if (U.isObject(x)) {
                if (x.status === "ok") {
                    return this.product_info_success(x);
                }
                if (x.status === "error") {
                    return this.product_info_fail("Ошибка при загрузке данных товара");
                }
            }
            return this.product_info_fail("Некорректный ответ сервера");
        };

        P.product_info_success = function (x) {
            var item = PPM.get(x.product.id);
            item.on_load(x);
            this.on_item_loaded(item);
            return this;
        };

        P.on_item_loaded = function (item) {
            this.render_value(item);
            return this;
        };

        P.render_value = function (i) {
            this.item = i;
            this.set_content(Mustache.render(T.item, this, T));            
            this.item = null;
            return this;
        };

        P.product_info_fail = function (x) {
            x = U.NEString(x, "Ошибка при загрузке данных товара");
            U.TError(x);
            return this.hide();
        };

        P.on_network_ends = function () {
            this.LEM.Run("REQUEST_END");
            return this;
        };

        P.init_handle = function () {
            if (!this.handle) {
                this.handle = jQuery(Mustache.render(T.frame, this));
            }
            return this;
        };

        P.show = function () {
            this.handle.appendTo('body');
            this.handle.show();
            jQuery('body').addClass("BasketRequestFrameIsVisible");
            return this;
        };

        P.hide = function () {
            this.handle.hide();
            jQuery('body').removeClass("BasketRequestFrameIsVisible");
            return this;
        };

        P.showLoader = function () {
            this.set_content(Mustache.render(T.loader, this));            
            return this;
        };
        P.show_loader = P.showLoader;

        P.set_content = function (x) {
            if (!this._content) {
                this._content = this.handle.find("[data-role='content']");
            }
            if (!this._size_content) {
                this._size_content = this.handle.find("[data-role='content-size']");
            }
            this._content.html(x);
            this._size_content.html(x);
            return this;
        };



        window.Eve.basket_request = basket_request;
        window.Eve.basket_request_ready = window.Eve.basket_request_ready || [];
        var k = [].concat(window.Eve.basket_request_ready);
        window.Eve.basket_request_ready = {
            push: function () {
                var arg = APS.call(arguments);
                for (var i = 0; i < arg.length; i++) {
                    if (U.isCallable(arg[i])) {
                        try {
                            arg[i]();
                        } catch (e) {
                            U.TError(e);
                        }
                    }
                }
            }
        };
        for (var i = 0; i < k.length; i++) {
            window.Eve.basket_request_ready.push(k[i]);
        }
    }
})();