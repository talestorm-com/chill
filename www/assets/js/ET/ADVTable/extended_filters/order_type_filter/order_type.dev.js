(function () {

    window.Eve = window.Eve || {};
    window.Eve.ADVTable = window.Eve.ADVTable || {};
    window.Eve.ADVTable.Ready = window.Eve.ADVTable.Ready || [];
    window.Eve.ADVTable.Ready.push(ready);

    function ready() {
        var NS = window.Eve.ADVTable, U = NS.Util,
                FNS = NS.InlineFilter, FFNS = FNS.filters,
                FILTER_ID = "Ordertype";
        FFNS[FILTER_ID + "InlineFilterFactory"] ? false : initPlugin();
        function initPlugin() {
            var R = null;
            function factory() {
                var PAR = FNS.AbstractFilter, PARP = PAR.prototype, APS = Array.prototype.slice;
                if (R) {
                    return R;
                }
                function InlineFilter(mailslot, column_id, column_key, params) {
                    return InlineFilter.is(this) ? this.init(mailslot, column_id, column_key, params) : InlineFilter.F(mailslot, column_id, column_key, params);
                }
                U.inheritFix(PAR, InlineFilter);
                var F = InlineFilter;
                //===============================================
                //======== AbstractFilter overrides =============
                //===============================================                
                F.prototype.value = null;

                F.prototype.afterInit = function (mailslot, col_id, col_key, params) {                                        
                    this.resetInternal();
                    return this;
                };
                F.prototype.resetInternal = function () {
                    this.value = -1;
                    return this;
                };
                F.prototype.getLayoutName = function () {
                    return 'filter.order_type_selector';
                };


                F.prototype.setValue = function (vv) {
                    this.onBeforeSetValue.apply(this, Array.prototype.slice.call(arguments));
                    var v1 = U.IntMoreOr(vv, -1, -1);
                    this.value = v1;
                    this.handle ? this._updateHandle() : false;
                    this.onAfterSetValue.apply(this, Array.prototype.slice.call(arguments));
                    return this;
                };

                F.prototype.onAfterSetHandle = function () {
                    this.scanLayout();
                    this.getLP('value').addEventListener('change', this.on_value_changed.bindToObject(this));
                    return PARP.onAfterSetHandle.apply(this, APS.call(arguments));
                };

                F.prototype.updateHandle = function () {
                    this.getLP('value').value = this.value;
                    return this;
                };




                F.prototype.on_value_changed = function () {
                    var xt = this.getLP('value').value;
                    this.setValue(xt);
                    this.trigger(true);
                };

                F.prototype.restoreParams = function (x) {
                    x = U.safeObject(x);
                    if (this.column_id && (this.column_id in x) && U.isObject(x[this.column_id])) {
                        var po = U.IntMoreOr(x[this.column_id].v, -1, -1);
                        this.setValue(po);
                    }
                    return this;
                };
                /**
                 * просто запихиваем в выходной объект ключ с имененм ячейки и значением = {v:value,d:dispalyValue} фильтра
                 * @param {Object} xo
                 * @returns {F}
                 */
                F.prototype.keepParams = function (xo) {
                    if (U.isObject(xo)) {
                        xo[this.column_id] = {v: this.value};
                    }
                    return this;
                };

                F.prototype.reset = function () {
                    this.resetInternal();
                    this.updateHandle();
                    this.trigger(true);
                };

                F.prototype.isActive = function () {
                    return this.value === -1 ? false : true;
                };

                F.prototype.trigger = function (onReset) {
                    onReset = U.anyBool(onReset, false);
                    if (onReset || this.isActive()) {
                        this.mailslot.trigger(NS.Events.EventList.TABLE_INLINE_FILTER_CHANGED);
                    }
                    return this;
                };

                F.prototype.getRemoteValue = function () {
                    return this.value === -1 ? null : this.value;// === 'on' ? 1 : (this.state === 'off' ? 0 : null);
                };



                F.prototype.matchLocal = function (o) {
                    return true; //нет локальной фильтрации                
                };

                F.prototype.checkValue = function (x) {
                    return x;
                };

                F.prototype.isLocal = function () {
                    return false;
                };
                

                //inbuild templates and styles
                /* <?php incTPL('layout.html','filter.order_type_selector')?> */
                var TPL = null;
                /* <?=outTPL('TPL')?>*/
                NS.TemplateManager.GlobalTemplateManager().addSharedTemplates(TPL);

                var style = {};
                /*<?= out_style() ?>*/
                var style_tag = document.createElement('style');
                style_tag.type = "text/css";
                style_tag.innerHTML = style.css;
                var head = document.getElementsByName("head");
                if (head && head.length) {
                    head[0].appendChild(style_tag);
                } else {
                    document.documentElement.appendChild(style_tag);
                }

                //===============================================
                //======== EOF AbstractFilter overrides =============
                //===============================================
                return R = F;
            }
            FFNS[FILTER_ID + "InlineFilterFactory"] = factory;
        }
    }

})();