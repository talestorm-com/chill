(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        /**
         * менеджер состояния для хранения текущих параметров корзины - есть ли цвета/размеры, id товара
         * выбранный цвет/размер
         * режим наличия
         * 
         */
        var E = window.Eve, EFO = E.EFO, U = EFO.U, H = null, APS = Array.prototype.slice;

        function product_manager() {
            return (product_manager.is(H) ? H : ((product_manager.is(this) ? this.init : product_manager.F).apply(this, APS.call(arguments))));
        }

        var P = U.FixCon(product_manager).prototype;
        P.index = null;

        P.init = function () {
            H = this;
            this.index = {};
            return this;
        };

        /**
         * хранить не нужно, эту инфу можно получать рантайм
         */
        P.get = function (product_id, color_count, size_count) {
            product_id = U.IntMoreOr(product_id, 0, null);
            color_count = U.IntMoreOr(color_count, 0, 0);
            size_count = U.IntMoreOr(size_count, 0, 0);
            if (product_id) {
                var key = ["P", product_id].join('');
                if (!product_manager_item.is(this.index[key])) {
                    var item = product_manager_item(product_id, color_count, size_count);
                    if (item && item.is_valid()) {
                        this.index[item.key] = item;
                    }
                }
                if (product_manager_item.is(this.index[key])) {
                    return this.index[key];
                }
            }
            return null;
        };

        P.clear = function () {
            this.index = {};
            return this;
        };



        function product_manager_item() {
            return (product_manager_item.is(this) ? this.init : product_manager_item.F).apply(this, APS.call(arguments));
        }

        var I = U.FixCon(product_manager_item).prototype;

        I.product_id = null;
        I.size_count = null;
        I.color_count = null;
        I.selected_size = null;
        I.selected_color = null;
        I.key = null;
        I.loaded = false;

        I.init = function (p, c, s) {
            this.product_id = U.IntMoreOr(p, 0, 0);
            this.color_count = U.IntMoreOr(c, 0, 0);
            this.size_count = U.IntMoreOr(s, 0, 0);
            this.key = ["P", this.product_id].join('');
            return this;
        };

        I.has_colors = function () {
            return this.color_count ? true : false;
        };

        I.has_sizes = function () {
            return this.size_count ? true : false;
        };

        I.is_valid = function () {
            return this.product_id ? true : false;
        };


        I.on_load = function (x) {
            var xp = U.safeObject(U.safeObject(x).product);
            this.alias = U.NEString(xp.alias, null);
            this.article = U.NEString(xp.article, null);
            this.consists = U.NEString(xp.consists, null);
            this.description = U.NEString(xp.description, null);
            this.discount = U.FloatMoreOr(xp.discount, 0, null);
            this.image = U.NEString(xp.image, null);
            this.is_dealer = U.anyBool(xp.is_dealer, null);
            this.name = U.NEString(xp.name, null);
            this.old_price = U.FloatMoreOr(xp.old_price, null);
            this.price = U.FloatMoreOr(xp.price, 0, null);
            this.version = U.NEString(xp.version, null);
            this.colors = product_manager_color_collection(xp.colors);
            this.sizes = product_manager_size_collection(xp.sizes);
            this.color_count = this.colors.get_length();
            this.size_count = this.sizes.get_length();
            this.loaded = true;
            console.log(this);
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="color collection">
        function product_manager_color_collection() {
            return (product_manager_color_collection.is(this) ? this.init : product_manager_color_collection.F).apply(this,APS.call(arguments));
        }
        var PMCC = U.FixCon(product_manager_color_collection).prototype;


        PMCC.items = null;
        PMCC.index = null;
        PMCC.init = function (x) {
            x = U.safeArray(x);
            this.items = [];
            this.index = {};
            for (var i = 0; i < x.length; i++) {
                var item = product_manager_color(x[i]);
                if (item && item.is_valid()) {
                    this.items.push(item);
                    this.index[item.guid] = item;
                }
            }
            return this;
        };

        PMCC.get_length = function () {
            return this.items.length;
        };

        PMCC.is_empty = function () {
            return this.items.length ? false : true;
        };

        PMCC.get_by_guid = function (x) {
            x = U.NEString(x, null);
            if (x) {
                if (product_manager_color.is(this.index[x])) {
                    return this.index[x];
                }
            }
            return null;
        };


        function product_manager_color() {
            return (product_manager_color.is(this) ? this.init : product_manager_color.F).apply(this, APS.call(arguments));
        }
        var PMC = U.FixCon(product_manager_color).prototype;

        PMC.exchange_uid = null;
        PMC.guid = null;
        PMC.html_color = null;
        PMC.image_exists = null;
        PMC.name = null;
        PMC.sort = null;

        PMC.init = function (x) {
            x = U.safeObject(x);
            this.exchange_uid = U.NEString(x.exchange_uid, null);
            this.guid = U.NEString(x.guid, null);
            this.html_color = /^#[a-f0-9]{6}$/i.test(U.NEString(x.html_color, '')) ? U.NEString(x.html_color, null) : null;
            this.image_exists = U.anyBool(x.image_exists, true);
            this.name = U.NEString(x.name, null);
            this.sort = U.IntOr(x.sort, null);
            return this;
        };

        PMC.is_valid = function () {
            return this.name && this.guid ? true : false;
        };

        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="size collection">
        function product_manager_size_collection() {
            return (product_manager_size_collection.is(this) ? this.init : product_manager_size_collection.F).apply(this, APS.call(arguments));
        }
        var PMSC = U.FixCon(product_manager_size_collection).prototype;
        PMSC.items = null;
        PMSC.index = null;
        PMSC.defs = null;
        PMSC.defs_index = null;

        PMSC.init = function (d) {
            this.items = [];
            this.index = {};
            this.defs=[];
            this.defs_index={};
            var defs = U.safeArray(d.defs);
            for (var i = 0; i < defs.length; i++) {
                var item = product_manager_size_def(defs[i]);
                if (item && item.is_valid()) {
                    this.defs.push(item);
                    this.defs_index[item.key] = item;
                }
            }
            var items = U.safeArray(d.items);
            for (var i = 0; i < items.length; i++) {
                var item = product_manager_size(items[i]);
                if (item && item.is_valid()) {
                    this.items.push(item);
                    this.index[item.key] = item;
                }
            }
            return this;
        };
        PMSC.get_length = function () {
            return this.items.length;
        };
        PMSC.is_empty = function () {
            return this.items.length ? false : true;
        };


        function product_manager_size_def() {
            return (product_manager_size_def.is(this) ? this.init : product_manager_size_def.F).apply(this, APS.call(arguments));
        }
        var PMSD = U.FixCon(product_manager_size_def).prototype;
        PMSD.id = null;
        PMSD.short_name = null;
        PMSD.name = null;
        PMSD.key = null;

        PMSD.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.name = U.NEString(x.name, null);
            this.short_name = U.NEString(x.short_name, null);
            this.key = this.id ? ["P", this.id].join('') : null;
            return this;
        };
        PMSD.is_valid = function () {
            return this.id && this.short_name && this.name ? true : false;
        };



        function product_manager_size() {
            return (product_manager_size.is(this) ? this.init : product_manager_size.F).apply(this, APS.call(arguments));
        }
        var PMS = U.FixCon(product_manager_size).prototype;
        PMS.id = null;
        PMS.key = null;
        PMS.value = null;
        PMS.alters = null;

        PMS.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.key = U.NEString(x.key, null);
            this.value = U.NEString(x.value, null);
            this.alters = {};
            var alters = U.safeObject(x.alters);
            for (var k in alters) {
                if (/^P\d{1,}$/i.test(k) && alters.hasOwnProperty(k) && U.isObject(alters[k])) {
                    var alter = product_manager_size_alter(alters[k]);
                    if (alter && alter.is_valid()) {
                        this.alters[alter.key] = alter;
                    }
                }
            }
            return this;
        };
        PMS.is_valid = function () {
            return (this.id && this.key && this.value) ? true : false;
        };

        PMS.has_alter = function (key) {
            return product_manager_size_alter.is(this.alters[key]);
        };
        PMS.has_alter_id = function (alter_id) {
            return this.has_alter(["P", alter_id].join(''));
        };


        function product_manager_size_alter() {
            return (product_manager_size_alter.is(this) ? this.init : product_manager_size_alter.F).apply(this, APS.call(arguments));
        }

        var PMSA = U.FixCon(product_manager_size_alter).prototype;
        PMSA.id = null;
        PMSA.sid = null;
        PMSA.alter_size = null;
        PMSA.value = null;
        PMSA.key = null;

        PMSA.init = function (x) {
            x = U.safeObject(x);
            this.id = U.IntMoreOr(x.id, 0, null);
            this.sid = U.IntMoreOr(x.sid, 0, null);
            this.alter_size = U.NEString(x.alter_size, null);
            this.value = this.alter_size;
            this.key = U.NEString(x.key, null);
            return this;
        };

        PMSA.is_valid = function () {
            return this.id && this.sid && this.key && this.value ? true : false;
        };




        //</editor-fold>




return;
        window.Eve.product_manager = product_manager;
        window.Eve.product_manager_ready = window.Eve.product_manager_ready || [];
        var k = [].concat(window.Eve.product_manager_ready);
        window.Eve.product_manager_ready = {
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
            window.Eve.product_manager_ready.push(k[i]);
        }
    }
})();