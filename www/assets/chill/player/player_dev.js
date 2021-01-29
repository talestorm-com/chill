(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(function () {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        if (!U.isCallable(E.chill_player)) {

            function player() {
                return (player.is(this) ? this.init : player.F).apply(this, APS.call(arguments));
            }
            var P = U.FixCon(player).prototype;
            var templates = {};
            /* <?= $this->include_templates(); ?> */
            P.handle = null;
            P.init = function () {
                this.handle = jQuery(templates.player);
                debugger;
                return this;
            };

            P.set_container = function (x) {
                if (U.isObject(x) && (x instanceof jQuery)) {
                    x.html('');
                    this.handle.appendTo(x);
                }
                return this;
            };

            P.setup = function (files) {
                debugger;
            };
            window.Eve.chill_player = player;
        }
    });
})();