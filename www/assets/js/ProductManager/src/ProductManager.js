(function () {
    window.Eve = window.Eve || [];
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice, H = null;
        function ProductManager() {
            return ProductManager.is(H) ? H : ((ProductManager.is(this) ? this.init : ProductManager.F).apply(this, APS.call(arguments)));
        }
        var PMP = U.FixCon(ProductManager).prototype;

        PMP.products = null;
        PMP.offline = null;
        PMP.remains = null;

        PMP.init = function () {
            H = this;
            this.products = {};
            this.offline = null;
            this.remains = {};
            return this;
        };

        PMP.load_product = function (id, context, callback) {
            var key = ["P", id].join('');
            if (product_info.is(this.products[key])) {
                var xco = U.coalesceObject(context, callback, this);
                var xca = U.coalesceCallable(callback, context, null);
                if (xca) {
                    try {
                        xca.apply(xco, [this.products[key]]);
                    } catch (e) {

                    }
                }
                return this;
            } else if (product_loader.is(this.products[key])) {
                this.products[key].add_callback(context, callback);
            } else {
                this.products[key] = product_loader(id, context, callback);
            }
            return this;
        };
        PMP.has_loaded_product = function (id) {
            var key = ["P", id].join('');
            return (product_info.is(this.products[key]));
        };
        PMP.load_products = function (ids, context, callback) {

        };

        PMP.get_product = function (id) {
            var key = ["P", id].join('');
            return product_info.is(this.products[key]) ? this.products[key] : null;
        };

        PMP.on_product_loaded = function (loader, pir) {
            var product = product_info(pir.product);
            this.products[product.key] = product;
            var whi = U.safeArray(pir.warehouse);
            for (var i = 0; i < whi.length; i++) {
                var whs = storage_data(whi[i]);
                this.remains[whs.key] = whs;
            }
            if (!this.offline) {
                this.offline = offline_shop_collection(pir.offline);
            }
            loader.run_callbacks(this, product);
            return this;
        };

        PMP.on_product_load_fail = function (loader, rsp) {
            debugger;
        };

        PMP.get_total_remains_of = function (id) {
            var key = ["P", id].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_total_remains();
            }
            return 0;
        };

        PMP.get_remains_by_size = function (product_id, size_id) {
            var key = ["P", product_id].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_size_remains(size_id);
            }
            return 0;
        };

        PMP.get_remains_by_size_and_color = function (product, size, color) {
            var key = ["P", product].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_size_color_remains(size, color);
            }
            return 0;
        };

        PMP.get_remains_by_color = function (product_id, color) {
            var key = ["P", product_id].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_color_remains(color);
            }
            return 0;
        };

        PMP.get_remains_by_shop_color_size = function (product_id, storage_id, color_id, size_id) {
            var key = ["P", product_id].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_shop_color_size_remains(storage_id, color_id, size_id);
            }
            return 0;
        };


        PMP.get_remains_by_storage = function (product_id, storage_id) {
            var key = ["P", product_id].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_storage_remains(storage_id);
            }
            return 0;
        };
        PMP.get_remains_by_shop_color = function (p, s, c) {
            var key = ["P", p].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_storage_remains_color(s, c);
            }
            return 0;
        };

        PMP.get_offline_shop_by_id = function (shop_id) {
            return this.offline.get_by_id(shop_id);
        };

        PMP.get_filter_qty_of = function (p, s, c, sz) {
            var key = ["P", p].join('');
            var sd = storage_data.is(this.remains[key]) ? this.remains[key] : null;
            if (sd) {
                return sd.get_filter_qty_of(s, c, sz);
            }
            return 0;
        };


        //<editor-fold defaultstate="collapsed" desc="product_info">
        function product_info() {
            return (product_info.is(this) ? this.init : product_info.F).apply(this, APS.call(arguments));
        }
        var PIP = U.FixCon(product_info).prototype;

        PIP.product_id = null;
        PIP.size_count = null;
        PIP.color_count = null;
        PIP.selected_size = null;
        PIP.selected_color = null;
        PIP.key = null;
        PIP.alias = null;
        PIP.article = null;
        PIP.consists = null;
        PIP.description = null;
        PIP.discount = null;
        PIP.image = null;
        PIP.is_dealer = null;
        PIP.name = null;
        PIP.old_price = null;
        PIP.price = null;
        PIP.version = null;
        PIP.colors = null;
        PIP.sizes = null;


        PIP.init = function (d) {
            d = U.safeObject(d);
            this.product_id = U.IntMoreOr(d.id, 0, 0);
            this.key = ["P", this.product_id].join('');
            this.alias = U.NEString(d.alias, null);
            this.article = U.NEString(d.article, null);
            this.consists = U.NEString(d.consists, null);
            this.description = U.NEString(d.description, null);
            this.discount = U.FloatMoreOr(d.discount, 0, null);
            this.image = U.NEString(d.image, null);
            this.is_dealer = U.anyBool(d.is_dealer, null);
            this.name = U.NEString(d.name, null);
            this.old_price = U.FloatMoreOr(d.old_price, null);
            this.price = U.FloatMoreOr(d.price, 0, null);
            this.version = U.NEString(d.version, null);
            this.colors = color_collection(d.colors);
            this.sizes = size_collection(d.sizes);
            this.color_count = this.colors.get_length();
            this.size_count = this.sizes.get_length();
            return this;
        };

        PIP.has_colors = function () {
            return this.color_count ? true : false;
        };

        PIP.has_sizes = function () {
            return this.size_count ? true : false;
        };

        PIP.is_valid = function () {
            return this.product_id ? true : false;
        };

        //<editor-fold defaultstate="collapsed" desc="colors">
        function color_collection() {
            return (color_collection.is(this) ? this.init : color_collection.F).apply(this, APS.call(arguments));
        }
        var CCP = U.FixCon(color_collection).prototype;


        CCP.items = null;
        CCP.index = null;
        CCP.init = function (x) {
            x = U.safeArray(x);
            this.items = [];
            this.index = {};
            for (var i = 0; i < x.length; i++) {
                var item = color(x[i]);
                if (item && item.is_valid()) {
                    this.items.push(item);
                    this.index[item.guid] = item;
                }
            }
            return this;
        };

        CCP.get_length = function () {
            return this.items.length;
        };

        CCP.is_empty = function () {
            return this.items.length ? false : true;
        };

        CCP.get_by_guid = function (x) {
            x = U.NEString(x, null);
            if (x) {
                if (color.is(this.index[x])) {
                    return this.index[x];
                }
            }
            return null;
        };


        function color() {
            return (color.is(this) ? this.init : color.F).apply(this, APS.call(arguments));
        }
        var CP = U.FixCon(color).prototype;

        CP.exchange_uid = null;
        CP.guid = null;
        CP.html_color = null;
        CP.image_exists = null;
        CP.name = null;
        CP.sort = null;

        CP.init = function (x) {
            x = U.safeObject(x);
            this.exchange_uid = U.NEString(x.exchange_uid, null);
            this.guid = U.NEString(x.guid, null);
            this.html_color = /^#[a-f0-9]{6}$/i.test(U.NEString(x.html_color, '')) ? U.NEString(x.html_color, null) : null;
            this.image_exists = U.anyBool(x.image_exists, true);
            this.name = U.NEString(x.name, null);
            this.sort = U.IntOr(x.sort, null);
            return this;
        };

        CP.is_valid = function () {
            return this.name && this.guid ? true : false;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="sizes">
        function size_collection() {
            return (size_collection.is(this) ? this.init : size_collection.F).apply(this, APS.call(arguments));
        }
        var SCP = U.FixCon(size_collection).prototype;
        SCP.items = null;
        SCP.index = null;
        SCP.defs = null;
        SCP.defs_index = null;

        SCP.init = function (d) {
            this.items = [];
            this.index = {};
            this.defs = [];
            this.defs_index = {};
            var defs = U.safeArray(d.defs);
            for (var i = 0; i < defs.length; i++) {
                var item = size_definintion(defs[i]);
                if (item && item.is_valid()) {
                    this.defs.push(item);
                    this.defs_index[item.key] = item;
                }
            }
            var items = U.safeArray(d.items);
            for (var i = 0; i < items.length; i++) {
                var item = size(items[i]);
                if (item && item.is_valid()) {
                    this.items.push(item);
                    this.index[item.key] = item;
                }
            }
            return this;
        };
        SCP.get_length = function () {
            return this.items.length;
        };
        SCP.is_empty = function () {
            return this.items.length ? false : true;
        };

        SCP.get_value_by_id = function (x) {
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].id === x) {
                    return this.items[i].value;
                }
            }

            return null;
        };


        function size_definintion() {
            return (size_definintion.is(this) ? this.init : size_definintion.F).apply(this, APS.call(arguments));
        }
        var SDP = U.FixCon(size_definintion).prototype;
        SDP.id = null;
        SDP.short_name = null;
        SDP.name = null;
        SDP.key = null;

        SDP.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.name = U.NEString(x.name, null);
            this.short_name = U.NEString(x.short_name, null);
            this.key = this.id ? ["P", this.id].join('') : null;
            return this;
        };
        SDP.is_valid = function () {
            return this.id && this.short_name && this.name ? true : false;
        };



        function size() {
            return (size.is(this) ? this.init : size.F).apply(this, APS.call(arguments));
        }
        var SP = U.FixCon(size).prototype;
        SP.id = null;
        SP.key = null;
        SP.value = null;
        SP.alters = null;

        SP.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.key = U.NEString(x.key, null);
            this.value = U.NEString(x.value, null);
            this.alters = {};
            var alters = U.safeObject(x.alters);
            for (var k in alters) {
                if (/^P\d{1,}$/i.test(k) && alters.hasOwnProperty(k) && U.isObject(alters[k])) {
                    var alter = size_alter(alters[k]);
                    if (alter && alter.is_valid()) {
                        this.alters[alter.key] = alter;
                    }
                }
            }
            return this;
        };
        SP.is_valid = function () {
            return (this.id && this.key && this.value) ? true : false;
        };

        SP.has_alter = function (key) {
            return size_alter.is(this.alters[key]);
        };
        SP.has_alter_id = function (alter_id) {
            return this.has_alter(["P", alter_id].join(''));
        };


        function size_alter() {
            return (size_alter.is(this) ? this.init : size_alter.F).apply(this, APS.call(arguments));
        }

        var SAP = U.FixCon(size_alter).prototype;
        SAP.id = null;
        SAP.sid = null;
        SAP.alter_size = null;
        SAP.value = null;
        SAP.key = null;

        SAP.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.sid = U.IntMoreOr(x.sid, 0, null);
            this.alter_size = U.NEString(x.alter_size, null);
            this.value = this.alter_size;
            this.key = U.NEString(x.key, null);
            return this;
        };

        SAP.is_valid = function () {
            return this.id && this.sid && this.key && this.value ? true : false;
        };

        //</editor-fold>
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="storage_data">
        function storage_data() {
            return (storage_data.is(this) ? this.init : storage_data.F).apply(this, APS.call(arguments));
        }
        var STP = U.FixCon(storage_data).prototype;
        STP.items = null;
        STP.id = null;
        STP.key = null;

        STP.init = function (data) {
            data = U.safeObject(data);
            this.items = [];
            this.id = U.IntMoreOr(data.id, 0, null);
            this.key = ["P", this.id ? this.id : "N"].join('');
            var ii = U.safeArray(data.items);
            for (var i = 0; i < ii.length; i++) {
                var item = storage_item(ii[i]);
                item && item.is_valid() ? this.items.push(item) : 0;
            }
            return this;
        };

        STP.get_total_remains = function () {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                q += this.items[i].qty;
            }
            return q;
        };

        STP.get_size_remains = function (size_id) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].size === size_id) {
                    q += this.items[i].qty;
                }
            }
            return q;
        };
        STP.get_size_color_remains = function (size_id, color_id) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].size === size_id && this.items[i].color === color_id) {
                    q += this.items[i].qty;
                }
            }
            return q;
        };

        STP.get_color_remains = function (color_id) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].color === color_id) {
                    q += this.items[i].qty;
                }
            }
            return q;
        };

        STP.get_storage_remains = function (storage_id) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].storage_id === storage_id) {
                    q += this.items[i].qty;
                }
            }
            return q;
        };

        STP.get_shop_color_size_remains = function (storage_id, color_id, size_id) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].storage_id === storage_id && this.items[i].color === color_id && this.items[i].size === size_id) {
                    q += this.items[i].qty;
                }
            }
            return q;
        };

        STP.get_storage_remains_color = function (s, c) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].storage_id === s && this.items[i].color === c) {
                    q += this.items[i].qty;
                }
            }
            return q;
        };

        STP.get_filter_qty_of = function (s, c, sz) {
            var q = 0;
            for (var i = 0; i < this.items.length; i++) {
                if (s === null || this.items[i].storage_id === s) {
                    if (c === null || c === this.items[i].color) {
                        if (sz === null || sz === this.items[i].size) {
                            q += this.items[i].qty;
                        }
                    }
                }
            }
            return q;
        };

        function storage_item() {
            return (storage_item.is(this) ? this.init : storage_item.F).apply(this, APS.call(arguments));
        }
        var SIP = U.FixCon(storage_item).prototype;
        SIP.color = null;//: "a847f437-a151-11e9-9352-2c56dc9ba4ec"
        SIP.hash = null;//: "P47527Ca847f437-a151-11e9-9352-2c56dc9ba4ecS11"
        SIP.product_id = null;//: 47527
        SIP.qty = null;//: 1
        SIP.size = null;//: 11
        SIP.storage_id = null;//: 2

        SIP.init = function (d) {
            d = U.safeObject(d);
            this.color = U.NEString(d.color, null);
            this.hash = U.NEString(d.hash, null);
            this.product_id = U.IntMoreOr(d.product_id, 0, null);
            this.qty = U.IntMoreOr(d.qty, 0, 0);
            this.size = U.IntMoreOr(d.size, 0, null);
            this.storage_id = U.IntMoreOr(d.storage_id, 0, null);
            return this;
        };
        SIP.is_valid = function () {
            return (this.hash && this.product_id && this.storage_id) ? true : false;
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="offline shops">
        function offline_shop_collection() {
            return (offline_shop_collection.is(this) ? this.init : offline_shop_collection.F).apply(this, APS.call(arguments));
        }
        var OSCP = U.FixCon(offline_shop_collection).prototype;
        OSCP.items = null;
        OSCP.index = null;
        OSCP.init = function (d) {
            this.items = [];
            this.index = {};
            d = U.safeArray(d);
            for (var i = 0; i < d.length; i++) {
                var item = offline_shop(d[i]);
                if (item && item.is_valid()) {
                    this.items.push(item);
                    this.index[item.key] = item;
                }
            }
            return this;
        };

        OSCP.get_by_id = function (x) {
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].id === x) {
                    return this.items[i];
                }
            }
            return null;
        };

        function offline_shop() {
            return (offline_shop.is(this) ? this.init : offline_shop.F).apply(this, APS.call(arguments));
        }
        var OSP = U.FixCon(offline_shop).prototype;
        OSP.address = null;//: "cscs"
        OSP.id = null;//: 1
        OSP.lat = null;//: 33.75
        OSP.lon = null;//: 57.29
        OSP.name = null;//: "scxss"
        OSP.storage_id = null;//: 5
        OSP.visible = null;//: true
        OSP.key = null;
        OSP.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.address = U.NEString(x.address, null);
            this.lat = U.FloatOr(x.lat, null);
            this.lon = U.FloatOr(x.lon, null);
            this.name = U.NEString(x.name, null);
            this.storage_id = U.IntMoreOr(x.storage_id, 0, null);
            this.visible = U.anyBool(x.visible, true);
            this.key = this.id ? ["P", this.id].join('') : null;
            return this;
        };

        OSP.is_valid = function () {
            return (this.id && this.storage_id && this.name) ? true : false;
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="product_loader">
        function product_loader() {
            return (product_loader.is(this) ? this.init : product_loader.F).apply(this, APS.call(arguments));
        }
        var PLP = U.FixCon(product_loader).prototype;
        PLP.product_id = null;
        PLP.callbacks = null;
        PLP.init = function (product_id, context, callback) {
            this.product_id = U.IntMoreOr(product_id, 0, null);
            this.callbacks = [product_loader_callback(context, callback)];
            jQuery.getJSON('/Basket/API', {action: "pm_product_info", product_id: this.product_id})
                    .done(this.on_request_done.bindToObject(this))
                    .fail(this.on_request_fail.bindToObject(this));
            return this;
        };

        PLP.on_request_done = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    ProductManager.F().on_product_loaded(this, d);
                    return this;
                }
            }
            ProductManager().on_product_load_fail(this, d);
            return this;
        };
        PLP.on_request_fail = function (d) {
            //при файле- освобождать слот ля последующей загрузки и выдавать ошибку?
            //при множественной загрузке?
            ProductManager().on_product_load_fail(this, d);
            return this;
        };

        PLP.add_callback = function (context, callable) {
            this.callbacks.push(product_loader_callback(context, callable));
            return this;
        };


        PLP.run_callbacks = function (default_context, product_info) {
            for (var i = 0; i < this.callbacks.length; i++) {
                this.callbacks[i].run(default_context, product_info);
            }
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="callback">
        function product_loader_callback() {
            return (product_loader_callback.is(this) ? this.init : product_loader_callback.F).apply(this, APS.call(arguments));
        }
        var PLC = U.FixCon(product_loader_callback).prototype;
        PLC.context = null;
        PLC.callable = null;
        PLC.init = function (xco, xca) {
            this.context = U.coalesceObject(xco, xca, null);
            this.callable = U.coalesceCallable(xca, xco, null);
            return this;
        };

        PLC.is_valid = function () {
            return U.isCallable(this.callable);
        };


        PLC.run = function (default_context) {
            if (this.is_valid()) {
                var params = APS.call(arguments);
                params = params.slice(1);
                var xc = U.coalesceObject(this.context, default_context, this);
                try {
                    this.callable.apply(xc, params);
                } catch (e) {

                }
            }
        };

        //</editor-fold>
        //</editor-fold>




        //<editor-fold defaultstate="collapsed" desc="export">
        ProductManager.size_collection = size_collection;
        ProductManager.size_definintion = size_definintion;
        ProductManager.size = size;
        ProductManager.size_alter = size_alter;
        ProductManager.color_collection = color_collection;
        ProductManager.color = color;
        ProductManager.product_info = product_info;
        E.ProductManager = ProductManager;
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="readyblock">
        E.product_manager_ready = E.product_manager_ready || [];
        var b = E.product_manager_ready;
        E.product_manager_ready = {
            push: function () {
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
            }
        };
        for (var i = 0; i < b.length; i++) {
            E.product_manager_ready.push(b[i]);
        }
        //</editor-fold>
    }
})();