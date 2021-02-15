(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        function size_item() {
            return (size_item.is(this) ? this.init : size_item.F).apply(this, APS.call(arguments));
        }
        var SIP = U.FixCon(size_item).prototype;
        SIP.id = null;
        SIP.size = null;
        SIP.guid = null;
        SIP.aliases = null;
        SIP.key = null;
        SIP.MC = null;
        SIP.init = function (data, MC) {
            this.MC = U.NEString(MC, 'sip_item_default');
            data = U.safeObject(data);
            this.id = U.IntMoreOr(data.id, 0, U.UID('new'));
            this.size = U.NEString(data.size, '');
            this.key = ["SIP", this.id].join('');
            this.guid = U.NEString(data.guid, '');
            this.aliases = {};
            return this;
        };
        SIP.add_alias = function (alias_id, value) {
            alias_id = U.NEString(alias_id, null);
            alias_id ? false : U.Error('SIP:invalid_alias_id');
            this.aliases[['A', alias_id].join('')] = {i: alias_id, v: U.NEString(value, null)};
            return this;
        };

        SIP.get_alias_value = function (alter_id) {
            alter_id = U.NEString(alter_id, null);
            alter_id ? false : U.Error('SIP:invalid_alias_id');
            var alias_key = ['A', alter_id].join('');
            if (U.isObject(this.aliases[alias_key]) && this.aliases[alias_key].i === alter_id) {
                return this.aliases[alias_key].v;
            }
            return null;
        };

        SIP.is_valid = function () {
            return true;
        };

        SIP.self_check = function () {
            var html_id = [this.MC, 'SIP', this.id].join('_');
            try {
                if (!U.NEString(this.guid, null)) {
                    U.Error(this.MC + ": guid is required");
                }
                if (!U.NEString(this.size, null)) {
                    U.Error(this.MC + ": shop size is required");
                }
            } catch (e) {
                e.html_id = html_id;
                throw e;
            }
            return this;
        };

        SIP.export = function () {
            var r = {
                id: this.id, tmp_id: this.id,
                guid: this.guid, size: this.size,
                aliases: []
            };
            for (var k in this.aliases) {
                if (this.aliases.hasOwnProperty(k) && U.isObject(this.aliases[k])) {
                    if (this.aliases[k].hasOwnProperty('i') && this.aliases[k].hasOwnProperty('v')) {
                        var i = U.IntMoreOr(this.aliases[k].i, 0, null);
                        if (i) {
                            var v = U.NEString(this.aliases[k].v, null);
                            if (v) {
                                r.aliases.push({i: i, v: v});
                            }
                        }
                    }
                }
            }
            return r;
        };


        function SIP_collection() {
            return (SIP_collection.is(this) ? this.init : SIP_collection.F).apply(this, APS.call(arguments));
        }
        var SIC = U.FixCon(SIP_collection).prototype;

        SIC.items = null;
        SIC.index = null;
        SIC.MC = null;
        SIC.init = function (main, aliases, MC) {
            this.MC = U.NEString(MC, 'sip_collection_global');
            this.items = [];
            this.index = {};
            this.import(U.safeArray(main), U.safeArray(aliases));
            return this;
        };
        SIC.import = function (m, a) {
            for (var i = 0; i < m.length; i++) {
                var _sip = size_item(m[i], this.MC);
                if (_sip && _sip.is_valid()) {
                    this.items.push(_sip);
                    this.index[_sip.key] = _sip;
                }
            }
            for (var i = 0; i < a.length; i++) {
                var sip_key = ['SIP', a[i].size_id].join('');
                if (size_item.is(this.index[sip_key])) {
                    this.index[sip_key].add_alias(a[i].alter_id, a[i].alter_size);
                }
            }
            return this;
        };

        SIC.add_default = function () {
            var _sip = size_item({}, this.MC);
            if (_sip && _sip.is_valid()) {
                this.items.push(_sip);
                this.index[_sip.key] = _sip;
            }
            return this;
        };

        SIC.remove_item = function (id) {
            id = U.NEString(id, null);
            if (id) {
                var sip_key = ['SIP', id].join('');
                if (size_item.is(this.index[sip_key])) {
                    var xdo = this.index[sip_key];
                    delete(this.index[sip_key]);
                    var xi = this.items.indexOf(xdo);
                    this.items = [].concat(this.items.slice(0, xi), this.items.slice(xi + 1));
                }
            }
            return this;
        };

        SIC.self_check = function () {
            for (var i = 0; i < this.items.length; i++) {
                this.items[i].self_check();
            }
            return this;
        };

        SIC.export = function () {
            var r = [];
            for (var i = 0; i < this.items.length; i++) {
                r.push(this.items[i].export());
            }
            return r;
        };
        SIC.sort_by_values = function () {
            this.items.sort(function (a, b) {
                return U.IntOr(a.size, 0) - U.IntOr(b.size, 0);
            });
            return this;
        };
        E.SIP_COLLECTION = SIP_collection;
        E.SIP_COLLECTION.item = size_item;

    }
})();