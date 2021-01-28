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
                /*  */
                var TPL = null;
                /* >>>>>>>TEMPLATES*/
 TPL={"filter.order_type_selector":"<div class=\"ADVTableFilterContainer ADVTableFilterContainerTextInput ADVTableFilterContainerSelectInput\" data-filter-marker=\"wrapper\" id=\"ADVTableFilter{{path}}{{uid}}\">\n    <div class=\"ADVTableFilterTextInputWrapper\" data-filter-marker=\"textWrapper\"> \n        <select data-filter-marker=\"value\">\n            <option value=\"-1\">\u0412\u0441\u0435<\/option>\n            <option value=\"0\">\u0417\u0430\u043a\u0430\u0437\u044b<\/option>\n            <option value=\"1\">\u0420\u0435\u0437\u0435\u0440\u0432\u044b<\/option>\n            <option value=\"2\">\u041f\u0440\u0435\u0434\u0437\u0430\u043a\u0430\u0437\u044b<\/option>\n        <\/select>        \n    <\/div>    \n<\/div>"};
/*<<<<<templates*/
                NS.TemplateManager.GlobalTemplateManager().addSharedTemplates(TPL);

                var style = {};
                /**/ style = {"css":".ADVTableSOBooleanon {\n    border: 1px solid white;\n    background: #00acc8;\n    outline: 1px solid #00acc8!important;\n}\n\n.ADVTableFilterBoolLayoutInputWrapper {\n    font-size: .95em;\n}\n\n.ADVTableFilterBoolLayoutInputWrapper>div {\n    box-sizing: border-box;\n}\n\n.ADVTableSOBooleanoff {\n    outline: 1px solid crimson!important;\n}\n\n.ADVTableFilterContainer.ADVTableFilterContainerTextInput.ADVTableFilterContainerSelectInput {\n    box-sizing: border-box;\n    padding: 0;\n    border-top: 1px solid silver;\n}\n\n.ADVTableFilterContainer.ADVTableFilterContainerTextInput.ADVTableFilterContainerSelectInput select {\n    box-sizing: border-box;\n    border: none;\n    background: transparent;\n    color: #00acc8;\n    cursor: pointer;\n    -webkit-appearance: none;\n    padding-left: .3em;\n    outline: none;\n    box-shadow: none;\n    width:100%;\n}"} /**/
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