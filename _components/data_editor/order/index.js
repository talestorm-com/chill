(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.js('/assets/js/ET/ADVTable/advt.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = window.Eve.ADVTable;
        var RT = {
            "P0": "Заказ",
            "P1": "Резерв",
            "P2": "Предзаказ"
        };
        var ST = {
            "P0": "Новый",
            "P1": "В работе",
            "P2": "На доставке",
            "P3": "Завершен",
            "P4": "Отменен"
        };
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        var SVG = null;
        /*<?=$this->create_svg('SVG')?>*/
        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}Items","TPLSItems")?>*/
        function F() {
            return  (F.is(this) ? this.init() : F.F());
        }

        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Sizeable'];
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
            PARP.onInit.apply(this, APS.call(arguments));
            this.init_table();
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            return this;
        };

        F.prototype.onAfterHide = function () {
            return PARP.onAfterHide.apply(this, APS.call(arguments));
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
                {'command': "cancel", 'text': "Закрыть"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Просмотр заказа";
        };
        //</editor-fold>   
        //<editor-fold defaultstate="collapsed" desc="table">                       
        F.prototype.get_table_def = function () {
            return {
                id: [MC, "Items", this.controller_id].join(''),
                template_key: [MC, "Items"].join(''),
                preset_key: [MC, "Items"].join(''),
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "uid", key: "uid", property: "uid", "text": "uid", filter: false, sort: false, visible: false},
                    {id: "article", key: "article", property: "article", "text": "Артикул", filter: false, sort: false},
                    {id: "product", key: "product", property: "product", "text": "Товар", filter: false, sort: false},
                    {id: "size", key: "size", property: "size", "text": "Размер(ы)", filter: false, sort: false},
                    {id: "price", key: "price", property: "price", "text": "Цена", filter: false, sort: false},
                    {id: "qty", key: "qty", property: "qty", "text": "К-во", filter: false, sort: false},
                    {id: "amount", key: "amount", property: "amount", "text": "Сумма", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_table = function () {
            var def = this.get_table_def();

            this.table = ADVT.Table(def);// нучить табло юзать чужой темплатесет

            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });

            this.table.addRenderer('has_valid_product_id', function () {
                return (U.IntMoreOr(this.product_id, 0, null) ? true : false);
            });

            this.table.addRenderer('render_price', function () {
                return EFO.Checks.formatPriceNSD(U.FloatOr(this.price, 0), 2);
            });
            this.table.addRenderer('render_amount', function () {
                return EFO.Checks.formatPriceNSD((U.FloatOr(this.price, 0) * U.IntMoreOr(this.qty, 0, 0)), 2);
            });
            this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getField('items').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/Order/API', {action: "get", id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_data_success(d.order);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            return this;
        };

        //</editor-fold>                          

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
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
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="display_order_list">
        F.prototype.onCommandDisplay_user_orders = function (t) {
            if (!t.hasClass(MC + "disabled")) {
                var id = U.IntMoreOr(t.data('id'), 0, null);
                if (id) {
                    this.user_to_scan = id;
                    this.load_order_list();
                    return this;
                }
            }
            return this;
        };

        F.prototype.load_order_list = function () {
            this.showLoader();
            Y.load('selectors.orders_by_user').done(this, this.on_list_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_list_ready = function (x) {
            x.show().load(this.user_to_scan);
            return this;
        };

        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="open_product">
        F.prototype.onCommandOpen_product = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this.product_id_to_open = id;
                this.load_product_editor();
            }
            return this;
        };
        F.prototype.load_product_editor = function () {
            this.showLoader();
            Y.load('data_editor.product').done(this, this.on_product_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_product_editor_ready = function (x) {
            x.show().load(this.product_id_to_open);
            return this;
        };
        //</editor-fold>
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="fields overrides">
        F.prototype._set_field_items = function (x) {
            this.datasource.setSource(U.safeArray(x.items));
            return this;
        };

        F.prototype._set_field_user_id = function (x, f) {
            var xx = U.IntMoreOr(x.user_id, 0, null);
            if (xx) {
                f.data('id', xx);
                f.removeClass(MC + "disabled");
            } else {
                f.data('id', xx);
                f.addClass(MC + "disabled");
            }
            return this;
        };

        F.prototype._set_field_reserve = function (x, f) {
            var xx = U.IntMoreOr(x.reserve, -1, 0);
            var key = ["P", xx].join('');
            f.val(RT[key] ? RT[key] : key);
            return this;
        };
        F.prototype._set_field_shop = function (x, f) {
            f.val(U.NEString(x.shop_name, ''));
            return this;
        };
        F.prototype._set_field_status = function (x, f) {
            var key = ["P", U.IntMoreOr(x.status, 0, 0)].join('');
            f.val(ST[key] ? ST[key] : key);
            return this;
        };

        F.prototype._set_field_amount = function (x, f) {
            f.val(EFO.Checks.formatPriceNSD(U.FloatOr(x.amount, 0), 2));
            return this;
        };
        F.prototype._set_field_user = function (x, f) {
            f.val(U.NEString(x.user_name, ''));
            return this;
        };

        F.prototype._set_field_phone = function (x, f) {
            var phone = EFO.Checks.formatPhone(U.NEString(x.user_phone, ''));
            if (phone) {
                f.show();
                f.html(phone);
                f.attr("href", ["tel:", phone.replace(/\D/ig, '')].join(''));
            } else {
                f.hide();
            }
            return this;
        };

        F.prototype._set_field_email = function (x, f) {
            var em = U.NEString(x.user_email, '');
            f.html(em);
            f.attr("href", ["mailto:", em].join(''));
            return this;
        };

        //</editor-fold>




        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            if (EFO.Filter.Values().BadValue.is(data.display_name) && !EFO.Filter.Values().BadValue.is(data.name)) {
                data.display_name = data.name;
            } else if (!EFO.Filter.Values().BadValue.is(data.display_name) && EFO.Filter.Values().BadValue.is(data.name)) {
                data.name = data.display_name;
            }
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            var post_data = {
                action: "put",
                data: JSON.stringify(data)
            };
            this.showLoader();
            jQuery.post('/admin/Storage/API', post_data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
            this.runCallback();
            if (!U.anyBool(keep_open, true)) {
                this.hide().clear();
            }
            return this;
        };
        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.order));
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

        F.prototype.on_network_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this;
        };

        F.prototype.on_network_fail_fatal = function (x) {
            this.on_network_fail.apply(this, APS.call(arguments));
            this.hide().clear();
            return this;
        };
        //</editor-fold>        
        //</editor-fold>       
        //
        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            throw new Error("component load error");
        };
        Y.reportSuccess(FQCN, F);//return factory,not instance
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