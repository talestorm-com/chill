(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Form = window.Eve.EFO.Form || {};
    window.Eve.EFO.Form.FW ? false : initPlugin();
    /**
     * абстракт для forms && fields && pages && blocks     
     * @returns {undefined}
     */
    function initPlugin() {
        var EFO = window.Eve.EFO, U = EFO.U;
        function F() {
            U.AbstractError();
        }
        F.xInheritE(EFO.Handlable);
        F.prototype._container = null;
        
        /**
         *override 
         * @returns {F}
         */
        F.prototype.onInit = function () {
            var Z = EFO.Handlable.prototype.onInit.apply(this, Array.prototype.slice.call(arguments));
            this.initFormEvents().initSubaccess();
            return Z;
        };

        F.prototype.getContainer = function () {
            return this._container;
        };

        F.prototype.setContainer = function ($x) {
            this._container = $x;
            this._container ? this.handle.appendTo(this._container) : false;
            return this.show();
        };

        F.prototype.show = function () {
            return this._container ? EFO.Handlable.prototype.show.apply(this, Array.prototype.slice.call(arguments)) : this;
        };

        F.prototype.getContentTemplate = function () {
            this.threadError("FW::getContentTemplate must be overriden");
            return 'fFWContent';
        };

        /**
         * override
         * @returns {undefined}
         */
        F.prototype.getCssClass = function () {
            U.AbstractError();
        };


        /**
         * миксины Fieldable и Monitorable
         * не используются - их методы встроены в сам блок формы (а равно и в поля)
         */

        F.prototype.initFormEvents = function () {
            this.handle.on('change', '[data-monitor]', this.onMonitor.bindToObject(this));
            this.handle.on('click', '[data-command]', this.onCommand.bindToObject(this));
            return this;
        };

        F.prototype.initSubaccess = function () {
            this._role = U.scan(this.handle, 'role');
            this._field = U.scan(this, handle, 'field');
            return this;
        };

        F.prototype.getField = function ($x) {
            ($x in this._field) ? false : U.THREAD_ERR("No field:" + $x);
            ($x in this._field) ? false : this._field[$x] = jQuery(null);
            return this._field[$x];
        };

        F.prototype.getRole = function ($x) {
            ($x in this._role) ? false : U.THREAD_ERR("No role:" + $x);
            ($x in this._role) ? false : this._role[$x] = jQuery(null);
            return this._role[$x];
        };

        F.prototype.onMonitor = function (e) {
            var T = jQuery(e.currentTarget);
            var propagate = U.anyBool(T.data('propagate', false));
            var prevent = U.anyBool((T.data('prevent'), true));
            var mon = U.NEString(T.data('monitor'));
            if (mon) {
                var amon = mon.split('');
                for (var i = 0; i < amon.length; i++) {
                    var cmon = U.NEString(amon[i]);
                    if (cmon) {
                        var omon = ["onMonitor", U.UCFirst(cmon)].join('');
                        if (U.isCallable(this[omon])) {
                            try {
                                this[omon](T, e);
                            } catch (e) {
                                U.THREAD_ERRO(e);
                            }
                            propagate ? false : e.stopPropagation();
                            prevent ? (e.preventDefault ? e.preventDefault() : e.returnValue = false) : false;
                        }
                    }
                }
            }
            return this;
        };

        F.prototype.onCommand = function (e) {
            var T = jQuery(e.currentTarget);
            var propagate = U.anyBool(T.data('propagate', false));
            var prevent = U.anyBool((T.data('prevent'), true));
            var cmd = U.NEString(T.data('command'));
            if (cmd) {
                var acmd = cmd.split('');
                for (var i = 0; i < acmd.length; i++) {
                    var ccmd = U.NEString(acmd[i]);
                    if (ccmd) {
                        var ocmd = ["onCommand", U.UCFirst(ccmd)].join('');
                        if (U.isCallable(this[ocmd])) {
                            try {
                                this[ocmd](T, e);
                            } catch (e) {
                                U.THREAD_ERRO(e);
                            }
                            propagate ? false : e.stopPropagation();
                            prevent ? (e.preventDefault ? e.preventDefault() : e.returnValue = false) : false;
                        }
                    }
                }
            }
            return this;
        };
        
        
        F.prototype.getFieldValue

        EFO.Form.FW = F;
    }
})();