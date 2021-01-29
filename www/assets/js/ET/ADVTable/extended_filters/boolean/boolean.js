(function () {

    window.Eve = window.Eve || {};
    window.Eve.ADVTable = window.Eve.ADVTable || {};
    window.Eve.ADVTable.Ready = window.Eve.ADVTable.Ready || [];
    window.Eve.ADVTable.Ready.push(ready);

    function ready() {
        var NS = window.Eve.ADVTable, U = NS.Util,
                FNS = NS.InlineFilter, FFNS = FNS.filters,
                FILTER_ID = "Boolean";
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
                var states = [
                    'disabled', 'on', 'off'
                ];
                F.prototype.state = null;

                F.prototype.afterInit = function (mailslot, col_id, col_key, params) {
                    this.resetInternal();
                    return this;
                };
                F.prototype.resetInternal = function () {
                    this.state = 'disabled';
                };
                F.prototype.getLayoutName = function () {
                    return 'filter.so_boolean';
                };


                F.prototype.setValue = function (vv) {
                    this.onBeforeSetValue.apply(this, Array.prototype.slice.call(arguments));
                    this.state = vv;
                    if (!this.state === 'on' && !this.state === 'off') {
                        this.resetInternal();
                    }
                    this.handle ? this._updateHandle() : false;
                    this.onAfterSetValue.apply(this, Array.prototype.slice.call(arguments));
                    return this;
                };

                F.prototype.onAfterSetHandle = function () {
                    this.scanLayout();
                    this.getLP('marker').addEventListener('click', U.bindTo(this, this.onClick1));
                    return PARP.onAfterSetHandle.apply(this, APS.call(arguments));
                };

                F.prototype.updateHandle = function () {
                    var e = this.getLP('marker');
                    var h = Eve.ADVTable.DOMAccess.CssHelper();
                    h.removeClassLike(e, 'ADVTableSOBoolean');
                    h.addClass(e, 'ADVTableSOBoolean' + this.state);

                    return this;
                };



                F.prototype.onClick1 = function (e) {
                    this.state = this.getNextState();
                    this.setValue(this.state);
                    this.trigger(true);
                    return this;
                };


                F.prototype.restoreParams = function (x) {
                    x = U.safeObject(x);
                    if (this.column_id && (this.column_id in x) && U.isObject(x[this.column_id])) {
                        var po = U.safeObject(x[this.column_id].v);
                        this.setValue(U.NEString(po.v, null));
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
                        xo[this.column_id] = {v: {v: U.NEString(this.state, null)}};
                    }
                    return this;
                };






                F.prototype.reset = function () {
                    this.resetInternal();
                    this.updateHandle();
                    this.trigger(true);
                };

                F.prototype.isActive = function () {
                    return this.state && (this.state === 'on' || this.state === 'off') ? true : false;
                };

                F.prototype.trigger = function (onReset) {
                    onReset = U.anyBool(onReset, false);
                    if (onReset || this.isActive()) {
                        this.mailslot.trigger(NS.Events.EventList.TABLE_INLINE_FILTER_CHANGED);
                    }
                    return this;
                };

                F.prototype.getRemoteValue = function () {
                    return this.state === 'on' ? 1 : (this.state === 'off' ? 0 : null);
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
 TPL={"filter.so_boolean":"<div class=\"ADVTableFilterContainer ADVTableFilterContainerBoolLayoutInput \" data-filter-marker=\"wrapper\" id=\"ADVTableFilter{{path}}{{uid}}\">    \n    <div class=\"ADVTableFilterTextInputWrapper ADVTableStorageBoolLayoutFieldWrapperWrapper\">\n        <div class=\"ADVTableFilterBoolLayoutInputWrapper ADVTableFilterBoolLayoutInputWrapper1\" > \n            <div class=\"\" data-filter-marker=\"marker\"><\/div>\n        <\/div>       \n    <\/div>    \n<\/div>"};
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