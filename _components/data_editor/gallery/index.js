(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.image_uploader').promise
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
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
            this.init_editor();
            this.init_image_list();
            return this;
        };
        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_info"

            ]);
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            this.editor.init_editor();
            return this;
        };

        F.prototype.onAfterHide = function () {
            this.editor.destroy_editor();
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
                {'command': "cancel", 'text': "Отмена"},
                {'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Редактирование галереи";
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
                jQuery.getJSON('/admin/Gallery/API', {action: "get", id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_data_success(d.gallery);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            this.image_list.set_params("common_gallery", this.getField('id').val());
            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">
        F.prototype.init_editor = function () {
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.editor = cf();
            this.editor.setContainer(this.getRole('info'));
            return this;
        };

        F.prototype._set_field_info = function (a) {
            this.editor.setText(U.NEString(a.info, ''), U.anyBool(a.html_mode, true));
        };

        F.prototype._get_field_info = function () {
            return this.editor.getText();
        };

        F.prototype._set_field_html_mode = function () {
            return false;
        };

        F.prototype._get_field_html_mode = function () {
            return this.editor.get_check_state();
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="image_list">
        F.prototype.init_image_list = function () {
            var L = Y.get_loaded_component('media.image_uploader');
            this.image_list = L();
            this.image_list.set_params("common_gallery", null);
            this.image_list.setContainer(this.getRole("image-list"));
            return this;
        };
        //</editor-fold>

        F.prototype.onTabSelectedInfo = function () {
            this.editor.refresh();
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.editor.setText('', true);
            this.image_list.set_params("common_gallery", null);
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
        F.prototype.onCommandApply = function () {
            this.save(true);
            return this;
        };
        F.prototype.onCommandSave = function () {
            this.save(false);
            return this;
        };
        //</editor-fold>
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
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            var post_data = {
                action: "post",
                data: JSON.stringify(data)
            };
            this.showLoader();
            jQuery.post('/admin/Gallery/API', post_data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.gallery));
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