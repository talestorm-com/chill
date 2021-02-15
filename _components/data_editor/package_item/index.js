(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>', MEDIA_CONTEXT = '<?= \Content\ClientPackage\Package::MEDIA_CONTEXT?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable', 'Tabbable'];
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
            this.init_ext_editors();
            return this;
        };


        F.prototype.init_ext_editors = function () {
            var UF = Y.get_loaded_component('media.image_uploader');
            this.image_list = UF();
            this.image_list.set_params(MEDIA_CONTEXT, null);
            this.image_list.setContainer(this.getRole("image-list"));

            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };





        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            return this;
        };

        F.prototype.onBeforeHide = function () {
            return PARP.onBeforeHide.apply(this, APS.call(arguments));
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
                {'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Редактирование пакета";
        };

        F.prototype.enumSubTemplates = function () {
            var r = [].concat(
                    PARP.enumSubTemplates.call(this),
                    [
                        MC + ".TAB_main"
                                , MC + ".TAB_images"
                                , MC + ".TAB_properties"
                    ]);
            return r;
        };

        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/Package/API', {action: "get", id: id})
                        .done(this.on_data_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };






        F.prototype.on_data_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this.on_data_success(U.safeObject(d.package));
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_network_fail_fatal = function () {
            return this.on_network_fail.apply(this, APS.call(arguments)).hide().clear();
        };

        F.prototype.on_network_fail = function (m) {
            U.TError(m);
            return this;
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
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
        //<editor-fold defaultstate="collapsed" desc="monitors">
        F.prototype.onMonitorMoney = function (t) {
            t.val(EFO.Checks.formatPriceNSD(U.FloatOr(t.val(), 0), 2));
            return this;
        };
        F.prototype.onMonitorIntval = function (t) {
            t.val(U.IntMoreOr(t.val(), 0, 1));
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="cancel command">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="login command">
        F.prototype.onCommandApply = function () {
            this.save(true);
            return this;
        };



        F.prototype.onNetworkFail = function (x) {
            var msg = "NetworkError";
            if (U.isError(x)) {
                msg = x.message;
            } else if (U.NEString(x, null)) {
                msg = x;
            } else if (U.isObject(x) && U.NEString(x.statusText, null)) {
                msg = x.statusText;
            }
            this.showError(msg);
            return this;
        };

        F.prototype.showError = function (tx) {
            new EveFlash({cssclass: "red", ICON: "stop", IMAGE: "stop", TO: 5000, CLOSE: false, TITLE: "Ошибка", TEXT: tx});
            return this;
        };

        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="restore command">
        F.prototype.onCommandSave = function () {
            //var df = EFO.Filter.Filter().applyFiltersToHash(this.getFields(), this.getFilters().getSectionExport('auth'));
            this.save(false);
            return this;
        };


        //</editor-fold>
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="save">             
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open, true);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('user'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (ee) {
                U.Error([MC, ee.message].join(':'));
            }
            this.showLoader();
            jQuery.post('/admin/Package/API', {action: 'post', data: JSON.stringify(data)})
                    .done(this.on_post_result.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_post_result = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.package));
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
      

        //<editor-fold defaultstate="collapsed" desc="fields">

        F.prototype._set_field_id = function (x, fi) {
            this.getField('id').val(U.IntMoreOr(x.id, 0, null));
            this.image_list.set_params(MEDIA_CONTEXT, U.IntMoreOr(x.id, 0, null));
            return this;
        };
        
        //<editor-fold defaultstate="collapsed" desc="copypasted">

        F.prototype._set_field_default_image = function () {
            return this;
        };
        F.prototype._get_field_default_image = function () {
            return this.image_list.get_default_image();
        };
        F.prototype._set_field_images = function () {
            return this;
        };

        F.prototype._get_field_images = function () {
            return null;
        };

        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };
        //</editor-fold>



        //</editor-fold>

   
   





        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            this.showError("Ошибка при загрузке компонента!");
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