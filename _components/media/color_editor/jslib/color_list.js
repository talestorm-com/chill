(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);

    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        function color_editor_color_list() {
            return (color_editor_color_list.is(this) ? this.init : color_editor_color_list.F).apply(this, APS.call(arguments));
        }
        var F = U.FixCon(color_editor_color_list), FP = F.prototype;

        FP.items = null;
        FP.index = null;

        FP.init = function () {
            this.reset();
            return this;
        };

        FP.reset = function () {
            this.items = [];
            this.index = {};
            return this;
        };

        FP.import = function (x) {
            this.reset();
            x = U.safeArray(x);
            for (var i = 0; i < x.length; i++) {
                var ci = C(x[i]);
                if (ci.guid) {
                    this.items.push(ci);
                    this.index[ci.guid] = ci;
                }
            }
            return this;
        };

        FP.set_sorting = function (sorts) {
            sorts = U.safeArray(sorts);
            for (var i = 0; i < sorts.length; i++) {
                var uid = U.NEString(sorts[i], null);
                if (uid) {
                    var color = this.get_by_uid(uid);
                    color ? color.sort = i : 0;
                }
            }
            this.resort();
        };

        FP.is_empty = function () {
            return !(this.items.length ? true : false);
        };

        FP.get_by_uid = function (uid) {
            uid = U.NEString(uid, null);
            return uid && C.is(this.index[uid]) ? this.index[uid] : null;
        };

        FP.each = function (co, ca) {
            var xco = U.isObject(co) ? co : (U.isObject(ca) ? ca : this);
            var xca = U.isCallable(ca) ? ca : (U.isCallable(co) ? co : null);
            if (xca) {
                for (var i = 0; i < this.items.length; i++) {
                    xca.apply(xco, [this.items[i], i]);
                }
            }
            return this;
        };


        FP.add_color = function (product_id) {
            var ci = C();
            ci.guid = U.UUID();
            ci.name = "Новый цвет";
            ci.sort = 0;
            ci.html_color = "#c5f326";
            if (ci.guid) {
                this.items.push(ci);
                this.index[ci.guid] = ci;
            }
            return this;
        };

        FP.resort = function () {
            this.items.sort(this._compare_fn);
            return this;
        };

        FP._compare_fn = function (a, b) {
            var r = a.sort - b.sort;
            if (r === 0) {
                r = a.guid < b.guid ? 1 : -1;
            }
            return r;
        };

        FP.export = function () {
            var t = [].concat(this.items);
            t.sort(this._compare_fn);
            var r = [];
            for (var i = 0; i < t.length; i++) {
                t[i].removed ? 0 : r.push(t[i].export());
            }
            return r;
        };

        function color_item() {
            return (color_item.is(this) ? this.init : color_item.F).apply(this, APS.call(arguments));
        }
        var C = U.FixCon(color_item), CP = C.prototype;
        CP.guid = null;
        CP.exchange_uid = null;
        CP.html_color = null;
        CP.name = null;
        CP.sort = null;
        CP.removed = false;

        CP.export = function () {
            return {guid: this.guid, exchange_uid: this.exchange_uid, html_color: this.html_color, name: this.name, sort: this.sort};
        };

        CP.init = function (x) {
            if (U.isObject(x)) {
                this.import(x);
            }
            return this;
        };
        CP.import = function (x) {
            x = U.safeObject(x);
            this.guid = U.NEString(x.guid, null);
            this.exchange_uid = U.NEString(x.exchange_uid, null);
            this.html_color = U.NEString(x.html_color, null);
            this.name = U.NEString(x.name, null);
            this.sort = U.IntOr(x.sort, 0);
            this.removed = false;
            return this;
        };

        CP.is_valid = function () {
            return (this.uid && this.name && this.html_color) ? true : false;
        };

        E.color_editor_color_list = F;
        F.color_item = C;
    }
})();