(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.js('/assets/js/types/menu_node.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        var SVG = null;
        /*<?=$this->create_svg('SVG')?>*/
        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        //<editor-fold defaultstate="collapsed" desc="sizeable">
        F.prototype.sizeable_getParams = function () {
            var w = this.sizeable_defaultWidth();
            var h = this.sizeable_defaultHeight();
            try {
                var u = JSON.parse(localStorage.getItem(MC + 'rsz'));
                if (U.isObject(u) && ('w' in u) && ('h' in u) && typeof (u.h) === 'number' && !isNaN(u.h) && typeof (u.w) === 'number' && !isNaN(u.w)) {
                    w = u.w;
                    h = u.h;
                }
            } catch (e) {
                w = this.sizeable_defaultWidth();
                h = this.sizeable_defaultHeight();
            }
            return {w: w, h: h};
        };
        F.prototype.sizeable_setParams = function (x) {
            if (U.isObject(x)) {
                if (('w' in x) && ('h' in x) && typeof (x.w) === 'number' && typeof (x.h) === 'number' && !isNaN(x.w) && !isNaN(x.h)) {
                    try {
                        localStorage.setItem(MC + 'rsz', JSON.stringify(x));
                    } catch (e) {

                    }
                }
            }
            return this;
        };
        F.prototype.sizeable_scaleFactor = function () {
            return 2;
        };
        //</editor-fold>
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            return this;
        };
        F.prototype.getContentTemplate = function () {
            return EFO.TemplateManager().get([MC, 'Main'].join('.'));
        };
        F.prototype.getControllerAlias = function () {
            return MC;
        };
        F.prototype.getCssClass = function () {
            return MC;
        };
        F.prototype.getFooterButtons = function () {
            return [
                {'command': "cancel", 'text': "Отмена"},
                //{'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Применить"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Редактирование пункта меню";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (node) {
            this.clear();
            this.Node = node;
            this.setFields(node);
            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.Node = null;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="footer commands">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };
        F.prototype.onCommandApply = function () {
            this.save(true);
            return this;
        };
        F.prototype.onCommandSave = function () {
            this.save(false);
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="select product">
        F.prototype.onCommandSelect_product = function () {
            this.showLoader();
            Y.load("selectors.product_selector").done(this, this.on_product_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_product_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_product_selected);
            return this;
        };

        F.prototype.on_product_selected = function (x) {
            x = U.safeArray(x);
            if (x.length) {
                this.getField('url').val('/product/' + x[0].alias);
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="select catalog">
        F.prototype.onCommandSelect_group = function () {
            this.showLoader();
            Y.load('selectors.catalog_group')
                    .done(this, this.on_group_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_group_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_group_selected);
            return this;
        };

        F.prototype.on_group_selected = function (x) {
            x = U.safeArray(x);
            if (x.length) {
                this.getField('url').val('/catalog/' + x[0].alias);
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="select subtree">
        F.prototype.onCommandSelect_group_alias = function () {
            this.showLoader();
            Y.load('selectors.catalog_group')
                    .done(this, this.on_group_selector_ready_2)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_group_selector_ready_2 = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_group_selected_2);
            return this;
        };

        F.prototype.on_group_selected_2 = function (x) {
            x = U.safeArray(x);
            if (x.length) {
                this.getField('url').val('catalog://' + x[0].alias);
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="select content block">
        F.prototype.onCommandSelect_block = function () {
            this.showLoader();
            Y.load('selectors.content_block_selector')
                    .done(this, this.on_block_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_block_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_block_selected);
            return this;
        };
        F.prototype.on_block_selected = function (x) {
            x = U.safeArray(x);
            if (x.length) {
                this.getField('url').val('contentblock://' + x[0].alias);
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="select infopage">
        F.prototype.onCommandSelect_infopage = function () {
            this.showLoader();
            Y.load('selectors.page_selector')
                    .done(this, this.on_page_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_page_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_page_selected);
            return this;
        };
        F.prototype.on_page_selected = function (x) {
            x = U.safeArray(x);
            if (x.length) {
                this.getField('url').val('/page/' + x[0].alias);
            }
            return this;
        };
        //</editor-fold>
        //</editor-fold>
        F.prototype._set_field_path = function (n, f) {
            if (n.parent) {
                f.val(n.parent.get_name_path("\\"));
            } else {
                f.val('Корневой');
            }
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        F.prototype.save = function (keep_open) {
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            this.Node.name = data.name;
            this.Node.url = data.url;
            this.Node.visible = data.visible;
            this.Node.sort_order = data.sort_order;
            this.Node.css_class = data.css_class;
            this.runCallback();
            if (!U.anyBool(keep_open, true)) {
                this.hide().clear();
            }
            return this;
        };
        F.prototype.on_post_result = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.data));
                    this.runCallback();
                    if (!this._keep_open) {
                        this.hide().clear();
                    }
                    return this;
                }
                if (d.status === "error") {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
        };
        Y.reportSuccess(FQCN, F());
        //</editor-fold>
    }
    //<editor-fold defaultstate="collapsed" desc="dependecy resolver">
    if (imports.length) {
        window.Eve.EFO.EFOPromise.waitForArray(imports)
                .done(initPlugin)
                .fail(function () {
                    Y.report_fail(FQCN, "Ошибке при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();