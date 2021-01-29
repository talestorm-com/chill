(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice, H = null;
        function voc() {
            return voc.is(H) ? H : ((voc.is(this) ? this.init : voc.F).apply(this, APS.call(arguments)));
        }
        var P = U.FixCon(voc).prototype;

        P.items = null;
        P.index = null;

        P.init = function () {
            H = this;
            this.items = [];
            this.index = {};
            return this;
        };

        P.import = function (x) {            
            if (U.isArray(x)) {
                this.index = {};
                this.items = [];
                for (var i = 0; i < x.length; i++) {
                    var item = vocitem(x[i]);
                    if (item && item.is_valid()) {
                        this.items.push(item);
                        this.index[item.get_type()] = item;
                    }
                }
            }
            return this;
        };

        P.get_name_of = function (ctype_text) {
            return vocitem.is(this.index[ctype_text]) ? this.index[ctype_text] : void(0);
        };

        function vocitem() {
            return (vocitem.is(this) ? this.init : vocitem.F).apply(this, APS.call(arguments));
        }

        var PP = U.FixCon(vocitem).prototype;

        PP.ctype = null;
        PP.name = null;
        PP.editor = null;
        PP.visible = null;
        PP.init = function (x) {
            x = U.safeObject(x);
            this.ctype = U.NEString(x.type, null);
            this.name = U.NEString(x.name, null);
            this.editor = U.NEString(x.editor, null);
            this.visible = U.anyBool(x.visible,true);
            return this;
        };

        PP.is_valid = function () {
            return !!(this.ctype && this.name && this.editor);
        };


        PP.get_type = function () {
            return this.ctype;
        };

        PP.get_name = function () {
            return this.name;
        };

        window.MediaTypeVoc = voc;

        window.MediaTypeVocReady = window.MediaTypeVocReady || [];
        var c = window.MediaTypeVocReady;
        window.MediaTypeVocReady = {
            push: function () {
                var args = APS.call(arguments);
                for (var i = 0; i < args.length; i++) {
                    if (U.isCallable(args[i])) {
                        try {
                            args[i]();
                        } catch (e) {

                        }
                    }
                }
            }
        };
        window.MediaTypeVocReady.push.apply(window.MediaTypeVocReady, c);

    }
})();