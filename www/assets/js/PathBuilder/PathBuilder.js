(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        if (!U.isCallable(E.PathBuilder)) {
            function PathBuilder() {
                return (PathBuilder.is(this) ? this.init : PathBuilder.F).apply(this, APS.call(arguments));
            }
            var P = U.FixCon(PathBuilder).prototype;


            P.parts = null;
            P.private = false;
            P.accept = "video/*";
            P.init = function () {
                this.parts = [];
                this.append_arguments.apply(this, APS.call(arguments));
                return this;
            };
            
            P.setAccept = function(x){
                this.accept = U.NEString(x,'video/*');
                return this;
            };

            P.append_arguments = function () {
                var args = APS.call(arguments);
                for (var i = 0; i < args.length; i++) {
                    var t = this.prepare_arguments(args[i]);
                    if (t && t.length) {
                        this.parts = this.parts.concat(t);
                    }
                }
                return this;
            };
            P.set_private = function (x) {
                this.private = U.anyBool(x, false);
                return this;
            };
            P.get_private = function () {
                return this.private;
            };

            P.build_path = function () {
                return ["/", this.parts.join("/")].join("");
            };
            P.build = P.build_path;

            P.by_appending = function () {
                var n = PathBuilder();
                n.parts = [].concat(this.parts);
                n.private = this.private;
                n.append_arguments.apply(n, APS.call(arguments));
                return n;
            };

            P.prepare_arguments = function (x) {
                x = U.NEString(x, '');
                var xa = x.split('/');
                var result = [];
                for (var i = 0; i < xa.length; i++) {
                    var t = U.NEString(xa[i], null);
                    t ? result.push(t) : 0;
                }
                return result;
            };
            E.PathBuilder = PathBuilder;
        }
    }
})();