(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, H = null, APS = Array.prototype.slice;

        function basket() {
            return basket.is(H) ? H : ((basket.is(this) ? this.init : basket.F).apply(this, APS.call(arguments)));
        }
        var P = U.FixCon(basket).prototype;

        P.items = null;
        P.index = null;


        P.init = function (x) {
            H = this;
            this.items = [];
            this.index = {};
            if (U.isArray(x)) {
                this.import(x);
            }
            return this;
        };


        P.import = function (x) {
            this.items = [];
            this.index = {};
            for (var i = 0; i < x.length; i++) {
                var item = basket_item().import(x);// а зачем вообще hwrap корзины? пустая/полная/положить убрать и хватит?
                if (item && item.is_valid() && item.qty) {
                    var joined_item = this.get_by_hash(item.hash);
                    if (joined_item) {
                        joined_item.join(item);
                    } else {
                        this.index[item.hash] = item;
                        this.items.push(item);
                    }
                }
            }
            return this;
        };

        P.get_item_by_hash = function (x, def) {
            x = U.NEString(x, null);
            if (x) {
                if (basket_item.is(this.index[x])) {
                    return this.index[x];
                }
            }
            return def;
        };

        P.export = function () {
            var r = [];
            for (var i = 0; i < this.items.length; i++) {
                r.push(this.items[i].export());
            }
            return r;
        };





        function basket_item() {
            return (basket_item.is(this) ? this.init : basket_item.F).apply(this, APS.call(arguments));
        }
        var I = U.FixCon(basket_item).prototype;

        I.product_id = null;
        I.color_id = null;
        I.size_id = null;
        I.hash = null;
        I.qty = null;


        I.init = function (product, color, size, qty) {
            this.product_id = U.IntMoreOr(product, 0, 0);
            this.color_id = U.NEString(color, null);
            this.size_id = U.IntMoreOr(size, 0, 0);
            this.hash = ["P", this.product_id, "S", this.size_id ? this.size_id : 'N', "C", this.color_id ? this.color_id : "N"].join('');
            this.qty = U.IntMoreOr(qty, 0, 1);
            return this;
        };

        I.is_valid = function () {
            return this.product_id ? true : false;
        };

        I.export = function () {
            return {h: this.hash, q: this.qty};
        };

        I.join = function (x) {
            if (basket_item.is(x)) {
                if (x.hash === this.hash) {
                    this.qty += x.qty;
                }
            }
            return this;
        };

        I.import = function (x) {
            x = U.safeObject(x);
            var hash = U.NEString(x.h, null);
            var qty = U.IntMoreOr(x.q, 0, 0);
            if (hash) {
                var m = /^P(\d{1,})S(\d{1,}|N)C(.{1,})$/i.exec(hash);
                if (m) {
                    this.product_id = U.IntMoreOr(m[1], 0, 0);
                    this.size_id = U.IntMoreOr(m[2], 0, 0);
                    this.color_id = m[3] === 'N' ? null : U.NEString(m[3], null);
                    this.qty = qty;
                }
            }
            return this;
        };



    }
})();