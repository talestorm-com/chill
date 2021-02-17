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
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.init_table();
            this.handle.on('change', 'input[type=file]', this.on_file.bindToObjectWParam(this));
            return this;
        };


        F.prototype.on_file = function (f, e) {
            var t = jQuery(f);
            var context = U.NEString(t.data('id'), null);
            if (U.NEString(t.val(), null) && context) {
                var FD = new FormData();
                FD.append('context', context);
                FD.append('file', t.get(0).files[0]);
                FD.append('action', 'upload_fallback');
                this.showLoader();
                jQuery.ajax({
                    url: '/MediaAPI/ImageFly/API',
                    data: FD,
                    processData: false,
                    type: 'POST',
                    contentType: false, // 'multipart/form-data; charset=utf-8; boundary='+Math.random().toString().substr(2),                
                    dataType: 'json'
                })
                        .done(this.on_image_uploaded.bindToObject(this))
                        .fail(this.on_image_upload_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this))
                        .always(this.reload.bindToObject(this));
            }

            return this;
        };

        F.prototype.on_image_uploaded = function (d) {

        };

        F.prototype.on_image_upload_fail = function (d) {
            U.TError("image_upload_error");
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
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "context",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "context", key: "context", property: "context", "text": "Контекст", filter: false, sort: false},
                    {id: "image", key: "image", property: "image", "text": "Изображение", filter: false, sort: false},
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
            this.table.addRenderer('get_context_image', (function (x) {
                return "/media/fallback/1/" + x.context + ".SW_250H_250CF_1.jpg?a=" + (new Date()).getTime();
            }).bindToObjectWParam(this));
            this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('body').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        F.prototype.reload = function () {
            this.showLoader();
            jQuery.getJSON('/admin/FallbackImage/API', {action: 'get_list'})
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
            return this;
        };

        F.prototype.on_load_success = function (d) {
            var x = U.safeArray(d.contexts);
            x.sort(function (a, b) {
                return (a.context < b.context ? -1 : (a.context > b.context ? 1 : 0));
            });
            this.datasource.setSource(x);
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
            Y.load('data_editor.content_block')
                    .done(this, this.on_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_editor_ready = function (x) {
            return x.show().load(this._id_to_edit).setCallback(this, this.reload);
            return this;
        };

        F.prototype.onCommandRemove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, 0);
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text("Удалить этот блок контента?<br><b style=\"color:crimson;font-size:.9em\">Это действие нельзя отменить<b>")
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            this.table.reloadFromUrl("/admin/ContentBlock/API?action=remove&id_to_remove=" + id);
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не удалять", "Удалить"])
                    .show();
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="edit context">
        F.prototype.onCommandEdit_context = function (t) {
            var context = U.NEString(t.data('id'), null);
            if (context) {
                this._context_to_edit = context;
                this.load_context_editor();
            }
            return this;
        };

        F.prototype.load_context_editor = function () {
            this.showLoader();
            Y.load('media.context_editor')
                    .done(this, this.on_context_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_context_editor_ready = function (x) {
            x.show().load(this._context_to_edit).setCallback(this.this.reload);
            return this;
        };
        //</editor-fold>        


        //<editor-fold defaultstate="collapsed" desc="crop fallback">
        F.prototype.onCommandCrop_fallback = function (t) {
            var context = U.NEString(t.data('id'), null);
            if (context) {
                this._context_to_crop = context;
                this.showLoader();
                Y.load('media.image_cropper')
                        .done(this, this.on_cropper_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            }
            return this;
        };

        F.prototype.on_cropper_ready = function (x) {
            x.show().load("fallback", "1", this._context_to_crop).setCallback(this, this.reload);
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="pixlr">
        F.prototype.onCommandEdit_fallback = function (t) {
            var context = U.NEString(t.data('id'), null);
            if (context) {
                this._context_to_editi = context;
                this.showLoader();
                Y.load('media.image_editor_pixlr')
                        .done(this, this.on_image_editor_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            }
            return this;
        };
        F.prototype.on_image_editor_ready = function (x) {
            x.show().load("fallback", "1", this._context_to_editi).setCallback(this, this.reload);
            return this;
        };

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
                    Y.report_fail(FQCN, "Ошибка при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();