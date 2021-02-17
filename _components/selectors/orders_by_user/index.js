(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/shop_filter/shop.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/order_type_filter/order_type.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/order_status_filter/order_status.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = window.Eve.ADVTable;
        var RT = {
            "P0": 'Заказ',
            "P1": "Резерв",
            "P2": "Предзаказ"
        };
        var statuses = [
            {value: 0, title: "Новый"},
            {value: 1, title: "В обработке"},
            {value: 2, title: "На доставке"},
            {value: 3, title: "Завершен"},
            {value: 4, title: "Отменен"}
        ];
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.user_id = null;
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
            this.init_table();
            return this;
        };
        F.prototype.sync_selection_text = function () {
            this.getRole('selection_len').html(this.selection.get_length());
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
            return "Заказы пользователя";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (id) {
            this.user_id = U.IntMoreOr(id, 0, null);
            this.table.body.DataDriver.refresh();
            return this;
        };



        //</editor-fold>  

        //<editor-fold defaultstate="collapsed" desc="table">   

        F.prototype.table_def = function () {
            return {
                id: MC,
                filters: true,
                sorter: "Remote",
                paginator: true,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "id", key: "id", property: "id", "text": "ID", filter: "Int", sort: true},
                    {id: "reserve", key: "reserve", property: "reserve", "text": "Тип", filter: "ordertype", sort: true},
                    {id: "shop", key: "shop", property: "shop", "text": "Магазин", filter: "Shop", sort: true},
                    {id: "created", key: "created", property: "created", "text": "Создан", filter: "Date", sort: true},
                    {id: "user_wrap", key: "user_wrap", property: "user_wrap", "text": "Покупатель", filter: false, sort: false, columns: [
                            {id: "user_name", key: "user_name", property: "user_name", "text": "Покупатель", filter: "String", sort: true},
                            {id: "user_phone", key: "user_phone", property: "user_phone", "text": "Телефон", filter: "String", sort: true},
                            {id: "user_email", key: "user_email", property: "user_email", "text": "Почта", filter: "String", sort: true},
                            {id: "dealer", key: "dealer", property: "dealer", "text": "Диллер", filter: "Bool", sort: true}
                        ]},
                    {id: "position", key: "position", property: "position", "text": "Позиций", filter: "Int", sort: true},
                    {id: "amount", key: "amount", property: "amount", "text": "Сумма", filter: "Numeric", sort: true},
                    {id: "status", key: "status", property: "status", "text": "Статус", filter: "Orderstatus", sort: true}
                ]
            };
        };

        F.prototype.init_table = function () {
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}","TPLS")?>*/
            this.table = ADVT.Table(this.table_def());
            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });

            this.table.addRenderer('translate_reserve', function () {
                var key = ["P", this.reserve].join('');
                return RT[key] ? RT[key] : key;
            });

            this.table.addRenderer('register_item_status', (function (x) {
                this._cstatus = U.IntMoreOr(x.status, 0, 0);
            }).bindToObjectWParam(this));
            this.table.addRenderer('status_options', function () {
                return statuses;
            });

            this.table.addRenderer('is_selected', (function (c) {
                return (c.value === this._cstatus) ? true : false;
            }).bindToObjectWParam(this));

            this.table.addRenderer('is_final_status', function () {
                return U.IntMoreOr(this.status, 0, 0) >= 3;
            });

            this.table.addRenderer('render_phone_as_string', function () {
                var m = U.NEString(U.NEString(this.user_phone, '').replace(/\D/ig, ""), null);
                if (m && m.length >= 11) {
                    return ["+", m].join('');
                }
                return '';
            });

            this.table.addRenderer('render_amount', function () {
                return EFO.Checks.formatPriceNSD(this.amount, 2);
            });
            var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/Order/API?action=list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            DSParams.onRequestParams(this, this.on_request_params);
            this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('table').get(0));
        };

        F.prototype.on_request_params = function (xx, yy) {
            if (this.user_id) {
                xx.filters.user_id = this.user_id;
            } else {
                yy.process = false;
            }
            return this;
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Комманды">

        //<editor-fold defaultstate="collapsed" desc="footer commands">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        F.prototype.onCommandEdit = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this._id_to_edit = id;
                if (this.order_editor_instance) {
                    return this.run_editor();
                }
                this.load_editor();
            }
            return this;
        };

        F.prototype.load_editor = function () {
            this.showLoader();
            Y.load('data_editor.order')
                    .done(this, this.on_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_editor_ready = function (x) {
            this.order_editor_instance = x();
            return this.run_editor();
            //return x()
            //return this;
        };
        F.prototype.run_editor = function () {
            this.order_editor_instance.show().load(this._id_to_edit);
            return this;
        };
        //</editor-fold>





        //</editor-fold>


        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            throw new Error("component load error");
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