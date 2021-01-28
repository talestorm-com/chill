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
                filters: true,
                sorter: "Remote",
                paginator: true,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "language_id", key: "language_id", property: "language_id", "text": "lang", filter: "String", sort: true},
                    {id: "literal", key: "literal", property: "literal", "text": "Литерал", filter: "String", sort: true},
                    {id: "default_translation", key: "default_translation", property: "default_translation", "text": "Фаллбак", filter: "String", sort: true},
                    {id: "translation", key: "translation", property: "translation", "text": "Перевод", filter: "String", sort: true},
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
            var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/TR/API?action=list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            //this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('body').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        F.prototype.reload = function () {
            this.table.body.DataDriver.refresh();
            return this;
        };
        F.prototype.on_load_fail = function (x) {
            x = U.NEString(x, "network error");
            U.TError(x);
            this.datasource.setSource([]);
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

        F.prototype.onCommandRegen = function () {
            jQuery.getJSON('/admin/TR/API/?action=regen');    
            return this;
        };


        F.prototype.onCommandRemove = function (t) {
            var id = U.NEString(t.data('id'), null);
            var section = U.NEString(t.data('section'),null);
            if (id && section) {
                this.table.reloadFromUrl("/admin/TR/API?action=remove&remove_language="+encodeURIComponent(section)+"&remove_literal="+encodeURIComponent(id));                
            }
            return this;
        };

        F.prototype.onMonitorChange = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                var section = U.NEString(t.data('section'), null);
                if (section) {
                    var v = t.val();
                    var self = this;
                    jQuery.getJSON("/admin/TR/API", {action: "set", literal: id, translation: v, language_id: section})
                            .done(function (d) {
                                if (U.isObject(d)) {
                                    if (d.status === 'ok') {
                                        self.handle.find("input[type=text][data-id=\"" + id + "\"][data-section=\""+section+"\"]").val(d.new);
                                    }
                                }
                            });
                }
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