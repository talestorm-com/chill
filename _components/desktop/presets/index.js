(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var ADVT = window.Eve.ADVTable;
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
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "key", key: "key", property: "key", "text": "Переменная", filter: false, sort: false},
                    {id: "value", key: "value", property: "value", "text": "Значение", filter: false, sort: false},
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
            //var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/Presets/API?action=list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            //this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('body').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        F.prototype.reload = function () {
            this.showLoader();
            jQuery.getJSON("/admin/Presets/API", {action: "list"})
                    .done(this.on_load_responce.bindToObject(this))
                    .fail(this.on_load_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_load_success(d);
                }
                if (d.status === "error") {
                    return this.on_load_fail(d.error_info.message);
                }
            }
            return this.on_load_fail("invalid server responce");
        };
        F.prototype.on_load_fail = function (x) {
            x = U.NEString(x, "network error");
            U.TError(x);
            this.datasource.setSource([]);
            return this;
        };

        F.prototype.on_load_success = function (d) {
            this.datasource.setSource(U.safeArray(U.safeObject(d).presets));
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

        F.prototype.onCommandAdd = function () {
            var x = window.prompt("preset_name", "");
            x = U.NEString(x, null);
            if (x) {
                x = x.replace(/\s/g, '');
                x = U.NEString(x, null);
                if (x) {
                    this.showLoader();
                    jQuery.getJSON("/admin/Presets/API", {action: "new", name: x})
                            .done(this.on_load_responce.bindToObject(this))
                            .fail(this.on_load_fail.bindToObject(this))
                            .always(this.hideLoader.bindToObject(this));
                }
            }
            return this;
        };


        F.prototype.onCommandRemove = function (t) {
            var id = U.NEString(t.data('id'), 0, 0);
            if (id) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text("Удалить эту настройку?<br><b style=\"color:crimson;font-size:.9em\">В большинстве случаев это приведет к каким-нибудь неприятностям<b>")
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.showLoader();
                                jQuery.getJSON("/admin/Presets/API", {action: "remove", id: id})
                                        .done(this.on_load_responce.bindToObject(this))
                                        .fail(this.on_load_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не удалять", "Удалить"])
                        .show();
            }
            return this;
        };

        F.prototype.onMonitorChange = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                var v = t.val();
                var self = this;
                jQuery.getJSON("/admin/Presets/API", {action: "set", name: id, value: v})
                        .done(function (d) {
                            if (U.isObject(d)) {
                                if (d.status === 'ok') {
                                    self.handle.find("input[type=text][data-id=\"" + id + "\"]").val(d.new);
                                }
                            }
                        });
            }
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