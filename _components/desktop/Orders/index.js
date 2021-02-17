(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
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
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var ADVT = window.Eve.ADVTable;
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
        EFO.TemplateManager().addObject(TPLS, MC);// префикс класса
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.order_editor_instance;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.init_table();
            return this;
        };


        F.prototype.onAfterShow = function () {
            this.reload();
            return PARP.onAfterShow.apply(this, APS.call(arguments));
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
            return [];
        };

        F.prototype.getDefaultTitle = function () {
            return "";
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
                    {id: "status", key: "status", property: "status", "text": "Статус", filter: "Orderstatus", sort: true},
                    {id: "control", key: "control", property: "control", text: "Контроль", sort: false, filter: false}
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
            this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('body').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        F.prototype.reload = function () {
            this.table.body.DataDriver.refresh();
            return this;

        };
        //</editor-fold>                        
        F.prototype.install = function (x) {
            var node = document.getElementById(x);
            if (node) {
                this.container_node = node;
                this.handle.appendTo(node);
                this.show();
                this.reload();
            }
            return this;
        };

        F.prototype.getContainer = function () {
            if (this.container_node) {
                return this.container_node;
            }
            if (!this.safe_node) {
                this.safe_node = document.createElement('div');
            }
            return this.safe_node;
        };

        F.prototype.onCommandNone = function () {
            return this;
        };

        F.prototype.onMonitorStatus = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            var new_status = U.IntMoreOr(t.val(), -1, 0);
            var cc = t.closest('.' + MC + 'CommonDisplay');
            cc.removeClass().addClass(MC + "CommonDisplay " + MC + "StatusDisplay" + new_status);
            jQuery.getJSON('/admin/Order/API', {action: "set_order_status", order_id: id, status: new_status})
                    .done(this.on_order_set_status_done.bindToObject(this))
                    .fail(this.on_order_set_status_fail.bindToObject(this));
            return this;
        };
        F.prototype.on_order_set_status_done = function (d) {
            d = U.safeObject(d);
            if (d.status === "ok") {
                return this;
            }
            if (d.status === 'error') {
                return this.on_order_set_status_fail(d.error_info.message);
            }
            return this.on_order_set_status_fail('invalid server responce');
        };
        F.prototype.on_order_set_status_fail = function (x) {
            x = U.NEString(x, "Ошибка при установке статуса");
            U.TError(x);
            return this;
        };

        F.prototype.onCommandAdd = function () {
            this._id_to_edit = null;
            if (this.order_editor_instance) {
                return this.run_editor();
            }
            return this.load_editor();
        };

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

        F.prototype.onCommandRemove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, 0);
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text("Удалить эту заказу?<br><b style=\"color:crimson;font-size:.9em\">Это действие нельзя отменить<b>")
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            this.table.reloadFromUrl("/admin/Order/API?action=remove&id_to_remove=" + id);
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не удалять", "Удалить"])
                    .show();
            return this;
        };



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
                    Y.report_fail(FQCN, "Ошибка при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();