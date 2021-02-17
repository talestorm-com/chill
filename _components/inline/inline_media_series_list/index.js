(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js'),
        Y.js('/assets/js/PathBuilder/PathBuilder.js')
    ];
    //</editor-fold>    
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.flatController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = E.ADVTable;
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
            return  (F.is(this) ? this.init : F.F).apply(this, APS.call(arguments));
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.CI = null;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">   '
        F.prototype.onInit = function (e_instance_id) {
            this.AL = {};
            e_instance_id = U.NEString(e_instance_id, null);
            e_instance_id ? 0 : U.Error("trailer list requires instance id as parameter");
            this.instance_id = [MC, e_instance_id].join('_');
            PARP.onInit.apply(this, APS.call(arguments));
            this.get_instance_id = this._get_instance_id.bindToObject(this);
            this.init_table();

            return this;
        };

        F.prototype.set_ci = function (c) {
            this.CI = U.isObject(c) ? c : null;
            return this;
        };

        F.prototype.ci_get_id = function () {
            if (this.CI) {
                try {
                    return this.CI.series_list_get_id();
                } catch (e) {

                }
            }
            return null;
        };
        F.prototype.ci_get_path = function () {
            if (this.CI) {
                try {
                    return this.CI.series_list_get_path();
                } catch (e) {

                }
            }
            return null;
        };

        F.prototype.ci_get_pathbuilder = function () {
            if (this.CI) {
                try {
                    return this.CI.series_list_get_pathbuilder();
                } catch (e) {

                }
            }
            return null;
        };

        F.prototype._get_instance_id = function () {
            return this.instance_id;
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

        //</editor-fold>   


        F.prototype.get_table_def = function () {
            return {
                id: this.instance_id,
                filters: true,
                sorter: 'Remote', //'SimpleLocal',
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "id", key: "id", property: "id", "text": "id", filter: 'Int', sort: true},
                    {id: "series_num", key: "series_num", property: "series_num", "text": "№№ Серии", filter: "Int", sort: true},
                    {id: "name", key: "name", property: "name", "text": "Название", filter: "String", sort: true},
                    {id: "enabled", key: "enabled", property: "enabled", "text": "Вкл", filter: "Bool", sort: true},
                    {id: "vertical", key: "vertical", property: "vertical", "text": "VV", filter: "Bool", sort: true},
                    {id: "control", key: "control", property: "control", "text": "control", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_table = function () {
            var def = this.get_table_def();
            var TMPLTS = null;
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplatesRet(__DIR__,"TMPLTS","TPLS")?>*/
            ADVT.TemplateManager.LocalTemplateManager(this.instance_id, TMPLTS);
            this.table = ADVT.Table(def);

            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });
            this.table.addRenderer('is_element_enabled', (function (x) {
                return U.anyBool(x.enabled, false);
            }).bindToObjectWParam(this));

            var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/MediaContent/API?action=list_season_series', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            DSParams.onRequestParams(this, this.on_request_params);
            this.source = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.table.setDataSource(this.source);
            this.table.appendTo(this.getRole(MC + "Table").get(0));
            return this;
        };
        F.prototype.on_request_params = function (a, b, c) {
            var id = U.IntMoreOr(this.ci_get_id(), 0, null);
            if (id) {
                a.filters.season_id = id;
                return this;
            }
            b.process = false;
            b.error = "Сначала нужно сохранить сезон";
            return this;
        };



        F.prototype.set_data = function () {
            this.table.body.DataDriver.refresh();
            return this;
        };

        F.prototype.reload = function () {
            this.table.body.DataDriver.refresh();
        };


        F.prototype.get_data = function () {
            return null; // самостоятельный
        };

        F.prototype.onCommandAdd = function () {
            var id = this.ci_get_id();
            if (!id) {
                U.TError("Сначала необходимо сохранить сезон!");
                return this;
            }
            this._id_to_edit = null;
            this.load_editor();
            return this;

        };

        F.prototype.onCommandEdit_series = function (t) {
            var id = this.ci_get_id();
            if (!id) {
                U.TError("Сначала необходимо сохранить сезон!");
                return this;
            }
            this._id_to_edit = U.IntMoreOr(t.data('id'), 0, null);
            if (this._id_to_edit) {
                this.load_editor();
            }
            return this;
        };

        F.prototype.load_editor = function () {
            this.showLoader();
            Y.load('data_editor.mediacontent.type_editor.season_series_editor')
                    .done(this, this.on_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_editor_ready = function (x) {            
            var pathbuilder = this.ci_get_pathbuilder();
            if (pathbuilder) {
                x.show().load(this._id_to_edit, this.ci_get_id(), pathbuilder).setCallback(this, this.on_season_editor_done);
            }
            return this;
        };
        F.prototype.on_season_editor_done = function () {
            this.reload();
        };

        F.prototype.onCommandRemove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text("Удалить эту серию и <b>все связанные файлы</b><br>?")
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.table.reloadFromUrl("/admin/MediaContent/API?action=remove_season_series&id_to_remove=" + id);
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не удалять", "Удалить"])
                        .show();
            }
            return this;
        };




        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            //this.source.setSource([]);
            this._row_index = 0;
            this._row_index2 = 0;
            this._row_index3 = 0;
            return this;
        };

        //</editor-fold>        

        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
        };
        Y.reportSuccess(FQCN, F);// конструктор, не инстанс
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