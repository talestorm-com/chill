(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(efo_ready);
    function efo_ready() {
        var EFO = window.Eve.EFO, U = EFO.U, MC = '<?=$this->MC?>', APS = Array.prototype.slice;
        function F() {
            return F.is(this) ? this.init() : F.F();
        }
        var FP = U.FixCon(F).prototype;
        FP.items = null;
        FP.index = null;
        FP.LEM = null;

        FP.init = function () {
            this.LEM = EFO.Events.LEM();
            return this.reset();
        };

        FP.reset = function () {
            this.items = [];
            this.index = {};
            return this.trigger();
        };

        FP.trigger = function () {
            this.LEM.Run("CHANGED", this);
            return this;
        };

        FP.add = function (id/**{Int}*/, sort/**{Int}*/) {
            id = U.IntMoreOr(id, 0, null);
            sort = U.IntOr(sort, 0);
            if (id) {
                var key = ["P", id].join('');
                if (!SR.is(this.index[key])) {
                    var i = SR(id);
                    this.index[key] = i;
                    this.items.push(i);
                }
                this.index[key].set_value(sort);
                this.trigger();
            }
            return this;
        };

        FP.get_length = function () {
            return this.items.length;
        };

        FP.is_empty = function () {
            return this.items.length ? false : true;
        };

        FP.get_data = function () {
            var r = [];
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].valid()) {
                    r.push(this.items[i].get_data());
                }
            }
            return r;
        };

        FP.get_value = function (id, def) {
            id = U.IntMoreOr(id, 0, null);
            var key = ["P", id].join('');
            if (id && SR.is(this.index[key])) {
                return this.index[key].get_value(def);
            }
            return def;
        };

        function SR() {
            return (SR.is(this) ? this.init : SR.F).apply(this, APS.call(arguments));
        }
        var SRP = U.FixCon(SR).prototype;
        SRP.id = null;
        SRP.value = null;
        SRP.key = null;

        SRP.init = function (id) {
            this.id = U.IntMoreOr(id, 0, null);
            this.key = ["P", this.id].join('');
            this.value = null;
            return this;
        };

        SRP.set_value = function (x) {
            this.value = U.IntOr(x, 0);
            return this;
        };

        SRP.get_value = function (def) {
            return U.IntOr(this.value, def);
        };

        SRP.get_data = function () {
            return {i: this.id, v: this.value};
        };

        SRP.valid = function () {
            return this.id && (null !== this.value);
        };

        window[MC + "SortMonitor"] = F;
    }
})();