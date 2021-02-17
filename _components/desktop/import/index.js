(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      

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
            return this;
        };


        F.prototype.onAfterShow = function () {
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
        F.prototype.install = function (x) {
            var node = document.getElementById(x);
            if (node) {
                this.container_node = node;
                this.handle.appendTo(node);
                this.show();
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


        F.prototype.forms = function () {
            return [
                {id: "ImportNomenclature", name: "Импорт номенклатуры"},
                {id: "ImportStorage", name: "Импорт остатков"}
            ];
        };


        F.prototype.onMonitorSelector = function (t) {
            var id = U.NEString(t.val(), null);
            if (id) {
                var tpl = null;
                try {
                    tpl = EFO.TemplateManager().get(id, MC);
                } catch (e) {
                    tpl = null;
                }
                if (tpl) {
                    this.getRole('render').html(Mustache.render(tpl, this));
                    this.getRole('render').find('input[type=file]').on('change', this.on_file_change.bindToObject(this));
                } else {
                    U.TError(MC + ":template not found");
                }
            }
            return this;
        };

        F.prototype.clear_file_name = function (x) {
            var ar = x.replace(/\\/g, "/").split("/");
            return ar[ar.length - 1];
        };
        F.prototype.on_file_change = function () {
            var v = U.NEString(this.getRole('render').find('input[type=file]').val(), null);
            this.getRole('render').find('.' + MC + "UploadText").html(v ? this.clear_file_name(v) : "Выберите файл");
            return this;
        };

        F.prototype.get_target_frame_name = function () {
            this.getRole('hidden_area').html('');
            var new_frame_id = [MC, (new Date()).getTime(), 'frame'].join('_');
            this.getRole('hidden_area').html(Mustache.render(EFO.TemplateManager().get('frame', MC), {id: new_frame_id}));
            this.getRole('hidden_area').find('iframe').get(0).onload = this.on_frame_error.bindToObject(this);
            return new_frame_id;
        };


        F.prototype.onCommandSubmit = function () {
            var v = U.NEString(this.getRole('render').find('input[type=file]').val(), null);
            if (!v) {
                U.TError(MC + ":select file");
                return this;
            }
            this.showLoader();
            var form = this.getRole('render').find('form');
            var fd = new FormData(form.get(0));
            jQuery.ajax({
                url: form.attr('action'),
                data: fd,
                processData: false,
                type: 'POST',
                contentType: false, // 'multipart/form-data; charset=utf-8; boundary='+Math.random().toString().substr(2),                
                dataType: 'json'
            })
                    .done(this.on_mediate_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this));
            return this;
        };
        //data-log

        F.prototype.write_log = function (x) {
            x = U.isArray(x) ? x : [x];
            var log = this.handle.find("[data-role='data-log']");
            log.val(U.NEString([U.NEString(log.val(), '')].concat(x).join("\n"), ""));
            log.scrollTop(5000000);
            return this;
        };
        F.prototype.on_mediate_responce = function (d) {
            if (U.isObject(d)) {
                if (U.isArray(d.data_import_log) && d.data_import_log.length) {
                    this.write_log(d.data_import_log);
                }
                if (d.status === 'redirect') {
                    jQuery.getJSON(d.redirect, d.redirect_params)
                            .done(this.on_mediate_responce.bindToObject(this))
                            .fail(this.on_network_fail.bindToObject(this));
                    return this;
                }
                if (d.status === 'ok') {
                    this.hideLoader();
                    this.write_log("Готово");
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };
        F.prototype.on_network_fail = function (x) {
            x = U.NEString(x, 'network error');
            U.TError(x);
            this.write_log(x);
            this.hideLoader();
            return this;
        };

        F.prototype.getCommonCallbackName = function () {
            if (!this._callback) {
                this._callback = [MC, 'callback', (new Date()).getTime()].join('');
                window[this._callback] = this.on_frame_callback.bindToObject(this);
            }
            return this._callback;
        };

        F.prototype.on_frame_callback = function (report_json) {
            debugger;
        };

        F.prototype.on_frame_error = function () {
            this.hideLoader();
            return this;
        };



        F.prototype.onCommandSelect_parent_category = function (t) {
            this._selector = jQuery(t.closest('.' + MC + 'CategorySelectorRow').get(0));

            this.showLoader();
            Y.load('selectors.catalog_group')
                    .done(this, this.on_category_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_category_selector_ready = function (x) {
            x.show().load().setCallback(this, this.on_category_selected);
            return this;
        };

        F.prototype.on_category_selected = function (x) {
            if (U.isArray(x) && x.length) {
                var xx = x[0];
                var id = U.IntMoreOr(xx.id, 0, null);
                var name = U.NEString(xx.get_name_path(), null);
                if (!(id && name)) {
                    id = '';
                    name = '';
                }
                this._selector.find('input[type=text]').val(name);
                this._selector.find('input[type=hidden]').val(id);
            }
            return this;
        };
        F.prototype.clear_parent_category = function (t) {
            var sel = t.closest('.' + MC + 'CategorySelectorRow');
            sel.find(['input[type=text]']).val('');
            sel.find(['input[type=hidden]']).val('');
            return this;
        };




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
                    Y.report_fail(FQCN, "Ошибка при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();