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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable'];
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
                    {id: "alias", key: "alias", property: "alias", "text": "Алиас", filter: "String", sort: true},
                    {id: "name", key: "name", property: "name", "text": "Наименование", filter: "String", sort: true},
                    {id: "created", key: "created", property: "created", "text": "Создано", filter: "Date", sort: true},
                    {id: "updated", key: "updated", property: "updated", "text": "Изменено", filter: "Date", sort: true},
                    {id: "published", key: "published", property: "published", "text": "Дата публикации", filter: "Date", sort: true},
                    {id: "active", key: "active", property: "active", "text": "Опубликован", filter: "Bool", sort: true},
                    {id: "cost", key: "cost", property: "cost", "text": "Ценник", filter: "Numeric", sort: true},                    
                    {id: "qty", key: "qty", property: "qty", "text": "К-во уроков", filter: "Int", sort: true},                                   
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

            this.table.addRenderer('render_cost', function () {
                return EFO.Checks.formatPriceNSD(this.cost, 2);
            });

            var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/Video/API?action=list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
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

        

        F.prototype.onCommandAdd = function () {
            this._id_to_edit = null;
            return this.load_editor();
        };

        F.prototype.onCommandEdit = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this._id_to_edit = id;                
                this.load_editor();
            }
            return this;
        };

        F.prototype.load_editor = function () {
            this.showLoader();
            Y.load('data_editor.video_group')
                    .done(this, this.on_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_editor_ready = function (x) {
            x.show().load(this._id_to_edit).setCallback(this,this.reload);            
            return this;
        };
        
        F.prototype.onCommandRemove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, 0);
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text("Удалить этот видеокурс?<br><b style=\"color:crimson;font-size:.9em\">Курс будет удален навсегда,<br>оплатившие его пользователи будут ныть и жаловаться,<br>В общем лучше не надо<b>")
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            this.table.reloadFromUrl("/admin/Video/API?action=remove&id_to_remove=" + id);
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