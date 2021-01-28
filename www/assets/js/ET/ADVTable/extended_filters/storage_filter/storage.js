(function () {

    window.Eve = window.Eve || {};
    window.Eve.ADVTable = window.Eve.ADVTable || {};
    window.Eve.ADVTable.Ready = window.Eve.ADVTable.Ready || [];
    window.Eve.ADVTable.Ready.push(ready);

    function ready() {
        var NS = window.Eve.ADVTable, U = NS.Util,
                FNS = NS.InlineFilter, FFNS = FNS.filters,
                FILTER_ID = "Storage";
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
                F.prototype.visible_value = null;

                F.prototype.afterInit = function (mailslot, col_id, col_key, params) {
                    this.resetInternal();
                    return this;
                };
                F.prototype.resetInternal = function () {
                    this.value = null;
                    this.visible_value = null;
                    return this;
                };
                F.prototype.getLayoutName = function () {
                    return 'filter.storage_selector';
                };


                F.prototype.setValue = function (vv) {
                    this.onBeforeSetValue.apply(this, Array.prototype.slice.call(arguments));
                    vv = U.safeObject(vv);
                    var v1 = U.IntMoreOr(vv.v1, 0, null);
                    var v2 = U.NEString(vv.v2, null);
                    if (!(v1 && v2)) {
                        v1 = null;
                        v2 = null;
                    }
                    this.value = v1;
                    this.visible_value = v2;
                    if (!(v1 && v2)) {
                        this.resetInternal();
                    }
                    this.handle ? this._updateHandle() : false;
                    this.onAfterSetValue.apply(this, Array.prototype.slice.call(arguments));
                    return this;
                };

                F.prototype.onAfterSetHandle = function () {
                    this.scanLayout();
                    this.getLP('value').addEventListener('click', U.bindTo(this, this.onClick1));
                    this.getLP('reset').addEventListener('click', U.bindTo(this, this.onClick2));
                    return PARP.onAfterSetHandle.apply(this, APS.call(arguments));
                };

                F.prototype.updateHandle = function () {
                    this.getLP('value').value = this.visible_value;
                    return this;
                };



                F.prototype.onClick1 = function (e) {
                    window.Eve.EFO.Com().load("selectors.storage_selector")
                            .done(this, this.on_selector_ready)
                            .fail(this, this.on_component_error);
                    return this;
                };

                F.prototype.on_component_error = function () {
                    U.TError("component load error");
                    return this;
                };

                F.prototype.on_selector_ready = function (x) {
                    x.show().load().set_allow_multi(false).setCallback(this, this.on_new_value_selected);
                    return this;
                };

                F.prototype.on_new_value_selected = function (x) {
                    this.value = null;
                    this.visible_value = null;
                    if (U.isArray(x) && x.length > 0) {
                        this.value = U.IntMoreOr(U.safeObject(x[0]).id, 0, null);
                        this.visible_value = U.NEString(U.safeObject(x[0]).name, null);
                        if (!(this.value && this.visible_value)) {
                            this.value = null;
                            this.visible_value = null;
                        }
                    }
                    this.setValue({v1: this.value, v2: this.visible_value});
                    this.trigger(true);
                    return this;
                };

                F.prototype.onClick2 = function () {
                    this.setValue(null);
                    this.trigger(true);
                    return this;
                };

                F.prototype.restoreParams = function (x) {
                    x = U.safeObject(x);
                    if (this.column_id && (this.column_id in x) && U.isObject(x[this.column_id])) {
                        var po = U.safeObject(x[this.column_id].v);
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
                        xo[this.column_id] = {v: {v1: U.IntMoreOr(this.value, 0, null), v2: U.NEString(this.visible_value, null)}};
                    }
                    return this;
                };






                F.prototype.reset = function () {
                    this.resetInternal();
                    this.updateHandle();
                    this.trigger(true);
                };

                F.prototype.isActive = function () {
                    return U.IntMoreOr(this, value, 0, null) ? true : false;
                };

                F.prototype.trigger = function (onReset) {
                    onReset = U.anyBool(onReset, false);
                    if (onReset || this.isActive()) {
                        this.mailslot.trigger(NS.Events.EventList.TABLE_INLINE_FILTER_CHANGED);
                    }
                    return this;
                };

                F.prototype.getRemoteValue = function () {
                    return this.value;// === 'on' ? 1 : (this.state === 'off' ? 0 : null);
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

                F.prototype.getNextState = function () {
                    return '';
                    for (var i = 0; i < states.length; i++) {
                        if (states[i] === this.state && U.NEString(states[i + 1], null)) {
                            return states[i + 1];
                        }
                    }
                    return states[0];
                };

                //inbuild templates and styles
                /*  */
                var TPL = null;
                /* >>>>>>>TEMPLATES*/
 TPL={"filter.storage_selector":"<div class=\"ADVTableFilterContainer ADVTableFilterContainerTextInput\" data-filter-marker=\"wrapper\" id=\"ADVTableFilter{{path}}{{uid}}\">\n    <div class=\"ADVTableFilterTextInputWrapper\" data-filter-marker=\"textWrapper\"> \n        <input type=\"text\" value=\"\" data-filter-marker=\"value\" readonly=\"readonly\" \/>\n    <\/div>\n    <div class=\"ADVTableFilterTextInputResetButton\" data-filter-marker=\"reset\">\n        <svg><use xlink:href=\"#ADVTIcon{{uid}}FilterResetIcon\" \/><\/svg>\n    <\/div>\n<\/div>"};
/*<<<<<templates*/
                NS.TemplateManager.GlobalTemplateManager().addSharedTemplates(TPL);

                var style = {};
                /**/ style = {"css":".ADVTableSOBooleanon {\n    border: 1px solid white;\n    background: #00acc8;\n    outline: 1px solid #00acc8!important;\n}\n\n.ADVTableFilterBoolLayoutInputWrapper {\n    font-size: .95em;\n}\n\n.ADVTableFilterBoolLayoutInputWrapper>div {\n    box-sizing: border-box;\n}\n\n.ADVTableSOBooleanoff {\n    outline: 1px solid crimson!important;\n}"} /**/
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